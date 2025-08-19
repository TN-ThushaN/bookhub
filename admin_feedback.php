<?php
include 'include/dbcon.php';
session_start();


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$sql = "SELECT feedback_id, name, email, topic, message, submitted_at FROM feedback ORDER BY submitted_at DESC";
$result = mysqli_query($con, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Feedback - Admin Panel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #f9f9f9;
        }

        h2 {
            color: #333;
            text-align: center;
        }

        .feedback-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 10px;
            margin: 20px auto;
            padding: 15px;
            max-width: 800px;
            box-shadow: 1px 2px 6px rgba(0,0,0,0.1);
        }

        .feedback-header {
            font-weight: bold;
            font-size: 1.1em;
            margin-bottom: 5px;
        }

        .feedback-date {
            color: gray;
            font-size: 0.9em;
            margin-bottom: 10px;
        }

        .feedback-topic {
            font-style: italic;
            color: #555;
            margin-bottom: 10px;
        }

        .back-btn {
            display: inline-block;
            margin-top: 30px;
            margin-left: 20px;
            padding: 10px 15px;
            background-color: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .back-btn:hover {
            background-color: #495057;
        }
    </style>
</head>
<body>

    <h2>User Feedback</h2>

    <?php
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='feedback-card'>";
            echo "<div class='feedback-header'>" . htmlspecialchars($row['name']) . " (" . htmlspecialchars($row['email']) . ")</div>";
            echo "<div class='feedback-date'>Submitted on " . date("F j, Y, g:i A", strtotime($row['submitted_at'])) . "</div>";
            echo "<div class='feedback-topic'>Topic: " . htmlspecialchars($row['topic']) . "</div>";
            echo "<p>" . nl2br(htmlspecialchars($row['message'])) . "</p>";
            echo "</div>";
        }
    } else {
        echo "<p style='text-align:center;'>No feedback submitted yet.</p>";
    }
    ?>

    <a href="admin-dashboard.php" class="back-btn">← Back</a>
<link rel="icon" href="logo.png" type="image/png">
</body>
</html>
