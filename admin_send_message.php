<?php
session_start();
require "include/dbcon.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$msg = "";
$msg_type = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send'])) {
    $receiver_id = intval($_POST['receiver_id']);
    $message = trim($_POST['message']);

    if (empty($receiver_id) || empty($message)) {
        $msg = "❌ All fields are required.";
        $msg_type = "error";
    } else {
        $check_user = $con->prepare("SELECT user_id FROM user WHERE user_id = ?");
        $check_user->bind_param("i", $receiver_id);
        $check_user->execute();
        $result = $check_user->get_result();

        if ($result->num_rows === 0) {
            $msg = "❌ User not found.";
            $msg_type = "error";
        } else {
            $stmt = $con->prepare("INSERT INTO notifications (user_id, message, created_at, is_read) VALUES (?, ?, NOW(), 0)");
            $stmt->bind_param("is", $receiver_id, $message);

            if ($stmt->execute()) {
                $msg = "✅ Notification sent successfully!";
                $msg_type = "success";
                $_POST = [];
            } else {
                $msg = "❌ Failed to send notification.";
                $msg_type = "error";
            }
            $stmt->close();
        }
        $check_user->close();
    }
}

$users_result = $con->query("SELECT user_id, name, email FROM user ORDER BY name ASC");
$users = [];
if ($users_result && $users_result->num_rows > 0) {
    while ($row = $users_result->fetch_assoc()) {
        $users[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Send Notification - Admin Panel</title>
<style>
body {
    font-family: Arial, sans-serif;
    background: #f0f0f5;
    margin: 20px;
}
.container {
    max-width: 600px;
    margin: auto;
    background: white;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    position: relative;
}
h2 {
    text-align: center;
    margin-bottom: 20px;
}
.msg {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 5px;
    font-weight: bold;
    text-align: center;
}
.msg.success {
    background: #d4edda;
    color: #155724;
}
.msg.error {
    background: #f8d7da;
    color: #721c24;
}
.form-group {
    margin-bottom: 20px;
}
label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
}
select, textarea {
    width: 100%;
    padding: 10px 12px;
    font-size: 1rem;
    border: 1.8px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
}
textarea {
    min-height: 120px;
    resize: vertical;
}
button {
    background-color: #007bff;
    color: white;
    padding: 12px;
    font-size: 1.1rem;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    width: 100%;
}
button:hover {
    background-color: #0056b3;
}
.back-button {
    display: inline-block;
    margin-top: 20px;
    padding: 10px 18px;
    background-color: #1abc9c;
    color: white;
    font-size: 14px;
    border-radius: 6px;
    text-decoration: none;
    box-shadow: 0 3px 6px rgba(0,0,0,0.1);
    transition: background-color 0.3s ease;
}
.back-button:hover {
    background-color: #159e86;
}
</style>
</head>
<body>

<div class="container">
    <h2>Send Notification to User</h2>

    <?php if ($msg): ?>
        <div class="msg <?=htmlspecialchars($msg_type)?>">
            <?=htmlspecialchars($msg)?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="receiver_id">Select User *</label>
            <select name="receiver_id" id="receiver_id" required>
                <option value="">-- Select User --</option>
                <?php foreach($users as $user): ?>
                    <option value="<?=htmlspecialchars($user['user_id'])?>" <?= (isset($_POST['receiver_id']) && $_POST['receiver_id'] == $user['user_id']) ? 'selected' : '' ?>>
                        <?=htmlspecialchars($user['name'])?> (<?=htmlspecialchars($user['email'])?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="message">Message *</label>
            <textarea name="message" id="message" maxlength="1000" required><?= isset($_POST['message']) ? htmlspecialchars($_POST['message']) : '' ?></textarea>
        </div>
        <button type="submit" name="send">Send Notification</button>
    </form>

    
    <a href="admin-dashboard.php" class="back-button">&#8592; Back</a>
</div>
<link rel="icon" href="logo.png" type="image/png">
</body>
</html>
