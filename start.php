<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Hub - Home</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            overflow-x: hidden;
        }

        header {
            height: 100vh;
            background-image: url('assets/home-bg.jpg'); 
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: white;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.7);
            position: relative;
            animation: fadeIn 2s ease-in-out;
        }

        header::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5); 
            z-index: 0;
        }

        header .content {
            z-index: 1;
            animation: slideDown 1.5s ease-out;
        }

        header h1 {
            font-size: 3rem;
            margin: 0;
            animation: zoomIn 2s ease-in;
        }

        header p {
            font-size: 1.2rem;
            margin: 10px 0 20px;
            animation: fadeInText 2.5s ease-in;
        }

        header a {
            text-decoration: none;
            color: white;
            background-color: #1abc9c;
            padding: 12px 25px;
            border-radius: 5px;
            font-size: 1rem;
            transition: background-color 0.3s, transform 0.2s;
            animation: fadeInButton 3s ease-in-out;
        }

        header a:hover {
            background-color: #16a085;
            transform: scale(1.05);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes slideDown {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes zoomIn {
            0% {
                transform: scale(0.8);
                opacity: 0;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes fadeInText {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes fadeInButton {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        
        .features {
            padding: 50px 20px;
            text-align: center;
        }

        .features h2 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            animation: fadeIn 2s ease-in;
        }

        .features p {
            font-size: 1rem;
            margin: 10px 0 30px;
            color: #555;
        }

        .features .feature-item {
            display: inline-block;
            width: 300px;
            margin: 10px;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            animation: fadeInText 2s ease-in-out;
        }

        .features .feature-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<link rel="icon" href="logo.png" type="image/png">
<body>
    
    <header>
        <div class="content">
            <h1>Welcome to the BOOKHUB</h1>
            <p>Streamline your library experience with modern tools and features.</p>
            <a href="login.php">Get Started</a>
        </div>
    </header>

</body>
</html>
<link rel="icon" href="logo.png" type="image/png">