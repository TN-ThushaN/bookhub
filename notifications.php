<?php
session_start();
require "include/dbcon.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];


$markStmt = $con->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ? AND is_read = 0");
$markStmt->bind_param("i", $user_id);
$markStmt->execute();
$markStmt->close();


$stmt = $con->prepare("
    SELECT notifications_id, message, created_at, is_read
    FROM notifications
    WHERE user_id = ?
    ORDER BY created_at DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Notifications</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      background-color: #f4f4f4;
      padding-bottom: 60px;
    }

    header {
      background-color: #2c3e50;
      color: white;
      text-align: center;
      padding: 15px;
    }

    nav {
      background-color: #34495e;
      text-align: center;
      padding: 10px;
    }

    nav a {
      color: white;
      text-decoration: none;
      margin: 0 15px;
      font-weight: bold;
    }

    .container {
      padding: 20px;
      max-width: 900px;
      margin: auto;
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
    }

    .notification {
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      padding: 15px 20px;
      margin-bottom: 15px;
      border-left: 6px solid #2c3e50;
    }

    .notification .sender {
      font-weight: 700;
      color: #34495e;
      margin-bottom: 8px;
    }

    .notification .message {
      white-space: pre-wrap;
      margin-bottom: 8px;
      color: #333;
    }

    .notification .date {
      font-size: 0.85rem;
      color: #777;
      text-align: right;
    }

    .back-btn {
      display: inline-block;
      margin: 20px 0;
      padding: 10px 20px;
      background-color: #2c3e50;
      color: white;
      text-decoration: none;
      border-radius: 5px;
    }

    .back-btn:hover {
      background-color: #34495e;
    }

    footer {
      background-color: #2c3e50;
      color: white;
      text-align: center;
      padding: 1px 0;
      position: fixed;
      bottom: 0;
      width: 100%;
    }
  </style>
</head>
<link rel="icon" href="logo.png" type="image/png">
<body>

<header>
  <h1>📩 Book Hub - Notifications</h1>
</header>

<nav>
  <a href="index.php">Home</a>
  <a href="books.php">Books</a>
  <a href="transactions.php">Transactions</a>
  <a href="notifications.php">Notifications</a>
  <a href="logout.php">Logout</a>
</nav>

<div class="container">
  <h2>Your Notifications</h2>

  <?php if ($result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
      <div class="notification">
        <div class="sender">From: Admin</div>
        <div class="message"><?= nl2br(htmlspecialchars($row['message'])) ?></div>
        <div class="date"><?= date("M d, Y h:i A", strtotime($row['created_at'])) ?></div>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p style="text-align: center; color: #666;">No notifications found.</p>
  <?php endif; ?>

  <a href="index.php" class="back-btn">⬅ Back to Home</a>
</div>

<footer>
  <p>&copy; 2025 Library Management System. All rights reserved.</p>
</footer>

</body>
</html>
