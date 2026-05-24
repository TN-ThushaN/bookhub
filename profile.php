<<<<<<< HEAD
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

$page_title = 'User Profile - Book Hub';
include 'include/header.php';
?>

<main>
  <div class="welcome-section" style="max-width: 800px; padding: 40px; margin-bottom: 20px;">

    <!-- Profile Header -->
    <div style="display: flex; align-items: center; gap: 30px; margin-bottom: 30px; flex-wrap: wrap; justify-content: center;">
      <img
        src="<?= htmlspecialchars($profile_image) ?>"
        alt="Profile Picture"
        style="
          width: 150px;
          height: 150px;
          border-radius: 50%;
          object-fit: cover;
          border: 4px solid #007bff;
          box-shadow: 0 0 20px rgba(0,123,255,0.3);
        "
      >
      <div style="text-align: left;">
        <h1 style="font-size: 2rem; margin: 0; color: #222;">
          <?= htmlspecialchars($user['name'] ?? 'User') ?>
        </h1>
        <p style="color: #777; margin-top: 6px; font-size: 1rem;">
          Member Since <?= (!empty($user['created_at'])) ? (new DateTime($user['created_at']))->format('F Y') : 'N/A' ?>
        </p>
      </div>
    </div>

    <!-- Info Cards -->
    <div style="
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 15px;
      margin-bottom: 20px;
    ">

      <?php
      $fields = [
        'Email'    => $user['email']    ?? 'N/A',
        'Username' => $user['username'] ?? 'N/A',
        'NIC'      => $user['nic']      ?? 'N/A',
        'Phone'    => $user['contact']  ?? 'N/A',
      ];
      foreach ($fields as $label => $value): ?>
        <div style="
          background: white;
          border: 1px solid #e0e0e0;
          border-radius: 10px;
          padding: 18px 20px;
          box-shadow: 0 2px 6px rgba(0,0,0,0.06);
        ">
          <div style="font-size: 11px; color: #999; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 6px;">
            <?= $label ?>
          </div>
          <div style="font-size: 1rem; color: #333; font-weight: 500; word-break: break-all;">
            <?= htmlspecialchars($value) ?>
          </div>
        </div>
      <?php endforeach; ?>

    </div>

    <!-- Address -->
    <div style="
      background: white;
      border: 1px solid #e0e0e0;
      border-radius: 10px;
      padding: 18px 20px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.06);
      margin-bottom: 30px;
    ">
      <div style="font-size: 11px; color: #999; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 6px;">
        Address
      </div>
      <div style="font-size: 1rem; color: #333; font-weight: 500;">
        <?= htmlspecialchars($user['address'] ?? 'N/A') ?>
      </div>
    </div>

    <!-- Edit Button -->
    <div style="display: flex; justify-content: center;">
      <a href="profile-settings.php" style="
        padding: 12px 30px;
        background: #007bff;
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 600;
      ">✏️ Edit Profile</a>
    </div>

  </div>
</main>

<?php include 'include/footer.php'; ?>
=======
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
>>>>>>> 5a31c04f2b6ace2b1b013822be65bcd13c895a56
