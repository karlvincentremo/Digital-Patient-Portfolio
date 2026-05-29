<?php
session_start();

// 1. Database Connection
$conn = new mysqli('localhost', 'root', '', 'barangay_health_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 2. Security Check
if (!isset($_SESSION['staff_id'])) { 
    header('Location: ../login.php'); 
    exit; 
}

// 3. Get the Baby ID
$selected_id = $_GET['baby_id'] ?? null;

if (!$selected_id) {
    header('Location: select_baby.php');
    exit;
}

// 4. Fetch Baby's Full Medical Profile
$query = "SELECT * FROM patients WHERE id = '$selected_id'";
$res = $conn->query($query);
$baby = $res->fetch_assoc();

if (!$baby) {
    die("Patient profile not found.");
}

$baby_name = $baby['first_name'] . ' ' . $baby['last_name'];

// 5. Fetch Recent Health Records using verified column names from your database image
$records_query = "SELECT * FROM health_records WHERE patient_id = '$selected_id' ORDER BY record_date DESC, record_time DESC";
$records_res = $conn->query($records_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Records — BrgyHealth</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root { --teal: #0d9488; --navy: #0f2744; --bg: #f8fafc; --white: #ffffff; --border: #e2e8f0; --text-m: #64748b; }
        body { font-family: 'DM Sans', sans-serif; background: var(--bg); margin: 0; display: flex; min-height: 100vh; }
        
        .sidebar { width: 260px; background: var(--navy); color: white; display: flex; flex-direction: column; padding: 2rem 1.5rem; position: fixed; height: 100vh; box-sizing: border-box; }
        .logo { font-family: 'Outfit', sans-serif; font-size: 1.5rem; font-weight: 700; margin-bottom: 3rem; display: flex; align-items: center; gap: 10px; }
        .nav-item { padding: 1rem; color: rgba(255,255,255,0.7); text-decoration: none; border-radius: 12px; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 12px; transition: 0.3s; }
        .nav-item.active, .nav-item:hover { background: rgba(255,255,255,0.1); color: white; }
        
        .main { flex: 1; margin-left: 260px; padding: 2rem 3rem; }
        .content-card { background: var(--white); border-radius: 20px; border: 1px solid var(--border); padding: 2rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); margin-bottom: 2rem; }
        
        .info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 2rem; margin-top: 1.5rem; }
        .info-box { border-bottom: 1px solid #f1f5f9; padding-bottom: 10px; }
        .info-box label { display: block; font-size: 0.75rem; text-transform: uppercase; color: var(--text-m); font-weight: 600; margin-bottom: 5px; }
        .info-box p { margin: 0; color: var(--navy); font-weight: 500; font-size: 1.1rem; }

        .record-entry { background: var(--white); border-radius: 15px; border: 1px solid var(--border); padding: 1.5rem; margin-bottom: 1.5rem; border-left: 6px solid var(--teal); position: relative; }
        .vitals-strip { display: flex; gap: 2rem; background: #f0fdfa; padding: 1rem; border-radius: 12px; margin: 1rem 0; flex-wrap: wrap; }
        .vital-item small { color: var(--text-m); font-size: 0.75rem; font-weight: 600; text-transform: uppercase; }
        .vital-item p { margin: 2px 0 0 0; color: var(--navy); font-weight: 700; }
        
        .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 2px solid var(--border); padding-bottom: 10px; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="logo">🏥 BrgyHealth</div>
    <nav style="flex: 1;">
        <a href="index.php?baby_id=<?php echo $selected_id; ?>" class="nav-item">💉 Vaccine</a>
        <a href="medical_records.php?baby_id=<?php echo $selected_id; ?>" class="nav-item active">📋 Medical Records</a>
    </nav>
    
    <a href="/health_monitoring/logout.php" 
       style="display: flex; align-items: center; gap: 12px; color: white !important; text-decoration: none; padding: 1rem; border-radius: 12px; opacity: 0.8;"
       onmouseover="this.style.opacity='1'; this.style.background='rgba(239,68,68,0.1)';"
       onmouseout="this.style.opacity='0.8'; this.style.background='transparent';">
       <span>🚪</span> Log Out
    </a>
</div>

<div class="main">
    <header style="margin-bottom: 2rem;">
        <h1 style="font-family:'Outfit'; color:var(--navy); margin-bottom: 5px;">Medical Profile</h1>
        <p style="color:var(--text-m); margin: 0;">Comprehensive health tracking for <strong><?php echo htmlspecialchars($baby_name); ?></strong></p>
    </header>

    <div class="content-card">
        <h3 style="margin-top:0; font-family:'Outfit'; color:var(--teal);">Basic Information</h3>
        <div class="info-grid">
            <div class="info-box"><label>Full Name</label><p><?php echo htmlspecialchars($baby_name); ?></p></div>
            <div class="info-box"><label>Date of Birth</label><p><?php echo date("F d, Y", strtotime($baby['date_of_birth'])); ?></p></div>
            <div class="info-box"><label>Gender</label><p><?php echo htmlspecialchars($baby['gender']); ?></p></div>
            <div class="info-box"><label>Blood Type</label><p><?php echo !empty($baby['blood_type']) ? $baby['blood_type'] : 'Not Recorded'; ?></p></div>
        </div>
    </div>

    <div class="section-header">
        <h2 style="font-family:'Outfit'; color:var(--navy); margin: 0;">Recent Health Visits</h2>
    </div>

    <?php if ($records_res && $records_res->num_rows > 0): ?>
        <?php while($row = $records_res->fetch_assoc()): ?>
            <div class="record-entry">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div style="display:flex; align-items:center; gap: 10px;">
                        <span style="font-size: 1.5rem;">🩺</span>
                        <h4 style="margin:0; color:var(--navy);"><?php echo date("M d, Y", strtotime($row['record_date'])); ?></h4>
                        <small style="color:var(--text-m); background:#f1f5f9; padding:2px 8px; border-radius:20px;"><?php echo date("h:i A", strtotime($row['record_time'])); ?></small>
                    </div>
                    <div style="text-align: right;">
                        <small style="color:var(--text-m); display:block;">Attending Staff</small>
                        <strong style="color:var(--teal);"><?php echo htmlspecialchars($row['attending_staff']); ?></strong>
                    </div>
                </div>

                <div class="vitals-strip">
                    <div class="vital-item"><small>Temp</small><p><?php echo $row['temperature']; ?>°C</p></div>
                    <div class="vital-item"><small>BP</small><p><?php echo $row['blood_pressure_systolic'] . '/' . $row['blood_pressure_diastolic']; ?></p></div>
                    <div class="vital-item"><small>Weight</small><p><?php echo $row['weight']; ?> kg</p></div>
                    <div class="vital-item"><small>Pulse</small><p><?php echo $row['pulse_rate']; ?> bpm</p></div>
                    <div class="vital-item"><small>Sugar</small><p><?php echo $row['blood_sugar']; ?> mg/dL</p></div>
                </div>

                <div style="margin-top: 1rem;">
                    <div style="margin-bottom: 0.5rem;">
                        <span style="color:var(--teal); font-weight:700;">Chief Complaint: </span>
                        <span style="color:var(--navy);"><?php echo htmlspecialchars($row['chief_complaint']); ?></span>
                    </div>
                    <div style="margin-bottom: 0.5rem;">
                        <span style="color:var(--teal); font-weight:700;">Diagnosis: </span>
                        <span style="color:var(--navy);"><?php echo htmlspecialchars($row['diagnosis']); ?></span>
                    </div>
                    <div>
                        <span style="color:var(--teal); font-weight:700;">Treatment: </span>
                        <span style="color:var(--navy);"><?php echo htmlspecialchars($row['treatment_given']); ?></span>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div style="text-align:center; padding:3rem; background:white; border-radius:20px; border:2px dashed var(--border);">
            <p style="color:var(--text-m);">No medical visit records found.</p>
        </div>
    <?php endif; ?>
</div>

</body>
</html>