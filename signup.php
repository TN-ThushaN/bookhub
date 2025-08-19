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
  <style>
    * {
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    body {
      background: linear-gradient(135deg, #2a9d8f, #264653);
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      padding: 20px;
    }
    .signup-container {
      background: #ffffff;
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
      width: 100%;
      max-width: 500px;
    }
    h1 {
      font-size: 2rem;
      color: #264653;
      margin-bottom: 10px;
      text-align: center;
    }
    .subtitle {
      color: #6c757d;
      text-align: center;
      margin-bottom: 30px;
      font-size: 0.95rem;
    }
    .form-group {
      margin-bottom: 18px;
    }
    .form-group input,
    .form-group textarea {
      width: 100%;
      padding: 14px;
      font-size: 1rem;
      border: 2px solid #e9ecef;
      border-radius: 10px;
      outline: none;
      transition: border-color 0.3s ease;
      background: #f9f9f9;
    }
    .form-group textarea {
      resize: none;
      height: 70px;
    }
    .form-group input:focus,
    .form-group textarea:focus {
      border-color: #2a9d8f;
      background: #fff;
    }
    .form-group input::placeholder {
      color: #adb5bd;
    }
    .signup-btn {
      width: 100%;
      padding: 14px;
      font-size: 1.1rem;
      font-weight: 600;
      border: none;
      border-radius: 10px;
      background: #2a9d8f;
      color: white;
      cursor: pointer;
      transition: background-color 0.3s ease;
      margin-bottom: 20px;
    }
    .signup-btn:hover {
      background: #21867a;
    }
    .error {
      color: #e63946;
      font-size: 0.9rem;
      margin-bottom: 15px;
      padding: 10px;
      background-color: #ffeaea;
      border-radius: 6px;
      border-left: 4px solid #e63946;
    }
    .links {
      text-align: center;
      font-size: 0.9rem;
    }
    .links a {
      text-decoration: none;
      color: #2a9d8f;
      font-weight: 500;
    }
    .links a:hover {
      color: #21867a;
      text-decoration: underline;
    }
    @media (max-width: 480px) {
      .signup-container {
        padding: 30px 20px;
      }
    }
  </style>
</head>
<body>
  <div class="signup-container">
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

      <button type="submit" name="signup" class="signup-btn">Create Account</button>
    </form>

    <div class="links">
      Already have an account? <a href="login.php">Login here</a>
    </div>
  </div>
</body>
</html>
<link rel="icon" href="logo.png" type="image/png">