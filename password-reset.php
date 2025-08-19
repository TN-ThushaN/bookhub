<?php
require 'include/dbcon.php';

 
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


define('RESET_TOKEN_EXPIRY_HOURS', 1);
define('SITE_URL', 'https://yourdomain.com'); 
define('FROM_EMAIL', 'noreply@yourdomain.com');
define('FROM_NAME', 'Library System');


define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_USERNAME', 'your@gmail.com');
define('SMTP_PASSWORD', 'your_app_password'); 
define('SMTP_PORT', 587);

$msg = "";
$msg_type = ""; 


session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function cleanExpiredTokens($con) {
    $stmt = $con->prepare("DELETE FROM password_resets WHERE expires_at < NOW()");
    $stmt->execute();
    $stmt->close();
}

function sendResetEmail($email, $token) {
    $mail = new PHPMailer(true);
    
    try {
        
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = SMTP_PORT;
        
        
        $mail->CharSet = 'UTF-8';
        
        
        $mail->setFrom(FROM_EMAIL, FROM_NAME);
        $mail->addAddress($email);
        $mail->addReplyTo(FROM_EMAIL, FROM_NAME);
        
        
        $reset_link = SITE_URL . "/reset-password.php?token=" . urlencode($token);
        
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Request - ' . FROM_NAME;
        
        $mail->Body = "
        <html>
        <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
            <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
                <h2 style='color: #2c3e50;'>Password Reset Request</h2>
                <p>You have requested a password reset for your account.</p>
                <p>Click the link below to reset your password:</p>
                <p style='margin: 20px 0;'>
                    <a href='$reset_link' 
                       style='background-color: #3498db; color: white; padding: 12px 24px; 
                              text-decoration: none; border-radius: 4px; display: inline-block;'>
                        Reset Password
                    </a>
                </p>
                <p><strong>This link will expire in " . RESET_TOKEN_EXPIRY_HOURS . " hour(s).</strong></p>
                <p>If you did not request this password reset, please ignore this email.</p>
                <hr style='margin: 30px 0; border: none; border-top: 1px solid #eee;'>
                <p style='font-size: 12px; color: #666;'>
                    If the button doesn't work, copy and paste this link into your browser:<br>
                    <a href='$reset_link'>$reset_link</a>
                </p>
            </div>
        </body>
        </html>";
        
        $mail->AltBody = "Password Reset Request\n\n" .
                        "You have requested a password reset for your account.\n" .
                        "Click the link below to reset your password:\n\n" .
                        "$reset_link\n\n" .
                        "This link will expire in " . RESET_TOKEN_EXPIRY_HOURS . " hour(s).\n" .
                        "If you did not request this password reset, please ignore this email.";
        
        return $mail->send();
        
    } catch (Exception $e) {
        error_log("Password reset email failed: " . $e->getMessage());
        return false;
    }
}


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $msg = "Invalid request. Please try again.";
        $msg_type = "error";
    } else if (isset($_POST["email"])) {
        $email = sanitizeInput($_POST["email"]);
        
        
        if (!validateEmail($email)) {
            $msg = "Please enter a valid email address.";
            $msg_type = "error";
        } else {
            
            cleanExpiredTokens($con);
            
           
            $stmt = $con->prepare("SELECT user_id FROM user WHERE email = ? LIMIT 1");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                
                $token = bin2hex(random_bytes(32));
                $expires = date("Y-m-d H:i:s", strtotime("+" . RESET_TOKEN_EXPIRY_HOURS . " hours"));
                
               
                $delete_stmt = $con->prepare("DELETE FROM password_resets WHERE email = ?");
                $delete_stmt->bind_param("s", $email);
                $delete_stmt->execute();
                $delete_stmt->close();
                
            
                $insert_stmt = $con->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
                $insert_stmt->bind_param("sss", $email, $token, $expires);
                
                if ($insert_stmt->execute()) {
                    if (sendResetEmail($email, $token)) {
                        $msg = "If an account with that email exists, we've sent you a password reset link.";
                        $msg_type = "success";
                    } else {
                        $msg = "There was an error sending the reset email. Please try again later.";
                        $msg_type = "error";
                        
                        
                        $cleanup_stmt = $con->prepare("DELETE FROM password_resets WHERE token = ?");
                        $cleanup_stmt->bind_param("s", $token);
                        $cleanup_stmt->execute();
                        $cleanup_stmt->close();
                    }
                } else {
                    $msg = "There was an error processing your request. Please try again later.";
                    $msg_type = "error";
                }
                
                $insert_stmt->close();
            } else {
                
                $msg = "If an account with that email exists, we've sent you a password reset link.";
                $msg_type = "success";
            }
            
            $stmt->close();
        }
    }
    
    
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<link rel="icon" href="logo.png" type="image/png">
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset - Library System</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h2 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #555;
        }
        input[type="email"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }
        input[type="email"]:focus {
            outline: none;
            border-color: #3498db;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #2980b9;
        }
        button:disabled {
            background-color: #bdc3c7;
            cursor: not-allowed;
        }
        .message {
            padding: 12px;
            border-radius: 4px;
            margin: 20px 0;
            text-align: center;
        }
        .message.success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        .message.error {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        .back-link a {
            color: #3498db;
            text-decoration: none;
        }
        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Reset Password</h2>
        
        <?php if (!empty($msg)): ?>
            <div class="message <?= $msg_type ?>">
                <?= htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>
        
        <form method="post" id="resetForm">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            
            <div class="form-group">
                <label for="email">Email Address:</label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       required 
                       placeholder="Enter your email address"
                       value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8') : '' ?>">
            </div>
            
            <button type="submit" id="submitBtn">Send Reset Link</button>
        </form>
        
        <div class="back-link">
            <a href="login.php">← Back to Login</a>
        </div>
    </div>

    <script>
        
        document.getElementById('resetForm').addEventListener('submit', function() {
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Sending...';
            
            
            setTimeout(function() {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Send Reset Link';
            }, 3000);
        });
    </script>
</body>
</html>