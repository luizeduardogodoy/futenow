<?php

session_start();

//require_once 'inc/autoload.inc.php';

if(!isset($_SESSION['AppFutNow_UserAuthenticated']))
	$_SESSION['AppFutNow_UserAuthenticated'] = false;

require_once 'templates/cadastrousuario.html';

$post = $_POST;

if($post['email'] != "" && $post['senha'] != ""){
		
	require_once 'inc/config.inc.php';
	require_once 'dao/usuario.php';	
		
	$usu = new Usuario();
	
	$usu->nome = $post['nome'];
	$usu->senha = md5($post['senha']);
	$usu->nascimento = $post['nascimento'];
	$usu->email = $post['email'];
	$usu->cidade_id = $post['cidade'];	
	
	var_dump($_POST);
	
	try{
		if($usu->Save())
			echo "salvo";
		else 
			echo 'não salvo';
		
		echo "Bem vindo ao futeNow " .  $post['nome']. ", <a href='login.php'>Clique aqui para fazer o login</a>";
	}
	catch(Exception $e){
		echo "Não foi possivel criar o usuário, entre em contato com o suporte técnico.";
	}
	
}
else{
	
	if(isset($_POST['frmPassou']) && $_POST['frmPassou'] == 'OK')
	Echo 'Informar email e senha!';
}