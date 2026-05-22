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

$page_title = 'Books - Book Hub';
include 'include/header.php';
?>

<main>
  <div class="welcome-section" style="padding: 20px; max-width: 1000px; margin-bottom: 20px;">
    <div class="search-bar" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
      <h2 style="margin: 0; font-size: 1.8rem; color: var(--text-primary);">📚 Book Catalog</h2>
      <form method="GET" action="" style="display: flex; gap: 10px;">
        <input type="text" name="search" placeholder="Search title or author..." value="<?= htmlspecialchars($search) ?>" 
               style="padding: 10px 15px; border-radius: 8px; border: 1px solid var(--glass-border); background: var(--glass-bg); color: var(--text-primary); outline: none; width: 250px;" />
        <button type="submit" class="btn">Search</button>
      </form>
    </div>
  </div>

  <div class="table-responsive">
    <table>
      <thead>
        <tr>
          <th>Title</th>
          <th>Author</th>
          <th>Genre</th>
          <th>Status</th>
          <th>Action</th>
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
          <td>
             <?php if ($available): ?>
                <span style="color: var(--success); font-weight: 600;">✅ Available</span>
             <?php else: ?>
                <span style="color: var(--danger); font-weight: 600;">❌ Unavailable</span>
             <?php endif; ?>
          </td>
          <td>
            <?php if ($available && !$already_borrowed): ?>
              <form method="POST" action="borrow.php" style="margin:0;">
                <input type="hidden" name="book_id" value="<?= $book_id ?>">
                <button type="submit" class="btn" style="padding: 6px 15px; font-size: 0.9rem;">Borrow</button>
              </form>
            <?php else: ?>
              <button class="btn" disabled style="background: var(--glass-bg); color: var(--text-secondary); cursor: not-allowed; padding: 6px 15px; font-size: 0.9rem;">Borrow</button>
            <?php endif; ?>
          </td>
        </tr>
        <?php endwhile; ?>
        <?php if (mysqli_num_rows($result) === 0): ?>
          <tr>
            <td colspan="5" style="text-align: center; padding: 30px; color: var(--text-secondary);">No books found matching your search.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <?php if ($total_pages > 1): ?>
  <div class="pagination" style="text-align: center; margin-top: 30px; display: flex; justify-content: center; gap: 8px;">
    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
      <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>" class="btn" 
         style="<?= ($i == $page) ? 'background: var(--accent-gradient);' : 'background: var(--glass-bg); color: var(--text-primary);' ?> padding: 8px 15px;">
        <?= $i ?>
      </a>
    <?php endfor; ?>
  </div>
  <?php endif; ?>
</main>

<?php include 'include/footer.php'; ?>
