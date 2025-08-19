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
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Book Hub - Home</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f4f4f9;
    }

    header {
      background-color: #2c3e50;
      color: white;
      padding: 15px 20px 50px;
      text-align: center;
      position: relative;
    }

    header h1 {
      margin: 0;
      font-size: 2.5rem;
    }

    header p {
      margin-top: 5px;
      font-size: 1rem;
      color: #ccc;
    }

    .profile-logo {
      position: absolute;
      top: 15px;
      right: 20px;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      overflow: hidden;
      border: 2px solid #1abc9c;
      cursor: pointer;
      transition: transform 0.3s ease;
    }

    .profile-logo img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .profile-logo:hover {
      transform: scale(1.1);
    }

    nav ul {
      list-style: none;
      padding: 0;
      margin: 0;
      display: flex;
      justify-content: center;
      background-color: #34495e;
    }

    nav ul li {
      margin: 0 10px;
      position: relative;
    }

    nav ul li a {
      text-decoration: none;
      color: white;
      padding: 10px 15px;
      display: inline-block;
    }

    nav ul li a:hover {
      background-color: #1abc9c;
      border-radius: 5px;
    }

    .notif-dot {
      position: absolute;
      top: 5px;
      right: -5px;
      background-color: red;
      color: white;
      border-radius: 50%;
      font-size: 10px;
      width: 16px;
      height: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
    }

    main {
      padding: 40px 20px;
      text-align: center;
    }

    .welcome-section {
      background-color: white;
      max-width: 600px;
      margin: auto;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0px 8px 20px rgba(0,0,0,0.1);
    }

    .welcome-section img {
      width: 80%;
      max-width: 200px;
      height: auto;
      border-radius: 12px;
      margin-bottom: 20px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.15);
      transition: transform 0.3s ease;
      cursor: pointer;
    }

    .welcome-section img:hover {
      transform: scale(1.05);
    }

    .dashboard-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
      gap: 20px;
      margin: 40px auto;
      max-width: 1000px;
      padding: 0 20px;
    }

    .dashboard-card {
      background-color: #ffffff;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      padding: 25px 20px;
      text-align: center;
      text-decoration: none;
      color: #2c3e50;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .dashboard-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 8px 18px rgba(0, 0, 0, 0.15);
    }

    .dashboard-card .icon {
      font-size: 40px;
      margin-bottom: 10px;
    }

    .dashboard-card h3 {
      margin: 10px 0 5px;
      color: #1abc9c;
    }

    .dashboard-card p {
      font-size: 14px;
      color: #555;
    }

    footer {
      background-color: #2c3e50;
      color: white;
      text-align: center;
      padding: 8px 0;
      position: fixed;
      bottom: 0;
      width: 100%;
    }
  </style>
</head>

<link rel="icon" href="logo.png" type="image/png">
<body>

<header>
  <h1>📚 Book Hub</h1>
  <p>Your Digital Library System – Smart, Fast, and Reliable</p>
  <a href="profile.php" class="profile-logo" title="Your Profile">
    <img src="<?php echo htmlspecialchars($profile_image); ?>" alt="Profile Picture" />
  </a>
</header>

<nav>
  <ul>
    <li><a href="index.php">Home</a></li>

    <li>
      <a href="notifications.php">
        Notifications
        <?php if ($unread_count > 0): ?>
          <span class="notif-dot"><?= $unread_count ?></span>
        <?php endif; ?>
      </a>
    </li>

    <li><a href="accounts.php">Accounts</a></li>
    <li><a href="events.php">Events</a></li>
    <li><a href="about.php">About us</a></li>
    <?php if (isset($_SESSION['username'])): ?>
      <li><a href="settings.php">Settings</a></li>
      <li><a href="logout.php">Logout</a></li>
    <?php endif; ?>
  </ul>
</nav>

<main>
  <div class="welcome-section">
    <img src="logo.png" alt="Library Banner" />
    <h2>Welcome to Our Digital Library</h2>
    <p>Explore, manage, and discover books effortlessly with <strong>BOOKHUB</strong>.</p>
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

<footer>
  <p>&copy; 2025 Library Management System. All rights reserved.</p>
</footer>

</body>
</html>
