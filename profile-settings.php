<?php
session_start();


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}


$conn = new mysqli("localhost", "root", "", "libmansysdb");
if ($conn->connect_error) {
    die("DB Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];


$sql = "SELECT * FROM user WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    echo "User not found.";
    exit;
}

$user = $result->fetch_assoc();
$conn->close();


$profilePic = (!empty($user['image']) && file_exists($user['image'])) ? $user['image'] : 'default-profile.jpg';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Profile Settings - Book Hub</title>
  <style>
    
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f9f9f9;
      display: flex;
      min-height: 100vh;
    }

    
    .sidebar {
      width: 220px;
      background-color: #2c3e50;
      color: white;
      padding: 20px 15px;
      display: flex;
      flex-direction: column;
      gap: 15px;
      position: relative;
      height: 100vh;
      overflow-y: auto;
    }
    .sidebar h2 {
      font-weight: 700;
      font-size: 1.6rem;
      margin-bottom: 20px;
      text-align: center;
      letter-spacing: 1px;
    }
    .sidebar a {
      color: white;
      text-decoration: none;
      font-size: 1rem;
      padding: 10px 12px;
      border-radius: 5px;
      transition: background-color 0.3s ease;
    }
    .sidebar a:hover,
    .sidebar a.active {
      background-color: #1abc9c;
      color: #fff;
      font-weight: 600;
    }

    
    .container {
      margin-left: 240px;
      padding: 30px 40px;
      flex-grow: 1;
      max-width: 900px;
      background-color: white;
      box-shadow: 0 4px 10px rgb(0 0 0 / 0.05);
      border-radius: 8px;
      margin-top: 40px;
      margin-bottom: 40px;
    }

    
    h1 {
      margin-bottom: 25px;
      color: #34495e;
      font-weight: 700;
      font-size: 2rem;
      letter-spacing: 0.04em;
    }

    
    .profile-pic-container {
      position: relative;
      width: 130px;
      height: 130px;
      margin-bottom: 30px;
    }
    .profile-pic {
      width: 130px;
      height: 130px;
      object-fit: cover;
      border-radius: 50%;
      border: 4px solid #1abc9c;
      box-shadow: 0 2px 6px rgba(0,0,0,0.15);
      display: block;
    }
    .upload-btn {
      position: absolute;
      bottom: 0;
      right: 0;
      background-color: #1abc9c;
      border: none;
      border-radius: 50%;
      width: 38px;
      height: 38px;
      color: white;
      font-size: 20px;
      cursor: pointer;
      box-shadow: 0 2px 6px rgba(0,0,0,0.2);
      transition: background-color 0.3s ease;
    }
    .upload-btn:hover {
      background-color: #16a085;
    }
    #file-input {
      display: none;
    }

    
    form {
      max-width: 600px;
    }
    .form-group {
      margin-bottom: 18px;
      display: flex;
      flex-direction: column;
    }
    label {
      font-weight: 600;
      margin-bottom: 6px;
      color: #34495e;
    }
    input[type="text"],
    input[type="email"],
    input[type="password"] {
      padding: 10px 12px;
      font-size: 1rem;
      border: 1.8px solid #ccc;
      border-radius: 6px;
      transition: border-color 0.3s ease;
    }
    input[type="text"]:focus,
    input[type="email"]:focus,
    input[type="password"]:focus {
      border-color: #1abc9c;
      outline: none;
      box-shadow: 0 0 6px #1abc9caa;
    }

    
    .save-btn {
      background-color: #1abc9c;
      border: none;
      color: white;
      font-weight: 700;
      padding: 12px 25px;
      border-radius: 7px;
      cursor: pointer;
      font-size: 1.1rem;
      transition: background-color 0.3s ease;
      margin-top: 10px;
      width: 100%;
      max-width: 200px;
    }
    .save-btn:hover {
      background-color: #16a085;
    }

    
    .alert {
      padding: 12px 15px;
      margin-bottom: 20px;
      border-radius: 6px;
      font-weight: 500;
    }
    .alert-success {
      background-color: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }
    .alert-error {
      background-color: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }
    .alert-info {
      background-color: #cce7ff;
      color: #0c5460;
      border: 1px solid #b8daff;
    }

    
    @media (max-width: 768px) {
      body {
        flex-direction: column;
      }
      .sidebar {
        width: 100%;
        height: auto;
        position: relative;
        flex-direction: row;
        justify-content: center;
        padding: 10px 0;
      }
      .sidebar a {
        margin: 0 10px;
        padding: 10px 15px;
      }
      .container {
        margin-left: 0;
        margin-top: 20px;
        padding: 20px;
      }
      .profile-pic-container {
        margin: 0 auto 30px auto;
      }
      form {
        max-width: 100%;
      }
    }
  </style>
</head>
<link rel="icon" href="logo.png" type="image/png">
<body>
  <div class="sidebar">
    <h2>Dashboard</h2>
    <a href="index.php">Home</a>
    <a href="profile.php">Profile</a>
    <a href="profile-settings.php" class="active">Profile Settings</a>
    <a href="logout.php">Logout</a>
  </div>

  <div class="container">
    <h1>Profile Settings</h1>

    <?php
    
    if (isset($_SESSION['success'])) {
        echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['success']) . '</div>';
        unset($_SESSION['success']);
    }
    if (isset($_SESSION['error'])) {
        echo '<div class="alert alert-error">' . htmlspecialchars($_SESSION['error']) . '</div>';
        unset($_SESSION['error']);
    }
    if (isset($_SESSION['info'])) {
        echo '<div class="alert alert-info">' . htmlspecialchars($_SESSION['info']) . '</div>';
        unset($_SESSION['info']);
    }
    ?>

    <form action="update-profile.php" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">

      
      <div class="profile-pic-container">
        <img id="profile-pic" src="<?php echo htmlspecialchars($profilePic); ?>" alt="Profile Picture" class="profile-pic" />
        <button type="button" class="upload-btn" onclick="document.getElementById('profile-image').click()">📷</button>
        <input type="file" name="profile_image" id="profile-image" accept="image/*" onchange="previewImage(event)" style="display: none;">
      </div>

      
      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>" required />
      </div>

      <div class="form-group">
        <label for="name">Full Name</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required />
      </div>

      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required />
      </div>

      <div class="form-group">
        <label for="nic">NIC</label>
        <input type="text" id="nic" name="nic" value="<?php echo htmlspecialchars($user['nic'] ?? ''); ?>" />
      </div>

      <div class="form-group">
        <label for="contact">Phone Number</label>
        <input type="text" id="contact" name="contact" value="<?php echo htmlspecialchars($user['contact'] ?? ''); ?>" />
      </div>

      <div class="form-group">
        <label for="address">Address</label>
        <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user['address'] ?? ''); ?>" />
      </div>

      <div class="form-group">
        <label for="password">New Password</label>
        <input type="password" id="password" name="password" placeholder="Enter new password (leave blank to keep current)" />
      </div>

      <button type="submit" class="save-btn">Save Changes</button>
    </form>
  </div>

  <script>
    function previewImage(event) {
      const file = event.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function () {
          document.getElementById("profile-pic").src = reader.result;
        };
        reader.readAsDataURL(file);
      }
    }
  </script>
</body>
<link rel="icon" href="logo.png" type="image/png">
</html>
