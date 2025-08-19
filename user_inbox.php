<?php
session_start();
require "include/dbcon.php";

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; 

$result = mysqli_query($con, "SELECT * FROM messages WHERE receiver_id = '$user_id' ORDER BY sent_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Messages</title>
</head>
<body>
<h2>Inbox</h2>
<table border="1" cellpadding="10">
    <tr>
        <th>Subject</th>
        <th>Message</th>
        <th>Received</th>
        <th>Status</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?= htmlspecialchars($row['subject']) ?></td>
            <td><?= htmlspecialchars($row['body']) ?></td>
            <td><?= $row['sent_at'] ?></td>
            <td><?= $row['is_read'] ? 'Read' : 'Unread' ?></td>
        </tr>
    <?php endwhile; ?>
</table>
</body>
</html>
