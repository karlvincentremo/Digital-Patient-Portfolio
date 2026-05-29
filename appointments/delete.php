<?php
// Direct connection using your exact database name
$servername = "localhost";
$username = "root";     
$password = "";         
$dbname = "barangay_health_db"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // We use 'appointment_id' because that's usually the primary key in this system
    $sql = "DELETE FROM appointments WHERE appointment_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header('Location: index.php?status=deleted');
        exit;
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

$conn->close();
header('Location: index.php');
exit;
?>