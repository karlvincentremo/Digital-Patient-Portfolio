<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'barangay_health_db');

// 1. Get the IDs from the URL
$record_id = isset($_GET['id']) ? $conn->real_escape_string($_GET['id']) : null;
$baby_id = isset($_GET['baby_id']) ? $conn->real_escape_string($_GET['baby_id']) : null;

if ($record_id && $baby_id) {
    // 2. Update the status to 'Completed' and set today's date
    $today = date('Y-m-d');
    $sql = "UPDATE immunization_records 
            SET status = 'Completed', 
                date_given = '$today' 
            WHERE id = '$record_id'";

    if ($conn->query($sql)) {
        // 3. If successful, show a quick success message then redirect
        echo "
        <!DOCTYPE html>
        <html>
        <head>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Record Updated',
                    text: 'Vaccination has been marked as completed.',
                    confirmButtonColor: '#0d9488',
                    timer: 2000,
                    showConfirmButton: false
                }).then(function() {
                    window.location.href = 'view_baby_records.php?baby_id=$baby_id';
                });
            </script>
        </body>
        </html>";
    } else {
        echo "Error updating record: " . $conn->error;
    }
} else {
    die("Error: Missing required information to update vaccine status.");
}
?>