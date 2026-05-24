<<<<<<< HEAD
<?php
session_start();
require "include/dbcon.php";

$user_id = $_SESSION['user_id'];
$query = "UPDATE notifications SET is_read = 1 WHERE user_id = $user_id";
mysqli_query($con, $query);

header("Location: notifications.php");
exit();
=======
<?php
session_start();
require "include/dbcon.php";

$user_id = $_SESSION['user_id'];
$query = "UPDATE notifications SET is_read = 1 WHERE user_id = $user_id";
mysqli_query($con, $query);

header("Location: notifications.php");
exit();
>>>>>>> 5a31c04f2b6ace2b1b013822be65bcd13c895a56
