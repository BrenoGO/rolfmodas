<?php
	include '../_config/conection.php';
	include '../functionsPDO.php';

	$json = file_get_contents('php://input');
	$obj = json_decode($json, true);
	$response = '';
	foreach ($obj as $i) {
		$qr="insert into usuarios (id_usuario, CEP, bairro, cidade, cnpj, complemento, contato, data_nascimento, email, estado, ie, logradouro, nomefantasia, num, outroscontatos, razaosocial, acesso, Obs) values (default, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
		$values=[$i['CEP'], $i['bairro'], $i['cidade'], $i['cnpj'], $i['complemento'], $i['contato'], $i['data_nascimento'], $i['email'], $i['estado'], $i['ie'], $i['logradouro'], $i['nomefantasia'], $i['num'], $i['outroscontatos'], $i['razaosocial'], 'Cliente', 'Cadastro pelo App'];
		//$stmt=executeSQL($dbh,$qr,$values);
		$stmt=true;
		if(is_string($stmt)){//deu erro no mysql..
			$response .= $stmt.' / ';
		}else{
			$response .= 'Inserido novo cliente de razaosocial: '. $i['razaosocial'] . ' com sucesso / ';
		}
	}
	echo json_encode($response);
	//lembrar de mudar o boolNew pra false no SQLite do app...
?>