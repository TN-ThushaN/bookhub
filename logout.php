<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout - Library Management System</title>
    <link rel="icon" href="logo.png" type="image/png">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #6e8efb, #a777e3);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
        }

        .logout-container {
            text-align: center;
            background: rgba(255, 255, 255, 0.1);
            padding: 30px 40px;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
        }

        .logout-container h1 {
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .logout-container p {
            font-size: 1rem;
            margin-bottom: 30px;
        }

        .buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .button {
            padding: 10px 20px;
            font-size: 1rem;
            font-weight: bold;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .button.confirm {
            background: #ff4a4a;
            color: #fff;
        }

        .button.confirm:hover {
            background: #e63939;
        }

        .button.cancel {
            background: #fff;
            color: #6e8efb;
        }

        .button.cancel:hover {
            background: #f4f4f4;
        }

        .button a {
            text-decoration: none;
            color: inherit;
        }
    </style>
</head>
<body>
    <div class="logout-container">
        <h1>Are you sure you want to logout?</h1>
        <p>You will need to log in again to access your account.</p>
        <div class="buttons">
            <form method="post">
            <button type="submit" class="button confirm" name="logout">
                Yes, Logout
            </button>
            </form>
            <button class="button cancel">
                <a href="profile.php">Cancel</a> 
            </button>
        </div>
    </div>
</body>
</html>

<?php
if(isset($_POST['logout'])){
    session_start();
    session_destroy();
    header("Location:start.php");
}
?>
