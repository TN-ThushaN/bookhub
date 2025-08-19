<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Settings - Library Management System</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Arial', sans-serif; }
    body { display: flex; height: 100vh; background: #f4f4f4; }
    .sidebar {
      width: 250px; background: #6e8efb; padding: 20px;
      height: 100vh; color: white;
    }
    .sidebar h2 { text-align: center; margin-bottom: 20px; }
    .sidebar a {
      display: block; color: white; text-decoration: none;
      padding: 10px; margin: 5px 0;
      border-radius: 5px; transition: 0.3s;
    }
    .sidebar a:hover { background: rgba(255, 255, 255, 0.2); }

    .container {
      flex: 1; padding: 40px; background: white;
      margin: 40px auto; width: 60%;
      box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
      border-radius: 10px;
    }
    h1 { text-align: center; margin-bottom: 20px; }
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; font-weight: bold; margin-bottom: 5px; }
    .form-group input, .form-group select {
      width: 100%; padding: 10px; font-size: 1rem;
      border: 2px solid #ddd; border-radius: 5px; outline: none;
    }
    .save-btn {
      width: 100%; padding: 12px; font-size: 1rem;
      font-weight: bold; border: none; border-radius: 10px;
      background: #6e8efb; color: white; cursor: pointer;
      transition: background 0.3s ease;
    }
    .save-btn:hover { background: #5a76d6; }
  </style>
</head>
<body>

  <div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="admin-dashboard.php">Dashboard</a>
    <a href="admin-settings.php">Admin Settings</a>
    <a href="logout.php">Logout</a>
  </div>

  <div class="container">
    <h1>⚙️ Admin Settings</h1>

    <form action="save_settings.php" method="POST">

     
      <div class="form-group">
        <label for="user_role_action">User Role Management</label>
        <select id="user_role_action" name="user_role_action">
          <option value="add">Add New Role</option>
          <option value="change">Change Role</option>
          <option value="remove">Remove Role</option>
        </select>
      </div>

      
      <div class="form-group">
        <label for="library_policy">Library Policies</label>
        <input type="text" id="library_policy" name="library_policy" placeholder="Update Library Policy">
      </div>

      
      <div class="form-group">
        <label for="theme">System Theme</label>
        <select id="theme" name="theme">
          <option value="light">Light Mode</option>
          <option value="dark">Dark Mode</option>
        </select>
      </div>

     
      <div class="form-group">
        <label>Database Backup</label>
        <button type="button" class="save-btn" onclick="backupDatabase()">Backup Now</button>
      </div>

      
      <div class="form-group">
        <label for="library_hours">Library Hours</label>
        <input type="text" id="library_hours" name="library_hours" placeholder="Enter Library Opening Hours (e.g., 8AM - 5PM)">
      </div>

      
      <div class="form-group">
        <label for="holidays">Library Holidays</label>
        <input type="text" id="holidays" name="holidays" placeholder="Enter Holiday Dates (e.g., 2025-01-01, 2025-12-25)">
      </div>

      
      <div class="form-group">
        <label for="report_settings">Report Settings</label>
        <select id="report_settings" name="report_settings">
          <option value="enable">Enable Automatic Reports</option>
          <option value="disable">Disable Automatic Reports</option>
        </select>
      </div>

      
      <div class="form-group">
        <label for="notifications">Admin Email Notifications</label>
        <select id="notifications" name="notifications">
          <option value="enable">Enable Email Alerts</option>
          <option value="disable">Disable Email Alerts</option>
        </select>
      </div>

      <button type="submit" class="save-btn">Save Changes</button>
    </form>
  </div>

  <script>
    function backupDatabase() {
      alert("📦 Database backup initiated. This will be connected to a backend script.");
      
    }
  </script>

</body>
</html>
