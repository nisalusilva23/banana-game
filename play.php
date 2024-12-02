<?php
// Include the database connection file
include 'connect.php';

// Start a session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Function to update score based on difficulty
function updateScore($player_name, $difficulty) {
    global $conn;  // Use the global database connection

    $points = 0; // Initialize points variable

    switch ($difficulty) {
        case 'easy':
            $points = 1; // Easy puzzles give 1 point
            break;
        case 'medium':
            $points = 2; // Medium puzzles give 2 points
            break;
        case 'hard':
            $points = 3; // Hard puzzles give 3 points
            break;
        default:
            $points = 0; // Default to 0 points if difficulty is invalid
    }

    // Check if the player already has an entry in the leaderboard
    $checkPlayer = "SELECT * FROM leaderboard WHERE player_name='$player_name'";
    $result = $conn->query($checkPlayer);

    // If player exists, update their score, otherwise insert a new entry
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();  // Fetch the existing player's data
        $newScore = $row['score'] + $points; // Calculate new score
        $updateQuery = "UPDATE leaderboard SET score='$newScore' WHERE player_name='$player_name'"; // Update query
        $conn->query($updateQuery); // Execute the update query
    } else {
        // Insert a new player with the starting score
        $insertQuery = "INSERT INTO leaderboard (player_name, score) VALUES ('$player_name', '$points')";
        $conn->query($insertQuery); // Execute the insert query
    }
}

// Retrieve the player's email from the session (assuming the player is logged in)
$player_name = $_SESSION['email'];

// URL for the external API that provides game data
$apiUrl = "http://marcconrad.com/uob/banana/api.php?out=json";

// Attempt to fetch the API response
$apiResponse = @file_get_contents($apiUrl);

if ($apiResponse !== false) {
    // Decode the JSON response into a PHP array
    $gameData = json_decode($apiResponse, true);

    // Check if the required data is present in the API response
    if (isset($gameData['question']) && isset($gameData['solution'])) {
        $questionImage = $gameData['question']; // Store the question image URL
        $solution = $gameData['solution']; // Store the solution
    } else {
        // If the expected data is not found, display an error message
        echo "Unexpected API response format.";
        $questionImage = null; // Set questionImage to null if the response format is unexpected
        $solution = null; // Set solution to null
    }
} else {
    // Handle case where API request fails
    echo "Error accessing API.";
    $questionImage = null; // Set questionImage to null in case of an error
    $solution = null; // Set solution to null in case of an error
}

// Set the default difficulty to 'easy' if not specified
$difficulty = isset($_GET['difficulty']) ? $_GET['difficulty'] : 'easy';

// Set the default time limit for the game (30 seconds for easy difficulty)
$timeLimit = 30; 

// Adjust the time limit based on the selected difficulty
switch ($difficulty) {
    case 'medium':
        $timeLimit = 20; // Medium difficulty gives 20 seconds
        break;
    case 'hard':
        $timeLimit = 15; // Hard difficulty gives 15 seconds
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
    <!-- Heading for the game -->
    <h1>Welcome to Peel the Puzzle</h1>

    <!-- Logout link -->
    <a href="logout.php" class="logout-btn">Logout</a>
    
    <!-- Home button with an icon -->
    <a href="homepage.php" class="home-btn"><i class="fas fa-home"></i> Home</a>

    <!-- Check if the puzzle question image is available -->
    <?php if ($questionImage): ?>
        <!-- Display the puzzle image -->
        <img src="<?php echo htmlspecialchars($questionImage); ?>" alt="Puzzle Image">
    <?php else: ?>
        <!-- Display error message if puzzle data couldn't be loaded -->
        <p>Error loading puzzle data.</p>
    <?php endif; ?>

    <!-- Timer display showing the remaining time -->
    <div class="timer">Time left: <span id="time-limit"><?php echo $timeLimit; ?></span>s</div>

    <!-- Instructions for the game -->
    <p class="instructions">Select the correct number within <span id="time-limit"><?php echo $timeLimit; ?></span> seconds.</p>

    <div class="number-bar">
        <!-- Loop to display number buttons from 0 to 9 -->
        <?php for ($i = 0; $i <= 9; $i++): ?>
            <button class="number-button" onclick="checkGuess(<?php echo $i; ?>)">
                <?php echo $i; ?>
            </button>
        <?php endfor; ?>
    </div>

    <!-- Display the result message (correct/wrong) -->
    <p id="result"></p>

    <script>
        // JavaScript to handle game logic and timer
        const solution = <?php echo isset($solution) ? $solution : 'null'; ?>;
        const resultDisplay = document.getElementById("result"); // Element to display result
        const timeLimit = <?php echo $timeLimit; ?>; // Time limit for the puzzle
        let timeLeft = timeLimit; // Initialize the countdown timer

        // Function to check if the player's guess is correct
        function checkGuess(guess) {
            // If solution is null, show an error message
            if (solution === null) {
                resultDisplay.innerHTML = "Error loading game data. Please try again later.";
                return;
            }

            // Check if the guess is correct
            if (guess === solution) {
                resultDisplay.innerHTML = "Correct! Loading next game...";

                // Update the score using AJAX call
                updateScoreAjax('<?php echo $difficulty; ?>');
                
                // Fetch a new puzzle by reloading the page after 0.5 seconds
                setTimeout(() => {
                    location.reload();
                }, 500); 
            } else {
                // If guess is wrong, show the incorrect message
                resultDisplay.innerHTML = "Wrong guess. Try again!";
            }
        }

        // Function to send the score update to the server using AJAX
        function updateScoreAjax(difficulty) {
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "update_score.php", true); // Open a POST request
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); // Set content type
            // Send the player name and difficulty level to the server
            xhr.send("difficulty=" + difficulty + "&player_name=" + "<?php echo $player_name; ?>");
        }

        // Timer countdown that decreases every second
        const timer = setInterval(() => {
            timeLeft--; // Decrease the time left by 1
            document.getElementById('time-limit').innerText = timeLeft; // Update the displayed time

            // When time reaches 0, stop the timer and reload the page
            if (timeLeft <= 0) {
                clearInterval(timer); // Stop the countdown
                resultDisplay.innerHTML = "Time's up! Loading next game..."; // Display time-up message
                setTimeout(() => {
                    location.reload(); // Reload the page after 0.5 seconds for next puzzle
                }, 500); 
            }
        }, 1000); // Set the interval to 1 second (1000 milliseconds)
    </script>
</body>
</html>
