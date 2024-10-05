<?php
session_start();
session_destroy(); // End the session
header("Location: ../pages/home.php"); // Redirect to the login page
exit();
?>
