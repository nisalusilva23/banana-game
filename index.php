<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register & Login</title>

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Favicon for the page -->
    <link rel="icon" type="image/x-icon" href="favicon.ico">

    <!-- External CSS stylesheet -->
    <link rel="stylesheet" href="css_files/style.css">
</head>

<body>
    <!-- link for CSS  -->
    <link rel="stylesheet" href="css_files/style.css">

    <!-- Popup message container -->
    <div id="popup" class="popup">
        <div class="popup-content">
            <!-- Close button for the popup -->
            <span class="close" onclick="closePopup()">&times;</span>
            <!-- Placeholder for the popup message -->
            <p id="popup-message"></p>
        </div>
    </div>

    <!-- Registration form container -->
    <div class="container" id="signup" style="display:none;">
        <h1 class="form-title">Register</h1>
        <form method="post" action="register.php">
            <!-- Input group for user name -->
            <div class="input-group">
                <i class="fas fa-user"></i> <!-- Icon for the name field -->
                <input type="text" name="name" id="name" placeholder="Name" required>
                <label for="fname">Name</label>
            </div>

            <!-- Input group for email -->
            <div class="input-group">
                <i class="fas fa-envelope"></i> <!-- Icon for the email field -->
                <input type="email" name="email" id="email" placeholder="Email" required>
                <label for="email">Email</label>
            </div>

            <!-- Input group for password -->
            <div class="input-group">
                <i class="fas fa-lock"></i> <!-- Icon for the password field -->
                <input type="password" name="password" id="password" placeholder="Password" required>
                <label for="password">Password</label>
            </div>

            <!-- Submit button for the registration form -->
            <input type="submit" class="btn" value="Sign Up" name="signUp">
        </form>

        <!-- Link to switch to the Sign-In form -->
        <div class="links">
            <p>Already Have Account?</p>
            <button id="signInButton">Sign In</button>
        </div>
    </div>

    <!-- Login form container -->
    <div class="container" id="signIn">
        <h1 class="form-title">Sign In</h1>
        <form method="post" action="register.php">
            <!-- Input group for email -->
            <div class="input-group">
                <i class="fas fa-envelope"></i> <!-- Icon for the email field -->
                <input type="email" name="email" id="email" placeholder="Email" required>
                <label for="email">Email</label>
            </div>

            <!-- Input group for password -->
            <div class="input-group">
                <i class="fas fa-lock"></i> <!-- Icon for the password field -->
                <input type="password" name="password" id="password" placeholder="Password" required>
                <label for="password">Password</label>
            </div>


            <!-- Submit button for the login form -->
            <input type="submit" class="btn" value="Sign In" name="signIn">
        </form>

        <!-- Link to switch to the Sign-Up form -->
        <div class="links">
            <p>Don't have account yet?</p>
            <button id="signUpButton">Sign Up</button>
        </div>
    </div>

    <!-- External JavaScript file -->
    <script src="script.js"></script>
</body>

</html>
