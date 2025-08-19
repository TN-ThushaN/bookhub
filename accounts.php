<?php
session_start();


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "libmansysdb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$user_id = $_SESSION['user_id'] ?? null;
$profile_image = 'default-profile.jpg';

if ($user_id) {
    $stmt = $conn->prepare("SELECT image FROM user WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
        $user = $res->fetch_assoc();
        if (!empty($user['image']) && file_exists($user['image'])) {
            $profile_image = $user['image'];
        }
    }
    $stmt->close();
}

$sql = "SELECT user_id, name, email, role, status, last_active FROM user ORDER BY user_id ASC"; 
$result = $conn->query($sql);
?>
<link rel="icon" href="logo.png" type="image/png">
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Accounts - Library Management System</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f4f4f9;
      color: #333;
    }

    header {
      background-color: #2c3e50;
      color: white;
      padding: 15px 20px 50px;
      text-align: center;
      position: relative;
    }

    header h1 {
      margin: 0;
      font-size: 2.5rem;
    }

    .profile-logo {
      position: absolute;
      top: 15px;
      right: 20px;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      overflow: hidden;
      border: 2px solid #1abc9c;
      cursor: pointer;
      transition: transform 0.3s ease;
    }

    .profile-logo img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .profile-logo:hover {
      transform: scale(1.1);
    }

    nav {
      background-color: #34495e;
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
    }

    nav a {
      padding: 14px 20px;
      display: inline-block;
      color: white;
      text-decoration: none;
      margin: 4px 10px;
      border-radius: 5px;
      transition: background 0.2s;
    }

    nav a:hover {
      background-color: #1abc9c;
    }

    .container {
      max-width: 1100px;
      margin: 30px auto 70px;
      background: #fff;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    #filterInput {
      width: 100%;
      padding: 12px;
      font-size: 16px;
      margin-bottom: 20px;
      border: 1px solid #ccc;
      border-radius: 8px;
      box-sizing: border-box;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    th, td {
      text-align: left;
      padding: 12px;
      border-bottom: 1px solid #e0e0e0;
    }

    th {
      background-color: #f4f4f4;
    }

    tr:hover {
      background-color: #f9f9f9;
    }

    .online {
      color: green;
      font-weight: bold;
    }

    .offline {
      color: gray;
      font-weight: bold;
    }

    .back-button {
      display: inline-block;
      margin-top: 20px;
      background-color: #1abc9c;
      color: white;
      padding: 10px 18px;
      font-size: 14px;
      border-radius: 8px;
      text-decoration: none;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      transition: background-color 0.3s ease;
    }

    .back-button:hover {
      background-color: #159e86;
    }

    footer {
      background-color: #2c3e50;
      color: white;
      text-align: center;
      padding: 8px 0;
      position: fixed;
      bottom: 0;
      width: 100%;
      font-size: 14px;
      box-shadow: 0 -3px 8px rgba(0,0,0,0.3);
    }

    @media (max-width: 768px) {
      nav {
        flex-wrap: wrap;
      }

      nav a {
        margin: 5px 10px;
        padding: 10px 15px;
      }
    }
  </style>
</head>
<body>

  <header>
    <h1>Library Management System - Accounts</h1>
    <a href="profile.php" class="profile-logo" title="Your Profile">
      <img src="<?php echo htmlspecialchars($profile_image); ?>" alt="Profile Picture" />
    </a>
  </header>

  

  <div class="container">
    <input type="text" id="filterInput" placeholder="Search accounts..." onkeyup="filterTable()" />
    <table id="accountsTable">
      <thead>
        <tr>
          
          <th>Name</th>
          <th>Email</th>
          <th>Role</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php
          if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                  $lastActive = strtotime($row['last_active']);
                  $isOnline = (time() - $lastActive <= 300);
                  $statusText = $isOnline ? "Online" : "Offline";
                  $statusClass = $isOnline ? "online" : "offline";

                  echo "<tr>";
                  
                  echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                  echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                  echo "<td>" . htmlspecialchars($row['role']) . "</td>";
                  echo "<td class='$statusClass'>$statusText</td>";
                  echo "</tr>";
              }
          } else {
              echo "<tr><td colspan='5'>No accounts found.</td></tr>";
          }
          $conn->close();
        ?>
      </tbody>
    </table>

    <a href="index.php" class="back-button">&#8592; Back to Home</a>
  </div>

  <footer>
    <p>&copy; 2025 Book Hub - Library Management System</p>
  </footer>

  <script>
    function filterTable() {
      const input = document.getElementById("filterInput").value.toLowerCase();
      const rows = document.querySelectorAll("#accountsTable tbody tr");
      rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(input) ? "" : "none";
      });
    }
  </script>
</body>

</html>
