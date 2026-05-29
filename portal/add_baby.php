<?php
session_start();

// 1. SECURITY: Check if parent is logged in
if (!isset($_SESSION['staff_id']) || $_SESSION['role'] !== 'client') {
    header('Location: ../login.php');
    exit;
}

$conn = new mysqli('localhost', 'root', '', 'barangay_health_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $parent_id = $_SESSION['staff_id'];
    $input_uid = $conn->real_escape_string($_POST['uid']); // Get UID from form

    // 2. CHECK: Find the patient record created by the doctor/staff
    $check_sql = "SELECT id, first_name, last_name, parent_id FROM patients WHERE uid = '$input_uid'";
    $result = $conn->query($check_sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $patient_id = $row['id'];

        // 3. VALIDATION: Check if someone else already claimed this baby
        if ($row['parent_id'] != 0 && $row['parent_id'] != $parent_id) {
            $error = "This UID has already been claimed by another account.";
        } else {
            // 4. LINK: Update the record to set the parent_id
            $update_sql = "UPDATE patients SET parent_id = '$parent_id' WHERE id = '$patient_id'";
            
            if ($conn->query($update_sql)) {
                // Success! Redirect to the selection page
                header('Location: select_baby.php?success=linked');
                exit;
            } else {
                $error = "Database error: " . $conn->error;
            }
        }
    } else {
        $error = "Invalid UID. Please double-check the 6-digit code provided by the Health Center.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Link Baby Profile — BrgyHealth</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@600;800&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'DM Sans', sans-serif; background: #f0fafa; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .card { background: white; padding: 40px; border-radius: 24px; box-shadow: 0 15px 35px rgba(13,148,136,0.1); width: 100%; max-width: 450px; }
        h1 { font-family: 'Outfit'; color: #0f2744; margin-bottom: 8px; font-size: 1.8rem; }
        p.sub { color: #64748b; margin-bottom: 30px; font-size: 0.9rem; }
        .fg { margin-bottom: 20px; }
        label { display: block; font-weight: 600; color: #0f2744; margin-bottom: 8px; font-size: 0.85rem; }
        input, select { width: 100%; padding: 12px 15px; border-radius: 12px; border: 1.5px solid #e2e8f0; font-size: 0.95rem; background: #f8fafc; box-sizing: border-box; }
        .btn { background: #0d9488; color: white; border: none; padding: 15px; width: 100%; border-radius: 12px; font-weight: 700; cursor: pointer; transition: 0.3s; margin-top: 10px; }
        .btn:hover { background: #0f766e; }
        .back { display: block; text-align: center; margin-top: 20px; color: #64748b; text-decoration: none; font-size: 0.85rem; }
    </style>
</head>
<body>

<div class="card">
    <h1>Link Baby Profile 👶</h1>
    <p class="sub">Enter the 6-digit UID code provided by the Health Center to sync your baby's roadmap.</p>

    <?php if($error): ?>
        <div style="background: #fee2e2; color: #b91c1c; padding: 12px; border-radius: 12px; margin-bottom: 20px; font-size: 0.85rem; border: 1px solid #fecaca;">
            ⚠️ <?= $error ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="fg">
            <label>6-Digit UID Code</label>
            <input type="text" name="uid" placeholder="e.g., 123456" maxlength="6" pattern="\d{6}" style="font-size: 1.2rem; letter-spacing: 4px; text-align: center;" required>
        </div>

        <button type="submit" class="btn">Connect to Roadmap →</button>
        <a href="select_baby.php" class="back">← Cancel and Go Back</a>
    </form>
    
    <p style="text-align: center; margin-top: 25px; color: #94a3b8; font-size: 0.75rem; line-height: 1.4;">
        Don't have a code? The Health Center staff generates this code when they register your child at the clinic.
    </p>
</div>

</body>
</html>