<?php
	include '../../_config/conection.php';
	include '../../functionsPDO.php';
	
	$qr='select razaosocial from usuarios where id_usuario=382';
	$values=array();
	$dados=fetchAssoc($dbh,$qr,$values);
	
	echo json_encode($dados['razaosocial']);
	
?>