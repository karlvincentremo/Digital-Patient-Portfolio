<?php
session_start();

// 1. Database Connection
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'barangay_health_db';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

// 2. Get the Patient ID
$selected_id = $_GET['baby_id'] ?? null;

// 3. Fetch Baby Name
$baby_name = "Patient";
if ($selected_id) {
    $name_res = $conn->query("SELECT first_name FROM patients WHERE id = '$selected_id'");
    if ($name_res && $row = $name_res->fetch_assoc()) {
        $baby_name = $row['first_name'];
    }
}

// 4. FETCH LIVE DATA
$categories = [];
$med_res = $conn->query("SELECT * FROM medicines ORDER BY category ASC, medicine_name ASC");

if ($med_res) {
    while($row = $med_res->fetch_assoc()){
        $categories[$row['category']][] = $row;
    }
} else {
    $error_msg = "Database Error: " . $conn->error;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicine List — BrgyHealth</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root { 
            --soft-teal: #14b8a6; 
            --mint: #f0fdfa; 
            --navy: #0f2744; 
            --bg: #f8fafc; 
        }
        
        body { font-family: 'DM Sans', sans-serif; background: var(--bg); margin: 0; display: flex; min-height: 100vh; }
        
        /* ======== SIDEBAR ======== */
        .sidebar { 
            width: 260px; 
            background: var(--navy); 
            color: white; 
            display: flex; 
            flex-direction: column; 
            padding: 2rem 1.5rem; 
            position: fixed; 
            top: 0; 
            left: 0; 
            bottom: 0; 
            z-index: 1000;
        }

        .logo { 
            font-family: 'Outfit', sans-serif; 
            font-size: 1.5rem; 
            font-weight: 700; 
            margin-bottom: 3rem; 
        }

        .nav-links { flex: 1; }

        .nav-item { 
            padding: 1rem; 
            color: rgba(255,255,255,0.7); 
            text-decoration: none; 
            border-radius: 12px; 
            margin-bottom: 0.5rem; 
            display: flex; 
            align-items: center; 
            gap: 12px; 
            transition: 0.3s;
        }

        .nav-item:hover, .nav-item.active { 
            background: rgba(255,255,255,0.1); 
            color: white; 
        }


        .logout-btn { 
            color: #fca5a5 !important; 
        }

        /* ======== MAIN CONTENT ======== */
        .main { margin-left: 310px; padding: 2rem 3rem; flex: 1; }
        
        .medicine-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); 
            gap: 1.5rem; 
            margin-top: 2rem; 
        }

        .category-card { background: white; border-radius: 20px; border: 1px solid #e2e8f0; padding: 1.5rem; box-shadow: 0 4px 6px rgba(0,0,0,0.02); }
        
        .status-pill { font-size: 0.65rem; padding: 3px 8px; border-radius: 6px; font-weight: 600; text-transform: uppercase; }
        .pill-In-Stock { background: var(--mint); color: var(--soft-teal); }
        .pill-Unavailable { background: #fef2f2; color: #ef4444; }

        .error-debug { background: #fffbeb; color: #92400e; padding: 1rem; border-radius: 10px; border: 1px solid #fef3c7; margin-bottom: 1rem; }
    </style>
</head>
<body>

<aside class="sidebar">
    <div class="logo">🏥 BrgyHealth</div>
    
    <nav class="nav-links">
        <a href="index.php?baby_id=<?= $selected_id ?>" class="nav-item">💉 Vaccine</a>
        <a href="medical_records.php?baby_id=<?= $selected_id ?>" class="nav-item">📋 Medical Records</a>
        <a href="medicine_list.php?baby_id=<?= $selected_id ?>" class="nav-item active">💊 Medicine List</a>
    </nav>

    <div class="sidebar-footer">
        <a href="../logout.php" class="nav-item logout-btn">🚪 Log Out</a>
    </div>
</aside>

<main class="main">
    <h1>Health Center Medications 💊</h1>
    <p>Live inventory for <strong><?= htmlspecialchars($baby_name) ?></strong></p>

    <?php if (isset($error_msg)): ?>
        <div class="error-debug"><strong>Table missing!</strong> Run the SQL to create the 'medicines' table. <br> Error: <?= $error_msg ?></div>
    <?php endif; ?>

    <?php if (empty($categories) && !isset($error_msg)): ?>
        <div class="error-debug">
            <strong>The table is empty!</strong> Please add medications in the Staff Dashboard to see them here.
        </div>
    <?php endif; ?>

    <div class="medicine-grid">
        <?php foreach ($categories as $catName => $meds): ?>
        <div class="category-card">
            <h3 style="color: var(--soft-teal); font-family: 'Outfit'; margin-top: 0;"><?= htmlspecialchars($catName) ?></h3>
            <?php foreach ($meds as $med): ?>
                <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #f1f5f9;">
                    <span style="font-size: 0.9rem; color: var(--navy); font-weight: 500;">
                        🔹 <?= htmlspecialchars($med['medicine_name']) ?>
                    </span>
                    <span class="status-pill pill-<?= str_replace(' ', '-', $med['status']) ?>">
                        <?= htmlspecialchars($med['status']) ?>
                    </span>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endforeach; ?>
    </div>
</main>

</body>
</html>