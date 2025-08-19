<?php
session_start();
require 'include/dbcon.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$result = mysqli_query($con, "SELECT * FROM user ORDER BY user_id DESC");
?>
<link rel="icon" href="logo.png" type="image/png">
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>User Management</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f4f4f4;
      margin: 0;
      padding: 30px;
    }

    h2 {
      text-align: center;
      color: #2c3e50;
      margin-bottom: 20px;
    }

    .add-btn {
      display: inline-block;
      margin: 0 auto 20px;
      padding: 10px 18px;
      background: #1abc9c;
      color: white;
      text-decoration: none;
      border-radius: 6px;
      font-weight: bold;
      box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
    }

    .add-btn:hover {
      background: #16a085;
    }

    .search-bar {
      max-width: 300px;
      margin: 0 auto 20px;
      text-align: center;
    }

    .search-bar input {
      width: 100%;
      padding: 10px;
      font-size: 14px;
      border: 1.5px solid #ccc;
      border-radius: 6px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
      background: white;
      border-radius: 8px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
      overflow: hidden;
    }

    th, td {
      padding: 14px;
      border-bottom: 1px solid #ddd;
      text-align: center;
    }

    th {
      background: #2c3e50;
      color: #fff;
    }

    tr:hover {
      background-color: #f9f9f9;
    }

    .action-buttons a,
    .action-buttons button {
      margin: 2px;
      padding: 6px 12px;
      border: none;
      border-radius: 4px;
      text-decoration: none;
      background: #1abc9c;
      color: white;
      font-size: 0.9rem;
      cursor: pointer;
    }

    .action-buttons a:hover,
    .action-buttons button:hover {
      background: #16a085;
    }

    .action-buttons form {
      display: inline;
    }

    .message {
      text-align: center;
      font-weight: bold;
      margin: 15px auto;
    }

    .message.success { color: green; }
    .message.error { color: red; }

    .back-link {
      display: inline-block;
      margin-top: 30px;
      text-decoration: none;
      color: #2c3e50;
      font-weight: bold;
      background: #e2e8ec;
      padding: 8px 16px;
      border-radius: 6px;
      transition: background 0.3s ease;
    }

    .back-link:hover {
      background: #d4e6e3;
      color: #1abc9c;
    }

    @media (max-width: 768px) {
      table, thead, tbody, th, td, tr {
        display: block;
      }

      th {
        position: absolute;
        top: -9999px;
        left: -9999px;
      }

      tr {
        margin: 0 0 15px 0;
        border: 1px solid #ccc;
        padding: 10px;
        border-radius: 8px;
        background: #fff;
      }

      td {
        border: none;
        position: relative;
        padding-left: 50%;
        text-align: left;
      }

      td::before {
        position: absolute;
        top: 12px;
        left: 15px;
        width: 45%;
        font-weight: bold;
        color: #1abc9c;
      }

      td:nth-of-type(1)::before { content: "ID"; }
      td:nth-of-type(2)::before { content: "Name"; }
      td:nth-of-type(3)::before { content: "Email"; }
      td:nth-of-type(4)::before { content: "Role"; }
      td:nth-of-type(5)::before { content: "Actions"; }
    }
  </style>
</head>
<body>

<h2>👤 User Management</h2>

<div style="text-align:center;">
  <a class="add-btn" href="add_user.php">➕ Add New User</a>
</div>

<div class="search-bar">
  <input type="text" id="searchInput" onkeyup="filterUsers()" placeholder="🔍 Search users...">
</div>

<?php if (isset($_GET['success'])): ?>
  <div class="message success">✅ <?= htmlspecialchars($_GET['success']) ?></div>
<?php elseif (isset($_GET['error'])): ?>
  <div class="message error">❌ <?= htmlspecialchars($_GET['error']) ?></div>
<?php endif; ?>

<table id="userTable">
  <thead>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Email</th>
      <th>Role</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
      <tr>
        <td><?= htmlspecialchars($row['user_id']) ?></td>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td><?= $row['role'] == 0 ? "Member" : "Admin" ?></td>
        <td class="action-buttons">
          <a href="edit_user.php?id=<?= urlencode($row['user_id']) ?>">✏️ Edit</a>
          <form method="GET" action="delete_user.php" onsubmit="return confirm('Are you sure you want to delete this user?');">
            <input type="hidden" name="id" value="<?= htmlspecialchars($row['user_id']) ?>">
            <button type="submit">🗑️ Delete</button>
          </form>
        </td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<div style="text-align:left;">
  <a class="back-link" href="admin-dashboard.php">🔙 Back </a>
</div>

<script>
  function filterUsers() {
    const input = document.getElementById("searchInput").value.toLowerCase();
    const rows = document.querySelectorAll("#userTable tbody tr");
    rows.forEach(row => {
      const text = row.innerText.toLowerCase();
      row.style.display = text.includes(input) ? "" : "none";
    });
  }
</script>

</body>
</html>
