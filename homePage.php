<!DOCTYPE html>
<html>
<head> 
	<link rel="stylesheet" type="text/css" href="styles.css">
	<title> Newsfeed </title>
</head>

<body>
<?php
	// require the users to login first
	require_once('authenticate.php');
?>

<?php // connect to database
	require 'database.php'; // Get the username from session 
	session_start();
	
		if($_SESSION['token'] != $_POST['token'])
	{
	   die("Request forgery detected");
	}
	echo  "Welcome back, ".$_SESSION['username']."!";
    echo "<br><br>";
?>
<form method="post" action="#"> 
	
<select name="category">
<option value="Science">Science</option>
<option value="Sports">Sports</option>
<option value="History">History</option>
<option value="Entertainment">Entertainment</option>
<option value="Literature">Literature</option>
<option value="Tourism">Tourism</option>
<option value="Education">Education</option>
<option value="Other">Other</option>
</select>

	<input type="Submit" name="submit" id="submit" value="select">
</form>


<?php
	
if ($_POST['submit'])
{   
	if($_POST['category']) // If category selection is made
	{   $selection = $_POST['category'];
		$stmt = $mysqli->prepare("select userID, storyID, subject, author, views, storyDate from story where category= ? ");
		$stmt->bind_param('s', $selection);
	}
 
	if($_POST['sortView']) // If sorting the story by page views is clicked, take the order as views
	{
		$stmt = $mysqli->prepare("select userID, storyID, subject, author, views, storyDate from story order by views");
	}
	else if($_POST['sortTime']) // If sorting the story by creating date is clicked, take the order as date
	{
		$stmt = $mysqli->prepare("select userID, storyID, subject, author, views, storyDate from story order by storyDate");
	}
}	


else // for other cases, sort the stories by storyID
	{
		$stmt = $mysqli->prepare("select userID, storyID, subject, author, views, storyDate from story order by storyID");
	}


if(!$stmt) // if query failed, print out error message  
	{
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	 
	$stmt->execute();
	$stmt->bind_result($userID, $storyID, $subject, $author,$views,$date); //get all information from story table based on storyID 
	 
	echo "<ul>\n";
	while($stmt->fetch()){
		// List all the subjects of story, with a link, when clicked, this page will be directed to another page to display 
		echo "<a href=\"./inter.php?storyID=$storyID\">$subject\n\n</a>";
		// also display the author name, views and date
		printf(" (%s)  ", $author);
		printf(" views(%d)  ", $views);
		printf(" (%s)  ", $date);
		echo "<br>";
	}
	echo "</ul>\n";
	$stmt->close();
	 
	// Only users can see the button for creating story, guests can not see this button
	if($_SESSION['guest']!=1)
	{
		printf('
		 <form action="createStory.php" method = "post"/>
			<input type="submit" value="Go to create!"/>
		 </form>
		');
	}
?>

<!--button for logging off-->
<form action = "./logOut.php" > 
	<input type = submit value = "Log out">
</form>

<!--button for sorting by views or sorting by date-->
<form action="homePage.php" method="POST">
	<input type="submit" name="sortView" value="Sort by views" width="56" height="35"/> 
	<input type="submit" name="sortTime" value="Sort by time" width="56" height="35"/> 
</form> 

</body>
</html>