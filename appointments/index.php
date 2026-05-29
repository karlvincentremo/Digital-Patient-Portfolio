<?php
$pageTitle = "Appointments - Barangay Health Center";
require_once('../includes/header.php');
$conn = getConnection();

$date_filter = isset($_GET['date']) ? sanitize($conn, $_GET['date']) : '';
$status_filter = isset($_GET['status']) ? sanitize($conn, $_GET['status']) : '';

$where = "WHERE 1=1";
if ($date_filter != '') {
    $where .= " AND a.appointment_date = '$date_filter'";
}
if ($status_filter != '') {
    $where .= " AND a.status = '$status_filter'";
}

$appointments = $conn->query("
    SELECT a.*, p.first_name, p.last_name, p.contact_number 
    FROM appointments a 
    JOIN patients p ON a.patient_id = p.id 
    $where 
    ORDER BY a.appointment_date ASC, a.appointment_time ASC
");
?>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">📅 Appointments</h2>
        <a href="add.php" class="btn btn-primary">+ Schedule Appointment</a>
    </div>
    
    <form method="GET" class="search-box">
        <input type="date" name="date" class="form-control" style="max-width: 200px;" value="<?php echo $date_filter; ?>">
        <select name="status" class="form-control" style="max-width: 200px;">
            <option value="">All Status</option>
            <option value="Pending" <?php echo $status_filter == 'Pending' ? 'selected' : ''; ?>>Pending</option>
            <option value="Completed" <?php echo $status_filter == 'Completed' ? 'selected' : ''; ?>>Completed</option>
            <option value="Cancelled" <?php echo $status_filter == 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
        </select>
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="index.php?date=<?php echo date('Y-m-d'); ?>" class="btn btn-warning">Today</a>
        <a href="index.php" class="btn btn-secondary" style="background: #6c757d; color: white; text-decoration: none; padding: 8px 15px; border-radius: 5px; font-size: 14px;">Show All</a>
    </form>
    
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Patient</th>
                    <th>Contact</th>
                    <th>Purpose</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($appointments && $appointments->num_rows > 0): ?>
                    <?php while ($row = $appointments->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo date('M d, Y', strtotime($row['appointment_date'])); ?></td>
                        <td><?php echo date('h:i A', strtotime($row['appointment_time'])); ?></td>
                        <td>
                            <a href="../patients/view.php?id=<?php echo $row['patient_id']; ?>">
                                <?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?>
                            </a>
                        </td>
                        <td><?php echo htmlspecialchars($row['contact_number'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($row['purpose']); ?></td>
                        <td>
                            <span class="badge badge-<?php 
                                echo $row['status'] == 'Completed' ? 'success' : 
                                    ($row['status'] == 'Cancelled' ? 'danger' : 'info'); 
                            ?>">
                                <?php echo htmlspecialchars($row['status'] ?? 'Pending'); ?>
                            </span>
                        </td>
                        <td class="action-buttons">
                            <?php $aid = $row['appointment_id']; ?>
                            
                            <?php if($row['status'] !== 'Completed'): ?>
                                <a href="complete.php?id=<?php echo $aid; ?>" class="btn btn-success btn-sm" title="Mark as Completed">✓</a>
                            <?php endif; ?>

                            <a href="edit.php?id=<?php echo $aid; ?>" class="btn btn-warning btn-sm">Edit</a>
                            
                            <a href="delete.php?id=<?php echo $aid; ?>" 
                               class="btn btn-danger btn-sm" 
                               onclick="return confirm('Are you sure you want to PERMANENTLY delete this appointment? This cannot be undone.')">
                               Delete
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No appointments found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php 
$conn->close();
require_once '../includes/footer.php'; 
?>