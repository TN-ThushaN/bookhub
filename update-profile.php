<?php
session_start();

// Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "libmansysdb");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get logged-in user ID from session for security
$user_id = $_SESSION['user_id'];

// Get form data safely
$username = trim($_POST['username'] ?? '');
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$nic = trim($_POST['nic'] ?? '');
$contact = trim($_POST['contact'] ?? '');
$address = trim($_POST['address'] ?? '');
$password = $_POST['password'] ?? '';

$imagePath = null;
$updatePassword = false;

// ==========================
// Profile Image Upload
// ==========================
if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {

    $fileTmp = $_FILES['profile_image']['tmp_name'];
    $fileName = $_FILES['profile_image']['name'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    $allowed = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($fileExt, $allowed)) {

        // Create uploads folder if not exists
        if (!is_dir("uploads")) {
            mkdir("uploads", 0777, true);
        }

        // Unique file name
        $newFileName = "uploads/profile_" . $user_id . "_" . time() . "." . $fileExt;

        if (move_uploaded_file($fileTmp, $newFileName)) {
            $imagePath = $newFileName;
        } else {
            $_SESSION['error'] = "Image upload failed.";
            header("Location: profile-settings.php");
            exit();
        }

    } else {
        $_SESSION['error'] = "Only JPG, JPEG, PNG, GIF files are allowed.";
        header("Location: profile-settings.php");
        exit();
    }
}

// ==========================
// Password Hashing
// ==========================
if (!empty($password)) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $updatePassword = true;
}

// ==========================
// Build SQL Query
// ==========================
$sql = "UPDATE user SET 
        username=?, 
        name=?, 
        email=?, 
        nic=?, 
        contact=?, 
        address=?";

$params = [$username, $name, $email, $nic, $contact, $address];
$types = "ssssss";

// Add password if entered
if ($updatePassword) {
    $sql .= ", password=?";
    $params[] = $hashedPassword;
    $types .= "s";
}

// Add image if uploaded
if ($imagePath) {
    $sql .= ", image=?";
    $params[] = $imagePath;
    $types .= "s";
}

// Where condition
$sql .= " WHERE user_id=?";
$params[] = $user_id;
$types .= "i";

// ==========================
// Prepare Statement
// ==========================
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

// Bind parameters
$stmt->bind_param($types, ...$params);

// Execute
if ($stmt->execute()) {
    $_SESSION['success'] = "Profile updated successfully.";
} else {
    $_SESSION['error'] = "Update failed: " . $stmt->error;
}

// Close
$stmt->close();
$conn->close();

// Redirect
header("Location: profile-settings.php");
exit();
?>