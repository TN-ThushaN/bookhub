<?php
session_start();
require 'include/dbcon.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header("Location: login.php");
  exit();
}


if (isset($_POST['add_book'])) {
  $title = $_POST['title'];
  $author = $_POST['author'];
  $isbn = $_POST['isbn'];
  $genre = $_POST['genre'];
  $quantity = $_POST['quantity'];

  
  $sql = "INSERT INTO books (title, author, isbn, genre, quantity, available_status) VALUES (?, ?, ?, ?, ?, 1)";
  $stmt = mysqli_prepare($con, $sql);
  mysqli_stmt_bind_param($stmt, "ssssi", $title, $author, $isbn, $genre, $quantity);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);
  header("Location: book_management.php?success=Book+added+successfully");
  exit();
}


if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  $sql = "DELETE FROM books WHERE book_id = ?";
  $stmt = mysqli_prepare($con, $sql);
  mysqli_stmt_bind_param($stmt, "i", $id);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);
  header("Location: book_management.php?success=Book+deleted+successfully");
  exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin - Book Management</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f0f2f5;
      margin: 0;
      padding: 20px;
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #2c3e50;
    }

    .back-btn {
      display: inline-block;
      margin-bottom: 20px;
      padding: 8px 15px;
      background-color: #7f8c8d;
      color: white;
      border-radius: 5px;
      text-decoration: none;
    }

    .message {
      text-align: center;
      padding: 10px;
      border-radius: 5px;
      width: 80%;
      margin: 0 auto 20px;
    }

    .success { background-color: #d4edda; color: #155724; }
    .error { background-color: #f8d7da; color: #721c24; }

    form.add-form {
      background: #ffffff;
      padding: 20px;
      border-radius: 8px;
      margin-bottom: 30px;
      max-width: 600px;
      margin-left: auto;
      margin-right: auto;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    form.add-form h3 {
      margin-top: 0;
      color: #27ae60;
    }

    .form-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
      gap: 10px;
    }

    input[type=text], input[type=number] {
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      width: 100%;
    }

    input[type=submit] {
      margin-top: 15px;
      background-color: #27ae60;
      color: white;
      padding: 10px;
      border: none;
      cursor: pointer;
      border-radius: 5px;
    }

    input[type=submit]:hover {
      background-color: #219150;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background: #fff;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    th, td {
      padding: 12px;
      text-align: center;
      border-bottom: 1px solid #ddd;
    }

    th {
      background-color: #2980b9;
      color: white;
    }

    tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    .actions {
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    .update-btn {
      background-color: #3498db;
      color: white;
      padding: 6px 10px;
      text-decoration: none;
      border-radius: 5px;
      display: inline-block;
    }

    .delete-btn {
      background-color: #e74c3c;
      color: white;
      padding: 6px 10px;
      text-decoration: none;
      border-radius: 5px;
      display: inline-block;
    }

    .update-btn:hover { background-color: #2d80c2; }
    .delete-btn:hover { background-color: #c0392b; }

    .bottom-back {
      display: block;
      margin-top: 40px;
      text-align: center;
    }
  </style>
</head>
<body>

<h2>📚 Admin - Book Management</h2>

<?php if (isset($_GET['success'])): ?>
  <div class="message success"><?= htmlspecialchars($_GET['success']) ?></div>
<?php elseif (isset($_GET['error'])): ?>
  <div class="message error"><?= htmlspecialchars($_GET['error']) ?></div>
<?php endif; ?>

<form class="add-form" method="POST" action="book_management.php">
  <h3>Add New Book</h3>
  <div class="form-grid">
    <input type="text" name="title" placeholder="Title" required>
    <input type="text" name="author" placeholder="Author" required>
    <input type="text" name="isbn" placeholder="ISBN" required>
    <input type="text" name="genre" placeholder="Genre" required>
    <input type="number" name="quantity" placeholder="Quantity" required>
  </div>
  <input type="submit" name="add_book" value="Add Book">
</form>

<h3 style="text-align:center; color:#2c3e50;">📖 Book List</h3>
<table>
  <tr>
    <th>ID</th>
    <th>Title</th>
    <th>Author</th>
    <th>ISBN</th>
    <th>Genre</th>
    <th>Quantity</th>
    <th>Actions</th>
  </tr>
  <?php
  $result = mysqli_query($con, "SELECT * FROM books ORDER BY book_id DESC");
  while ($row = mysqli_fetch_assoc($result)):
  ?>
    <tr>
      <td><?= htmlspecialchars($row['book_id']) ?></td>
      <td><?= htmlspecialchars($row['title']) ?></td>
      <td><?= htmlspecialchars($row['author']) ?></td>
      <td><?= htmlspecialchars($row['ISBN']) ?></td>
      <td><?= htmlspecialchars($row['genre']) ?></td>
      <td><?= htmlspecialchars($row['quantity']) ?></td>
      <td class="actions">
        <a href="update.php?book_id=<?= $row['book_id'] ?>" class="update-btn">Update</a>
        <a href="delete_book.php?delete=<?= $row['book_id'] ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this book?');">Delete</a>
      </td>
    </tr>
  <?php endwhile; ?>
</table>

<a href="admin-dashboard.php" class="back-btn bottom-back">← Back to Dashboard</a>

</body>
<link rel="icon" href="logo.png" type="image/png">
</html>
