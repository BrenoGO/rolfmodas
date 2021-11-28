<?php
	require '../../_config/conection.php';
	require '../../functionsPDO.php';
	$q=$_GET['q'];
	if(strlen($q)<=0){
		echo 'none';
	}else{
		$qr="select t.ref,p.descricao from tabela_pedidos t
		join produtos p on t.ref=p.ref
		where t.ref like ? or p.descricao like ?";
		$values=array('%'.$q.'%','%'.$q.'%');
		
		$array=fetchToArray($dbh,$qr,$values);
		echo '<a href="?acao=editPedPrazo&formAdd">Adicionar Produto</a></br>';
		foreach($array as $row){
			echo '<a href="?acao=editPedPrazo&editRef='.$row['ref'].'">Ref.: '.$row['ref']. ': '.$row['descricao'].'</a></br>';
		}
	}
?>