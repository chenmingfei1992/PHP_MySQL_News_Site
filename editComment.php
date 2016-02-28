<!DOCTYPE html> 
<html> 
<head>
	<title>Edit Comment</title>
</head>
<body>

<?php
	// require the user to login first
	require_once('authenticate.php');
?>


<?php
   
	require 'database.php';
	session_start();
		if($_SESSION['token'] != $_POST['token'])
	{
	   die("Request forgery detected");
	}
	//get storyID from session
	$commentID= $_GET['commentID'];
	//send the query for selecting columns in story table
	$stmt = $mysqli->prepare("select words from comment where commentID = ?");
	  
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	// user storyID as parameter
	$stmt->bind_param('i', $commentID);
	$stmt->execute();
	// the old information will be put into the form, waiting for the user to edit
	$stmt->bind_result( $words);
	$stmt->fetch();
	$stmt->close();

	// the updated information input by the user after editing
	$updatedWords = $_POST['words'];
	

	// send the query for updating the information
	$newstmt = $mysqli->prepare("update comment set words = '$updatedWords' where commentID=?");
	if(!$newstmt)
		{
			printf("Query fail:%s\n", $mysqli->error);exit;
		}
	//use storyID as parameter	
	$newstmt->bind_param('i',$commentID);
	$newstmt->execute();
	$newstmt->close();
	// If all the text forms are input and submitted, go back to homepage, otherwise, stays on editing page
	if($_POST['words'])
	{
	  header("Location: ./displayStory.php");
	}

?>

<!--form for editing the story contents, author name, and subject-->
<form method="post" action="#"> 
	
	<textarea name="words" id="words" cols="50" rows="5" > <?php echo $words; ?> </textarea><br />
		<input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
	<input type="Submit" name="submit" id="submit" value="Update">
</form>

</body>		
</html>