<?php
	include '../_config/conection.php';
	include '../functionsPDO.php';
	
	$qr="select t.ref,p.descricao,p.preco,t.refs_cores,t.tamanhos from tabela_app t
		join produtos p on t.ref=p.ref
		order by t.ref";
	$values=array();
	
	$array=fetchToArray($dbh,$qr,$values);
	echo json_encode($array);
	//echo json_encode('testeee');
	
?>
