<?php
session_start();
require "include/dbcon.php";

$user_id = $_SESSION['user_id'];
$query = "UPDATE notifications SET is_read = 1 WHERE user_id = $user_id";
mysqli_query($con, $query);

header("Location: notifications.php");
exit();
