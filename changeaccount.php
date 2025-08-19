<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Account - Library Management System</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .change-account-container {
            background: #ffffff;
            padding: 30px 40px;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            max-width: 500px;
            width: 100%;
        }

        .change-account-container h1 {
            font-size: 1.8rem;
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-size: 1rem;
            font-weight: bold;
            margin-bottom: 5px;
            color: #555;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-sizing: border-box;
            outline: none;
            transition: border 0.3s;
        }

        .form-group input:focus {
            border-color: #6e8efb;
        }

        .button-group {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .button {
            padding: 10px 20px;
            font-size: 1rem;
            font-weight: bold;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .button.save {
            background: #6e8efb;
            color: #fff;
        }

        .button.save:hover {
            background: #5a76d6;
        }

        .button.cancel {
            background: #ff4a4a;
            color: #fff;
        }

        .button.cancel:hover {
            background: #e63939;
        }
    </style>
</head>
<body>
    <div class="change-account-container">
        <h1>Change Account Details</h1>
        <form action="/update-account" method="POST">
            <!-- Full Name -->
            <div class="form-group">
                <label for="full-name">Full Name</label>
                <input type="text" id="full-name" name="full_name" placeholder="Enter your full name" required>
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>

            <!-- Contact Number -->
            <div class="form-group">
                <label for="contact-number">Contact Number</label>
                <input type="tel" id="contact-number" name="contact_number" placeholder="Enter your contact number" required>
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>

            <!-- Buttons -->
            <div class="button-group">
                <button type="submit" class="button save">Save Changes</button>
                <button type="button" class="button cancel" onclick="window.location.href='home.html';">Cancel</button>
            </div>
        </form>
    </div>
</body>
</html>
