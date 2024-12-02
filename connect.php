<?php

// Database connection details
$host = "localhost"; 
$user = "root";      
$pass = "";          
$db = "login";       

// Create a new mysqli object for database connection
$conn = new mysqli($host, $user, $pass, $db);

// Check if the connection to the database was successful
if ($conn->connect_error) {
    // Display an error message if the connection failed
    echo "Failed to Connect DB" . $conn->connect_error;
}

?>