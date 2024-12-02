// Get references to the DOM elements
const signUpButton = document.getElementById('signUpButton');
const signInButton = document.getElementById('signInButton');
const signInForm = document.getElementById('signIn');
const signUpForm = document.getElementById('signup');

// Event listener to display the Sign Up form and hide the Sign In form when the 'Sign Up' button is clicked
signUpButton.addEventListener('click', function () {
    signInForm.style.display = "none";  // Hide Sign In form
    signUpForm.style.display = "block"; // Show Sign Up form
});

// Event listener to display the Sign In form and hide the Sign Up form when the 'Sign In' button is clicked
signInButton.addEventListener('click', function () {
    signInForm.style.display = "block"; // Show Sign In form
    signUpForm.style.display = "none";  // Hide Sign Up form
});

// Function to show an alert message
function showAlert(message) {
    document.getElementById('alert-message').innerText = message;
    document.getElementById('alert').style.display = 'block';
}

// Function to close the alert message
function closeAlert() {
    document.getElementById('alert').style.display = 'none';
}
