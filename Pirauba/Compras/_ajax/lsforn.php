<?php

$q=$_GET['q'];
	require '../../_config/conection.php';
	if ($q == ''){
		$stmt=$dbhRPI->prepare("select * from usuarios where acesso='Fornecedor'");
		$stmt->execute();
		echo '<span onclick="FormCompra(0)" id="fornecedor" style="cursor: pointer;">Cadastrar Novo</span></br>';
		while($ln=$stmt->fetch(PDO::FETCH_ASSOC)){
			echo '<span onclick="FormCompra('.$ln['id_usuario'].')" id="fornecedor" style="cursor: pointer;">'.$ln['razaosocial'].'</span></br>';
		}
	}
if(strlen($q) > 0){
	$stmt=$dbhRPI->prepare("select * from usuarios where acesso='Fornecedor' and ( (razaosocial like ?) or (nomefantasia like ?) or (cidade like ?) )");
	$stmt->execute(array('%'.$q.'%','%'.$q.'%','%'.$q.'%'));
	echo '<span onclick="FormCompra(0)" id="fornecedor" style="cursor: pointer;">Cadastrar Novo</span></br>';
	while($ln=$stmt->fetch(PDO::FETCH_ASSOC)){
		echo '<span onclick="FormCompra('.$ln['id_usuario'].')" id="fornecedor" style="cursor: pointer;">'.$ln['razaosocial'].'</span></br>';
	}
}

?>