<?php
session_start();
require "include/dbcon.php";

if (!isset($_SESSION['role'])) {
    header("Location:login.php");
    exit();
}

$totalUsers = 0;
$totalBooks = 0;
$issuedBooks = 0;
$pendingReturns = 0;
$monthlyData = array_fill(1, 12, 0);

if ($con) {
    $userResult = mysqli_query($con, "SELECT COUNT(*) AS total_users FROM user");
    $bookResult = mysqli_query($con, "SELECT COUNT(*) AS total_books FROM books");
    $issuedResult = mysqli_query($con, "SELECT COUNT(*) AS issued_books FROM transactions WHERE return_date IS NULL");
    $pendingResult = mysqli_query($con, "SELECT COUNT(*) AS pending_returns FROM transactions WHERE return_date IS NULL AND due_date < CURDATE()");

    $totalUsers = mysqli_fetch_assoc($userResult)['total_users'] ?? 0;
    $totalBooks = mysqli_fetch_assoc($bookResult)['total_books'] ?? 0;
    $issuedBooks = mysqli_fetch_assoc($issuedResult)['issued_books'] ?? 0;
    $pendingReturns = mysqli_fetch_assoc($pendingResult)['pending_returns'] ?? 0;

    $monthlyQuery = "SELECT MONTH(issue_date) AS month, COUNT(*) AS count 
                     FROM transactions 
                     WHERE YEAR(issue_date) = YEAR(CURDATE()) 
                     GROUP BY MONTH(issue_date)";
    $monthlyResult = mysqli_query($con, $monthlyQuery);
    while ($row = mysqli_fetch_assoc($monthlyResult)) {
        $monthlyData[(int)$row['month']] = (int)$row['count'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
        }

        .dashboard-container {
            display: flex;
            flex-direction: row;
        }

        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            color: #fff;
            padding: 20px;
            min-height: 100vh;
            position: fixed;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            margin-bottom: 15px;
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
            padding: 10px;
            display: block;
            border-radius: 5px;
        }

        .sidebar ul li a:hover {
            background-color: #1abc9c;
        }

        .dashboard-content {
            margin-left: 270px;
            padding: 20px;
            flex-grow: 1;
            width: 100%;
        }

        header h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .stats-overview {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .stat-box {
            background: white;
            flex: 1 1 200px;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .stat-box h3 {
            font-size: 16px;
            color: #555;
            margin-bottom: 10px;
        }

        .stat-box p {
            font-size: 28px;
            color: #2c3e50;
            margin: 0;
        }

        .reports {
            margin-top: 30px;
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            max-width: 100%;
        }

        #transactionChart {
            width: 100%;
            height: 250px !important;
        }

        @media (max-width: 768px) {
            .dashboard-container {
                flex-direction: column;
            }

            .sidebar {
                position: relative;
                width: 100%;
                min-height: auto;
            }

            .dashboard-content {
                margin-left: 0;
                padding: 15px;
            }

            .stat-box {
                flex: 1 1 100%;
            }

            header h1 {
                font-size: 20px;
                text-align: center;
            }
        }
    </style>
    <link rel="icon" href="logo.png" type="image/png">
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <h2>Admin Panel</h2>
            <ul>
                <li><a href="user_management.php">User Management</a></li>
                <li><a href="book_management.php">Book Management</a></li>
                <li><a href="admin_transaction.php">Transactions</a></li>
                <li><a href="admin_overdue.php">Overdue Books</a></li>
                <li><a href="admin_add_event.php">Add Events</a></li>
                <li><a href="admin_feedback.php">View Feedback</a></li>
                <li><a href="admin_send_message.php">Send Message</a></li>
                <li><a href="admin_reports.php">Reports</a></li>
                <li><a href="logout.php">Logout</a></li>
                
            </ul>
        </aside>

        <main class="dashboard-content">
            <header>
                <h1>Library Admin Dashboard</h1>
            </header>

            <section class="stats-overview">
                <div class="stat-box">
                    <h3>Total Users</h3>
                    <p><?= $totalUsers ?></p>
                </div>
                <div class="stat-box">
                    <h3>Total Books</h3>
                    <p><?= $totalBooks ?></p>
                </div>
                <div class="stat-box">
                    <h3>Issued Books</h3>
                    <p><?= $issuedBooks ?></p>
                </div>
                <div class="stat-box">
                    <h3>Pending Returns</h3>
                    <p><?= $pendingReturns ?></p>
                </div>
            </section>

            <section class="reports">
                <h2 style="font-size: 18px;">Monthly Issued Books</h2>
                <canvas id="transactionChart"></canvas>
            </section>
        </main>
    </div>

    <script>
        const ctx = document.getElementById('transactionChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                         'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Issued Books',
                    data: <?= json_encode(array_values($monthlyData)) ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderRadius: 5,
                    maxBarThickness: 25
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
