<?php
session_start();
require 'include/dbcon.php';


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$error = '';
$success = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $role = (int)$_POST['role'];

    
    $check = mysqli_query($con, "SELECT * FROM user WHERE email='$email'");
    if (mysqli_num_rows($check) > 0) {
        $error = "Email already exists.";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $insert = "INSERT INTO user (name, email, password, role) VALUES ('$name', '$email', '$hashedPassword', $role)";
        if (mysqli_query($con, $insert)) {
            header("Location: user_management.php?success=User added successfully.");
            exit();
        } else {
            $error = "Failed to add user.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Add New User</title>
  <style>
    body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 30px; }
    .form-container {
      background: #fff;
      max-width: 500px;
      margin: 0 auto;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    h2 { text-align: center; }
    label { display: block; margin-top: 15px; }
    input, select {
      width: 100%; padding: 10px; margin-top: 5px;
      border: 1px solid #ccc; border-radius: 5px;
    }
    button {
      margin-top: 20px; width: 100%;
      padding: 10px; background: #1abc9c;
      color: white; border: none; border-radius: 5px;
      cursor: pointer;
    }
    button:hover { background: #16a085; }
    .back-link {
      display: block; text-align: center; margin-top: 15px;
      color: #1abc9c; text-decoration: none;
    }
    .error { color: red; text-align: center; }
  </style>
</head>
<body>

<div class="form-container">
  <h2>➕ Add New User</h2>

  <?php if (!empty($error)): ?>
    <p class="error">❌ <?= $error ?></p>
  <?php endif; ?>

  <form method="POST">
    <label for="name">Name</label>
    <input type="text" name="name" required>

    <label for="email">Email</label>
    <input type="email" name="email" required>

    <label for="password">Password</label>
    <input type="password" name="password" required>

    <label for="role">Role</label>
    <select name="role" required>
      <option value="0">Member</option>
      <option value="1">Admin</option>
    </select>

    <button type="submit">📥 Add User</button>
  </form>

  <a class="back-link" href="user_management.php">🔙 Back to User Management</a>
</div>

</body>
</html>
