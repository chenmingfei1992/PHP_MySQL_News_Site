<?php

session_start();
//Get the storyID from the homePage
$storyID= $_GET['storyID'];
//Set the storyID as session variable, shared with other php files
$_SESSION['storyID']=$storyID;
//Direct the page to display page
header("Location: ./displayStory.php");

?>