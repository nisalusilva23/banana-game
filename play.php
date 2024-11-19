<?php
include 'connect.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Function to update score based on difficulty
function updateScore($player_name, $difficulty) {
    global $conn;
    $points = 0;

    switch ($difficulty) {
        case 'easy':
            $points = 1;
            break;
        case 'medium':
            $points = 2;
            break;
        case 'hard':
            $points = 3;
            break;
        default:
            $points = 0;
    }

    $checkPlayer = "SELECT * FROM leaderboard WHERE player_name='$player_name'";
    $result = $conn->query($checkPlayer);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $newScore = $row['score'] + $points;
        $updateQuery = "UPDATE leaderboard SET score='$newScore' WHERE player_name='$player_name'";
        $conn->query($updateQuery);
    } else {
        $insertQuery = "INSERT INTO leaderboard (player_name, score) VALUES ('$player_name', '$points')";
        $conn->query($insertQuery);
    }
}

//user's email is stored in session after login
$player_name = $_SESSION['email'];

$apiUrl = "http://marcconrad.com/uob/banana/api.php?out=json";
$apiResponse = @file_get_contents($apiUrl);

if ($apiResponse !== false) {
    $gameData = json_decode($apiResponse, true);

    if (isset($gameData['question']) && isset($gameData['solution'])) {
        $questionImage = $gameData['question'];
        $solution = $gameData['solution'];
    } else {
        echo "Unexpected API response format.";
        $questionImage = null;
        $solution = null;
    }
} else {
    echo "Error accessing API.";
    $questionImage = null;
    $solution = null;
}

$difficulty = isset($_GET['difficulty']) ? $_GET['difficulty'] : 'easy';
$timeLimit = 30; 

switch ($difficulty) {
    case 'medium':
        $timeLimit = 20;
        break;
    case 'hard':
        $timeLimit = 10;
        break;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WELCOME TO PEEL THE PUZZLE</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>

        .home-btn {
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
        body {
            font-family: Arial, sans-serif;
            display: flex;
            font-size: 1.5rem;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            background-color: #f5f5f5;
            background-image: url('Images/ai-generated-8889421.jpg');
            background-size: cover;
            color: #333;
        }

        h1 {
            margin-bottom: 20px;
            color: #fff;
        }

        img {
            width: 600px; 
            height: auto; 
            margin-bottom: 20px; 
        }

        .number-bar {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .number-button {
            width: 50px;
            height: 50px;
            margin: 0 5px; 
            background-color: #f0a500;
            color: white;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .number-button:hover {
            background-color: #d18e00;
        }

        #result {
            margin-top: 20px;
            font-size: 1.2rem;
            font-weight: bold;
            color: #fff;
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
            transition: background-color 0.3s ease;
        }
        .logout-btn:hover {
            background-color: #d18e00;
        }

        .timer {
            position: absolute;
            top: 50%;
            left: 20px;
            transform: translateY(-50%);
            font-size: 2rem;
            color: #fff;
            font-weight: bold;
        }

        .instructions {
            margin-bottom: 20px;
            font-size: 1.5rem;
            color: #fff;
        }
    </style>
</head>
<body>
    <h1>Welcome to Peel the Puzzle</h1>
    <a href="logout.php" class="logout-btn">Logout</a>
    <a href="homepage.php" class="home-btn"><i class="fas fa-home"></i> Home</a>

    <?php if ($questionImage): ?>
        <img src="<?php echo htmlspecialchars($questionImage); ?>" alt="Puzzle Image">
    <?php else: ?>
        <p>Error loading puzzle data.</p>
    <?php endif; ?>

    <div class="timer">Time left: <span id="time-limit"><?php echo $timeLimit; ?></span>s</div>

    <p class="instructions">Select the correct number within <span id="time-limit"><?php echo $timeLimit; ?></span> seconds.</p>

    <div class="number-bar">
        <!-- Number buttons from 0 to 9 -->
        <?php for ($i = 0; $i <= 9; $i++): ?>
            <button class="number-button" onclick="checkGuess(<?php echo $i; ?>)">
                <?php echo $i; ?>
            </button>
        <?php endfor; ?>
    </div>

    <p id="result"></p>

    <script>
        const solution = <?php echo isset($solution) ? $solution : 'null'; ?>;
        const resultDisplay = document.getElementById("result");
        const timeLimit = <?php echo $timeLimit; ?>;
        let timeLeft = timeLimit;

        function checkGuess(guess) {
            if (solution === null) {
                resultDisplay.innerHTML = "Error loading game data. Please try again later.";
                return;
            }

            if (guess === solution) {
                resultDisplay.innerHTML = "Correct! Loading next game...";
                
                // Update the score using AJAX call
                updateScoreAjax('<?php echo $difficulty; ?>');
                
                // Fetch a new puzzle by reloading the page
                setTimeout(() => {
                    location.reload();
                }, 500); // wait 0.5 second before reloading for next game
            } else {
                resultDisplay.innerHTML = "Wrong guess. Try again!";
            }
        }

        function updateScoreAjax(difficulty) {
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "update_score.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.send("difficulty=" + difficulty + "&player_name=" + "<?php echo $player_name; ?>");
        }

        // Timer countdown
        const timer = setInterval(() => {
            timeLeft--;
            document.getElementById('time-limit').innerText = timeLeft;

            if (timeLeft <= 0) {
                clearInterval(timer);
                resultDisplay.innerHTML = "Time's up! Loading next game...";
                setTimeout(() => {
                    location.reload();
                }, 250); // wait 0.5 second before reloading for next game
            }
        }, 1000);
    </script>
</body>
</html>
