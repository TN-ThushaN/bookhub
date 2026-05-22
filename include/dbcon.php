<?php
// Database connection configuration
$host = "localhost";
$username = "root";
$password = ""; // Default Laragon/XAMPP password is empty
$database = "bookhub"; // Adjust if your database name is different

// Create connection
$con = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
