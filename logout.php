<?php
// Destroy all session data
session_destroy(); 

// Redirect the user to the index page
header("location: index.php"); 

// Ensure no further code is executed after the redirect
exit(); 
?>
