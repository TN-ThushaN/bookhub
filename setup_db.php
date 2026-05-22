<?php
$host = "localhost";
$username = "root";
$password = "";

// Create connection without database to create it first
$con = mysqli_connect($host, $username, $password);

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS bookhub";
if (mysqli_query($con, $sql)) {
    echo "Database created successfully\n";
} else {
    echo "Error creating database: " . mysqli_error($con) . "\n";
}

// Select database
mysqli_select_db($con, "bookhub");

// Create tables
$tables = [
    "CREATE TABLE IF NOT EXISTS user (
        user_id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        username VARCHAR(255) NULL,
        email VARCHAR(255) NULL,
        password VARCHAR(255) NOT NULL,
        contact VARCHAR(50) NULL,
        address TEXT NULL,
        nic VARCHAR(50) NULL,
        role VARCHAR(50) DEFAULT 'user',
        image VARCHAR(255) NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    "CREATE TABLE IF NOT EXISTS books (
        book_id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        author VARCHAR(255) NOT NULL,
        isbn VARCHAR(100) NULL,
        genre VARCHAR(100) NULL,
        quantity INT DEFAULT 1,
        available_status TINYINT(1) DEFAULT 1
    )",
    "CREATE TABLE IF NOT EXISTS transactions (
        transaction_id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        book_id INT,
        borrow_date DATE NULL,
        issue_date DATE NULL,
        due_date DATE NULL,
        return_date DATE NULL,
        status VARCHAR(50) NULL
    )",
    "CREATE TABLE IF NOT EXISTS notifications (
        notification_id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        message TEXT,
        is_read TINYINT(1) DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    "CREATE TABLE IF NOT EXISTS events (
        event_id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255),
        description TEXT,
        event_date DATE,
        audience VARCHAR(100),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    "CREATE TABLE IF NOT EXISTS feedback (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255),
        email VARCHAR(255),
        topic VARCHAR(255),
        message TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )"
];

foreach ($tables as $query) {
    if (mysqli_query($con, $query)) {
        echo "Table created successfully\n";
    } else {
        echo "Error creating table: " . mysqli_error($con) . "\n";
    }
}

// Insert an admin user
$admin_pass = password_hash("admin123", PASSWORD_DEFAULT);
$admin_query = "INSERT INTO user (name, username, email, password, role) VALUES ('Admin', 'admin', 'admin@bookhub.com', '$admin_pass', 'admin') ON DUPLICATE KEY UPDATE role='admin'";
if (mysqli_query($con, $admin_query)) {
    echo "Admin user created successfully (username: admin, email: admin@bookhub.com, password: admin123)\n";
} else {
    echo "Error creating admin user: " . mysqli_error($con) . "\n";
}

mysqli_close($con);
?>
