<?php

require_once 'config.inc.php';

if($_GET['req'] != 'estado' && $_GET['req'] != 'cidade')
	require_once 'sessao.inc.php';

if(isset($_GET['req'])){
	$sql = "SELECT * FROM " . $_GET['req'] . " ";
	
	if($_GET['req'] == 'local'){
		$sql .= "WHERE bairro_id = " . $_GET['bairro_id'];
	}
	if($_GET['req'] == 'bairro'){
		$sql .= "WHERE cidade_id = " . unserialize($_SESSION['AppFutNow_UserConnected'])->cidade_id;
	}
	if($_GET['req'] == 'cidade'){
		$sql .= "WHERE uf_id = " . $_GET['estado_id'];
	}
	
	if($_GET['req'] == 'index'){
		
		$usuarioConectado = unserialize($_SESSION['AppFutNow_UserConnected']);
		
		$sql  = "SELECT a.*, b.nome AS local_nome FROM evento a ";
		$sql .= "INNER JOIN local b ON a.local_id = b.id ";
		$sql .= "INNER JOIN bairro c ON b.bairro_id = c.id ";
		$sql .= "INNER JOIN cidade d ON c.cidade_id = d.id ";
		$sql .= "WHERE hora_limite_confirmacao >= '" . Date('Y-m-d H:i:s') . "' ";
		$sql .= "AND usuario_id <> " . $usuarioConectado->id . " ";
		$sql .= "AND cidade_id = " . $usuarioConectado->cidade_id . " AND faltam > 0 ORDER BY hora_limite_confirmacao ASC";

		$res = $db->Execute($sql);
		$i = 0;
		while(!$res->EOF){
			
			$usu = new Usuario();	
			$usu->Load('id = ?', $res->fields('usuario_id'));	
			
			$row = array();
			
			$row["id"]          = $usu->id;
			$row["UsuarioNome"] = $usu->nome;
			$row["LocalNome"]   = $res->fields('local_nome');
			$row["Observacao"]  = $res->fields('observacoes');
			$row["HoraInicio"]  =  $res->fields('inicio');
			$row["HoraConfirmacao"] = $res->fields('hora_limite_confirmacao');
			$row["Faltam"] = $res->fields('faltam');
			$data[$i] = $row;
			$i++;
			
			$res->MoveNext();
		}	
 
		header("Content-type: application/json"); 
		echo "{\"data\":" .json_encode($data). "}";
		
		exit;
	}
	
	
	$res = $db->Execute($sql);
	
	while(!$res->EOF){
		if($_GET['req'] == 'local'){
			$arr[] = array('id' => $res->fields('id'), 'nome' => $res->fields('nome'), "bairro_id" => $res->fields('bairro_id'));
		}
		else if($_GET['req'] == 'bairro'){		
			$arr[] = array('bairro_id' => $res->fields('id'), 'nome' => $res->fields('nome'));
		}
		else
			$arr[] = array('id' => $res->fields('id'), 'nome' => $res->fields('nome'));
		$res->MoveNext();
	}
	
	print json_encode($arr);
	
}

/*
$usu = new Usuario();


$usu->nome = 'Luiz';
$usu->email = 'luiz@actuary.com.br';
$usu->senha = md5('suporte');
$usu->Save();*/