<?php
/*
if(isset($_POST)){
	print_r($_POST);
	die;
}
*/
$acesso='Fornecedor';

$id=$_POST['id'];
$rsocial=$_POST['rsocial'];
$fantasia=$_POST['fantasia'];
$nasc=$_POST['nasc'];
if($nasc==""){
	$nasc='1900-01-01';
}
$cnpj=$_POST['cnpj'];
$ie=$_POST['ie'];
$cep=$_POST['cep'];
$estado=$_POST['estado'];
$cidade=$_POST['cidade'];
$bairro=$_POST['bairro'];
$log=$_POST['log'];
$num=$_POST['num'];
$comp=$_POST['comp'];
$cont=$_POST['cont'];
$email=$_POST['email'];
$outcont=$_POST['outcont'];

require '../../_config/conection.php';

if($id <> 0){
	$stmt=$dbhRPI->prepare("update usuarios set 
	razaosocial=?, cnpj=?, data_nascimento=?, nomefantasia=?, cidade=?, estado=?, contato=?, outroscontatos=?, email=?, ie=?, logradouro=?, num=?, complemento=?, bairro=?, CEP=?
	where id_usuario=?");
	if($stmt->execute(array($rsocial,$cnpj,$nasc,$fantasia,$cidade,$estado,$cont,$outcont,$email,$ie,$log,$num,$comp,$bairro,$cep,$id))){
		echo 'Alteracao';
	}else{
		print_r( $stmt->errorInfo());
	}
}else{
	$stmt=$dbhRPI->prepare("insert into usuarios (razaosocial,cnpj,data_nascimento,nomefantasia,cidade,estado,contato,outroscontatos,email,ie,logradouro,num,complemento,bairro,CEP,acesso) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
	if($stmt->execute(array($rsocial,$cnpj,$nasc,$fantasia,$cidade,$estado,$cont,$outcont,$email,$ie,$log,$num,$comp,$bairro,$cep,$acesso))){
		$stmt=$dbhRPI->prepare("select max(id_usuario) as id from usuarios where razaosocial=?");
		$stmt->execute(array($rsocial));
		$ln=$stmt->fetch(PDO::FETCH_ASSOC);
		$NovoId=$ln['id'];
		echo 'Cadastro. id='.$NovoId;
	}else{
		print_r( $stmt->errorInfo());
	}
}

?>