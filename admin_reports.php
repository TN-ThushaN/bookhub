<?php
session_start();
require 'include/dbcon.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}


$start_date = isset($_GET['start_date']) ? mysqli_real_escape_string($con, $_GET['start_date']) : '';
$end_date = isset($_GET['end_date']) ? mysqli_real_escape_string($con, $_GET['end_date']) : '';
$where = '';

if ($start_date && $end_date) {
    $where = "WHERE t.borrow_date BETWEEN '$start_date' AND '$end_date'";
}


$books_count = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS total FROM books"))['total'];
$user_count = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS total FROM user"))['total'];
$transactions_count = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS total FROM transactions"))['total'];


$report_query = "SELECT t.*, u.name AS user_name, b.title AS book_title 
                 FROM transactions t
                 JOIN user u ON t.user_id = u.user_id
                 JOIN books b ON t.book_id = b.book_id
                 $where
                 ORDER BY t.borrow_date DESC";
$report_result = mysqli_query($con, $report_query);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin Reports</title>
  <style>
    body { 
      font-family: Arial; 
      background: #f7f7f7; 
      padding: 20px; 
      margin: 0;
    }

    h2 { 
      text-align: center; 
      color: #2c3e50;
    }

    .summary { 
      display: flex; 
      gap: 20px; 
      margin-bottom: 20px; 
      justify-content: center; 
      flex-wrap: wrap;
    }

    .card {
      flex: 1; 
      background: #fff; 
      padding: 20px;
      border-radius: 10px; 
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      text-align: center;
      max-width: 200px;
    }

    table { 
      width: 100%; 
      border-collapse: collapse; 
      background: white; 
      margin-top: 20px; 
    }

    th, td { 
      padding: 10px; 
      border: 1px solid #ccc; 
      text-align: center; 
    }

    th { 
      background: #1abc9c; 
      color: white; 
    }

    form { 
      margin-bottom: 20px; 
      text-align: center; 
    }

    input[type="date"], input[type="submit"] {
      padding: 8px; 
      border: 1px solid #ccc; 
      border-radius: 5px;
      font-size: 14px;
    }

    input[type="submit"] {
      background-color: #1abc9c; 
      color: white; 
      border: none;
      cursor: pointer;
    }

    input[type="submit"]:hover {
      background-color: #16a085;
    }

    a.export-link {
      padding: 8px 12px;
      background-color: #2980b9;
      color: white;
      text-decoration: none;
      border-radius: 5px;
      margin-left: 10px;
    }

    a.export-link:hover {
      background-color: #2471a3;
    }

    .back-btn {
      display: inline-block;
      margin-top: 30px;
      padding: 10px 16px;
      background-color: #7f8c8d;
      color: white;
      text-decoration: none;
      border-radius: 5px;
    }

    .back-btn:hover {
      background-color: #636e72;
    }
  </style>
</head>
<body>

<h2>📊 Admin Reports</h2>

<div class="summary">
  <div class="card">
    <h3>Total Books</h3>
    <p><?= $books_count ?></p>
  </div>
  <div class="card">
    <h3>Total Users</h3>
    <p><?= $user_count ?></p>
  </div>
  <div class="card">
    <h3>Total Transactions</h3>
    <p><?= $transactions_count ?></p>
  </div>
</div>

<form method="GET">
  <label>Start Date: </label>
  <input type="date" name="start_date" value="<?= htmlspecialchars($start_date) ?>">
  <label>End Date: </label>
  <input type="date" name="end_date" value="<?= htmlspecialchars($end_date) ?>">
  <input type="submit" value="Filter">
  <a class="export-link" href="export_csv.php?start_date=<?= urlencode($start_date) ?>&end_date=<?= urlencode($end_date) ?>">📁 Export CSV</a>
</form>

<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>User</th>
      <th>Book</th>
      <th>Borrow Date</th>
      <th>Due Date</th>
      <th>Return Date</th>
      <th>Status</th>
    </tr>
  </thead>
  <tbody>
    <?php if (mysqli_num_rows($report_result) > 0): ?>
      <?php while ($row = mysqli_fetch_assoc($report_result)): ?>
      <tr>
        <td><?= $row['transaction_id'] ?></td>
        <td><?= htmlspecialchars($row['user_name']) ?></td>
        <td><?= htmlspecialchars($row['book_title']) ?></td>
        <td><?= $row['borrow_date'] ?></td>
        <td><?= $row['due_date'] ?></td>
        <td><?= $row['return_date'] ?? '-' ?></td>
        <td><?= ucfirst($row['status']) ?></td>
      </tr>
      <?php endwhile; ?>
    <?php else: ?>
      <tr><td colspan="7">No transactions found for the selected date range.</td></tr>
    <?php endif; ?>
  </tbody>
</table>

<div style="margin-top: 20px;">
  <a href="admin-dashboard.php" class="back-btn">← Back </a>
</div>

</body>
</html>
<link rel="icon" href="logo.png" type="image/png">