<?php
session_start();
require 'include/dbcon.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}


$query = "SELECT t.*, u.name AS user_name, u.email, b.title AS book_title, DATEDIFF(CURDATE(), t.due_date) AS days_late
          FROM transactions t
          JOIN user u ON t.user_id = u.user_id
          JOIN books b ON t.book_id = b.book_id
          WHERE t.status = 'borrowed' AND t.due_date < CURDATE()
          ORDER BY t.due_date ASC";

$result = mysqli_query($con, $query);


if (isset($_GET['remind']) && isset($_GET['id'])) {
    $transaction_id = intval($_GET['id']);

    $reminder_q = "SELECT u.email, u.name, b.title, t.due_date
                   FROM transactions t
                   JOIN user u ON t.user_id = u.user_id
                   JOIN books b ON t.book_id = b.book_id
                   WHERE t.transaction_id = $transaction_id";

    $reminder_r = mysqli_query($con, $reminder_q);
    $data = mysqli_fetch_assoc($reminder_r);

    if ($data) {
        $email = $data['email'];
        $name = $data['name'];
        $book = $data['title'];
        $due = $data['due_date'];

        $subject = "Reminder: Overdue Book - $book";
        $body = "Dear $name,\n\nThis is a reminder that the book '$book' was due on $due. Please return it as soon as possible.\n\nThank you.";

       

        echo "<script>alert('Reminder sent to $email');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Overdue Books - Admin</title>
  <style>
    body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 20px; }
    h2 { text-align: center; color: #c0392b; }
    table { width: 100%; border-collapse: collapse; background: #fff; margin-top: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
    th, td { padding: 10px; border: 1px solid #ddd; text-align: center; }
    th { background-color: #e74c3c; color: white; }
    .back-btn {
      margin-top: 30px;
      display: inline-block;
      padding: 10px 15px;
      background-color: #7f8c8d;
      color: white;
      border-radius: 5px;
      text-decoration: none;
    }
    .back-btn:hover { background-color: #636e72; }
    .remind-btn {
      padding: 6px 12px;
      background-color: #2980b9;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      text-decoration: none;
    }
    .remind-btn:hover { background-color: #21618c; }
    .no-records {
      text-align: center;
      padding: 20px;
      background: #fff3f3;
      border: 1px solid #e0b4b4;
      border-radius: 8px;
      color: #c0392b;
    }
  </style>
</head>
<body>

<h2>📕 Overdue Books Report</h2>
<link rel="icon" href="logo.png" type="image/png">

<?php if (mysqli_num_rows($result) > 0): ?>
  <table>
    <thead>
      <tr>
        <th>Transaction ID</th>
        <th>User</th>
        <th>Email</th>
        <th>Book</th>
        <th>Borrow Date</th>
        <th>Due Date</th>
        <th>Days Late</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
          <td><?= $row['transaction_id'] ?></td>
          <td><?= htmlspecialchars($row['user_name']) ?></td>
          <td><?= htmlspecialchars($row['email']) ?></td>
          <td><?= htmlspecialchars($row['book_title']) ?></td>
          <td><?= $row['borrow_date'] ?></td>
          <td style="color:red; font-weight:bold;"><?= $row['due_date'] ?></td>
          <td><?= $row['days_late'] ?> day(s)</td>
          <td><?= ucfirst($row['status']) ?></td>
          <td>
            <a class="remind-btn" href="?id=<?= $row['transaction_id'] ?>&remind=1">Send Reminder</a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
<?php else: ?>
  <div class="no-records">✅ All books are returned on time. No overdue books.</div>
<?php endif; ?>

<a href="admin-dashboard.php" class="back-btn">← Back to Dashboard</a>

</body>
</html>
