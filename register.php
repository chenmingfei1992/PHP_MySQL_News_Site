<?php

require 'database.php';
// Get input username and password for registeration
$regPass = $_POST['regPass'];
$regName= $_POST['regName'];
// encrypt the passaword
$cryPass = crypt($regPass, '$1$asdf$');

//send the query of insert information into user table
$stmt = $mysqli->prepare("insert into user (username, password) values (?, ?)");

if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

// Use input username and encrypted password as parameters 
$stmt->bind_param('ss', $regName, $cryPass);
//Insert the data into database
$stmt->execute();
$stmt->close();

header("Location: ./preLogin.php");

?>