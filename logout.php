<?php
session_start();
session_destroy(); // This deletes the session data
header("Location: login.php"); // Send them back to the login page
exit();
?>