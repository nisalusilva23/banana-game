<?php
include 'connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('Images/ai-generated-8889421.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 80vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #fff;
            text-align: center;
            overflow: hidden;
        }
        .home-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            padding: 15px 25px;
            background-color: #f0a500;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            display: flex;
            align-items: center;
        }

        .home-btn:hover {
            background-color: #d18e00;
            transform: scale(1.1);
        }

        .home-btn i {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <a href="homepage.php" class="home-btn"><i class="fas fa-home"></i> Home</a>        
</body>
</html>
