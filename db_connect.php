<?php
$servername = "localhost";
$username = "root"; // This is the default username for XAMPP
$password = "";     // The default password is just completely empty
$dbname = "online_exam_db"; // The database we just created

// Create the connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection worked
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>