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