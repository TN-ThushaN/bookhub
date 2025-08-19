<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Hub - Settings</title>
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
        nav {
            background-color: #34495e;
            padding: 10px;
        }
        nav a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-size: 1rem;
        }
        nav a:hover {
            text-decoration: underline;
        }
        .container {
            padding: 20px;
        }
        .settings-section {
            margin-bottom: 20px;
        }
        .settings-section h2 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }
        .settings-section label {
            display: block;
            margin: 10px 0 5px;
        }
        .settings-section input, .settings-section select, .settings-section button {
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
            width: 100%;
        }
        footer {
            text-align: center;
            padding: 1px;
            background-color: #2c3e50;
            color: white;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<link rel="icon" href="logo.png" type="image/png">
<body>
    <header>
        <h1>Book Hub - Settings</h1>
    </header>
    <nav>
        <a href="index.php">Home</a>
        <a href="accounts.php">Accounts</a>
        <a href="transactions.php">Transactions</a>
        <a href="reports.php">Reports</a>
        <a href="notifications.php">Notifications</a>
        <a href="contact us.php">Contact us</a>
    </nav>
    <div class="container">
        <div class="settings-section">
            <h2>Library Preferences</h2>
            <label for="libraryName">Library Name:</label>
            <input type="text" id="libraryName" placeholder="Enter library name">
            
            <label for="maxBorrowDays">Maximum Borrow Days:</label>
            <input type="number" id="maxBorrowDays" placeholder="Enter maximum days for borrowing">
            
            <label for="overdueFine">Overdue Fine per Day:</label>
            <input type="number" id="overdueFine" placeholder="Enter overdue fine per day">
            
            <button onclick="savePreferences()">Save Preferences</button>
        </div>
        
        <div class="settings-section">
            
            
            <button onclick="updateUserRole()">Update Role</button>
        </div>
    </div>
    <footer>
        <p>&copy; 2025 Library Management System. All rights reserved.</p>
    </footer>

    <script>
        
        const preferences = {
            libraryName: "Default Library",
            maxBorrowDays: 14,
            overdueFine: 1,
        };

        function savePreferences() {
            const libraryName = document.getElementById("libraryName").value;
            const maxBorrowDays = document.getElementById("maxBorrowDays").value;
            const overdueFine = document.getElementById("overdueFine").value;

            if (libraryName && maxBorrowDays && overdueFine) {
                preferences.libraryName = libraryName;
                preferences.maxBorrowDays = parseInt(maxBorrowDays);
                preferences.overdueFine = parseFloat(overdueFine);

                alert("Preferences saved successfully!");
            } else {
                alert("Please fill in all fields.");
            }
        }

        function updateUserRole() {
            const selectedRole = document.getElementById("userRole").value;
            alert(`User role updated to ${selectedRole}.`);
            
        }
    </script>
</body>
</html>
