<?php
include 'connect.php';

session_start();

if (isset($_POST['signUp'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);  // Encrypt password

    $checkEmail = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($checkEmail);

    if ($result->num_rows > 0) {
        $_SESSION['login_error'] = "Email Address Already Exists!";
        header("Location: index.php");
        exit();
    } else {
        $insertQuery = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";
        if ($conn->query($insertQuery) === TRUE) {
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['login_error'] = "Error: " . $conn->error;
            header("Location: index.php");
            exit();
        }
    }
}

if (isset($_POST['signIn'])) {
    $email = $_POST['email'];
    $password = md5($_POST['password']);  // Encrypt password

    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION['email'] = $email;
        header("Location: homepage.php");
        exit();
    } else {
        $_SESSION['login_error'] = "Incorrect Email or Password";
        header("Location: index.php");
        exit();
    }
}
?>
