<?php
session_start();
require "include/dbcon.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];


$notif_query = "SELECT COUNT(*) as unread_count FROM notifications WHERE user_id = ? AND is_read = 0";
$stmt = $con->prepare($notif_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$notif_data = $result->fetch_assoc();
$unread_count = $notif_data['unread_count'] ?? 0;


$query = "SELECT * FROM user WHERE user_id = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    echo "User not found.";
    exit();
}

$user = mysqli_fetch_assoc($result);
$profile_image = (!empty($user['image']) && file_exists($user['image'])) ? $user['image'] : 'default-profile.jpg';
$page_title = 'Book Hub - Home';
include 'include/header.php';
?>

<main>
  <div class="welcome-section">
    <h2>Welcome to BookHub</h2>
    <p>Explore, manage, and discover books effortlessly with your modern digital library.</p>
  </div>

  <div class="dashboard-grid">
    <a href="books.php" class="dashboard-card">
      <div class="icon">📚</div>
      <h3>Books</h3>
      <p>Browse and borrow your favorite books.</p>
    </a>

    <a href="transactions.php" class="dashboard-card">
      <div class="icon">🔁</div>
      <h3>Transactions</h3>
      <p>Track your borrowing and returns.</p>
    </a>

    <a href="feedback.php" class="dashboard-card">
      <div class="icon">📝</div>
      <h3>Feedback</h3>
      <p>Share your thoughts and suggestions.</p>
    </a>

    <a href="events.php" class="dashboard-card">
      <div class="icon">📅</div>
      <h3>Events</h3>
      <p>Stay updated with upcoming events.</p>
    </a>

    <a href="faqs.php" class="dashboard-card">
      <div class="icon">❓</div>
      <h3>FAQs</h3>
      <p>Get help with common questions.</p>
    </a>
  </div>
</main>

<?php include 'include/footer.php'; ?>

