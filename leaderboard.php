<?php
// Include the database connection file
include 'connect.php';

// Start the session if it hasn't been started already
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Retrieve the player's email from the session
$player_name = $_SESSION['email'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard</title>

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="style.css">

    <!-- Inline CSS for the leaderboard styling -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('Images/ai-generated-8889421.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: black;
            text-align: center;
        }

        /* Styling for the leaderboard table */
        table {
            width: 60%;
            margin: 20px auto;
            border-collapse: collapse;
            font-size: 1.5rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            overflow: hidden;
        }

        /* Styling for table headers and cells */
        th, td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f0a500;
            color: white;
            text-transform: uppercase;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        /* Styling for the home button */
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

        /* Styling for the page header */
        h1 {
            font-size: 3.5rem;
            color: #f0a500;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }
    </style>
</head>

<body>
    <!-- Home button to navigate back to the homepage -->
    <a href="homepage.php" class="home-btn"><i class="fas fa-home"></i> Home</a>
    <h1>Leaderboard</h1>
    
    <?php
    // SQL query to fetch the top 5 players based on their scores
    $query = "SELECT name, score FROM users ORDER BY score DESC LIMIT 5";
    $result = $conn->query($query);

    // Check if there are results
    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>Player Name</th><th>Score</th></tr>";
        
        // Loop through each row in the result set and display the data
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>" . htmlspecialchars($row["name"]) . "</td><td>" . $row["score"] . "</td></tr>";
        }

        echo "</table>";
    } else {
        // Display a message if there are no scores
        echo "<p>No scores to display.</p>";
    }

    // Close the database connection
    $conn->close();
    ?>
</body>
</html>
