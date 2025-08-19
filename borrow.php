<?php
session_start();
require 'include/dbcon.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['book_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$book_id = intval($_POST['book_id']);


$borrowed_query = mysqli_query($con, "SELECT COUNT(*) as count FROM transactions WHERE user_id = '$user_id' AND status = 'borrowed'");
$borrowed_count = mysqli_fetch_assoc($borrowed_query)['count'];

if ($borrowed_count >= 3) {
    echo "<script>alert('You have reached the borrowing limit of 3 books.'); window.location.href='index.php';</script>";
    exit();
}


$book_query = mysqli_query($con, "SELECT available_status FROM books WHERE book_id = '$book_id'");
$book = mysqli_fetch_assoc($book_query);

if (!$book || $book['available_status'] <= 0) {
    echo "<script>alert('Book is not available.'); window.location.href='index.php';</script>";
    exit();
}


$today = date('Y-m-d');
$due_date = date('Y-m-d', strtotime("+14 days")); 
$insert = mysqli_query($con, "INSERT INTO transactions (user_id, book_id, borrow_date, due_date, status, issue_date)
    VALUES ('$user_id', '$book_id', '$today', '$due_date', 'borrowed', '$today')");

if ($insert) {
    
    mysqli_query($con, "UPDATE books SET available_status = available_status - 1 WHERE book_id = '$book_id'");

    echo "<script>alert('Book borrowed successfully.'); window.location.href='books.php';</script>";
} else {
    echo "<script>alert('Failed to borrow the book.'); window.location.href='books.php';</script>";
}
?>
