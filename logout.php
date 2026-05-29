<?php
session_start();
session_unset();
session_destroy();

// Change this line to point to your new landing page folder
header("Location: landingpage/index.php"); 
exit();
?>