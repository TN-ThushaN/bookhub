<?php
session_start();
require 'include/dbcon.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header("Location: login.php");
  exit();
}

if (!isset($_GET['book_id'])) {
  header("Location: book_management.php?error=No+book+selected");
  exit();
}

$book_id = $_GET['book_id'];


$stmt = mysqli_prepare($con, "SELECT * FROM books WHERE book_id = ?");
mysqli_stmt_bind_param($stmt, "i", $book_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$book = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$book) {
  header("Location: book_management.php?error=Book+not+found");
  exit();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_book'])) {
  $title = $_POST['title'];
  $author = $_POST['author'];
  $isbn = $_POST['isbn'];
  $genre = $_POST['genre'];
  $quantity = $_POST['quantity'];

  $update_sql = "UPDATE books SET title = ?, author = ?, isbn = ?, genre = ?, quantity = ? WHERE book_id = ?";
  $stmt = mysqli_prepare($con, $update_sql);
  mysqli_stmt_bind_param($stmt, "ssssii", $title, $author, $isbn, $genre, $quantity, $book_id);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);

  header("Location: book_management.php?success=Book+updated+successfully");
  exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit Book</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f4f4;
      padding: 30px;
    }
    h2 {
      text-align: center;
      color: #2c3e50;
    }
    form {
      background: #fff;
      padding: 20px;
      max-width: 600px;
      margin: 0 auto;
      border-radius: 8px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    input[type="text"], input[type="number"] {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border-radius: 5px;
      border: 1px solid #ccc;
    }
    input[type="submit"] {
      background: #27ae60;
      color: white;
      padding: 10px 20px;
      border: none;
      cursor: pointer;
      border-radius: 5px;
    }
    input[type="submit"]:hover {
      background: #219150;
    }
    .back-btn {
      display: block;
      text-align: center;
      margin-top: 20px;
      text-decoration: none;
      background: #7f8c8d;
      color: white;
      padding: 8px 15px;
      border-radius: 5px;
    }
  </style>
</head>
<body>

<h2>✏️ Edit Book</h2>

<form method="POST" action="">
  <input type="text" name="title" value="<?= htmlspecialchars($book['title']) ?>" required>
  <input type="text" name="author" value="<?= htmlspecialchars($book['author']) ?>" required>
  <input type="text" name="isbn" value="<?= htmlspecialchars($book['ISBN']) ?>" required>
  <input type="text" name="genre" value="<?= htmlspecialchars($book['genre']) ?>" required>
  <input type="number" name="quantity" value="<?= htmlspecialchars($book['quantity']) ?>" required>
  <input type="submit" name="update_book" value="Update Book">
</form>

<a href="book_management.php" class="back-btn">← Back to Book Management</a>

</body>
</html>
