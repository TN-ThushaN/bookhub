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


$borrowed_books = [];
$bq = mysqli_query($con, "SELECT book_id FROM transactions WHERE user_id = '$user_id' AND status = 'borrowed'");
while ($b = mysqli_fetch_assoc($bq)) {
    $borrowed_books[] = $b['book_id'];
}


$search_esc = mysqli_real_escape_string($con, $search);


$count_query = "SELECT COUNT(*) as total FROM books WHERE title LIKE '%$search_esc%' OR author LIKE '%$search_esc%'";
$total_books = mysqli_fetch_assoc(mysqli_query($con, $count_query))['total'];
$total_pages = ceil($total_books / $limit);


$query = "SELECT * FROM books WHERE title LIKE '%$search_esc%' OR author LIKE '%$search_esc%' ORDER BY title ASC LIMIT $limit OFFSET $offset";
$result = mysqli_query($con, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title> Books</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="logo.png" type="image/png">
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
    nav a:hover {
      color: #1abc9c;
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
    .borrow-btn {
      background-color: #2980b9;
      color: white;
      border: none;
      padding: 6px 12px;
      border-radius: 3px;
      cursor: pointer;
    }
    .borrow-btn:disabled {
      background-color: #999;
      cursor: not-allowed;
    }
    .borrow-btn:hover:not(:disabled) {
      background-color: #1c5980;
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
    footer {
      background-color: #2c3e50;
      color: white;
      text-align: center;
      padding: 10px;
      position: fixed;
      bottom: 0;
      width: 100%;
    }
  </style>
</head>
<body>

<header>
  <h2>📚 Books</h2>
</header>

<nav>
  <a href="index.php">Home</a>
  <a href="my_books.php">My Books</a>
  <a href="transactions.php">Transactions</a>
</nav>

<div class="container">
  <div class="search-bar">
    <form method="GET" action="">
      <input type="text" name="search" placeholder="Search title or author..." value="<?= htmlspecialchars($search) ?>" />
      <button type="submit">Search</button>
    </form>
    
  </div>

  <div style="margin-bottom: 15px;">
    <a href="index.php" style="text-decoration: none;">
      <button class="dark-toggle">← Back to Home</button>
    </a>
  </div>

  <div class="table-responsive">
    <table>
      <thead>
        <tr>
          <th>Title</th>
          <th>Author</th>
          <th>Genre</th>
          <th>Status</th>
          <th>Borrow</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)):
          $book_id = $row['book_id'];
          $available = $row['available_status'] > 0;
          $already_borrowed = in_array($book_id, $borrowed_books);
        ?>
        <tr>
          <td><?= htmlspecialchars($row['title']) ?></td>
          <td><?= htmlspecialchars($row['author']) ?></td>
          <td><?= htmlspecialchars($row['genre']) ?></td>
          <td><?= $available ? "✅ Available" : "❌ Unavailable" ?></td>
          <td>
            <?php if ($available && !$already_borrowed): ?>
              <form method="POST" action="borrow.php" style="margin:0;">
                <input type="hidden" name="book_id" value="<?= $book_id ?>">
                <button type="submit" class="borrow-btn">Borrow</button>
              </form>
            <?php else: ?>
              <button class="borrow-btn" disabled>Borrow</button>
            <?php endif; ?>
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
  &copy; 2025 Library Management System
</footer>

<script>
function toggleDark() {
  document.body.classList.toggle('dark');
  localStorage.setItem('darkmode', document.body.classList.contains('dark'));
}
if (localStorage.getItem('darkmode') === 'true') {
  document.body.classList.add('dark');
}
</script>

</body>
</html>
