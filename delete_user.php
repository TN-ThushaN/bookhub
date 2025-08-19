<?php
session_start();
require 'include/dbcon.php';


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}


if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $user_id = (int)$_GET['id'];

    
    if ($user_id == $_SESSION['admin_id']) {
        header("Location: user_management.php?error=You cannot delete your own account.");
        exit();
    }

    
    $query = "DELETE FROM user WHERE user_id = $user_id LIMIT 1";
    $result = mysqli_query($con, $query);

    if ($result) {
        header("Location: user_management.php?success=User deleted successfully.");
        exit();
    } else {
        header("Location: user_management.php?error=Failed to delete user.");
        exit();
    }
} else {
    header("Location: user_management.php?error=Invalid user ID.");
    exit();
}
