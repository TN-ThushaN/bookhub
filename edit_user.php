<?php
session_start();
require 'include/dbcon.php';


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}


if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: user_management.php?error=Invalid user ID.");
    exit();
}

$user_id = (int)$_GET['id'];
$user = null;


$query = "SELECT * FROM user WHERE user_id = $user_id LIMIT 1";
$result = mysqli_query($con, $query);
if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
} else {
    header("Location: user_management.php?error=User not found.");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $role = (int)$_POST['role'];

    if (empty($name) || empty($email)) {
        $error = "All fields are required.";
    } else {
        $updateQuery = "UPDATE user SET name = '$name', email = '$email', role = $role WHERE user_id = $user_id";
        if (mysqli_query($con, $updateQuery)) {
            header("Location: user_management.php?success=User updated successfully.");
            exit();
        } else {
            $error = "Failed to update user.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 40px;
        }
        .container {
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-top: 12px;
            font-weight: bold;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        button {
            background: #1abc9c;
            color: white;
            padding: 10px 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background: #16a085;
        }
        .back-link {
            text-align: center;
            margin-top: 15px;
        }
        .error {
            color: red;
            margin-bottom: 15px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>✏️ Edit User</h2>

    <?php if (isset($error)): ?>
        <p class="error">❌ <?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="name">Name:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>

        <label for="email">Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

        <label for="role">Role:</label>
        <select name="role">
            <option value="0" <?= $user['role'] == 0 ? 'selected' : '' ?>>Member</option>
            <option value="1" <?= $user['role'] == 1 ? 'selected' : '' ?>>Admin</option>
        </select>

        <button type="submit">💾 Save Changes</button>
    </form>

    <div class="back-link">
        <a href="user_management.php">🔙 Back to User Management</a>
    </div>
</div>

</body>
</html>
