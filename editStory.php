<!DOCTYPE html> 
<html> 
<head>
	<title>Edit story</title>
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
	$storyID= $_GET['storyID'];
	//send the query for selecting columns in story table
	$stmt = $mysqli->prepare("select subject, author,contents from story where storyID = ?");
	  
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	// user storyID as parameter
	$stmt->bind_param('i', $storyID);
	$stmt->execute();
	// the old information will be put into the form, waiting for the user to edit
	$stmt->bind_result( $subject, $author,$contents);
	$stmt->fetch();
	$stmt->close();

	// the updated information input by the user after editing
	$updatedSubject = $_POST['subject'];
	$updatedAuthor = $_POST['postedby'];
	$updatedContents = $_POST['news'];
	$date =  date("Y-m-d H:i:s");

	// send the query for updating the information
	$newstmt = $mysqli->prepare("update story set subject = '$updatedSubject', 
	contents='$updatedContents', author = '$updatedAuthor',storyDate ='$date' where storyID=?");
	if(!$newstmt)
		{
			printf("Query fail:%s\n", $mysqli->error);exit;
		}
	//use storyID as parameter	
	$newstmt->bind_param('i',$storyID);
	$newstmt->execute();
	$newstmt->close();
	// If all the text forms are input and submitted, go back to homepage, otherwise, stays on editing page
	if(($_POST['postedby'])&&($_POST['subject'])&&($_POST['news']))
	{
	  header("Location: ./homePage.php");
	}

?>

<!--form for editing the story contents, author name, and subject-->
<form method="post" action="#"> 
	Posted By:<br /><input name="postedby" id="postedby" type="Text" size="50" value ="<?php echo $author; ?>" maxlength="50"><br />
	Subject:<br /><input name="subject" id="subject" type="Text" size="50" value ="<?php echo $subject; ?>" maxlength="50"><br />
	<textarea name="news" id="news" cols="50" rows="5" > <?php echo $contents; ?> </textarea><br />
		<input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
	<input type="Submit" name="submit" id="submit" value="Update">
</form>

</body>		
</html>