<?php
$pageTitle = "Edit Health Record - Barangay Health Center";
require_once('../includes/header.php');
$conn = getConnection();

// Safety: Define setAlert if it doesn't exist
if (!function_exists('setAlert')) {
    function setAlert($type, $message) {
        $_SESSION['alert_type'] = $type;
        $_SESSION['alert_message'] = $message;
    }
}

// 1. Get the ID of the record to edit
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// 2. Fetch current data for this record
$sql = "SELECT * FROM health_records WHERE id = $id";
$result = $conn->query($sql);

if (!$result || $result->num_rows == 0) {
    echo "<div class='alert alert-danger'>Record not found.</div>";
    require_once('../includes/footer.php');
    exit;
}
$record = $result->fetch_assoc();

// 3. Get patients list for the dropdown
$patients = $conn->query("SELECT id, first_name, last_name FROM patients ORDER BY last_name, first_name");

// 4. Handle the Form Submission (Update)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $patient_id = (int)$_POST['patient_id'];
    $record_date = sanitize($conn, $_POST['record_date']);
    $record_time = sanitize($conn, $_POST['record_time']);
    $temperature = $_POST['temperature'] ? (float)$_POST['temperature'] : null;
    $bp_systolic = $_POST['blood_pressure_systolic'] ? (int)$_POST['blood_pressure_systolic'] : null;
    $bp_diastolic = $_POST['blood_pressure_diastolic'] ? (int)$_POST['blood_pressure_diastolic'] : null;
    $pulse = $_POST['pulse_rate'] ? (int)$_POST['pulse_rate'] : null;
    $respiratory = $_POST['respiratory_rate'] ? (int)$_POST['respiratory_rate'] : null;
    $weight = $_POST['weight'] ? (float)$_POST['weight'] : null;
    $height = $_POST['height'] ? (float)$_POST['height'] : null;
    $blood_sugar = $_POST['blood_sugar'] ? (float)$_POST['blood_sugar'] : null;
    $oxygen = $_POST['oxygen_saturation'] ? (int)$_POST['oxygen_saturation'] : null;
    $complaint = sanitize($conn, $_POST['chief_complaint']);
    $diagnosis = sanitize($conn, $_POST['diagnosis']);
    $treatment = sanitize($conn, $_POST['treatment_given']);
    $staff = sanitize($conn, $_POST['attending_staff']);
    $notes = sanitize($conn, $_POST['notes']);
    
    $update_sql = "UPDATE health_records SET 
                    patient_id=?, record_date=?, record_time=?, temperature=?, 
                    blood_pressure_systolic=?, blood_pressure_diastolic=?, 
                    pulse_rate=?, respiratory_rate=?, weight=?, height=?, 
                    blood_sugar=?, oxygen_saturation=?, chief_complaint=?, 
                    diagnosis=?, treatment_given=?, attending_staff=?, notes=? 
                   WHERE id=?";
    
    if ($stmt = $conn->prepare($update_sql)) {
        // Types: 17 original fields + 1 ID at the end = 18 total parameters
        $stmt->bind_param("issdiiiidddisssssi", 
            $patient_id, $record_date, $record_time, $temperature, 
            $bp_systolic, $bp_diastolic, $pulse, $respiratory, 
            $weight, $height, $blood_sugar, $oxygen, 
            $complaint, $diagnosis, $treatment, $staff, $notes, $id
        );
        
        if ($stmt->execute()) {
            setAlert('success', 'Health record updated successfully!');
            echo "<script>window.location.href='index.php';</script>";
            exit;
        } else {
            setAlert('danger', 'Error updating record: ' . $conn->error);
        }
    }
}
?>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">📝 Edit Health Record</h2>
        <a href="index.php" class="btn btn-warning">← Back</a>
    </div>
    
    <form method="POST">
        <h3 style="margin-bottom: 1rem; color: #2c5f2d;">Patient & Visit Info</h3>
        <div class="form-row">
            <div class="form-group">
                <label>Patient *</label>
                <select name="patient_id" class="form-control" required>
                    <?php while ($p = $patients->fetch_assoc()): ?>
                        <option value="<?php echo $p['id']; ?>" <?php echo $record['patient_id'] == $p['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($p['last_name'] . ', ' . $p['first_name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Date *</label>
                <input type="date" name="record_date" class="form-control" value="<?php echo $record['record_date']; ?>" required>
            </div>
            <div class="form-group">
                <label>Time *</label>
                <input type="time" name="record_time" class="form-control" value="<?php echo $record['record_time']; ?>" required>
            </div>
        </div>
        
        <h3 style="margin: 1.5rem 0 1rem; color: #2c5f2d;">Vital Signs</h3>
        <div class="form-row">
            <div class="form-group">
                <label>Temperature (°C)</label>
                <input type="number" name="temperature" class="form-control" step="0.1" value="<?php echo $record['temperature']; ?>">
            </div>
            <div class="form-group">
                <label>BP (Systolic)</label>
                <input type="number" name="blood_pressure_systolic" class="form-control" value="<?php echo $record['blood_pressure_systolic']; ?>">
            </div>
            <div class="form-group">
                <label>BP (Diastolic)</label>
                <input type="number" name="blood_pressure_diastolic" class="form-control" value="<?php echo $record['blood_pressure_diastolic']; ?>">
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label>Pulse Rate (bpm)</label>
                <input type="number" name="pulse_rate" class="form-control" value="<?php echo $record['pulse_rate']; ?>">
            </div>
            <div class="form-group">
                <label>Respiratory Rate</label>
                <input type="number" name="respiratory_rate" class="form-control" value="<?php echo $record['respiratory_rate']; ?>">
            </div>
            <div class="form-group">
                <label>Oxygen Sat (%)</label>
                <input type="number" name="oxygen_saturation" class="form-control" value="<?php echo $record['oxygen_saturation']; ?>">
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label>Weight (kg)</label>
                <input type="number" name="weight" class="form-control" step="0.1" value="<?php echo $record['weight']; ?>">
            </div>
            <div class="form-group">
                <label>Height (cm)</label>
                <input type="number" name="height" class="form-control" step="0.1" value="<?php echo $record['height']; ?>">
            </div>
            <div class="form-group">
                <label>Blood Sugar</label>
                <input type="number" name="blood_sugar" class="form-control" step="0.1" value="<?php echo $record['blood_sugar']; ?>">
            </div>
        </div>
        
        <h3 style="margin: 1.5rem 0 1rem; color: #2c5f2d;">Clinical Notes</h3>
        <div class="form-group">
            <label>Chief Complaint</label>
            <textarea name="chief_complaint" class="form-control" rows="2"><?php echo htmlspecialchars($record['chief_complaint']); ?></textarea>
        </div>
        
        <div class="form-group">
            <label>Diagnosis</label>
            <textarea name="diagnosis" class="form-control" rows="2"><?php echo htmlspecialchars($record['diagnosis']); ?></textarea>
        </div>
        
        <div class="form-group">
            <label>Treatment Given</label>
            <textarea name="treatment_given" class="form-control" rows="2"><?php echo htmlspecialchars($record['treatment_given']); ?></textarea>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label>Attending Staff</label>
                <input type="text" name="attending_staff" class="form-control" value="<?php echo htmlspecialchars($record['attending_staff']); ?>">
            </div>
        </div>
        
        <div class="form-group">
            <label>Additional Notes</label>
            <textarea name="notes" class="form-control" rows="2"><?php echo htmlspecialchars($record['notes']); ?></textarea>
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Update Health Record</button>
            <a href="index.php" class="btn btn-warning">Cancel</a>
        </div>
    </form>
</div>

<?php 
$conn->close();
require_once '../includes/footer.php'; 
?>