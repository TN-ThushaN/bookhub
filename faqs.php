<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>FAQs - Library Management</title>
  <link rel="icon" href="logo.png" type="image/png">
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f8f9fa;
      color: #333;
    }
    header {
      background-color: #2c3e50;
      color: white;
      padding: 20px;
      text-align: center;
    }
    header h1 {
      margin: 0;
    }
    .container {
      max-width: 800px;
      margin: 0 auto;
      padding: 20px;
    }
    .search-box {
      width: 100%;
      max-width: 400px;
      padding: 10px;
      font-size: 1rem;
      margin: 0 auto 20px;
      display: block;
      border: 2px solid #ccc;
      border-radius: 5px;
      outline: none;
    }
    .search-box:focus {
      border-color: #2c3e50;
    }
    .faq {
      background: white;
      padding: 15px;
      margin: 15px 0;
      border-radius: 5px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      text-align: left;
      transition: 0.3s ease;
    }
    .faq h2 {
      margin: 0;
      font-size: 1.2rem;
      cursor: pointer;
      color: #2c3e50;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .faq h2 span {
      font-weight: bold;
      transition: transform 0.3s;
    }
    .faq p {
      display: none;
      margin-top: 10px;
      color: #555;
    }
    .faq.open p {
      display: block;
    }
    .faq.open h2 {
      color: #1abc9c;
    }
    .back-btn {
      display: inline-block;
      margin-top: 30px;
      background-color: #2980b9;
      color: white;
      padding: 10px 18px;
      text-decoration: none;
      font-size: 14px;
      border-radius: 5px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      transition: background-color 0.3s ease;
    }
    .back-btn:hover {
      background-color: #1c5980;
    }
    footer {
      text-align: center;
      background: #2c3e50;
      color: white;
      padding: 10px;
      position: fixed;
      bottom: 0;
      width: 100%;
    }
  </style>
</head>
<body>
  <header>
    <h1>📘 Frequently Asked Questions</h1>
  </header>

  <div class="container">
    <input type="text" class="search-box" id="search" placeholder="Search FAQs..." onkeyup="searchFAQs()">

    <div class="faq">
      <h2>What are the library's operating hours? <span>+</span></h2>
      <p>Our library is open from 9:00 AM to 8:00 PM, Monday through Saturday. We are closed on Sundays and public holidays.</p>
    </div>
    <div class="faq">
      <h2>How can I borrow books? <span>+</span></h2>
      <p>You can borrow books by logging into your account and requesting available titles. Each user can borrow up to 3 books at a time.</p>
    </div>
    <div class="faq">
      <h2>What should I do if I lose a book? <span>+</span></h2>
      <p>If you lose a book, report it to the admin through your account. You may be asked to replace it or pay a penalty as per the library's policy.</p>
    </div>
    <div class="faq">
      <h2>Can I access digital resources? <span>+</span></h2>
      <p>Currently, Book Hub focuses only on physical book borrowing. Digital resources are not yet available.</p>
    </div>

    
    <a href="index.php" class="back-btn">🔙 Back to Home</a>
  </div>

  <footer>
    <p>&copy; 2025 Library Management System. All rights reserved.</p>
  </footer>

  <script>
    
    document.querySelectorAll(".faq h2").forEach(header => {
      header.addEventListener("click", () => {
        const faq = header.parentElement;
        faq.classList.toggle("open");
        header.querySelector("span").textContent = faq.classList.contains("open") ? "−" : "+";
      });
    });

    
    function searchFAQs() {
      const input = document.getElementById("search").value.toLowerCase();
      document.querySelectorAll(".faq").forEach(faq => {
        const question = faq.querySelector("h2").textContent.toLowerCase();
        const answer = faq.querySelector("p").textContent.toLowerCase();
        faq.style.display = (question.includes(input) || answer.includes(input)) ? "block" : "none";
      });
    }
  </script>
</body>

</html>
