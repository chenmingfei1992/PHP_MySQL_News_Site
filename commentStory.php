<!DOCTYPE html>
<html>
<head>
	<title>Insert</title>
</head>
<body>

<?php
	
	$textOne = $_POST['textOne'];
	$textTwo = $_POST['textTwo'];

	mysql_connect('localhost','chenmingfei','chenmingfei');
	mysql_select_db('assignment');

	if(mysql_query("INSERT INTO test (textOne,textTwo) VALUES ('$textOne','$textTwo')"))
		echo 'Info inserted';
	mysql_close(); 

?>


</body>
</html>