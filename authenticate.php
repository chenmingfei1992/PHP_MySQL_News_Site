<?php
session_start();
if(empty($_SESSION['username'])&&empty($_SESSION['guest'])) 
//if no username is set, then login first
{
    header('Location: preLogin.php');
}
?>