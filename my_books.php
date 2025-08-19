<?php
session_start();
require "include/dbcon.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$search = $_GET['search'] ?? '';
$page = max(1, intval($_GET['page'] ?? 1));
$limit = 10;
$offset = ($page - 1) * $limit;


$count_q = "SELECT COUNT(*) as total FROM transactions t 
            JOIN books b ON t.book_id = b.book_id 
            WHERE t.user_id = '$user_id' AND t.status = 'borrowed' 
            AND (b.title LIKE '%$search%' OR b.author LIKE '%$search%')";
$total_books = mysqli_fetch_assoc(mysqli_query($con, $count_q))['total'];
$total_pages = ceil($total_books / $limit);


$query = "SELECT t.*, b.title, b.author, b.genre, b.book_id 
          FROM transactions t 
          JOIN books b ON t.book_id = b.book_id 
          WHERE t.user_id = '$user_id' AND t.status = 'borrowed' 
          AND (b.title LIKE '%$search%' OR b.author LIKE '%$search%') 
          ORDER BY t.borrow_date DESC 
          LIMIT $limit OFFSET $offset";
$result = mysqli_query($con, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Borrowed Books</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    :root {
      --bg: #f4f4f4;
      --text: #333;
      --card: #fff;
    }
    body.dark {
      --bg: #121212;
      --text: #f4f4f4;
      --card: #1e1e1e;
    }
    body {
      background-color: var(--bg);
      color: var(--text);
      font-family: Arial, sans-serif;
      margin: 0;
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
      padding: 10px;
      text-align: center;
    }
    nav a {
      color: white;
      margin: 0 15px;
      text-decoration: none;
      font-weight: bold;
    }
    .container {
      width: 100%;
      padding: 20px 40px;
      box-sizing: border-box;
    }
    .search-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      margin-bottom: 15px;
    }
    .search-bar form {
      display: flex;
      align-items: center;
      gap: 5px;
    }
    .search-bar input {
      padding: 5px 10px;
      font-size: 14px;
      width: 200px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    .search-bar button[type="submit"] {
      padding: 5px 10px;
      font-size: 14px;
      background-color: #27ae60;
      color: white;
      border: none;
      cursor: pointer;
      border-radius: 4px;
    }
    .dark-toggle {
      padding: 5px 10px;
      background-color: #2980b9;
      color: white;
      border: none;
      cursor: pointer;
      border-radius: 4px;
      font-size: 14px;
    }
    .table-responsive {
      overflow-x: auto;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      background-color: var(--card);
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    th, td {
      padding: 10px;
      border: 1px solid #ccc;
      text-align: center;
    }
    th {
      background-color: #2c3e50;
      color: white;
    }
    button {
      background-color: #c0392b;
      color: white;
      border: none;
      padding: 6px 10px;
      border-radius: 3px;
      cursor: pointer;
    }
    .overdue {
      background-color: #f8d7da;
    }
    footer {
      background-color: #2c3e50;
      color: white;
      text-align: center;
      padding: 10px;
      position: fixed;
      bottom: 0;
      width: 100%;
    }
    .pagination {
      text-align: center;
      margin-top: 20px;
    }
    .pagination a {
      padding: 6px 12px;
      background-color: #2980b9;
      color: white;
      text-decoration: none;
      margin: 0 3px;
      border-radius: 3px;
    }
    .pagination a.active {
      background-color: #1abc9c;
    }
  </style>
  <link rel="icon" href="logo.png" type="image/png">
</head>
<body>

<header>
  <h2>📘 My Borrowed Books</h2>
</header>



<div class="container">
  <div class="search-bar">
    <form method="GET" action="">
      <input type="text" name="search" placeholder="Search title or author..." value="<?php echo htmlspecialchars($search); ?>">
      <button type="submit">Search</button>
    </form>
  </div>

 
  <div style="margin-bottom: 15px;">
    <a href="books.php" style="text-decoration: none;">
      <button class="dark-toggle">← Back</button>
    </a>
  </div>

  <div class="table-responsive">
    <table>
      <thead>
        <tr>
          <th>Title</th>
          <th>Author</th>
          <th>Genre</th>
          <th>Borrowed On</th>
          <th>Due Date</th>
          <th>Return</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)):
          $isOverdue = (strtotime($row['due_date']) < strtotime(date('Y-m-d')));
        ?>
          <tr class="<?= $isOverdue ? 'overdue' : '' ?>">
            <td><?= htmlspecialchars($row['title']) ?></td>
            <td><?= htmlspecialchars($row['author']) ?></td>
            <td><?= htmlspecialchars($row['genre']) ?></td>
            <td><?= htmlspecialchars($row['borrow_date']) ?></td>
            <td><?= htmlspecialchars($row['due_date']) ?></td>
            <td>
              <form method="POST" action="return_book.php">
                <input type="hidden" name="book_id" value="<?= $row['book_id'] ?>">
                <button type="submit">Return</button>
              </form>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <div class="pagination">
    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
      <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>" class="<?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a>
    <?php endfor; ?>
  </div>
</div>

<footer>
  <p>&copy; 2025 Library Management System</p>
</footer>

</body>
</html>
