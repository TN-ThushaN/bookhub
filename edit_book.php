<?php
session_start();
require 'include/dbcon.php';

if (!isset($_SESSION['admin_id'])) {
  header("Location: login.php");
  exit();
}

if (!isset($_GET['book_id'])) {
  header("Location: admin_books.php?error=Missing+book+ID");
  exit();
}

$book_id = intval($_GET['book_id']);


if (isset($_POST['update_book'])) {
  $title = $_POST['title'];
  $author = $_POST['author'];
  $isbn = $_POST['isbn'];
  $genre = $_POST['genre'];
  $quantity = $_POST['quantity'];

  $sql = "UPDATE books SET title=?, author=?, isbn=?, genre=?, quantity=? WHERE book_id=?";
  $stmt = mysqli_prepare($con, $sql);
  mysqli_stmt_bind_param($stmt, "ssssii", $title, $author, $isbn, $genre, $quantity, $book_id);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);

  header("Location: admin_books.php?success=Book+updated+successfully");
  exit();
}


$sql = "SELECT * FROM books WHERE book_id=?";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "i", $book_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$book = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$book) {
  header("Location: admin_books.php?error=Book+not+found");
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
      padding: 20px;
    }
    .container {
      max-width: 600px;
      margin: auto;
      background: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    h2 {
      text-align: center;
      color: #2c3e50;
    }
    input[type=text], input[type=number] {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    input[type=submit] {
      background-color: #3498db;
      color: white;
      border: none;
      padding: 10px 20px;
      cursor: pointer;
      border-radius: 5px;
    }
    input[type=submit]:hover {
      background-color: #2c80b4;
    }
    .back-link {
      display: inline-block;
      margin-bottom: 10px;
      text-decoration: none;
      color: #3498db;
    }
  </style>
</head>
<body>
  <div class="container">
    <a href="admin_books.php" class="back-link">← Back to Book List</a>
    <h2>Edit Book</h2>
    <form method="POST">
      <input type="text" name="title" value="<?= htmlspecialchars($book['title']) ?>" required>
      <input type="text" name="author" value="<?= htmlspecialchars($book['author']) ?>" required>
      <input type="text" name="isbn" value="<?= htmlspecialchars($book['isbn']) ?>" required>
      <input type="text" name="genre" value="<?= htmlspecialchars($book['genre']) ?>" required>
      <input type="number" name="quantity" value="<?= htmlspecialchars($book['quantity']) ?>" required>
      <input type="submit" name="update_book" value="Update Book">
    </form>
  </div>
</body>
</html>
