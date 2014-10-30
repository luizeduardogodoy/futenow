<?php

require_once 'inc/config.inc.php';
require_once 'inc/sessao.inc.php';

require_once 'templates/evento.html';
require_once 'dao/evento.php';


if(isset($_POST['frmPassou']) && $_POST['frmPassou'] == "OK"){

	$evento = new Evento();
		
	$evento->usuario_id = unserialize($_SESSION['AppFutNow_UserConnected'])->id;
	
	$inicio = explode(" ",$_POST['inicio']);
	$confirmacao = explode(" ",$_POST['hora_limite_confirmacao']);
	
	$evento->local_id 					= $_POST['local'];
	$evento->inicio 						= dbDate($inicio[0]) . ' ' . $inicio[1];
	$evento->hora_limite_confirmacao = dbDate($confirmacao[0]) . ' ' . $confirmacao[1];
	$evento->observacoes 				= $_POST['observacoes'];
	$evento->pagar 						= $_POST['pagar'] == "on" ? true : false;
	$evento->faltam 						= $_POST['faltam'];
	
	try {		
			
		var_dump($_POST);
	
		$evento->Save();	
		
		echo "Evento criado com sucesso!";
		
	} catch (Exception $e) {
		
		echo $e->getMessage();
	}	
}

function dbDate($date){
	$date = explode("/",$date);
	
	return $date[2]."-".$date[1]."-".$date[0];	
}