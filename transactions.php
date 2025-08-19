<?php
session_start();

require "include/dbcon.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$query = "SELECT b.title, b.author, t.issue_date, t.due_date, t.return_date, t.transaction_id
          FROM transactions AS t
          JOIN books AS b ON t.book_id = b.book_id
          WHERE t.user_id = '$user_id'";

$result = mysqli_query($con, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>My Transactions</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style>
    
    * {
      box-sizing: border-box;
    }

    html, body {
      height: 100%;
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      overflow-x: hidden; 
    }

    body {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      position: relative;
      padding-bottom: 60px; 
    }

    header {
      background-color: #2c3e50;
      color: white;
      text-align: center;
      padding: 15px 20px;
      flex-shrink: 0;
    }

    nav {
      background-color: #34495e;
      padding: 10px;
      text-align: center;
      flex-shrink: 0;
    }

    nav a {
      color: white;
      text-decoration: none;
      margin: 0 15px;
      font-weight: 600;
      font-size: 1rem;
      transition: color 0.3s ease;
    }

    nav a:hover {
      color: #1abc9c;
    }

    
    .container {
      flex: 1 0 auto;
      width: 100%;
      margin: 20px 0;
      padding: 0 10px; 
      animation: fadeIn 0.8s ease-in-out;
      overflow-x: auto;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background-color: #fff;
      margin-top: 20px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
      min-width: 700px; 
      word-break: break-word;
    }

    th, td {
      padding: 12px 10px;
      border: 1px solid #ccc;
      text-align: center;
      font-size: 0.95rem;
    }

    th {
      background-color: #2c3e50;
      color: white;
    }

    a.btn-back {
      display: inline-block;
      padding: 10px 25px;
      background-color: #2980b9;
      color: white;
      border-radius: 5px;
      text-decoration: none;
      font-weight: 600;
      margin-bottom: 20px;
      transition: background-color 0.3s ease;
    }

    a.btn-back:hover {
      background-color: #1c5980;
    }

    footer {
      background-color: #2c3e50;
      color: white;
      text-align: center;
      padding: 12px 20px;
      flex-shrink: 0;
      position: fixed;
      bottom: 0;
      left: 0;
      width: 100%;
      font-size: 0.9rem;
      user-select: none;
      box-shadow: 0 -2px 8px rgba(0,0,0,0.2);
    }

    .status-returned {
      color: green;
      font-weight: bold;
    }

    .status-pending {
      color: orange;
      font-weight: bold;
    }

    
    @media (max-width: 600px) {
      nav a {
        margin: 0 8px;
        font-size: 0.9rem;
      }

      th, td {
        font-size: 0.85rem;
        padding: 8px 6px;
      }

      a.btn-back {
        padding: 8px 18px;
        font-size: 0.9rem;
      }
    }
  </style>
</head>
<link rel="icon" href="logo.png" type="image/png" />
<body>

<header>
  <h1>Book Hub - My Transactions</h1>
</header>

<link rel="icon" href="logo.png" type="image/png">

<div class="container">
  <a href="index.php" class="btn-back" title="Back to Home">← Back to Home</a>

  <h2>📘 My Borrowed Books</h2>
  <table>
    <thead>
      <tr>
        <th>Title</th>
        <th>Author</th>
        <th>Issue Date</th>
        <th>Due Date</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
    <?php
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            if ($row['return_date']) {
                $status_text = "✅ Returned";
                $status_class = "status-returned";
            } else {
                $status_text = "⏳ Pending";
                $status_class = "status-pending";
            }
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['title']) . "</td>";
            echo "<td>" . htmlspecialchars($row['author']) . "</td>";
            echo "<td>" . htmlspecialchars($row['issue_date']) . "</td>";
            echo "<td>" . htmlspecialchars($row['due_date']) . "</td>";
            echo "<td class='{$status_class}'>$status_text</td>";
            echo "</tr>";
        }
    } else {
      echo "<tr><td colspan='5'>No transactions found.</td></tr>";
    }
    ?>
    </tbody>
  </table>
</div>


<footer>
  <p>&copy; 2025 Library Management System. All rights reserved.</p>
</footer>

</body>
</html>
