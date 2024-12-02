<?php
include 'connect.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the player's email from session
    $player_email = $_SESSION['email'];
    // Get the difficulty level from the POST request
    $difficulty = $_POST['difficulty'];

    // Call the updateScore function to update the player's score
    updateScore($player_email, $difficulty);
}

function updateScore($player_email, $difficulty) {
    global $conn;
    $points = 0;

    // Determine points based on the difficulty level
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

    // Check if the player exists in the users table
    $checkPlayer = "SELECT * FROM users WHERE email='$player_email'";
    $result = $conn->query($checkPlayer);

    if ($result->num_rows > 0) {
        // Player exists, update their score
        $row = $result->fetch_assoc();
        $newScore = $row['score'] + $points;
        $updateQuery = "UPDATE users SET score='$newScore' WHERE email='$player_email'";
        $conn->query($updateQuery);

        // Check if the score update was successful
        if ($conn->affected_rows > 0) {
            echo "Score updated successfully";
        } else {
            echo "Failed to update score";
        }
    }
}
?>
