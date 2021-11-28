<?php
	include '../_config/conection.php';
	include '../functionsPDO.php';
	
	$qr="select 
	id_usuario,razaosocial,cnpj,data_nascimento,nomefantasia,cidade,estado,contato,outroscontatos,email,ie,logradouro,num,complemento,bairro,CEP 
	from usuarios where acesso = 'Cliente';";
	$values=array();
	
	$array=fetchToArray($dbh,$qr,$values);
	echo json_encode($array);
	//echo json_encode('testeee');
?>
