<?php
// Include the database connection file
include 'connect.php';

// Start a session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Function to update score based on difficulty
function updateScore($player_name, $difficulty) {
    global $conn; // Use the global database connection

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
        $row = $result->fetch_assoc(); // Fetch the existing player's data
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
            margin-top: 10px;
        }

        .number-button {
            width: 30px;
            height: 30px;
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
        .next-game-btn {
            position: relative;
            display: inline-block;
            margin-top: 20px;
            margin-left: 10px;
            padding: 10px 20px;
            background-color: #f0a500;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .next-game-btn:hover {
            background-color: #d18e00;
            transform: scale(1.1);
        }

    </style>
</head>
<body>
    <h1>Welcome to Peel the Puzzle</h1>
    <a href="logout.php" class="logout-btn">Logout</a>
    <a href="homepage.php" class="home-btn"><i class="fas fa-home"></i> Home</a>

    <?php if ($questionImage): ?>
    <!-- Display the puzzle question image if available -->
    <img src="<?php echo htmlspecialchars($questionImage); ?>" alt="Puzzle Image">
<?php else: ?>
    <!-- Show an error message if puzzle data couldn't be loaded -->
    <p>Error loading puzzle data.</p>
<?php endif; ?>

<!-- Timer display showing the remaining time -->
<div class="timer">Time left: <span id="time-limit"><?php echo $timeLimit; ?></span>s</div>

<!-- Instructions for the player -->
<p class="instructions">Select the correct number within <span id="time-limit"><?php echo $timeLimit; ?></span> seconds.</p>

<!-- Number buttons for player to select their guess -->
<div class="number-bar">
    <?php for ($i = 0; $i <= 9; $i++): ?>
        <!-- Create a button for each number from 0 to 9 -->
        <button class="number-button" onclick="checkGuess(<?php echo $i; ?>)"><?php echo $i; ?></button>
    <?php endfor; ?>
</div>

<!-- Area to display the result of the player's guess -->
<p id="result"></p>

<script>
    // The correct solution fetched from the API
    const solution = <?php echo isset($solution) ? $solution : 'null'; ?>;
    // DOM element to display results (correct/wrong message)
    const resultDisplay = document.getElementById("result");
    // Time limit for the current puzzle
    const timeLimit = <?php echo $timeLimit; ?>;
    // Countdown timer variable
    let timeLeft = timeLimit;

    // Timer countdown logic, decreases time every second
    const timer = setInterval(() => {
        timeLeft--; // Decrement the timer
        document.getElementById('time-limit').innerText = timeLeft; // Update timer display

        // If time runs out, stop the timer and show a "time's up" message
        if (timeLeft <= 0) {
            clearInterval(timer); // Stop the timer
            resultDisplay.innerHTML = "Time's up!";
            showNextGameButton(); // Show the "Next Game" button
        }
    }, 1000); // Runs every 1 second

    // Function to check if the player's guess is correct
    function checkGuess(guess) {
        if (solution === null) {
            // If no solution is provided, show an error
            resultDisplay.innerHTML = "Error loading game data.";
            return;
        }

        // If the guess matches the solution
        if (guess === solution) {
            clearInterval(timer); // Stop the timer
            resultDisplay.innerHTML = "Correct!"; // Show a success message
            updateScoreAjax('<?php echo $difficulty; ?>'); // Update the score via AJAX
            showNextGameButton(); // Show the "Next Game" button
        } else {
            // If the guess is incorrect
            resultDisplay.innerHTML = "Try again."; // Show an error message
        }
    }

    // Function to send the player's score to the server using AJAX
    function updateScoreAjax(difficulty) {
        const xhr = new XMLHttpRequest(); // Create an XMLHttpRequest object
        xhr.open("POST", "update_score.php", true); // Set up a POST request to the server
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); // Set the request header
        // Send the difficulty level and player name to the server
        xhr.send(`difficulty=${difficulty}&player_name=${"<?php echo $player_name; ?>"}`);
    }

    // Function to display the "Next Game" button
    function showNextGameButton() {
        const button = document.createElement("a"); // Create an anchor element for the button
        button.textContent = "Next Game";
        button.href = ""; // Set the href to reload the page for the next game
        button.className = "next-game-btn"; // Add the CSS class for styling
        resultDisplay.appendChild(button); // Add the button to the result display area
    }
</script>
</body>
</html>
