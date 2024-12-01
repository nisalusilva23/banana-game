<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include("connect.php");

// user's email is stored in session after login
$userEmail = $_SESSION['email'];

// Fetch the user's name from the users table
function getUserName($userEmail) {
    global $conn;
    $query = "SELECT name FROM users WHERE email = '$userEmail'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['name'];
    } else {
        return "User";
    }
}

$userName = getUserName($userEmail);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WELCOME TO PEEL THE PUZZLE</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap');

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

        .form-title {
            font-size: 3.5rem;
            font-weight: bold;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            animation: fadeIn 2s ease-in-out;
        }

        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background-color: #f0a500;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        .logout-btn:hover {
            background-color: #d18e00;
            transform: scale(1.1);
        }

        .leaderboard-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            padding: 10px 20px;
            background-color: #f0a500;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        .leaderboard-btn:hover {
            background-color: #d18e00;
            transform: scale(1.1);
        }

        .difficulty-btn-container {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-top: 20px;
        }

        .play-btn {
            font-size: 2rem;
            padding: 15px 30px;
            background-color: rgba(240, 165, 0, 0.8);
            color: #fff;
            text-decoration: none;
            border-radius: 20px;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.3s ease;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            animation: popIn 1s ease-in-out;
        }
        .play-btn:hover {
            background-color: rgba(240, 165, 0, 1);
            transform: translateY(-5px) scale(1.05);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes popIn {
            from { opacity: 0; transform: scale(0.5); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>
</head>
<body>
    <h1 class="form-title">Hi, <?php echo htmlspecialchars($userName); ?> !</h1>
    <h2 class="form-title">Welcome to Peel the Puzzle</h2>
    <a href="logout.php" class="logout-btn">Logout</a>
    <a href="leaderboard.php" class="leaderboard-btn">Leaderboard</a>

    <div class="difficulty-btn-container">
        <a href="play.php?difficulty=easy" class="play-btn">EASY</a>
        <a href="play.php?difficulty=medium" class="play-btn">MEDIUM</a>
        <a href="play.php?difficulty=hard" class="play-btn">HARD</a>
    </div>
</body>
</html>