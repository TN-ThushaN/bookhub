<?php
session_start();
require 'include/dbcon.php';


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}


$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;


$where = "";
if (!empty($_GET['from_date']) && !empty($_GET['to_date'])) {
    $from = $_GET['from_date'];
    $to = $_GET['to_date'];
    $where = "WHERE t.borrow_date BETWEEN '$from' AND '$to'";
}


$countQuery = "SELECT COUNT(*) as total FROM transactions t $where";
$countResult = mysqli_query($con, $countQuery);
$total = mysqli_fetch_assoc($countResult)['total'];
$pages = ceil($total / $limit);


$query = "SELECT t.*, u.name AS user_name, b.title AS book_title 
          FROM transactions t
          JOIN user u ON t.user_id = u.user_id
          JOIN books b ON t.book_id = b.book_id
          $where
          ORDER BY t.transaction_id DESC
          LIMIT $start, $limit";
$result = mysqli_query($con, $query);
?>
<link rel="icon" href="logo.png" type="image/png">
<!DOCTYPE html>
<html>
<head>
    <title>Admin - Transaction Management</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f4f4f4; }
        h2 { text-align: center; }
        form { margin-bottom: 20px; display: flex; justify-content: center; gap: 10px; }
        input[type="date"], input[type="submit"] { padding: 8px; }

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
            background: #2c3e50;
            color: white;
        }

        .pagination {
            margin-top: 20px;
            text-align: center;
        }

        .pagination a {
            margin: 0 5px;
            padding: 8px 12px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .pagination a.active {
            background: #2980b9;
        }

        .back-btn {
            display: inline-block;
            margin-top: 30px;
            padding: 8px 15px;
            background-color: #7f8c8d;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .back-btn:hover {
            background-color: #5e6e70;
        }
    </style>
</head>
<body>

<h2>📄 Admin - Transaction Management</h2>


<form method="GET">
    <label>From:
        <input type="date" name="from_date" value="<?= htmlspecialchars($_GET['from_date'] ?? '') ?>">
    </label>
    <label>To:
        <input type="date" name="to_date" value="<?= htmlspecialchars($_GET['to_date'] ?? '') ?>">
    </label>
    <input type="submit" value="Filter">
</form>


<table>
    <tr>
        <th>ID</th>
        <th>User</th>
        <th>Book</th>
        <th>Borrow Date</th>
        <th>Due Date</th>
        <th>Issue Date</th>
        <th>Return Date</th>
        <th>Status</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
        <tr>
            <td><?= htmlspecialchars($row['transaction_id']) ?></td>
            <td><?= htmlspecialchars($row['user_name']) ?></td>
            <td><?= htmlspecialchars($row['book_title']) ?></td>
            <td><?= htmlspecialchars($row['borrow_date']) ?></td>
            <td><?= htmlspecialchars($row['due_date']) ?></td>
            <td><?= htmlspecialchars($row['issue_date']) ?></td>
            <td><?= htmlspecialchars($row['return_date']) ?></td>
            <td><?= htmlspecialchars($row['status']) ?></td>
        </tr>
    <?php endwhile; ?>
</table>


<div class="pagination">
    <?php for ($i = 1; $i <= $pages; $i++): ?>
        <a href="?page=<?= $i ?>&from_date=<?= htmlspecialchars($_GET['from_date'] ?? '') ?>&to_date=<?= htmlspecialchars($_GET['to_date'] ?? '') ?>"
           class="<?= $i == $page ? 'active' : '' ?>">
            <?= $i ?>
        </a>
    <?php endfor; ?>
</div>


<div style="text-align:center;">
    <a href="admin-dashboard.php" class="back-btn">← Back to Dashboard</a>
</div>

</body>
</html>
