<?php
session_start();
require "include/dbcon.php";

$successMessage = '';
$errorMessage = '';

if (isset($_POST['send_message'])) {
    $name    = htmlspecialchars(trim($_POST['name']));
    $email   = htmlspecialchars(trim($_POST['email']));
    $topic   = htmlspecialchars(trim($_POST['topic']));
    $message = htmlspecialchars(trim($_POST['message']));

    if (!empty($name) && !empty($email) && !empty($topic) && !empty($message)) {
        $sql = "INSERT INTO feedback (name, email, topic, message) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $topic, $message);
            $query_run = mysqli_stmt_execute($stmt);

            if ($query_run) {
                $successMessage = "Thank you, <strong>$name</strong>. Your feedback has been received.";
            } else {
                $errorMessage = "Database Error: " . mysqli_error($con);
            }
            mysqli_stmt_close($stmt);
        } else {
            $errorMessage = "Prepare Error: " . mysqli_error($con);
        }
    } else {
        $errorMessage = "Please fill out all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Library Feedback & Help</title>
  <link rel="icon" href="logo.png" type="image/png">
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f8f9fa;
    }

    header {
      background-color: #2c3e50;
      color: white;
      padding: 15px;
      text-align: center;
    }

    header h1 {
      margin: 0;
      font-size: 2rem;
    }

    .container {
      padding: 20px;
      max-width: 600px;
      margin: 0 auto;
    }

    form {
      background: white;
      border: 1px solid #ddd;
      padding: 20px;
      border-radius: 5px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    form h2 {
      margin-top: 0;
      color: #2c3e50;
    }

    label {
      font-weight: bold;
      display: block;
      margin: 10px 0 5px;
    }

    input, textarea, select, button {
      width: 100%;
      padding: 10px;
      font-size: 1rem;
      margin-bottom: 20px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    textarea {
      resize: vertical;
    }

    button {
      background-color: #2c3e50;
      color: white;
      border: none;
      cursor: pointer;
    }

    button:hover {
      background-color: #34495e;
    }

    .message {
      margin-bottom: 20px;
      padding: 10px;
      border-radius: 5px;
    }

    .success {
      background-color: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }

    .error {
      background-color: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }

    .back-btn {
      display: inline-block;
      margin-top: 30px;
      background-color: #2c3e50;
      color: white;
      padding: 10px 18px;
      text-decoration: none;
      font-size: 14px;
      border-radius: 5px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      transition: background-color 0.3s ease;
    }

    .back-btn:hover {
      background-color: #1a242f;
    }

    footer {
      text-align: center;
      padding: 12px 10px;
      background-color: #2c3e50;
      color: white;
      margin-top: 60px;
    }

    @media (max-width: 600px) {
      .back-btn {
        font-size: 13px;
        padding: 8px 14px;
      }
    }
  </style>
</head>
<body>

  <header>
    <h1>Library Feedback & Help</h1>
  </header>

  <div class="container">
    <?php if ($successMessage): ?>
      <div class="message success"><?= $successMessage ?></div>
    <?php elseif ($errorMessage): ?>
      <div class="message error"><?= $errorMessage ?></div>
    <?php endif; ?>

    <form method="POST" action="">
      <h2>Submit Feedback or Request Help</h2>

      <label for="name">Your Name</label>
      <input type="text" id="name" name="name" placeholder="Enter your name" required>

      <label for="email">Your Email</label>
      <input type="email" id="email" name="email" placeholder="Enter your email" required>

      <label for="topic">Select a Topic</label>
      <select id="topic" name="topic" required>
        <option value="">-- Choose Topic --</option>
        <option value="feedback">Feedback</option>
        <option value="help">Help Request</option>
        <option value="complaint">Complaint</option>
        <option value="other">Other</option>
      </select>

      <label for="message">Message</label>
      <textarea id="message" name="message" rows="6" placeholder="Write your message here..." required></textarea>

      <button type="submit" name="send_message">Submit</button>
    </form>

    
    <a href="index.php" class="back-btn">🔙 Back to Home</a>
  </div>

  <footer>
    <p>&copy; 2025 Library Management System. All rights reserved.</p>
  </footer>

</body>
</html>
