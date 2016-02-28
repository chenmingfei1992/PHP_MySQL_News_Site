<?php


	session_start();

	// Detect request forgery
	if($_SESSION['token'] != $_POST['token'])
	{
	   die("Request forgery detected");
	}

	// connect to mysql database
	require 'database.php';

	// Get username and password from login page
	$username_1 = $_POST['username'];
	$password_1 = $_POST['password'];
	//flag for nonuser
	$guest = 0;
	
	if ($username_1 == 'guest' && $password_1 == 'guest')
	 {
       $guest = 1;
     }

	$_SESSION['guest'] = $guest;

	//encrypt the input password
	$encry_password = crypt($password_1, '$1$asdf$');

    // prepare to select columns of database
	$stmt = $mysqli->prepare("select userID,username, password from user where username=?");
	if(!$stmt)
		{ 
		  // If query tailed, print error message 	
          printf("Query prep failed: %s\n", $mysqli->error);
          exit;
        }
    // query with input username
	$stmt->bind_param('s',$username_1);
	$stmt->execute();
	//put results in variables 
	$stmt->bind_result($u_id,$user_id,$pwd_hash);
	$stmt->fetch();
	
	// check whether passwords match correctly
	if(crypt($password_1,$pwd_hash)==$pwd_hash || ($guest =1))
	{  
	   // set the username and userID as session variable for all the php files
       $_SESSION['userID'] = $u_id;
       $_SESSION['username'] = $user_id;
       header("Location: ./homePage.php");
    }
    else
    {   // if password not match, back to login page
       echo "login fail.";
       header("Location: ./preLogin.php");
    }

?>
