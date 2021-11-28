<?php
	include '../_config/conection.php';
	include '../functionsPDO.php';
	
	$json = file_get_contents('php://input');
	
	$obj = json_decode($json,true);
	
	$id= $obj['id'];
	$senha=MD5($obj['senha']);
	
	$qr='SELECT * FROM usuarios WHERE senha = ? AND (id_usuario = ?)';
	$values=array($senha,$id);
	$bool=seQrExiste($dbh,$qr,$values);
	if($bool === true){
		echo json_encode($id);
	}else{
		echo json_encode('no');
	}
	
?>
