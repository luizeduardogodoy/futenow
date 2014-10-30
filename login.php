<?php

session_start();

//require_once 'inc/autoload.inc.php';

if(!isset($_SESSION['AppFutNow_UserAuthenticated']))
	$_SESSION['AppFutNow_UserAuthenticated'] = false;

require_once 'templates/login.html';

$post = $_POST;

if($post['usuario'] != "" && $post['senha'] != ""){
		
	require_once 'inc/config.inc.php';
	require_once 'dao/usuario.php';	
		
	$usu = new Usuario();	
	
	if($usu->Load("email = '" . $post['usuario'] . "' AND senha = '" . md5($post['senha']) . "'")){
	
		$_SESSION['AppFutNow_UserAuthenticated'] = true;
		$_SESSION['AppFutNow_UserConnected'] = serialize($usu);
		header('Location: index.php');	
	}else{
	
		echo '<br>Usuário ou senha inválido(s)';
	}
}
else{
	
	if(isset($_POST['frmPassou']) && $_POST['frmPassou'] == 'OK')
	Echo 'Informar usuário e senha!';
}