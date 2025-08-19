<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Library Management System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: #f4f4f4;
        }

        .container {
            width: 50%;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        h1 {
            margin-bottom: 20px;
            color: #6e8efb;
        }

        .contact-info {
            margin-bottom: 20px;
        }

        .contact-info p {
            font-size: 16px;
            margin-bottom: 5px;
        }

        .contact-info i {
            margin-right: 8px;
            color: #6e8efb;
        }

        .form-group {
            text-align: left;
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            font-size: 1rem;
            border: 2px solid #ddd;
            border-radius: 5px;
            outline: none;
        }

        .form-group textarea {
            resize: vertical;
            height: 120px;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            border-color: #6e8efb;
            box-shadow: 0 0 8px rgba(110, 142, 251, 0.5);
        }

        .send-btn {
            width: 100%;
            padding: 12px;
            font-size: 1rem;
            font-weight: bold;
            border: none;
            border-radius: 10px;
            background: #6e8efb;
            color: white;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .send-btn:hover {
            background: #5a76d6;
        }

        @media (max-width: 768px) {
            .container {
                width: 80%;
            }
        }
    </style>
</head>
<link rel="icon" href="logo.png" type="image/png">
<body>

    <div class="container">
        <h1>Contact Us</h1>

        <div class="contact-info">
            <p><i>📍</i> Address: 123 Library Street, City, Country</p>
            <p><i>📧</i> Email: support@library.com</p>
            <p><i>📞</i> Phone: +123 456 7890</p>
            <p><i>🕒</i> Hours: Mon - Fri, 9:00 AM - 6:00 PM</p>
        </div>

        <form action="/send-message" method="POST">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="message">Message</label>
                <textarea id="message" name="message" required></textarea>
            </div>

            <button type="submit" class="send-btn">Send Message</button>
        </form>
    </div>

</body>
</html>
