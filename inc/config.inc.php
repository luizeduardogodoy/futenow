<?php

include('D:/tecnologia/lib/adodb510/adodb.inc.php');
require_once('D:/tecnologia/lib/adodb510/adodb-active-record.inc.php');
$db = NewADOConnection('pgsql');
$db->Connect('localhost', 'postgres', 'postgres', 'futenow');

ADOdb_Active_Record::SetDatabaseAdapter($db);

require_once 'D:/tecnologia/futenow/futenow/dao/usuario.php';
/*
$usu = new Usuario();


$usu->nome = 'Luiz';
$usu->email = 'luiz@actuary.com.br';
$usu->senha = md5('suporte');
$usu->Save();*/