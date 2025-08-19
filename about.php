<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>About Us - Book Hub</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f8f9fa;
      color: #333;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    header {
      background-color: #2c3e50;
      color: white;
      padding: 20px;
      text-align: center;
    }

    header h1 {
      margin: 0;
      font-size: 2.2rem;
    }

    nav {
      background-color: #34495e;
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      padding: 10px;
    }

    nav a {
      color: white;
      text-decoration: none;
      margin: 8px 15px;
      font-size: 1rem;
      padding: 5px 10px;
    }

    nav a:hover {
      background-color: #1abc9c;
      border-radius: 4px;
    }

    .container {
      max-width: 1000px;
      margin: 20px auto;
      padding: 0 20px 40px;
      flex: 1; 
    }

    .section {
      background: white;
      margin-bottom: 25px;
      padding: 25px 20px;
      border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .section h2 {
      margin-top: 0;
      color: #2c3e50;
    }

    .section p {
      line-height: 1.6;
      color: #555;
    }

    .back-btn {
      display: inline-block;
      margin-top: 20px;
      background-color: #2c3e50;
      color: white;
      padding: 10px 16px;
      font-size: 14px;
      text-decoration: none;
      border-radius: 5px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      transition: background-color 0.3s ease;
    }

    .back-btn:hover {
      background-color: #1a242f;
    }

    footer {
      text-align: center;
      padding: 10px;
      background-color: #2c3e50;
      color: white;
    }

    @media (max-width: 768px) {
      header h1 {
        font-size: 1.8rem;
      }

      .back-btn {
        font-size: 13px;
        padding: 8px 14px;
      }
    }
  </style>
</head>
<body>

  <header>
    <h1>📖 About Us</h1>
  </header>

  <div class="container">
    <div class="section">
      <h2>Our Mission</h2>
      <p>At <strong>Book Hub</strong>, our mission is to create a seamless and modern experience for borrowing books from the library. We are dedicated to providing fast, accessible, and user-friendly services that support education, research, and lifelong learning.</p>
    </div>

    <div class="section">
      <h2>Our History</h2>
      <p>Founded in 2025, Book Hub began as a student project to solve the delays and difficulties in traditional book borrowing systems. Over time, it has grown into a fully functional platform trusted by hundreds of users for managing their book loans digitally. We continue to improve with feedback and innovation.</p>
    </div>

    <div class="section">
      <h2>Why Book Hub?</h2>
      <p>We believe in the power of reading and easy access to knowledge. Book Hub is committed to simplifying how libraries operate by integrating technology with traditional borrowing practices. Whether you're a student, a teacher, or a book lover, Book Hub makes your library experience better.</p>
    </div>

   
    <a href="index.php" class="back-btn">🔙 Back to Home</a>
  </div>

  <footer>
    <p>&copy; 2025 Book Hub - Library Management System</p>
  </footer>

</body>
<link rel="icon" href="logo.png" type="image/png">
</html>
