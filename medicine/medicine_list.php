<?php
session_start();
require_once '../config/database.php'; // Adjust path if necessary
$conn = getConnection();

// 1. Handle Status Toggle
if (isset($_GET['toggle_id'])) {
    $id = intval($_GET['toggle_id']);
    $current = $_GET['status'];
    $new_status = ($current == 'In Stock') ? 'Unavailable' : 'In Stock';
    $conn->query("UPDATE medicines SET status = '$new_status' WHERE id = $id");
    header("Location: medicine_list.php");
    exit();
}

// 2. Fetch all medicines
$res = $conn->query("SELECT * FROM medicines ORDER BY category ASC, medicine_name ASC");
$categories = [];
while ($row = $res->fetch_assoc()) {
    $categories[$row['category']][] = $row;
}

$staffName = htmlspecialchars($_SESSION['staff_name'] ?? 'Staff');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Medicine Inventory — BrgyHealth</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        /* Re-using your exact dashboard variables and layout */
        :root {
            --forest-deepest: #0a1f0e;
            --forest-leaf: #3a9c58;
            --forest-light: #4db870;
            --fog: #edf7ef;
            --navy: #0f2744;
            --sidebar-w: 260px;
        }

        body { font-family: 'DM Sans', sans-serif; background: var(--fog); margin: 0; display: flex; }

        /* ======== SIDEBAR (Your Theme) ======== */
        .sidebar {
            width: var(--sidebar-w); background: var(--forest-deepest);
            position: fixed; top: 0; left: 0; bottom: 0;
            display: flex; flex-direction: column; z-index: 100; color: white;
        }
        .sidebar-logo { padding: 28px 24px; border-bottom: 1px solid rgba(77,184,112,0.15); }
        .sidebar-logo h2 { font-family: 'Playfair Display', serif; font-size: 15px; margin: 0; }
        .nav-section { padding: 20px 16px; flex: 1; }
        .sidebar a {
            display: flex; align-items: center; gap: 12px; color: rgba(255,255,255,0.75);
            text-decoration: none; padding: 11px 14px; border-radius: 10px; font-size: 14px; transition: 0.2s;
        }
        .sidebar a:hover, .sidebar a.active { background: rgba(77,184,112,0.15); color: white; }
        .sidebar a.active { font-weight: 500; box-shadow: inset 0 0 0 1px rgba(77,184,112,0.3); }

        /* ======== MAIN CONTENT ======== */
        .main { margin-left: var(--sidebar-w); padding: 32px 36px; width: 100%; }
        .topbar h1 { font-family: 'Playfair Display', serif; color: var(--forest-deepest); margin: 0; }

        .medicine-grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px; margin-top: 30px;
        }
        .category-card {
            background: white; border-radius: 18px; padding: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid rgba(255,255,255,0.8);
        }
        .cat-title { color: var(--forest-leaf); border-bottom: 2px solid #f0fdfa; margin-bottom: 15px; font-weight: 600; }
        
        .med-row { display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid #f1f5f9; }
        
        /* Toggle Buttons */
        .status-btn { 
            padding: 5px 12px; border-radius: 20px; text-decoration: none; font-size: 0.75rem; font-weight: 600; transition: 0.2s;
        }
        .btn-In-Stock { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
        .btn-Unavailable { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
    </style>
</head>
<body>

<aside class="sidebar">
    <div class="sidebar-logo">
        <span style="font-size: 28px;">🌿</span>
        <h2>Barangay Health Center</h2>
    </div>
    <nav class="nav-section">
        <a href="../index.php">📊 Dashboard</a>
        <a href="../patients/index.php">👥 Patients</a>
        <a href="medicine_list.php" class="active">💊 Medicine List</a>
    </nav>
<div style="padding: 1.25rem; border-top: 1px solid rgba(255,255,255,.08); margin-top: auto;">
    <a href="/health_monitoring/logout.php" class="premium-logout">
        <span class="logout-icon">🚪</span>
        <span class="logout-text">Sign Out</span>
    </a>
</div>
</aside>

<main class="main">
    <div class="topbar">
        <h1>Medicine Inventory 💊</h1>
        <p style="color: #6b8f74;">Toggle stock availability for the patient portal.</p>
    </div>

    <div class="medicine-grid">
        <?php foreach ($categories as $catName => $meds): ?>
        <div class="category-card">
            <div class="cat-title"><?= $catName ?></div>
            <?php foreach ($meds as $m): ?>
            <div class="med-row">
                <span style="font-size: 13px;"><?= $m['medicine_name'] ?></span>
                <a href="?toggle_id=<?= $m['id'] ?>&status=<?= $m['status'] ?>" 
                   class="status-btn btn-<?= str_replace(' ', '-', $m['status']) ?>">
                    <?= $m['status'] ?>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endforeach; ?>
    </div>
</main>

</body>
</html>