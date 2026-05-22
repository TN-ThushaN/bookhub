<?php
$page_title = $page_title ?? 'Book Hub';
$profile_image = $profile_image ?? 'default-profile.jpg';
$unread_count = $unread_count ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo htmlspecialchars($page_title); ?></title>
  <link rel="icon" href="logo.png" type="image/png">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<header>
  <div class="header-container">
    <div class="logo">
      <a href="index.php">
        <h1>📚 Book Hub</h1>
        <p>Smart, Fast, Reliable</p>
      </a>
    </div>
    
    <nav>
      <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="books.php">Books</a></li>
        <li>
          <a href="notifications.php" style="position:relative;">
            Notifications
            <?php if ($unread_count > 0): ?>
              <span class="notif-dot"><?= $unread_count ?></span>
            <?php endif; ?>
          </a>
        </li>
        <li><a href="events.php">Events</a></li>
        <li><a href="about.php">About</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
          <li><a href="settings.php">Settings</a></li>
          <li><a href="logout.php">Logout</a></li>
        <?php else: ?>
          <li><a href="login.php">Login</a></li>
        <?php endif; ?>
      </ul>
    </nav>

    <?php if (isset($_SESSION['user_id'])): ?>
      <a href="profile.php" class="profile-logo" title="Your Profile">
        <img src="<?php echo htmlspecialchars($profile_image); ?>" alt="Profile" />
      </a>
    <?php endif; ?>
  </div>
</header>
