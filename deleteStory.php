<!DOCTYPE html> 
<html> 
<head>
	<title> delete Story and Comments</title>
</head>
<body>
	
<?php
//require the user to login first
require_once('authenticate.php');
?>

<form action = "./homePage.php" > 
	<input type = submit value = "Go back to home">
</form>


<?php
require_once 'database.php';
session_start();
	if($_SESSION['token'] != $_POST['token'])
	{
	   die("Request forgery detected");
	}
// get storyID from session
$storyID = $_SESSION['storyID'];


//send the query to delete comment from database
$stmt = $mysqli->prepare("delete from comment where storyID=?");
if(!$stmt){printf("Query fail: $mysqli->error");exit;}
//use storyID as parameter
$stmt->bind_param('i',$storyID );
$stmt->execute();
$stmt->close();

//send the query to delete story from database
$newstmt = $mysqli->prepare("delete from story where storyID=?");
if(!$newstmt){printf("Query fail: $mysqli->error");exit;}
//use storyID as parameter
$newstmt->bind_param('i',$storyID );
$newstmt->execute();
$newstmt->close();

?>
</body>		
</html>