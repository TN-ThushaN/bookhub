<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Controls - Library System</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            background-color: #f4f4f4;
        }
        .container {
            display: flex;
            width: 100%;
        }
        .sidebar {
            width: 250px;
            background: #333;
            color: white;
            padding: 20px;
            height: 100vh;
        }
        .sidebar h2 {
            text-align: center;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar ul li {
            padding: 10px;
            margin: 10px 0;
            background: #444;
            cursor: pointer;
        }
        .sidebar ul li a {
            text-decoration: none;
            color: white;
            display: block;
        }
        .sidebar ul li:hover {
            background: #555;
        }
        .content {
            flex: 1;
            padding: 20px;
        }
        .analytics {
            background: white;
            padding: 20px;
            margin-top: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .controls {
            margin-top: 20px;
        }
        .controls button {
            margin-right: 10px;
            padding: 10px;
            border: none;
            background-color: #007bff;
            color: white;
            cursor: pointer;
            border-radius: 5px;
        }
        .controls button:hover {
            background-color: #0056b3;
        }
        .dark-mode {
            background-color: #121212;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <nav class="sidebar">
            <h2>Admin Panel</h2>
            <ul>
                <li><a href="dashboard.html">Dashboard</a></li>
                <li><a href="manage-accounts.html">Manage User Accounts</a></li>
                <li><a href="add-staff.html">Add New Staff</a></li>
                <li><a href="manage-books.html">Manage Books</a></li>
                <li><a href="transactions.html">Manage Transactions</a></li>
                <li><a href="reports.html">Generate Reports</a></li>
                <li><a href="logs.html">System Logs</a></li>
                <li><a href="backup.html">Backup & Restore</a></li>
                <li><a href="security.html">Security & Access Control</a></li>
                <li><a href="system-settings.html">System Settings</a></li>
                <li><a href="user-roles.html">User Role Management</a></li>
                <li><a href="activity-log.html">Activity Log</a></li>
                <li><a href="#" onclick="toggleDarkMode()">Toggle Dark Mode</a></li>
                <li><a href="#" onclick="showNotifications()">Notifications</a></li>
                <li><a href="logout.html">Logout</a></li>
            </ul>
        </nav>
        
        <main class="content">
            <h1>Admin Controls</h1>
            <p>As an Admin, you have full control over the Library Management System, including user accounts, staff, books, transactions, reports, security, and system settings.</p>
            
            <section class="analytics">
                <h2>System Analytics</h2>
                <canvas id="adminChart"></canvas>
            </section>
            
            <section class="controls">
                <h2>Admin Features</h2>
                <button onclick="toggleDarkMode()">Toggle Dark Mode</button>
                <button onclick="showNotifications()">View Notifications</button>
                <button onclick="generateBackup()">Backup Data</button>
                <button onclick="restoreBackup()">Restore Data</button>
                <button onclick="viewActivityLog()">View Activity Log</button>
            </section>
        </main>
    </div>

    <script>
        const ctx = document.getElementById('adminChart').getContext('2d');
        const adminChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Active Users',
                    data: [120, 150, 180, 200, 250, 270, 300, 320, 340, 360, 380, 400],
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
        
        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode');
        }
        
        function showNotifications() {
            alert("No new notifications.");
        }
        
        function generateBackup() {
            alert("Backup process started!");
        }
        
        function restoreBackup() {
            alert("Restore process initiated!");
        }
        
        function viewActivityLog() {
            alert("Displaying activity logs.");
        }
    </script>
</body>
</html>

