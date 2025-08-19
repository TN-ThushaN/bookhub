<?php
require 'include/dbcon.php';
$token = $_GET['token'] ?? '';
$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $password = $_POST["password"];
    $cpassword = $_POST["cpassword"];
    $token = $_POST["token"];

    if ($password !== $cpassword) {
        $msg = "Passwords do not match!";
    } else {
        $check = mysqli_query($con, "SELECT * FROM password_resets WHERE token='$token' AND expires_at > NOW()");
        if ($row = mysqli_fetch_assoc($check)) {
            $email = $row['email'];
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            mysqli_query($con, "UPDATE user SET password='$hashed' WHERE email='$email'");
            mysqli_query($con, "DELETE FROM password_resets WHERE email='$email'");
            $msg = "Password updated! <a href='login.php'>Login</a>";
        } else {
            $msg = "Invalid or expired token!";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Reset Password</title></head>
<body>
<h2>Create New Password</h2>
<form method="post">
    <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
    <input type="password" name="password" placeholder="New password" required><br>
    <input type="password" name="cpassword" placeholder="Confirm password" required><br>
    <button type="submit">Reset Password</button>
</form>
<p><?= $msg ?></p>
</body>
</html>
