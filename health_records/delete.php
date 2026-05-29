<?php
require_once('../includes/header.php'); // This handles connection and session
$conn = getConnection();

// 1. Get the ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    // 2. Prepare the delete statement
    $stmt = $conn->prepare("DELETE FROM health_records WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        // 3. Set success message
        if (function_exists('setAlert')) {
            setAlert('success', 'Health record deleted successfully.');
        }
    } else {
        if (function_exists('setAlert')) {
            setAlert('danger', 'Error deleting record: ' . $conn->error);
        }
    }
    $stmt->close();
}

// 4. Redirect back to the list
echo "<script>window.location.href='index.php';</script>";
exit;
?>