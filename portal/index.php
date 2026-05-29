<?php
session_start();

// 1. Database Connection
$conn = new mysqli('localhost', 'root', '', 'barangay_health_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 2. Security Check 
// (Checking for staff_id because your system uses that for the logged-in session ID)
if (!isset($_SESSION['staff_id'])) { 
    header('Location: ../login.php'); 
    exit; 
}

// 3. Get the Patient ID from the URL (passed from select_baby.php)
$selected_id = $_GET['baby_id'] ?? null;

if (!$selected_id) {
    header('Location: select_baby.php');
    exit;
}

// 4. Fetch the Baby's Info from the 'patients' table
$query = "SELECT first_name, last_name, gender, date_of_birth FROM patients WHERE id = '$selected_id'";
$res = $conn->query($query);
$baby_data = $res->fetch_assoc();

if (!$baby_data) {
    die("Patient profile not found. Please link the baby again using the UID.");
}

// Combine names for the display
$baby_name = $baby_data['first_name'] . ' ' . $baby_data['last_name'];

// 5. Fetch Immunization Records
$records_sql = "SELECT * FROM immunization_records WHERE baby_id = '$selected_id' ORDER BY id ASC";
$records_res = $conn->query($records_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Portal — Health Center</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --teal: #0d9488;
            --teal-d: #0f766e;
            --navy: #0f2744;
            --bg: #f8fafc;
            --white: #ffffff;
            --border: #e2e8f0;
            --text-m: #64748b;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            margin: 0;
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: var(--navy);
            color: white;
            display: flex;
            flex-direction: column;
            padding: 2rem 1.5rem;
        }

        .logo {
            font-family: 'Outfit', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 3rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-item {
            padding: 1rem;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            border-radius: 12px;
            margin-bottom: 0.5rem;
            transition: 0.3s;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .nav-item.active, .nav-item:hover {
            background: rgba(255,255,255,0.1);
            color: white;
        }

        /* Main Content */
        .main {
            flex: 1;
            padding: 2rem 3rem;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2.5rem;
        }

        .welcome h1 {
            font-family: 'Outfit', sans-serif;
            margin: 0;
            color: var(--navy);
            font-size: 1.8rem;
        }

        .welcome p {
            color: var(--text-m);
            margin: 5px 0 0;
        }

        /* Table Section */
        .content-card {
            background: var(--white);
            border-radius: 20px;
            border: 1px solid var(--border);
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        }

        .card-header {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f8fafc;
            text-align: left;
            padding: 1rem 2rem;
            font-size: 0.85rem;
            color: var(--text-m);
            text-transform: uppercase;
        }

        td {
            padding: 1.2rem 2rem;
            border-bottom: 1px solid var(--border);
            font-size: 0.95rem;
            color: var(--navy);
        }

        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-pending { background: #fffbeb; color: #b45309; }
        .badge-done { background: #f0fdf4; color: #15803d; }

        .logout-btn {
            margin-top: auto;
            color: #fca5a5;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="logo">🏥 BrgyHealth</div>
    <nav>
        <a href="index.php?baby_id=<?php echo $selected_id; ?>" class="nav-item active">💉 Vaccine</a>
        <a href="medical_records.php?baby_id=<?php echo $selected_id; ?>" class="nav-item">📋 Medical Records</a>
        <a href="medicine_list.php?baby_id=<?php echo $selected_id; ?>" class="nav-item">💊 Medicine List</a>
    </nav>
    <a href="../logout.php" class="nav-item logout-btn">🚪 Log Out</a>
</div>

<div class="main">
    <header>
        <div class="welcome">
            <h1>Health Roadmap for <?php echo htmlspecialchars($baby_name); ?> 👶</h1>
            <p>Here is your baby's health summary.</p>
        </div>
    </header>

    <div class="content-card">
        <div class="card-header">
            <h3 style="margin:0; font-family:'Outfit'">Immunization Roadmap</h3>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Age</th>
                    <th>Vaccine Name</th>
                    <th>Type</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($records_res && $records_res->num_rows > 0): ?>
                    <?php while($row = $records_res->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['scheduled_age']); ?></td>
                        <td><b><?php echo htmlspecialchars($row['vaccine_name']); ?></b></td>
                        <td>
                            <?php 
                                // Logic to show "Oral Drops" for OPV and "Injection" for others
                                echo (strpos($row['vaccine_name'], 'OPV') !== false) ? 'Oral Drops' : 'Injection'; 
                            ?>
                        </td> 
                        <td>
                            <?php 
                                $status = strtolower($row['status']);
                                if ($status == 'completed' || $status == 'done'): 
                                    $vax_date = $row['date_given']; 
                                    $display_date = ($vax_date && $vax_date != '0000-00-00') 
                                                    ? date("M d, Y", strtotime($vax_date)) 
                                                    : "Recently";
                            ?>
                                <span class="badge badge-done">✓ Completed on <?php echo $display_date; ?></span>
                            <?php else: ?>
                                <span class="badge badge-pending">Upcoming</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align:center;">No immunization records found for this baby.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>