<?php
// Use the header you already have—this file contains the database connection
require_once('../includes/header.php'); 
$conn = getConnection();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    // We use the column name 'appointment_id' to match your database
    $stmt = $conn->prepare("UPDATE appointments SET status = 'Completed' WHERE appointment_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    if (function_exists('setAlert')) {
        setAlert('success', 'Appointment marked as completed!');
    }
}

// Send them back to the list
header('Location: index.php');
exit;