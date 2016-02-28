<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="styles.css">
	<title> register </title>
</head>

<body>

<?php
	// generate a 10-character random string
	$_SESSION['token'] = substr(md5(rand()), 0, 10);
	session_start();
?>


<p id="title">
	Mingfei and Richard <br/> 
	Newsfeed
</p>

<p>
	Login as guest with the user name "guest" and password "guest", or register a new account.
</p>

<!--form for user to login-->
<form action = "check.php" method = "Post">
	<label for = "username">Username:</label>
	<input type = "text" name="username" id="username"/>
	<br>
	<label for = "password">Password:</label>
	<input type = "password" name="password" id="password"/>
	<br>
	<input type = "submit" value = "Login!"/>
</form>

<!--form for user to register-->
<form action = "register.php" method = "Post">
    <label for = "regName">Username:</label>
    <input type = "text" name="regName" id="regName"/>
	<br>
    <label for = "regPass">Password:</label>
    <input type = "text" name="regPass" id="regPass"/>
	<br>
	<input type = "submit" value = "Sign Up"/>
</form>

</body>

</html>