<?php
session_start();
// Clear the session variables
unset($_SESSION['username']);
unset($_SESSION['guest']);
unset($_SESSION['storyID']);
//Go back to login page
header("Location: ./preLogin.php");

?>