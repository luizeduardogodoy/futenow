<?php

session_start();

if(!isset($_SESSION['AppFutNow_UserAuthenticated']) || $_SESSION['AppFutNow_UserAuthenticated'] == false){
	
	echo '<a href="login.php">Clique aqui para fazer o login</a>';
	
	exit;
}