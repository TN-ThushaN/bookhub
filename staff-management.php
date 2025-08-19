<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Management - Book Hub</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: #f4f4f4;
        }

        .container {
            max-width: 1200px;
            margin: 50px auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            padding: 30px;
        }

        h1 {
            text-align: center;
            font-size: 2rem;
            margin-bottom: 20px;
            color: #333;
        }

        .add-staff-button {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 20px;
            font-size: 1rem;
            font-weight: bold;
            color: #fff;
            background: #6e8efb;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            transition: background 0.3s;
        }

        .add-staff-button:hover {
            background: #5a76d6;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #6e8efb;
            color: #fff;
        }

        tr:hover {
            background: #f1f1f1;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .action-buttons button {
            padding: 8px 12px;
            font-size: 0.9rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .edit-button {
            background: #ffc107;
            color: #fff;
        }

        .edit-button:hover {
            background: #e0a800;
        }

        .delete-button {
            background: #ff4a4a;
            color: #fff;
        }

        .delete-button:hover {
            background: #e63939;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Staff Management</h1>
        <a href="addstaff.html" class="add-staff-button">Add New Staff</a>
        <table>
            <thead>
                <tr>
                    <th>Staff ID</th>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Contact</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                
                <tr>
                    <td>S12345</td>
                    <td>John Smith</td>
                    <td>Librarian</td>
                    <td>+123 456 7890</td>
                    <td class="action-buttons">
                        <button class="edit-button">Edit</button>
                        <button class="delete-button">Delete</button>
                    </td>
                </tr>
                
            </tbody>
        </table>
    </div>
</body>
</html>
