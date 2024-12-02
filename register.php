<?php
// Include the database connection file
include 'connect.php';

// Start the session to manage user sessions
session_start();

// Check if the sign-up form is submitted
if (isset($_POST['signUp'])) {
    // Retrieve user inputs from the sign-up form
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);  // Encrypt the password using MD5

    // Check if the email already exists in the database
    $checkEmail = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($checkEmail);

    // If the email already exists, set an error message and redirect to the sign-up page
    if ($result->num_rows > 0) {
        $_SESSION['login_error'] = "Email Address Already Exists!";
        header("Location: index.php");  // Redirect to the sign-up page
        exit();  // Stop further execution
    } else {
        // If email does not exist, insert the new user into the database
        $insertQuery = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";
        if ($conn->query($insertQuery) === TRUE) {
            // If the insert is successful, redirect to the sign-in page
            header("Location: index.php");
            exit();
        } else {
            // If there is an error during the insert, set an error message and redirect to the sign-up page
            $_SESSION['login_error'] = "Error: " . $conn->error;
            header("Location: index.php");
            exit();
        }
    }
}

// Check if the sign-in form is submitted
if (isset($_POST['signIn'])) {
    // Retrieve user inputs from the sign-in form
    $email = $_POST['email'];
    $password = md5($_POST['password']);  // Encrypt the password using MD5

    // Check if the email and password match any user in the database
    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);

    // If the user is found, store the email in the session and redirect to the homepage
    if ($result->num_rows > 0) {
        $_SESSION['email'] = $email;  // Store the user's email in the session
        header("Location: homepage.php");  // Redirect to the homepage
        exit();  // Stop further execution
    } else {
        // If no matching user is found, set an error message and redirect to the sign-in page
        $_SESSION['login_error'] = "Incorrect Email or Password";
        header("Location: index.php");
        exit();  // Stop further execution
    }
}
?>
