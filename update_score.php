<?php
// Include the database connection file
include 'connect.php';

// Start the session to manage user sessions
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the form was submitted via POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the player's email from the session
    $player_email = $_SESSION['email'];

    // Get the difficulty level from the POST request 
    $difficulty = $_POST['difficulty'];

    // Call the updateScore function to update the player's score in the database
    updateScore($player_email, $difficulty);
}

// Function to update the player's score based on the difficulty
function updateScore($player_email, $difficulty) {
    global $conn;  // Access the global database connection

    
    $points = 0;

    // Determine points based on the difficulty level
    switch ($difficulty) {
        case 'easy':
            $points = 1;  t
            break;
        case 'medium':
            $points = 2;  
            break;
        case 'hard':
            $points = 3;  
            break;
        default:
            $points = 0;  d
    }

    // Check if the player exists in the 'users' table by their email
    $checkPlayer = "SELECT * FROM users WHERE email='$player_email'";
    $result = $conn->query($checkPlayer);

    if ($result->num_rows > 0) {
        // If the player exists, fetch the current score and add the new points
        $row = $result->fetch_assoc();
        $newScore = $row['score'] + $points;

        // Update the player's score in the database
        $updateQuery = "UPDATE users SET score='$newScore' WHERE email='$player_email'";
        $conn->query($updateQuery);

        // Check if the score was successfully updated
        if ($conn->affected_rows > 0) {
            echo "Score updated successfully"; 
        } else {
            echo "Failed to update score";  
        }
    }
}
?>
