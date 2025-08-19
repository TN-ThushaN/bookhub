<?php
session_start();
require "include/dbcon.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_id'])) {
    $book_id = intval($_POST['book_id']);
    $user_id = $_SESSION['user_id'];
    $return_date = date('Y-m-d');

    
    $check = $con->prepare("SELECT * FROM transactions 
                            WHERE user_id = ? AND book_id = ? AND status = 'borrowed'");
    $check->bind_param("ii", $user_id, $book_id);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        
        $update = $con->prepare("UPDATE transactions 
                                 SET status = 'returned', return_date = ? 
                                 WHERE user_id = ? AND book_id = ? AND status = 'borrowed'");
        $update->bind_param("sii", $return_date, $user_id, $book_id);
        $update->execute();

        
        $bookUpdate = $con->prepare("UPDATE books 
                                     SET available_status = available_status + 1 
                                     WHERE book_id = ?");
        $bookUpdate->bind_param("i", $book_id);
        $bookUpdate->execute();

        $_SESSION['borrow_msg'] = "✅ Book returned successfully.";
    } else {
        $_SESSION['borrow_msg'] = "⚠️ No active borrow record found.";
    }
} else {
    $_SESSION['borrow_msg'] = "❌ Invalid return request.";
}

header("Location: my_books.php");
exit();
