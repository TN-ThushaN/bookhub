<?php
session_start();
require "include/dbcon.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


$today = date('Y-m-d H:i:s');
$stmt = $con->prepare("SELECT title, description, event_date FROM events WHERE event_date >= ? ORDER BY event_date ASC");
$stmt->bind_param("s", $today);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Library Events - Library Management System</title>
  <link rel="icon" href="logo.png" type="image/png">
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f8f9fa;
      margin: 0;
      padding-bottom: 60px;
    }
    header {
      background: #2c3e50;
      color: white;
      padding: 15px;
      text-align: center;
    }
    nav {
      background: #34495e;
      padding: 10px;
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
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
      max-width: 900px;
      margin: 20px auto;
      padding: 20px;
      background: white;
      border-radius: 5px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      position: relative;
    }
    h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #2c3e50;
    }
    .event-list {
      list-style: none;
      padding: 0;
    }
    .event-item {
      border-bottom: 1px solid #ddd;
      padding: 15px 0;
    }
    .event-item:last-child {
      border-bottom: none;
    }
    .event-title {
      font-weight: bold;
      font-size: 1.2rem;
      color: #34495e;
    }
    .event-date {
      color: #888;
      margin-top: 5px;
    }
    .event-description {
      margin-top: 10px;
      line-height: 1.5;
      color: #555;
    }
    .back-btn {
      display: inline-block;
      background-color: #2c3e50;
      color: white;
      padding: 10px 16px;
      text-decoration: none;
      font-size: 14px;
      border-radius: 5px;
      
      bottom: -50px;
      left: 20px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      transition: background-color 0.3s ease;
    }
    .back-btn:hover {
      background-color: #1a242f;
    }
    footer {
      text-align: center;
      background: #2c3e50;
      color: white;
      padding: 10px;
      position: fixed;
      bottom: 0;
      width: 100%;
    }
  </style>
</head>
<body>
  <header>
    <h1>📅 Library Events</h1>
  </header>
  
  <div class="container">
    <h2>Upcoming Events</h2>
    <ul class="event-list">
      <?php
      if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              echo "<li class='event-item'>";
              echo "<div class='event-title'>" . htmlspecialchars($row['title']) . "</div>";
              echo "<div class='event-date'>" . date('F j, Y | g:i A', strtotime($row['event_date'])) . "</div>";
              echo "<div class='event-description'>" . nl2br(htmlspecialchars($row['description'])) . "</div>";
              echo "</li>";
          }
      } else {
          echo "<p style='text-align:center;'>No upcoming events at the moment.</p>";
      }
      ?>
    </ul>

    
    <a href="index.php" class="back-btn">🔙 Back to Home</a>
  </div>

  <footer>
    <p>&copy; 2025 Library Management System. All rights reserved.</p>
  </footer>
</body>
</html>
