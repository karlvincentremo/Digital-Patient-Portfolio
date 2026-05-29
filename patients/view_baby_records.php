<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'barangay_health_db');

$baby_id = isset($_GET['baby_id']) ? $conn->real_escape_string($_GET['baby_id']) : null;

if (!$baby_id) {
    die("Error: No patient ID provided.");
}

$info_sql = "SELECT * FROM patients WHERE id = '$baby_id'";
$info_res = $conn->query($info_sql);
$baby = $info_res->fetch_assoc();

if (!$baby) {
    die("Error: Patient record not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vaccine Schedule — BrgyHealth</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@600;800&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        body { font-family: 'DM Sans', sans-serif; background: #f8fafc; padding: 40px; }
        .header { margin-bottom: 30px; }
        h1 { font-family: 'Outfit'; color: #0f2744; }
        .table-card { background: white; border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); overflow: hidden; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #f1f5f9; padding: 15px; text-align: left; color: #64748b; font-size: 0.8rem; text-transform: uppercase; }
        td { padding: 15px; border-bottom: 1px solid #f1f5f9; }
        
        .btn-update { 
            background: #0d9488; 
            color: white; 
            padding: 8px 16px; 
            border-radius: 8px; 
            font-size: 0.85rem; 
            font-weight: 600; 
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn-update:hover { background: #0f766e; transform: scale(1.02); }
        
        .done-badge { 
            color: #15803d; 
            font-weight: 700; 
            display: flex; 
            align-items: center; 
            gap: 5px;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .uid-bar { background: #0f2744; color: white; padding: 15px 25px; border-radius: 12px; margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center; }
        .uid-value { font-family: 'Outfit'; font-weight: 700; color: #5eead4; font-size: 1.3rem; letter-spacing: 2px; }
    </style>
</head>
<body>

<div class="header">
    <a href="view.php?id=<?= $baby_id ?>" style="text-decoration:none; color:#0d9488; font-weight: 600;">← Back to Profile</a>
    <h1><?= htmlspecialchars($baby['first_name']) ?>'s Vaccine Track</h1>
</div>

<div class="uid-bar">
    <div>
        <p style="margin:0; font-size: 0.7rem; text-transform: uppercase; color: #94a3b8;">Patient UID</p>
        <span class="uid-value"><?= $baby['uid'] ?: '---' ?></span>
    </div>
</div>

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>Age</th>
                <th>Vaccine Name</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $records = $conn->query("SELECT * FROM immunization_records WHERE baby_id = '$baby_id'");
            while($row = $records->fetch_assoc()):
                $is_done = (trim(strtolower($row['status'])) !== 'pending');
            ?>
            <tr>
                <td><?= htmlspecialchars($row['scheduled_age']) ?></td>
                <td><strong><?= htmlspecialchars($row['vaccine_name']) ?></strong></td>
                <td>
                    <span style="color: <?= $is_done ? '#15803d' : '#64748b' ?>;">
                        <?= $is_done ? '✅ Completed' : '⏳ Pending' ?>
                    </span>
                </td>
                <td>
                    <?php if (!$is_done): ?>
                        <button type="button" class="btn-update" 
                                onclick="verifyAndMark('<?= $row['id'] ?>', '<?= $baby_id ?>', '<?= addslashes(htmlspecialchars($row['vaccine_name'])) ?>')">
                            Mark as Done
                        </button>
                    <?php else: ?>
                        <div class="done-badge">
                            <span>✓ Marked Done</span>
                        </div>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script>
function verifyAndMark(recordId, babyId, vaccineName) {
    Swal.fire({
        title: 'Security Verification',
        text: "Confirm: Has the baby successfully received the " + vaccineName + " vaccine?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#0d9488',
        cancelButtonColor: '#ef4444',
        confirmButtonText: 'Yes, Mark as Vaccinated',
        cancelButtonText: 'No, Cancel',
        borderRadius: '15px'
    }).then((result) => {
        if (result.isConfirmed) {
            // Success animation before redirecting
            Swal.fire({
                title: 'Updating...',
                timer: 800,
                showConfirmButton: false,
                didOpen: () => { Swal.showLoading() }
            }).then(() => {
                window.location.href = "update_vaccine.php?id=" + recordId + "&baby_id=" + babyId;
            });
        }
    });
}
</script>

</body>
</html>