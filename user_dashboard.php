<?php
session_start();
require_once "include/dbcon.php"; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/
$sql = "SELECT name, email, nic, contact, address, created_at FROM user WHERE user_id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard - Library Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        header {
            background-color: #2c3e50;
            color: white;
            padding: 15px;
            text-align: center;
        }
        nav {
            background-color: #34495e;
            padding: 10px;
            display: flex;
            justify-content: center;
        }
        nav a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-size: 1rem;
        }
        nav a:hover {
            text-decoration: underline;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .dashboard-section {
            margin-bottom: 20px;
        }
        .dashboard-section h2 {
            font-size: 1.5rem;
            color: #2c3e50;
            border-bottom: 2px solid #34495e;
            padding-bottom: 5px;
        }
        .book-list, .notifications-list {
            list-style: none;
            padding: 0;
        }
        .book-list li, .notifications-list li {
            background: #ecf0f1;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
        }
        .book-list li span {
            font-weight: bold;
            color: #e74c3c;
        }
        .btn {
            background-color: #3498db;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
        }
        .btn:hover {
            background-color: #2980b9;
        }
        footer {
            text-align: center;
            padding: 10px;
            background-color: #2c3e50;
            color: white;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
<header>
    <h1>Library User Dashboard</h1>
</header>
<nav>
    <a href="index.php">Home</a>
    <a href="books.php">Books</a>
    <a href="transactions.php">My Transactions</a>
    <a href="notifications.php">Notifications</a>
    <a href="settings.php">Settings</a>
</nav>
<div class="container">
    <div class="dashboard-section">
        <h2>My Borrowed Books</h2>
        <ul class="book-list">
            
            <li>Book: <b>The Great Gatsby</b> - Due Date: <span>March 10, 2025</span></li>
            <li>Book: <b>1984</b> - Due Date: <span>March 15, 2025</span></li>
        </ul>
    </div>

    <div class="dashboard-section">
        <h2>Recent Notifications</h2>
        <ul class="notifications-list">
            
            <li>Reminder: "The Great Gatsby" is due in 5 days.</li>
            <li>New books added in the Science Fiction section!</li>
        </ul>
    </div>

    <div class="dashboard-section">
        <h2>My Account</h2>
        <p><b>Name:</b> <?= htmlspecialchars($user['name']) ?></p>
        <p><b>Email:</b> <?= htmlspecialchars($user['email']) ?></p>
        <p><b>NIC:</b> <?= htmlspecialchars($user['nic']) ?></p>
        <p><b>Contact:</b> <?= htmlspecialchars($user['contact']) ?></p>
        <p><b>Address:</b> <?= htmlspecialchars($user['address']) ?></p>
        <p><b>Joined On:</b> <?= htmlspecialchars($user['created_at']) ?></p>
        <a href="settings.php" class="btn">Edit Profile</a>
    </div>
</div>
<footer>
    <p>&copy; 2025 Library Management System. All rights reserved.</p>
</footer>
</body>
</html>
