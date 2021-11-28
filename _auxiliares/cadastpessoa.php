<?php
$tipo_pessoa=$_POST['tipo_pessoa'];
$rsocial = $_POST['rsocial'];
$rsocial = str_replace("'","",$rsocial);
$nfantasia = $_POST['fantasia'];
$nfantasia = str_replace("'","",$nfantasia);
$data_nascimento=$_POST['data_nascimento'];
$cidade = $_POST['cidade'];
$estado = $_POST['estado'];
$cnpj = $_POST['cnpj'];
$cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);
$contato = $_POST['contato'];
$outroscontatos = $_POST['outroscontatos'];
$email = $_POST['email'];
$ie = $_POST['ie'];
$ie = preg_replace('/[^0-9]/', '', (string) $ie);
$xLgr = $_POST['logradouro'];
$nro = $_POST['numero'];
$xCpl = $_POST['complem'];
$bairro = $_POST['bairro'];
$CEP=$_POST['CEP'];
$CEP = preg_replace('/[^0-9]/', '', (string) $CEP);
if(isset($_POST['id_patrocinador'])){
	$id_patrocinador=$_POST['id_patrocinador'];
}else{
	$id_patrocinador = '';
}

if( (isset($_GET['id'])) or (isset($_POST['id'])) ){
	if(isset($_GET['id'])){$id_usuario=$_GET['id'];}
	if(isset($_POST['id'])){$id_usuario=$_POST['id'];}
	
	$stmt=$dbh->prepare("update usuarios set razaosocial=?,nomefantasia=?,data_nascimento=?,cidade=?,estado=?,cnpj=?,contato=?,outroscontatos=?,email=?,ie=?,logradouro=?,num=?,complemento=?,bairro=?,CEP=?,tipo_pessoa=? where id_usuario=?");
	if($stmt->execute(array($rsocial,$nfantasia,$data_nascimento,$cidade,$estado,$cnpj,$contato,$outroscontatos,$email,$ie,$xLgr,$nro,$xCpl,$bairro,$CEP,$tipo_pessoa,$id_usuario))){
		if(!isset($_POST['boolemoutroforn'])){
			echo 'Alteração de usuário realizada com sucesso</br>';
		}
	}else{
		echo 'Erro na alteração do cadastro do usuário';
	}
	
}else{
	if(isset($_POST['nsenha'])){
		$senha= $_POST['nsenha'];
		if(strlen($senha)<4){
			echo "<script language='javascript' type='text/javascript'>alert('Senha deve ter no mínimo 4 dígitos');window.location.href='?default';</script>";
		}
		$senha= MD5($senha);
		$senha2= MD5($_POST['confnsenha']);
		if($senha <> $senha2){
			echo "<script language='javascript' type='text/javascript'>alert('Senha deve ser igual à confirma senha');window.location.href='?default';</script>";
			die;
		}
	}else{
		$senha='';
	}
	
	$stmt=$dbh->prepare("select cnpj from usuarios where cnpj=?");
	$stmt->execute(array($cnpj));
	$ver=$stmt->rowCount();
	if($ver >=1 ){
		echo "<script language='javascript' type='text/javascript'>alert('CNPJ Existente');window.location.href='?default';</script>";
		die;
	}

	
	$acesso='Consumidor';
	$stmt=$dbh->prepare("insert into usuarios (id_usuario,razaosocial,cnpj,data_nascimento,nomefantasia,cidade,estado,contato,outroscontatos,email,ie,logradouro,num,complemento,bairro,CEP,tipo_pessoa,senha,acesso,rede) values (default,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
	$rede='/';
	if($stmt->execute(array($rsocial,$cnpj,$data_nascimento,$nfantasia,$cidade,$estado,$contato,$outroscontatos,$email,$ie,$xLgr,$nro,$xCpl,$bairro,$CEP,$tipo_pessoa,$senha,$acesso,$rede))){
		echo 'Cadastro de usuário realizado com sucesso.';
		if($id_patrocinador == ''){
			$id_patrocinador = 382;
		}
		$stmt=$dbh->prepare("select id_usuario from usuarios where cnpj=?");
		$stmt->execute(array($cnpj));
		$ln=$stmt->fetch(PDO::FETCH_ASSOC);
		$id_novo=$ln['id_usuario'];
		
		$stmt=$dbh->prepare("select rede from usuarios where id_usuario=?");
		$stmt->execute(array($id_patrocinador));
		$ln=$stmt->fetch(PDO::FETCH_ASSOC);
		$rede_ant=$ln['rede'];
		$rede_nova=$rede_ant.$id_novo.'/';
		$stmt=$dbh->prepare("update usuarios set rede=? where id_usuario=?");
		$stmt->execute(array($rede_nova,$id_patrocinador));
		
		//mandar e-mail p rolf confirmando cadastro..
		$message = '
		<html>
		<head>
		<title>E-mail de Cadastro!</title>
		<style>
			
		</style>
		
		</head>
		<body>
		<h2>Novo Cadastro realizado</h2>
		<p>ID do cadastrado:'.$id_novo.'</p>
		<p>Cliente:'.$rsocial.'</p>
		</body>
		</html>';
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "From: rolf@rolfmodas.com.br" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		$to="rolf@rolfmodas.com.br";
		$subject="Novo cadastro";
		mail($to,$subject,$message,$headers);
		//fim do mandar email..
	
	}else{
		echo 'Erro ao realizar o cadastro de usuário...';
	}
	
}
