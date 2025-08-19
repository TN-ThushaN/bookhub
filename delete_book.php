<?php
session_start();
require 'include/dbcon.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['delete'])) {
    $book_id = $_GET['delete'];

    
    $check_stmt = mysqli_prepare($con, "SELECT * FROM books WHERE book_id = ?");
    mysqli_stmt_bind_param($check_stmt, "i", $book_id);
    mysqli_stmt_execute($check_stmt);
    $result = mysqli_stmt_get_result($check_stmt);

    if (mysqli_num_rows($result) > 0) {
        
        $stmt = mysqli_prepare($con, "DELETE FROM books WHERE book_id = ?");
        mysqli_stmt_bind_param($stmt, "i", $book_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        header("Location: book_management.php?success=Book+deleted+successfully");
    } else {
        header("Location: book_management.php?error=Book+not+found");
    }
} else {
    header("Location: book_management.php?error=Invalid+request");
    exit();
}
?>
