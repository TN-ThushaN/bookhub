<?php
session_start();
require "include/dbcon.php";


if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$error = '';

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    
    if (empty($username) || empty($password)) {
        $error = "Please fill in all fields";
    } else {
        
        $query = "SELECT user_id, name, password, role FROM user WHERE name = ?";
        $stmt = mysqli_prepare($con, $query);
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($row = mysqli_fetch_assoc($result)) {
                
                if (password_verify($password, $row['password']) || $password === $row['password']) {
                    
                    $_SESSION['user_id'] = $row['user_id'];
                    $_SESSION['user'] = $row['name'];
                    $_SESSION['role'] = $row['role'];

                    
                    if ($_SESSION['role'] == 'admin') {
                        header("Location: admin-dashboard.php");
                    } else {
                        header("Location: index.php");
                    }
                    exit();
                } else {
                    $error = "Incorrect password!";
                }
            } else {
                $error = "User not found!";
            }
            mysqli_stmt_close($stmt);
        } else {
            $error = "Database error occurred. Please try again.";
        }
    }
}


$success_message = '';
if (isset($_SESSION['registered'])) {
    $success_message = $_SESSION['registered'];
    unset($_SESSION['registered']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Book Hub</title>
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
        <p class="subtitle">Sign in to your account</p>
        
        <?php if (!empty($success_message)): ?>
            <div class="success"><?= htmlspecialchars($success_message) ?></div>
        <?php endif; ?>
        
        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <form action="login.php" method="POST" novalidate>
            <div class="form-group">
                <input type="text" name="username" placeholder="Username" 
                       value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" class="btn" style="width: 100%;" name="login">Sign In</button>
        </form>
        
        <div class="links">
            <a href="password-reset.php">Forgot Password?</a>
            <span style="margin: 0 10px; color: var(--text-secondary);">|</span>
            <a href="signup.php">Create Account</a>
        </div>
        
        <div style="text-align: center; margin-top: 20px;">
           <a href="index.php" style="color: var(--text-secondary); font-size: 0.85rem;">&larr; Back to Home</a>
        </div>
    </div>
</body>
</html>