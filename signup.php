<?php
session_start();
require "include/dbcon.php";

$errors = [];

if (isset($_POST['signup'])) {
    $name = trim($_POST['name']);
    $nic = trim($_POST['nic']);
    $contact = trim($_POST['contact']);
    $address = trim($_POST['address']);
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    $role = 'user'; 

    if (empty($name) || empty($contact) || empty($nic) || empty($address) || empty($password) || empty($cpassword)) {
        $errors[] = "All fields are required";
    }

    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long";
    }

    if ($password !== $cpassword) {
        $errors[] = "Passwords do not match";
    }

    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO user (name, nic, password, contact, address, role) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ssssss", $name, $nic, $hashed_password, $contact, $address, $role);
            $query_run = mysqli_stmt_execute($stmt);

            if ($query_run) {
                $_SESSION['registered'] = "Account created successfully!";
                header("Location: login.php");
                exit();
            } else {
                $errors[] = "Failed to create account.";
            }
            mysqli_stmt_close($stmt);
        } else {
            $errors[] = "Database error: " . mysqli_error($con);
        }
        mysqli_close($con);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sign Up - Book Hub</title>
  <link rel="icon" href="logo.png" type="image/png">
  <link rel="stylesheet" href="assets/css/style.css">
  <style>
    body {
      justify-content: center;
      align-items: center;
      display: flex;
    }
  </style>
</head>
<body>
  <div class="auth-container">
    <h1>📚 Book Hub</h1>
    <p class="subtitle">Create your account to get started</p>

    <?php if (!empty($errors)): ?>
      <?php foreach ($errors as $error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
      <?php endforeach; ?>
    <?php endif; ?>

    <form method="post" novalidate>
      <div class="form-group">
        <input type="text" name="name" placeholder="Full Name" required>
      </div>

      <div class="form-group">
        <input type="text" name="nic" placeholder="NIC Number" required>
      </div>

      <div class="form-group">
        <input type="tel" name="contact" placeholder="Contact Number" required>
      </div>

      <div class="form-group">
        <textarea name="address" placeholder="Address" required></textarea>
      </div>

      <div class="form-group">
        <input type="password" name="password" placeholder="Password (min 8 chars)" required>
      </div>

      <div class="form-group">
        <input type="password" name="cpassword" placeholder="Confirm Password" required>
      </div>

      <button type="submit" name="signup" class="btn" style="width: 100%;">Create Account</button>
    </form>

    <div class="links">
      Already have an account? <a href="login.php">Login here</a>
    </div>
    
    <div style="text-align: center; margin-top: 20px;">
        <a href="index.php" style="color: var(--text-secondary); font-size: 0.85rem;">&larr; Back to Home</a>
    </div>
  </div>
</body>
</html>