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

$page_title = 'My Transactions - Book Hub';
include 'include/header.php';
?>

<main>
  <div class="welcome-section" style="padding: 20px; max-width: 1000px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
    <h2 style="margin: 0; font-size: 1.8rem; color: var(--text-primary);">📘 My Borrowed Books</h2>
  </div>

  <div class="table-responsive">
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
                  $status_html = "<span style='color: var(--success); font-weight: 600;'>✅ Returned</span>";
              } else {
                  $status_html = "<span style='color: #f59e0b; font-weight: 600;'>⏳ Pending</span>";
              }
              echo "<tr>";
              echo "<td>" . htmlspecialchars($row['title']) . "</td>";
              echo "<td>" . htmlspecialchars($row['author']) . "</td>";
              echo "<td>" . htmlspecialchars($row['issue_date']) . "</td>";
              echo "<td>" . htmlspecialchars($row['due_date']) . "</td>";
              echo "<td>{$status_html}</td>";
              echo "</tr>";
          }
      } else {
        echo "<tr><td colspan='5' style='text-align: center; padding: 30px; color: var(--text-secondary);'>No transactions found.</td></tr>";
      }
      ?>
      </tbody>
    </table>
  </div>
</main>

<?php include 'include/footer.php'; ?>
