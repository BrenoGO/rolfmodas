<?php 

$cnpj=$_POST['cnpj'];
$cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);
$rsocial=$_POST['nomecompleto'];
$contato=$_POST['contato'];
$data_nascimento=$_POST['data_nascimento'];
$cidade = $_POST['cidade'];
$estado = $_POST['estado'];
$sexo=$_POST['sexo'];
$email=$_POST['email'];
$senha=$_POST['senha'];
$confsenha=$_POST['confsenha'];
$acesso='Consumidor';
if(strlen($senha)<4){
	echo "<script language='javascript' type='text/javascript'>alert('Senha deve ter no mínimo 4 dígitos');window.location.href='?default';</script>";
	die;
}
$senha= MD5($senha);
$senha2= MD5($confsenha);
if($senha <> $senha2){
	echo "<script language='javascript' type='text/javascript'>alert('Senha deve ser igual à confirma senha');window.location.href='?default';</script>";
	die;
}
if(strlen($cnpj) < 11){
	echo "<script language='javascript' type='text/javascript'>alert('CPF não contém todos os dígitos');window.location.href='?default';</script>";
	die;
}
$stmt=$dbh->prepare("insert into usuarios (id_usuario,razaosocial,cnpj,data_nascimento,cidade,estado,sexo,contato,email,senha,acesso) values (default,?,?,?,?,?,?,?,?,?,?)");
$stmt->execute(array($rsocial,$cnpj,$data_nascimento,$cidade,$estado,$sexo,$contato,$email,$senha,$acesso));

$nomefantasia='';
$stmt=$dbh->prepare("select id_usuario from usuarios where cnpj=?");
$stmt->execute(array($cnpj));
$ln=$stmt->fetch(PDO::FETCH_ASSOC);
$idusuario=$ln['id_usuario'];
if(file_exists('../define_session.php')){
	require '../define_session.php';
}else{
	require 'define_session.php';
}

