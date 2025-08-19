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
    <style>
        * {
            margin: 0;
            padding: 0;
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
        .login-container {
            background: #ffffff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 100%;
            max-width: 400px;
        }
        .logo {
            margin-bottom: 20px;
        }
        h1 {
            font-size: 2rem;
            color: #264653;
            margin-bottom: 10px;
            font-weight: 600;
        }
        .subtitle {
            color: #6c757d;
            margin-bottom: 30px;
            font-size: 0.95rem;
        }
        .form-group {
            margin-bottom: 20px;
            position: relative;
        }
        .form-group input {
            width: 100%;
            padding: 14px 20px;
            font-size: 1rem;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            outline: none;
            transition: border-color 0.3s ease;
        }
        .form-group input:focus {
            border-color: #2a9d8f;
        }
        .form-group input::placeholder {
            color: #adb5bd;
        }
        .login-btn {
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
        .login-btn:hover {
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
        .success {
            color: #2a9d8f;
            font-size: 0.9rem;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #e8f5f3;
            border-radius: 6px;
            border-left: 4px solid #2a9d8f;
        }
        .links {
            margin-top: 20px;
            font-size: 0.9rem;
        }
        .links a {
            text-decoration: none;
            color: #2a9d8f;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        .links a:hover {
            color: #21867a;
            text-decoration: underline;
        }
        .divider {
            margin: 0 8px;
            color: #6c757d;
        }
        
        
        @media (max-width: 480px) {
            .login-container {
                padding: 30px 20px;
                margin: 10px;
            }
            h1 {
                font-size: 1.7rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <h1>📚 Book Hub</h1>
            <p class="subtitle">Sign in to your account</p>
        </div>
        
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
            <button type="submit" class="login-btn" name="login">Sign In</button>
        </form>
        
        <div class="links">
            <a href="password-reset.php">Forgot Password?</a>
            <span class="divider">|</span>
            <a href="signup.php">Create Account</a>
        </div>
    </div>
    <link rel="icon" href="logo.png" type="image/png">
</body>
</html>