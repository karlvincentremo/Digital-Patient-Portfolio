<?php
$pageTitle = "View Health Record - Barangay Health Center";
require_once('../includes/header.php');
$conn = getConnection();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// FIXED: Removed birthdate and gender from the query to prevent "Unknown Column" error
$sql = "SELECT h.*, p.first_name, p.last_name 
        FROM health_records h 
        JOIN patients p ON h.patient_id = p.id 
        WHERE h.id = $id";

$result = $conn->query($sql);

if (!$result || $result->num_rows == 0) {
    echo "<div class='alert alert-danger'>Record not found.</div>";
    require_once('../includes/footer.php');
    exit;
}

$row = $result->fetch_assoc();
?>

<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h2 class="card-title">📋 Health Record Details</h2>
        <div>
            <a href="index.php" class="btn btn-warning">← Back</a>
            <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">Edit Record</a>
        </div>
    </div>

    <div class="view-section" style="margin-bottom: 25px;">
        <h3 style="color: #2c5f2d; border-bottom: 2px solid #eee; padding-bottom: 5px;">👤 Patient Information</h3>
        <p style="font-size: 1.1rem;"><strong>Name:</strong> <?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></p>
        <p><strong>Visit Date:</strong> <?php echo date('F d, Y', strtotime($row['record_date'])); ?> at <?php echo date('h:i A', strtotime($row['record_time'])); ?></p>
    </div>

    <div class="view-section" style="margin-top: 20px;">
        <h3 style="color: #2c5f2d; border-bottom: 2px solid #eee; padding-bottom: 5px;">🌡️ Vital Signs</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-top: 10px;">
            <div class="vital-item"><strong>Temperature:</strong> <?php echo $row['temperature'] ? $row['temperature'] . '°C' : '-'; ?></div>
            <div class="vital-item"><strong>Blood Pressure:</strong> <?php echo $row['blood_pressure_systolic'] ? $row['blood_pressure_systolic'] . '/' . $row['blood_pressure_diastolic'] . ' mmHg' : '-'; ?></div>
            <div class="vital-item"><strong>Pulse Rate:</strong> <?php echo $row['pulse_rate'] ? $row['pulse_rate'] . ' bpm' : '-'; ?></div>
            <div class="vital-item"><strong>Respiratory:</strong> <?php echo $row['respiratory_rate'] ? $row['respiratory_rate'] . ' cpm' : '-'; ?></div>
            <div class="vital-item"><strong>Oxygen Sat:</strong> <?php echo $row['oxygen_saturation'] ? $row['oxygen_saturation'] . '%' : '-'; ?></div>
            <div class="vital-item"><strong>Weight:</strong> <?php echo $row['weight'] ? $row['weight'] . ' kg' : '-'; ?></div>
            <div class="vital-item"><strong>Height:</strong> <?php echo $row['height'] ? $row['height'] . ' cm' : '-'; ?></div>
            <div class="vital-item"><strong>Blood Sugar:</strong> <?php echo $row['blood_sugar'] ? $row['blood_sugar'] . ' mg/dL' : '-'; ?></div>
        </div>
    </div>

    <div class="view-section" style="margin-top: 25px;">
        <h3 style="color: #2c5f2d; border-bottom: 2px solid #eee; padding-bottom: 5px;">🩺 Clinical Findings</h3>
        <div style="margin-top: 10px;">
            <p><strong>Chief Complaint:</strong></p>
            <div style="background: #fdfdfd; padding: 10px; border-left: 4px solid #2c5f2d; margin-bottom: 15px;">
                <?php echo nl2br(htmlspecialchars($row['chief_complaint'] ?: 'None')); ?>
            </div>

            <p><strong>Diagnosis:</strong></p>
            <div style="background: #fdfdfd; padding: 10px; border-left: 4px solid #2c5f2d; margin-bottom: 15px;">
                <?php echo nl2br(htmlspecialchars($row['diagnosis'] ?: 'No diagnosis recorded')); ?>
            </div>

            <p><strong>Treatment Given:</strong></p>
            <div style="background: #fdfdfd; padding: 10px; border-left: 4px solid #2c5f2d; margin-bottom: 15px;">
                <?php echo nl2br(htmlspecialchars($row['treatment_given'] ?: 'No treatment recorded')); ?>
            </div>
        </div>
    </div>

    <div class="view-section" style="margin-top: 20px; background: #f4f7f6; padding: 20px; border-radius: 8px;">
        <p><strong>Attending Staff:</strong> <?php echo htmlspecialchars($row['attending_staff'] ?: 'Not specified'); ?></p>
        <hr>
        <p><strong>Additional Notes:</strong></p>
        <p><?php echo nl2br(htmlspecialchars($row['notes'] ?: 'No additional notes.')); ?></p>
    </div>
</div>

<?php 
$conn->close();
require_once '../includes/footer.php'; 
?>