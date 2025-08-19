<?php
session_start();


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}


$conn = new mysqli("localhost", "root", "", "libmansysdb");
if ($conn->connect_error) {
    die("DB connection failed: " . $conn->connect_error);
}


$user_id = $_POST['user_id'];
$username = trim($_POST['username']);
$name = trim($_POST['name']);
$email = trim($_POST['email']);
$nic = trim($_POST['nic']);
$contact = trim($_POST['contact']);
$address = trim($_POST['address']);
$password = $_POST['password']; 

$updatePassword = false;
$imagePath = null;


if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
    $fileTmp = $_FILES['profile_image']['tmp_name'];
    $fileName = basename($_FILES['profile_image']['name']);
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($fileExt, $allowed)) {
        $newFileName = 'uploads/profile_' . $user_id . '.' . $fileExt;
        if (!is_dir('uploads')) {
            mkdir('uploads', 0755, true);
        }
        if (move_uploaded_file($fileTmp, $newFileName)) {
            $imagePath = $newFileName;
        } else {
            $_SESSION['error'] = "Failed to upload profile image.";
            header("Location: profile-settings.php");
            exit;
        }
    } else {
        $_SESSION['error'] = "Invalid image format. Allowed: JPG, JPEG, PNG, GIF.";
        header("Location: profile-settings.php");
        exit;
    }
}


if (!empty($password)) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $updatePassword = true;
}


$sql = "UPDATE user SET username=?, name=?, email=?, nic=?, contact=?, address=?";
$params = [$username, $name, $email, $nic, $contact, $address];
$types = "ssssss";

if ($updatePassword) {
    $sql .= ", password=?";
    $params[] = $hashedPassword;
    $types .= "s";
}

if ($imagePath) {
    $sql .= ", image=?";
    $params[] = $imagePath;
    $types .= "s";
}

$sql .= " WHERE user_id=?";
$params[] = $user_id;
$types .= "i";


$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    $_SESSION['success'] = "Profile updated successfully.";
} else {
    $_SESSION['error'] = "Failed to update profile. Error: " . $conn->error;
}

$stmt->close();
$conn->close();


header("Location: profile-settings.php");
exit;

