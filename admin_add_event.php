<?php
session_start();
include 'include/dbcon.php';


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}

$message = '';
$message_type = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $event_date = $_POST['event_date'] ?? '';
    $audience = $_POST['audience'] ?? '';

    $errors = [];

    if (empty($title)) {
        $errors[] = "Title is required.";
    } elseif (strlen($title) > 255) {
        $errors[] = "Title must be less than 255 characters.";
    }

    if (empty($description)) {
        $errors[] = "Description is required.";
    }

    if (empty($event_date)) {
        $errors[] = "Event date is required.";
    } else {
        $date = DateTime::createFromFormat('Y-m-d\TH:i', $event_date);
        if (!$date || $date < new DateTime()) {
            $errors[] = "Event date must be in the future.";
        }
    }

    $valid_audiences = ['all', 'students', 'staff'];
    if (!in_array($audience, $valid_audiences)) {
        $errors[] = "Invalid audience selection.";
    }

    if (empty($errors)) {
        $stmt = $con->prepare("INSERT INTO events (title, description, event_date, audience, created_at) VALUES (?, ?, ?, ?, NOW())");
        if ($stmt) {
            $stmt->bind_param("ssss", $title, $description, $event_date, $audience);
            if ($stmt->execute()) {
                $message = "✅ Event added successfully!";
                $message_type = "success";
                $title = $description = $event_date = $audience = '';
            } else {
                $message = "❌ Error adding event: " . $stmt->error;
                $message_type = "error";
            }
            $stmt->close();
        } else {
            $message = "❌ Database error: " . $con->error;
            $message_type = "error";
        }
    } else {
        $message = implode("<br>", $errors);
        $message_type = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Event - Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 700px;
            background: #fff;
            margin: 40px auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        h1 {
            text-align: center;
            color: #343a40;
            margin-bottom: 30px;
        }

        .message {
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }

        .message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 8px;
        }

        input[type="text"],
        input[type="datetime-local"],
        textarea,
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            font-size: 16px;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        .btn-group {
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 25px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            border: none;
        }

        .btn-primary {
            background: #007bff;
            color: #fff;
        }

        .btn-primary:hover {
            background: #0056b3;
        }

        .btn-secondary {
            background: #6c757d;
            color: #fff;
        }

        .btn-secondary:hover {
            background: #545b62;
        }

        .bottom-back {
            text-align: center;
            margin-top: 30px;
        }

        .back-link {
            display: inline-block;
            padding: 10px 20px;
            background-color: #343a40;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
        }

        .back-link:hover {
            background-color: #1d2124;
        }

        @media (max-width: 600px) {
            .btn-group {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Add New Event</h1>

    <?php if (!empty($message)): ?>
        <div class="message <?= $message_type ?>">
            <?= $message ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="title">Event Title *</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($title ?? '') ?>" maxlength="255" required>
        </div>

        <div class="form-group">
            <label for="description">Description *</label>
            <textarea id="description" name="description" required><?= htmlspecialchars($description ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label for="event_date">Event Date & Time *</label>
            <input type="datetime-local" id="event_date" name="event_date" value="<?= htmlspecialchars($event_date ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label for="audience">Target Audience</label>
            <select id="audience" name="audience">
                <option value="all" <?= (($audience ?? '') === 'all') ? 'selected' : '' ?>>All Users</option>
                
            </select>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn btn-primary">Add Event</button>
            <a href="admin-dashboard.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>

    
    <div class="bottom-back">
        <a href="admin-dashboard.php" class="back-link">🔙 Back </a>
    </div>
</div>
<link rel="icon" href="logo.png" type="image/png">
<script>
    
    document.addEventListener('DOMContentLoaded', function () {
        const now = new Date();
        const formatted = now.toISOString().slice(0, 16);
        document.getElementById('event_date').min = formatted;
    });

    
    const successMessage = document.querySelector('.message.success');
    if (successMessage) {
        setTimeout(() => {
            successMessage.style.opacity = '0';
            setTimeout(() => successMessage.remove(), 500);
        }, 3000);
    }
</script>

</body>
</html>
