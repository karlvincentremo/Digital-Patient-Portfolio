<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

/* =========================
   DATABASE CONNECTION
========================= */
require_once 'config/database.php';
$conn = getConnection();
$dbOk = ($conn && !$conn->connect_error); 

/* =========================
   DEFAULT VALUES
========================= */
$totalPatients = 0;
$todayAppointments = 0;
$seniorPatients = 0;
$babyPatients = 0;
$recentPats = false;

/* =========================
   DATABASE QUERIES
========================= */
if ($dbOk) {
    // 1. Count total patients
    $res = $conn->query("SELECT COUNT(*) AS c FROM patients");
    if ($res) { $totalPatients = $res->fetch_assoc()['c']; }

 // 2. Count Minors (Patients aged 17 and below)
    // Counts patients born within the last 17 years from today
    $babyRes = $conn->query("SELECT COUNT(*) AS c FROM patients WHERE date_of_birth >= DATE_SUB(CURDATE(), INTERVAL 17 YEAR)");
    if ($babyRes) { 
        $babyPatients = $babyRes->fetch_assoc()['c']; 
    }

    // 3. Count Seniors (using 'date_of_birth')
    // Counts patients born 60 years ago or earlier
    $seniorRes = $conn->query("SELECT COUNT(*) AS c FROM patients WHERE date_of_birth <= DATE_SUB(CURDATE(), INTERVAL 60 YEAR)");
    if ($seniorRes) { 
        $seniorPatients = $seniorRes->fetch_assoc()['c']; 
    }

    // 4. Recently added patients
    $recentPats = $conn->query("SELECT * FROM patients ORDER BY id DESC LIMIT 6");
}
/* =========================
   STAFF INFO 
========================= */
// 1. Get the position from the session (default to 'Staff' if not set)
$userRole = $_SESSION['staff_position'] ?? 'Staff';

// 2. Format the greeting based on the 3 main types
$roleClean = strtolower(trim($userRole));

if ($roleClean === 'doctor') {
    $greeting = "Good Morning, Doctor 👋";
    $displayInitial = "D";
} elseif ($roleClean === 'nurse') {
    $greeting = "Good Morning, Nurse 👋";
    $displayInitial = "N";
} else {
    // This covers 'Worker', 'Staff', 'Admin', or anyone else
    $greeting = "Good Morning, Health Worker 👋";
    $displayInitial = "H";
}

/* =========================
   REAL APPOINTMENTS DATA
========================= */
$today = date('Y-m-d');
$todayApps = [];

if ($dbOk) {
    // 1. Update the Stat Card count for today
    $countRes = $conn->query("SELECT COUNT(*) AS c FROM appointments WHERE appointment_date = '$today'");
    if ($countRes) {
        $todayAppointments = $countRes->fetch_assoc()['c'];
    }

    // 2. Get the list for the table
    $todayApps = $conn->query("
        SELECT a.*, p.first_name, p.last_name 
        FROM appointments a 
        JOIN patients p ON a.patient_id = p.id 
        WHERE a.appointment_date = '$today'
        ORDER BY a.appointment_time ASC
    ");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Barangay Health Center — Dashboard</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
:root {
    --forest-deepest: #0a1f0e;
    --forest-dark:    #122d18;
    --forest-mid:     #1a4726;
    --forest-rich:    #1e5c30;
    --forest-bright:  #2d7d46;
    --forest-leaf:    #3a9c58;
    --forest-light:   #4db870;
    --moss:           #6abf80;
    --sage:           #a8d5b5;
    --mist:           #d4ead9;
    --fog:            #edf7ef;
    --bark:           #5c4033;
    --amber:          #d97706;
    --amber-light:    #fef3c7;
    --text-dark:      #0d1f12;
    --text-mid:       #2e4a35;
    --text-soft:      #6b8f74;
    --white:          #ffffff;
    --card-bg:        rgba(255,255,255,0.92);
    --sidebar-w:      260px;
}

* { box-sizing: border-box; margin: 0; padding: 0; }

body {
    font-family: 'DM Sans', sans-serif;
    background: var(--fog);
    color: var(--text-dark);
    min-height: 100vh;
    overflow-x: hidden;
}

/* ======== SIDEBAR ======== */
.sidebar {
    width: var(--sidebar-w);
    background: var(--forest-deepest);
    position: fixed;
    top: 0; left: 0; bottom: 0;
    display: flex;
    flex-direction: column;
    padding: 0;
    overflow: hidden;
    z-index: 100;
}

.sidebar::before {
    content: '';
    position: absolute;
    inset: 0;
    background:
        radial-gradient(ellipse at 20% 80%, rgba(45,125,70,0.3) 0%, transparent 60%),
        radial-gradient(ellipse at 80% 20%, rgba(26,71,38,0.4) 0%, transparent 50%);
    pointer-events: none;
}

/* Decorative leaf veins */
.sidebar::after {
    content: '';
    position: absolute;
    bottom: -40px; right: -60px;
    width: 220px; height: 220px;
    border-radius: 50% 0 50% 0;
    border: 1px solid rgba(77,184,112,0.12);
    transform: rotate(30deg);
    pointer-events: none;
}

.sidebar-logo {
    padding: 28px 24px 24px;
    border-bottom: 1px solid rgba(77,184,112,0.15);
    position: relative;
    z-index: 1;
}

.sidebar-logo .logo-icon {
    font-size: 28px;
    display: block;
    margin-bottom: 8px;
}

.sidebar-logo h2 {
    font-family: 'Playfair Display', serif;
    font-size: 15px;
    color: var(--white);
    line-height: 1.3;
    font-weight: 500;
}

.sidebar-logo span {
    font-size: 11px;
    color: var(--moss);
    font-weight: 300;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    display: block;
    margin-top: 3px;
}

.nav-section {
    padding: 20px 16px 8px;
    position: relative;
    z-index: 1;
    flex: 1;
}

.nav-label {
    font-size: 10px;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: rgba(168,213,181,0.5);
    padding: 0 10px;
    margin-bottom: 8px;
}

.sidebar a {
    display: flex;
    align-items: center;
    gap: 12px;
    color: rgba(255,255,255,0.75);
    text-decoration: none;
    padding: 11px 14px;
    border-radius: 10px;
    margin-bottom: 4px;
    font-size: 14px;
    font-weight: 400;
    transition: all 0.2s ease;
    position: relative;
}

.sidebar a:hover {
    background: rgba(77,184,112,0.15);
    color: var(--white);
}

.sidebar a.active {
    background: linear-gradient(135deg, rgba(58,156,88,0.5), rgba(45,125,70,0.3));
    color: var(--white);
    font-weight: 500;
    box-shadow: inset 0 0 0 1px rgba(77,184,112,0.3);
}

.sidebar a.active::before {
    content: '';
    position: absolute;
    left: 0; top: 50%;
    transform: translateY(-50%);
    width: 3px; height: 20px;
    background: var(--forest-light);
    border-radius: 0 3px 3px 0;
}

.nav-icon { font-size: 17px; width: 20px; text-align: center; }

.sidebar-footer {
    padding: 16px 16px 24px;
    border-top: 1px solid rgba(77,184,112,0.1);
    position: relative;
    z-index: 1;
}

.sidebar-footer a {
    display: flex;
    align-items: center;
    gap: 12px;
    color: rgba(255,255,255,0.5);
    text-decoration: none;
    padding: 10px 14px;
    border-radius: 10px;
    font-size: 14px;
    transition: all 0.2s;
}

.sidebar-footer a:hover {
    color: #ff8080;
    background: rgba(255,100,100,0.1);
}

/* ======== MAIN ======== */
.main {
    margin-left: var(--sidebar-w);
    padding: 32px 36px;
    min-height: 100vh;
    position: relative;
}

/* background texture */
.main::before {
    content: '';
    position: fixed;
    top: 0; left: var(--sidebar-w); right: 0; bottom: 0;
    background:
        radial-gradient(ellipse at 85% 10%, rgba(58,156,88,0.08) 0%, transparent 50%),
        radial-gradient(ellipse at 10% 90%, rgba(45,125,70,0.06) 0%, transparent 50%);
    pointer-events: none;
    z-index: 0;
}

.main > * { position: relative; z-index: 1; }

/* ======== TOPBAR ======== */
.topbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 36px;
}

.topbar-left h1 {
    font-family: 'Playfair Display', serif;
    font-size: 28px;
    font-weight: 700;
    color: var(--forest-deepest);
    line-height: 1.2;
}

.topbar-left p {
    color: var(--text-soft);
    font-size: 14px;
    margin-top: 4px;
    font-weight: 300;
}

.topbar-right {
    display: flex;
    align-items: center;
    gap: 14px;
}

.date-badge {
    background: var(--card-bg);
    border: 1px solid rgba(58,156,88,0.2);
    border-radius: 50px;
    padding: 8px 18px;
    font-size: 13px;
    color: var(--text-mid);
    font-weight: 500;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.avatar {
    width: 42px; height: 42px;
    background: linear-gradient(135deg, var(--forest-leaf), var(--forest-bright));
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 16px;
    box-shadow: 0 3px 12px rgba(45,125,70,0.4);
}

/* ======== STAT CARDS ======== */
.cards {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 32px;
}

.card {
    background: var(--card-bg);
    border-radius: 18px;
    padding: 24px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    border: 1px solid rgba(255,255,255,0.8);
    position: relative;
    overflow: hidden;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.1);
}

.card::after {
    content: '';
    position: absolute;
    top: -30px; right: -30px;
    width: 100px; height: 100px;
    border-radius: 50%;
    opacity: 0.08;
}

.card-1::after { background: var(--forest-leaf); }
.card-2::after { background: var(--amber); }
.card-3::after { background: #0ea5e9; }
.card-4::after { background: #a855f7; }

.card-icon {
    width: 44px; height: 44px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px;
    margin-bottom: 16px;
}

.card-1 .card-icon { background: rgba(58,156,88,0.12); }
.card-2 .card-icon { background: rgba(217,119,6,0.12); }
.card-3 .card-icon { background: rgba(14,165,233,0.12); }
.card-4 .card-icon { background: rgba(168,85,247,0.12); }

.card h3 {
    font-family: 'Playfair Display', serif;
    font-size: 38px;
    font-weight: 700;
    line-height: 1;
    color: var(--forest-deepest);
    margin-bottom: 6px;
}

.card p {
    font-size: 13px;
    color: var(--text-soft);
    font-weight: 400;
}

.card-trend {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 12px;
    font-weight: 600;
    margin-top: 10px;
    padding: 3px 8px;
    border-radius: 20px;
}

.trend-up { background: rgba(58,156,88,0.12); color: var(--forest-leaf); }
.trend-neutral { background: rgba(107,143,116,0.12); color: var(--text-soft); }

/* ======== GRID ======== */
.content-grid {
    display: grid;
    grid-template-columns: 1.1fr 0.9fr;
    gap: 24px;
}

/* ======== TABLE CARD ======== */
.table-card {
    background: var(--card-bg);
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    border: 1px solid rgba(255,255,255,0.8);
}

.table-header {
    padding: 18px 24px;
    background: linear-gradient(135deg, var(--forest-mid), var(--forest-rich));
    color: white;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.table-header-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-family: 'Playfair Display', serif;
    font-size: 16px;
    font-weight: 500;
}

.table-header-title .th-icon {
    width: 32px; height: 32px;
    background: rgba(255,255,255,0.15);
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 15px;
}

.view-all {
    font-size: 12px;
    color: rgba(255,255,255,0.65);
    text-decoration: none;
    border: 1px solid rgba(255,255,255,0.2);
    padding: 4px 12px;
    border-radius: 20px;
    transition: all 0.2s;
}

.view-all:hover { background: rgba(255,255,255,0.15); color: white; }

table {
    width: 100%;
    border-collapse: collapse;
}

table th {
    background: rgba(237,247,239,0.7);
    padding: 11px 20px;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: var(--text-soft);
    text-align: left;
}

table td {
    padding: 14px 20px;
    border-bottom: 1px solid rgba(0,0,0,0.04);
    font-size: 14px;
    color: var(--text-dark);
}

table tbody tr:last-child td { border-bottom: none; }

table tbody tr {
    transition: background 0.15s;
}

table tbody tr:hover { background: rgba(58,156,88,0.04); }

.patient-time {
    font-weight: 600;
    font-size: 13px;
    color: var(--forest-mid);
}

.patient-name { font-weight: 500; }
.patient-purpose {
    font-size: 12px;
    color: var(--text-soft);
    margin-top: 2px;
}

.badge {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.3px;
}

.completed {
    background: rgba(58,156,88,0.12);
    color: var(--forest-rich);
}

.pending {
    background: var(--amber-light);
    color: #92400e;
}

.patient-id {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    color: var(--text-soft);
    background: var(--fog);
    padding: 3px 10px;
    border-radius: 20px;
    font-family: monospace;
}

.empty-state {
    text-align: center;
    padding: 40px 20px;
    color: var(--text-soft);
}

.empty-state .empty-icon { font-size: 40px; margin-bottom: 12px; }
.empty-state p { font-size: 14px; }

/* ======== ANIMATE IN ======== */
@keyframes fadeUp {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
}

.card { animation: fadeUp 0.5s ease both; }
.card:nth-child(1) { animation-delay: 0.05s; }
.card:nth-child(2) { animation-delay: 0.10s; }
.card:nth-child(3) { animation-delay: 0.15s; }
.card:nth-child(4) { animation-delay: 0.20s; }

.topbar  { animation: fadeUp 0.4s ease both; }
.table-card { animation: fadeUp 0.5s ease both; animation-delay: 0.25s; }

/* ======== RESPONSIVE ======== */
@media (max-width: 1100px) {
    .cards { grid-template-columns: 1fr 1fr; }
    .content-grid { grid-template-columns: 1fr; }
}

@media (max-width: 768px) {
    :root { --sidebar-w: 0px; }
    .sidebar { display: none; }
    .main { padding: 20px 16px; }
    .cards { grid-template-columns: 1fr 1fr; gap: 12px; }
    .topbar { flex-direction: column; align-items: flex-start; gap: 14px; }
}

@media (max-width: 480px) {
    .cards { grid-template-columns: 1fr; }
}
</style>
</head>
<body>

<aside class="sidebar">

    <div class="sidebar-logo">
        <span class="logo-icon">🌿</span>
        <h2>Barangay Health Center</h2>
        <span>Patient Management System</span>
    </div>

    <nav class="nav-section">
    <p class="nav-label">Main Menu</p>

    <a href="index.php" class="active">
        <span class="nav-icon">📊</span>
        Dashboard   
    </a>
    <a href="patients/index.php">
        <span class="nav-icon">👥</span>
        Clients
    </a>
    <a href="appointments/index.php">
        <span class="nav-icon">📅</span>
        Appointments
    </a>
    <a href="medicine/medicine_list.php">
        <span class="nav-icon">💊</span>
        Medicine List
    </a>

    <a href="health_records/index.php">
        <span class="nav-icon">📋</span>
        Health Records
    </a>

    <p class="nav-label" style="margin-top:20px;">System</p>

    <a href="settings/index.php">
        <span class="nav-icon">⚙️</span>
        Settings
    </a>
</nav>
<div style="padding: 1.25rem; border-top: 1px solid rgba(255,255,255,.08); margin-top: auto;">
    <a href="/health_monitoring/logout.php" class="premium-logout">
        <span class="logout-icon">🚪</span>
        <span class="logout-text">Sign Out</span>
    </a>
</div>

</aside>

<main class="main">

    <div class="topbar-container" style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
    <div class="welcome-text">
        <h1 style="font-family: 'Playfair Display', serif; color: var(--forest-deepest); margin: 0;">
            <?= $greeting ?>
        </h1>
        <p style="color: #6b8f74; margin: 5px 0 0 0;">Here's what's happening at the health center today.</p>
    </div>

    <div style="display: flex; align-items: center; gap: 15px;">
        <div class="date-badge">
            📅 <?= date('l, M d, Y') ?>
        </div>

        <div class="avatar">
            <?= $displayInitial ?>
        </div>
    </div>
</div>
</div>

    <div class="cards">

        <div class="card card-1">
            <div class="card-icon">🧑‍⚕️</div>
            <h3><?= number_format($totalPatients) ?></h3>
            <p>Total Patients</p>
            <div class="card-trend trend-up">↑ Registered</div>
        </div>

        <div class="card card-2">
            <div class="card-icon">📅</div>
            <h3><?= number_format($todayAppointments) ?></h3>
            <p>Today's Appointments</p>
            <div class="card-trend trend-neutral">— Scheduled</div>
        </div>

        <div class="card card-3">
            <div class="card-icon">🧓</div>
            <h3><?= number_format($seniorPatients) ?></h3>
            <p>Senior Citizens</p>
            <div class="card-trend trend-neutral">— On record</div>
        </div>

        <div class="card card-4">
            <div class="card-icon">👶</div>
            <h3><?= number_format($babyPatients) ?></h3>
            <p>Babies / Infants</p>
            <div class="card-trend trend-neutral">— On record</div>
        </div>

    </div>

    <div class="content-grid">

        <div class="table-card">

            <div class="table-header">
                <div class="table-header-title">
                    <div class="th-icon">📅</div>
                    Today's Appointments
                </div>
                <a href="appointments/index.php" class="view-all">View all →</a>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Patient & Purpose</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($todayApps && $todayApps->num_rows > 0): ?>
                    <?php while($a = $todayApps->fetch_assoc()): ?>
                        <tr>
                            <td><span class="patient-time"><?= date('h:i A', strtotime($a['appointment_time'])) ?></span></td>
                            <td>
                                <div class="patient-name"><?= htmlspecialchars($a['first_name'] . ' ' . $a['last_name']) ?></div>
                                <div class="patient-purpose"><?= htmlspecialchars($a['purpose']) ?></div>
                            </td>
                            <td>
                                <span class="badge <?= strtolower($a['status']) ?>">
                                    <?= ucfirst($a['status']) ?>
                                </span>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">
                            <div class="empty-state">
                                <div class="empty-icon">📅</div>
                                <p>No appointments scheduled for today.</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>

        </div>

        <div class="table-card">

            <div class="table-header">
                <div class="table-header-title">
                    <div class="th-icon">🌱</div>
                    Recently Added
                </div>
                <a href="patients/index.php" class="view-all">View all →</a>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Patient Name</th>
                        <th>ID</th>
                    </tr>
                </thead>
                <tbody>

                <?php if($recentPats && $recentPats->num_rows > 0): ?>
                    <?php while($p = $recentPats->fetch_assoc()): ?>
                        <tr>
                            <td class="patient-name">
                                <?= htmlspecialchars($p['first_name'] ?? '') ?>
                                <?= htmlspecialchars($p['last_name'] ?? '') ?>
                            </td>
                            <td>
                                <span class="patient-id"># <?= $p['id'] ?? '' ?></span> 
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="2">
                            <div class="empty-state">
                                <div class="empty-icon">🌿</div>
                                <p>No patients found yet.</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>

                </tbody>
            </table>

        </div>

    </div>

</main>

</body>
</html>