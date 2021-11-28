<?php

//requesitos pra utilizar este php auxiliar:
//tem que ter require PCP/functions.php em PCP
//$pedido;$valor;
//
$valor_produtos=valor_produtos($pedido,$dbh,'E');
if(!isset($status)){
	$status=3;
}
$stmt=$dbh->prepare("update pedidos set status_pagamento=? where pedido=?");
$stmt->execute(array($status,$pedido));


//Pegar id_usuario e acesso do cliente que fez a compra..
$stmt=$dbh->prepare("
select u.id_usuario,u.acesso,p.frete from pedidos p
join usuarios u on u.id_usuario = p.id_cliente
where p.pedido=?;
");
$stmt->execute(array($pedido));
$ln=$stmt->fetch(PDO::FETCH_ASSOC);
$id_usuario=$ln['id_usuario'];
$acesso_usuario=$ln['acesso'];
$frete=$ln['frete'];

/*
//Gerar bonus para Pablo para as camisas em nome dele. (1003;1004;1005;1006;2003;2004;2005;2006);
$id_pablo=426;
$stmt=$dbh->prepare("select sum(tot) as tot from pcp where loc=? and situacao='E' and (ref='1003' or ref='1004' or ref='1005' or ref='1006' or ref='2003' or ref='2004' or ref='2005' or ref='2006')");
$stmt->execute(array($pedido));
$ln=$stmt->fetch(PDO::FETCH_ASSOC);
$bonusartista=$ln['tot'];
$stmt=$dbh->prepare("select bonus from usuarios where id_usuario=?");
$stmt->execute(array($id_pablo));
$ln=$stmt->fetch(PDO::FETCH_ASSOC);
$novobonus=$bonusartista + $ln['bonus'];
$stmt=$dbh->prepare("update usuarios set bonus=? where id_usuario=?");
$stmt->execute(array($novobonus,$id_pablo));
//Fim do bonus do Pablo
*/

//
$iniped=explode('-',$pedido);
if($iniped[0] == 'PM'){
	$tipo = 'Venda Comissionada';
}elseif($iniped[0] == 'PE'){
	$tipo = 'Pedido PE';
}else{
	$tipo = 'Meus pedidos';
}

$num_doc=$pedido;
$stmt=$dbh->prepare("insert into faturamentos (id_fat,num_docs,id_usuario,data_fat,valor,tipo,dataalter) values
				(default,?,?,?,?,?,default)");
				


if($stmt->execute(array($num_doc,$id_usuario,date('Y-m-d'),$valor_produtos,$tipo))){
	$stmt=$dbh->prepare("select razaosocial from usuarios where id_usuario=?");
	$stmt->execute(array($id_usuario));
	$ln=$stmt->fetch(PDO::FETCH_ASSOC);
	$rsocial=$ln['razaosocial'];
	//mandar e-mail p rolf confirmando pagamento..
	$message = '
	<html>
	<head>
	<title>E-mail de Faturamento</title>
	<style>
		
	</style>
	
	</head>
	<body>
	<h2>Faturamento ocorrido:</h2>
	<p>Num_docs:'.$num_doc.'</p>
	<p>Cliente:'.$rsocial.'</p>
	<p>Valor_produtos:'.$valor_produtos.'</p>
	<p>Frete:'.$frete.'</p>
	<p>Valor:'.$valor.'</p>
	
	<p>Tipo:'.$tipo.'</p>
	</body>
	</html>';
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "From: rolf@rolfmodas.com.br" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	$to="rolf@rolfmodas.com.br";
	$subject="Faturamento";
	mail($to,$subject,$message,$headers);
	//fim do mandar email..
}

if($tipo=='Venda Comissionada'){
	
	//pegar id de quem indicou o usuario
	$stmt=$dbh->prepare("select id_usuario from usuarios where rede like ?");
	$stmt->execute(array('%'.$id_usuario.'%'));
	if($stmt->rowCount()>=1){
		$ln=$stmt->fetch(PDO::FETCH_ASSOC);
		$id_up=$ln['id_usuario'];
		//fim do "pegar id de quem indicou"
		//pegar comissao de indicação
		$stmt=$dbh->prepare("select dado_1 from dadosgerais where nome_dado=?");
		$stmt->execute(array('Bônus de Indicação'));
		$ln=$stmt->fetch(PDO::FETCH_ASSOC);
		$comissao=$ln['dado_1'];
		$valor_bonus=($valor-$frete)*$comissao/100;
		//fim do pegar comissao de indicação
		$tipo_bonus='Comissão Consultor';
		$stmt=$dbh->prepare("insert into bonus (id_bonus,id_recebedor,id_comprador,pedido,valor_bonus,data_bonus,data_efetivado,tipo_bonus) values 
										(default,?,?,?,?,?,?,?)");
		$stmt->execute(array($id_up,$id_usuario,$pedido,$valor_bonus,date('Y-m-d'),date('Y-m-d'),$tipo_bonus));
						
		$stmt=$dbh->prepare("select bonus from usuarios where id_usuario =?");
		$stmt->execute(array($id_up));
		$ln=$stmt->fetch(PDO::FETCH_ASSOC);
		$bonus_atual=$ln['bonus']+$valor_bonus;	
		$stmt=$dbh->prepare("update usuarios set bonus=? where id_usuario=?");
		$stmt->execute(array($bonus_atual,$id_up));
	}	
	
	//Criar Bonus do nível 2..
		//pegar id de quem indicou o usuario (up2)
	$stmt=$dbh->prepare("select id_usuario from usuarios where rede like ?");
	$stmt->execute(array('%'.$id_up.'%'));
	if($stmt->rowCount()>=1){
		$ln=$stmt->fetch(PDO::FETCH_ASSOC);
		$id_up2=$ln['id_usuario'];
			//fim do "pegar id de quem indicou (up2)"
		//pegar comissao de indicação
		$stmt=$dbh->prepare("select dado_1 from dadosgerais where nome_dado=?");
		$stmt->execute(array('Bônus de Indicação N2'));
		$ln=$stmt->fetch(PDO::FETCH_ASSOC);
		$comissao=$ln['dado_1'];
		$valor_bonus=($valor-$frete)*$comissao/100;
		//fim do pegar comissao de indicação
		$tipo_bonus='Comissão Consultor N2';
		$stmt=$dbh->prepare("insert into bonus (id_bonus,id_recebedor,id_comprador,pedido,valor_bonus,data_bonus,data_efetivado,tipo_bonus) values 
										(default,?,?,?,?,?,?,?)");
		$stmt->execute(array($id_up2,$id_usuario,$pedido,$valor_bonus,date('Y-m-d'),date('Y-m-d'),$tipo_bonus));
						
		$stmt=$dbh->prepare("select bonus from usuarios where id_usuario =?");
		$stmt->execute(array($id_up2));
		$ln=$stmt->fetch(PDO::FETCH_ASSOC);
		$bonus_atual=$ln['bonus']+$valor_bonus;	
		$stmt=$dbh->prepare("update usuarios set bonus=? where id_usuario=?");
		$stmt->execute(array($bonus_atual,$id_up2));
	}
	//fim do Bonus pro nivel 2..
	
	
}else{

}



