<?php

//requesitos pra utilizar auxiliar define_session:
//quando o cliente acessa, tendo cookie ou nao..
//$nomefantasia, $acesso,$idusuario
$_SESSION['nomefantasia']=$nomefantasia;
$_SESSION['acesso']=$acesso;
$_SESSION['id_usuario']=$idusuario;
		
//Definir mark-up e qualificação
$stmt=$dbh->prepare("select * from dadosgerais where nome_dado='Mark-up'");
$stmt->execute();
$markup=$stmt->fetch(PDO::FETCH_ASSOC);
$markconsumidor = explode('=',$markup['dado_1']);
$markconsumidor = $markconsumidor[1];
$markconsumidorespecial = explode('=',$markup['dado_2']);
$markconsumidorespecial = $markconsumidorespecial[1];

$_SESSION['markup'] = $markconsumidor;
$_SESSION['qualificacao']='Consumidor';

if(strpos($_SESSION['acesso'],'Consumidor') !== false){
	$_SESSION['markup'] = $markconsumidor;
	$_SESSION['qualificacao']='Consumidor';	
}
if(strpos($_SESSION['acesso'],'Consumidor Especial') !== false){
	$_SESSION['markup'] = $markconsumidorespecial;
	$_SESSION['qualificacao']='Consumidor Especial';
}
if(strpos($_SESSION['acesso'],'Cliente') !== false){
	$_SESSION['markup'] = 1;
	$_SESSION['qualificacao']='Cliente';
}
if(strpos($_SESSION['acesso'],'Representante') !== false){
	$_SESSION['markup'] = 1;
	$_SESSION['qualificacao']='Interno';
}
if(strpos($_SESSION['acesso'],'adm') !== false){
	$_SESSION['markup'] = 1;
	$_SESSION['qualificacao']='Interno';
}
$_SESSION['limitevenda'] = 10000000;
//não estou considerando limites de venda..
