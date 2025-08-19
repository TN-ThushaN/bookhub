<?php
session_start();
require "include/dbcon.php";


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}


$user_result = mysqli_query($con, "SELECT user_id, username FROM users ORDER BY username ASC");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = trim($_POST['message']);
    $target_user = $_POST['target_user'];

    if ($message !== "") {
        if ($target_user === "all") {
            $users_query = mysqli_query($con, "SELECT user_id FROM users");
            while ($user = mysqli_fetch_assoc($users_query)) {
                $uid = $user['user_id'];
                mysqli_query($con, "INSERT INTO notifications (user_id, message, created_at, is_read) VALUES ('$uid', '$message', NOW(), 0)");
            }
            $status = "Notification sent to all users.";
        } else {
            $uid = intval($target_user);
            mysqli_query($con, "INSERT INTO notifications (user_id, message, created_at, is_read) VALUES ('$uid', '$message', NOW(), 0)");
            $status = "Notification sent to selected user.";
        }
    } else {
        $error = "Message cannot be empty.";
    }
}
?>
<link rel="icon" href="logo.png" type="image/png">
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Send Notification</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f4f4;
      margin: 0;
      padding: 0 20px;
    }
    header, footer {
      background: #2c3e50;
      color: #fff;
      text-align: center;
      padding: 15px 0;
    }
    .container {
      max-width: 600px;
      background: #fff;
      margin: 40px auto;
      padding: 20px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    label {
      display: block;
      margin-top: 15px;
    }
    select, textarea, button {
      width: 100%;
      padding: 10px;
      margin-top: 8px;
      border-radius: 5px;
      border: 1px solid #ccc;
    }
    button {
      background: #2c3e50;
      color: white;
      font-weight: bold;
      margin-top: 20px;
      cursor: pointer;
    }
    button:hover {
      background: #34495e;
    }
    .msg {
      text-align: center;
      margin-top: 20px;
      color: green;
      font-weight: bold;
    }
    .error {
      color: red;
      text-align: center;
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
  </style>
</head>
<body>

<header>
  <h1>📢 Admin - Send Notification</h1>
</header>

<nav>
  <a href="index.php">Home</a>
  <a href="user_management.php">Users</a>
  <a href="books.php">Books</a>
  <a href="transactions.php">Transactions</a>
  <a href="reports.php">Reports</a>
  <a href="settings.php">Settings</a>
  <a href="logout.php">Logout</a>
</nav>

<div class="container">
  <h2>Send a Notification</h2>

  <?php if (isset($status)) echo "<div class='msg'>$status</div>"; ?>
  <?php if (isset($error)) echo "<div class='error'>$error</div>"; ?>

  <form method="post">
    <label for="target_user">Select Recipient:</label>
    <select name="target_user" required>
      <option value="all">All Users</option>
      <?php while ($row = mysqli_fetch_assoc($user_result)): ?>
        <option value="<?= $row['user_id'] ?>"><?= htmlspecialchars($row['username']) ?></option>
      <?php endwhile; ?>
    </select>

    <label for="message">Notification Message:</label>
    <textarea name="message" rows="5" required placeholder="Enter your message here..."></textarea>

    <button type="submit">Send Notification</button>
  </form>
</div>

<footer>
  <p>&copy; 2025 Library Management System</p>
</footer>

</body>
</html>
