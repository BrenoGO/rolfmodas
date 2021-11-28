<?php

$id=$_GET['id'];
if ($id <> 'id_novo'){
	require '../../_config/conection.php';
	$stmt=$dbhRPI->prepare("select * from usuarios where id_usuario=?");
	$stmt->execute(array($id));
	$ln=$stmt->fetch(PDO::FETCH_ASSOC);
	$return=$ln['razaosocial'].'/'.$ln['nomefantasia'].'/'.$ln['data_nascimento'].'/'.$ln['cnpj'].'/'.$ln['ie'].'/'.$ln['CEP'].'/'.$ln['estado'].'/'.$ln['cidade'].'/'.$ln['bairro'].'/'.$ln['logradouro'].'/'.$ln['num'].'/'.$ln['complemento'].'/'.$ln['contato'].'/'.$ln['email'].'/'.$ln['outroscontatos'];

	echo $return;
}

?>