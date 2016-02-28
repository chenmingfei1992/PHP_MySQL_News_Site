<?php

// connect to the database
// 3 tables in the database: story, user, comment
$mysqli = new mysqli('localhost', 'chenmingfei', 'chenmingfei', 'module3Group');
 
if($mysqli->connect_errno) {
	printf("Connection Failed: %s\n", $mysqli->connect_error);
	exit;
}
?>