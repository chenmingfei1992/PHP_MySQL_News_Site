<!DOCTYPE html>
<head>

<?php
	require 'database.php';
	session_start();
		if($_SESSION['token'] != $_POST['token'])
	{
	   die("Request forgery detected");
	}
	//get storyID from session
	$storyID=$_SESSION['storyID'];
	$currentUserID = $_SESSION['userID'];
	// send the query to select columns of story table
	$stmt = $mysqli->prepare("select storyID, subject, author,contents, views,likes from story where storyID = ?");  
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	// use storyID as parameter
	$stmt->bind_param('i', $storyID);
	$stmt->execute();
	//story the information from database into variables
	$stmt->bind_result($storyID, $subject, $author,$contents,$views,$likes);
	$stmt->fetch();
	$stmt->close();

	//Every time this page is loaded and the reason is not because clicking like, page view will be added by 1
	if(!$_POST['like'])
	{
		$views = $views+1;
	}
	//Every time clicking the like button, page view will be added by 1
	else
	{
		$likes = $likes+1;
	}

	// send the query to update columns of story table, update the changed views and likes
	$newstmt = $mysqli->prepare("update story set views = '$views',likes ='$likes' where storyID=?");
	if(!$newstmt){printf("Query fail:%s\n", $mysqli->error);exit;}
	//use storyID as parameter
	$newstmt->bind_param('i',$storyID);
	$newstmt->execute();
	$newstmt->close();
?>


<h1>
	<?php
	//print out the subject of story
	printf("%s  ", $subject);
	?>
</h1>

<h2>
	<?php
	//print out the author name of story, and also number of page views, likes
	printf("<br> Author:  %s <br>", $author);
	printf("Views:  (%s)   Likes: (%s)  ", $views, $likes);
	?>
</h2>

<h6>
	<?php
		// Each user can only delete and edit his/her own story, so for the stories of other authors, the user will not see the links
		if($author == $_SESSION['username'])
		{
			// the link for editing the story
			echo "<a href='./editStory.php?storyID=$storyID'>  edit  </a> ";
			echo "<br>";
			// the link for deleting the story
			echo  "<a href='./deleteStory.php?storyID=$storyID'>  delete  </a>";
		}
	?>
</h6>

<p>
	<?php
		//print out the contents of story
		printf("Contents:  %s <br>", $contents);
	?>
</p>

<h3>
<?php
// the button for adding a like for the story
echo     '<form action="displayStory.php" method="POST">
          <input type="image" name="like" src="like.tiff" value="Submit" width="56" height="35"/> 
          </form> ';
?>
</h3>



<h6>
<?php
	echo "comments: ";
	echo "<br>";
	// send the query to select columns from comment table
	$commentstmt = $mysqli->prepare("select words,userID,commentID from comment where storyID = ?");
	if(!$commentstmt)
		{   // if query failed, give error message
			printf("Query fail:%s\n", $mysqli->error);exit;
		}
	//use storyID as parameter	
	$commentstmt->bind_param('i',$storyID);
	$commentstmt->execute();
	//store the information into variable
	$commentstmt->bind_result($commentWords,$userID,$commentID);
	while($commentstmt->fetch()){
		// print out the comments
		printf("%s  ", $commentWords);
		if($userID==$currentUserID)
		{
			echo "<a href='./editComment.php?commentID=$commentID'>  edit  </a> ";
		}
		echo "<br>";	
	}

	echo "<br>";
	echo "<br>";
	$commentstmt->close();
?>


<!--the area for user to input a comment-->
	Put your comments here:

<?php
	// guest user can only view stories, only users can see the form of commenting and creating story part
	if($_SESSION['guest']!=1)
	{
		// form for input a comment
	printf('
		<form action="commentStory.php" method = "post"/>
		<div><textarea name="commentText"cols="50" rows="3"></textarea></div>
		<div><input type="submit" value="comment"/></div>
		</form>
		');

		// button for adding a story
	printf('
		<form action="createStory.php" method = "post"/>
		<div><input type="submit" value="Go to create!"/></div>
		</form>
		');
		
	}
?>
</h6>	

<br><br>
	<!--area where user can link to another story-->
	Link to another story by typing in the name of the story:
	
<?php
	
	
	// guest users cannot link to another story.
	if($_SESSION['guest']!=1)
	{
	// User enters the subject of another article into the form.
	printf('
		<form method = "post"/>
			<div><textarea name="linkText"cols="50" rows="3"></textarea></div>
			
			<div><input type="submit" value="Add link"/></div>
		</form>
		');	
	}
	
?>

<?php
	// Connect to the database.
	require 'database.php';
	
	// Put the user's subject text into a variable.
	$result = $_POST['linkText'];
	
	// Find the story with that subject text if it exists.
	if($result) {
		$stmt = $mysqli->prepare("select storyID, subject from story WHERE subject='$result'");
		if(!$stmt){
			printf("Query Prep Failed: %s\n", $mysqli->error);
			exit;
		}
		$stmt->execute();
		$stmt->bind_result($storyID, $subject);
		// Print the link to that story.
		while($stmt->fetch()){
			echo "<a href=\"./inter.php?storyID=$storyID\">$subject\n\n</a>"; 
		}
		$stmt->close();
	}
?>

</body>
</html>