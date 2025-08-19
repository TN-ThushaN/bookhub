<?php
session_start();
require 'include/dbcon.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];


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
  <title>User Profile - Book Hub</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #ffffff;
      color: #333;
      min-height: 100vh;
      display: flex;
    }

    .sidebar {
      width: 220px;
      background-color: #2c3e50;
      color: white;
      padding: 20px 15px;
      display: flex;
      flex-direction: column;
      gap: 15px;
      height: 100vh;
      overflow-y: auto;
      position: sticky;
      top: 0;
    }

    .sidebar h2 {
      font-weight: 700;
      font-size: 1.6rem;
      margin-bottom: 20px;
      text-align: center;
    }

    .sidebar a {
      color: white;
      text-decoration: none;
      padding: 10px 12px;
      border-radius: 5px;
      transition: background 0.3s;
    }

    .sidebar a:hover,
    .sidebar a.active {
      background-color: #1abc9c;
      color: #fff;
      font-weight: 600;
    }

    .main {
      flex-grow: 1;
      padding: 40px;
      background-color: #ffffff;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .profile-container {
      width: 100%;
      max-width: 800px;
      background: #ffffff;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .profile-header {
      display: flex;
      align-items: center;
      gap: 25px;
      margin-bottom: 30px;
    }

    .profile-header img {
      width: 130px;
      height: 130px;
      border-radius: 50%;
      object-fit: cover;
      border: 4px solid #1abc9c;
    }

    .profile-header h1 {
      font-size: 2rem;
      font-weight: 700;
      color: #2c3e50;
    }

    .info-grid {
      display: grid;
      gap: 20px;
      margin-top: 20px;
    }

    .info-item {
      background: #f4f4f4;
      padding: 20px;
      border-radius: 10px;
      border-left: 4px solid #1abc9c;
    }

    .info-label {
      font-size: 0.85rem;
      color: #555;
      text-transform: uppercase;
      margin-bottom: 5px;
      letter-spacing: 1px;
    }

    .info-value {
      font-size: 1.1rem;
      font-weight: 500;
      color: #222;
    }

    .actions {
      margin-top: 30px;
      display: flex;
      gap: 15px;
    }

    .btn {
      background-color: #1abc9c;
      border: none;
      padding: 12px 20px;
      font-weight: 600;
      border-radius: 7px;
      color: white;
      cursor: pointer;
      text-decoration: none;
      transition: background-color 0.3s ease;
    }

    .btn:hover {
      background-color: #16a085;
    }

    @media (max-width: 768px) {
      body {
        flex-direction: column;
      }
      .sidebar {
        width: 100%;
        flex-direction: row;
        justify-content: center;
        position: relative;
        height: auto;
      }
      .main {
        padding: 20px;
      }
      .profile-header {
        flex-direction: column;
        text-align: center;
      }
    }
  </style>
</head>
<link rel="icon" href="logo.png" type="image/png">
<body>
  <div class="sidebar">
    <h2>Book Hub</h2>
    <a href="index.php">Home</a>
    <a href="profile.php" class="active">Profile</a>
    <a href="profile-settings.php">Settings</a>
    <a href="logout.php">Logout</a>
  </div>

  <div class="main">
    <div class="profile-container">
      <div class="profile-header">
        <img src="<?php echo htmlspecialchars($profile_image); ?>" alt="Profile Picture">
        <h1><?php echo htmlspecialchars($user['name'] ?? 'User'); ?></h1>
      </div>

      <div class="info-grid">
        <div class="info-item">
          <div class="info-label">Email</div>
          <div class="info-value"><?php echo htmlspecialchars($user['email'] ?? 'N/A'); ?></div>
        </div>
        <div class="info-item">
          <div class="info-label">Username</div>
          <div class="info-value"><?php echo htmlspecialchars($user['username'] ?? 'N/A'); ?></div>
        </div>
        <div class="info-item">
          <div class="info-label">NIC</div>
          <div class="info-value"><?php echo htmlspecialchars($user['nic'] ?? 'N/A'); ?></div>
        </div>
        <div class="info-item">
          <div class="info-label">Phone</div>
          <div class="info-value"><?php echo htmlspecialchars($user['contact'] ?? 'N/A'); ?></div>
        </div>
        <div class="info-item">
          <div class="info-label">Address</div>
          <div class="info-value"><?php echo htmlspecialchars($user['address'] ?? 'N/A'); ?></div>
        </div>
        <div class="info-item">
          <div class="info-label">Member Since</div>
          <div class="info-value">
            <?php
              if (!empty($user['created_at'])) {
                  echo (new DateTime($user['created_at']))->format('F j, Y');
              } else {
                  echo 'N/A';
              }
            ?>
          </div>
        </div>
      </div>

      <div class="actions">
        <a href="profile-settings.php" class="btn">Edit Profile</a>
        <a href="index.php" class="btn">Back to Home</a>
      </div>
    </div>
  </div>
</body>
<link rel="icon" href="logo.png" type="image/png">
</html>
