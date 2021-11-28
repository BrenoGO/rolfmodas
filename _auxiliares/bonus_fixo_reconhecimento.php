<?php

//verificar se tem bonus no mês passado e deste mês não efetivado e se ele deve ser efetivado.
//Requisito: $cargo;
$tipo_bonus='Bonus Fixo '.$cargo;
$stmt=$dbh->prepare("select dado_1 from dadosgerais where nome_dado=?");
$stmt->execute(array($tipo_bonus));
$ln=$stmt->fetch(PDO::FETCH_ASSOC);
$bonus_fixo=$ln['dado_1'];


$ultimo_dia = date("t", mktime(0,0,0,$mes_passado,'01',$ano_do_mes_passado));
$data_bonus=$ano_do_mes_passado.'-'.$mes_passado.'-'.$ultimo_dia;

$stmt=$dbh->prepare("insert into bonus (id_bonus,id_recebedor,valor_bonus,data_bonus,data_efetivado,tipo_bonus) 
values (default,?,?,?,?,?)");
$stmt->execute(array($_SESSION['id_usuario'],$bonus_fixo,$data_bonus,date('Y-m-d'),$tipo_bonus));

$stmt=$dbh->prepare("select bonus from usuarios where id_usuario =?");
$stmt->execute(array($_SESSION['id_usuario']));
$ln=$stmt->fetch(PDO::FETCH_ASSOC);
$bonus_atual=$ln['bonus']+$bonus_fixo;
	
$stmt=$dbh->prepare("update usuarios set bonus=?,qualificacao=? where id_usuario=?");
$stmt->execute(array($bonus_atual,$cargo,$_SESSION['id_usuario']));

//Verifica se bateu 3 vezes seguidas ou 6 vezes nos últimos 12 meses mas que nunca recebeu o Reconhecimento neste cargo..
//Verificar se já foi reconhecido...
$stmt=$dbh->prepare("select * from bonus where id_recebedor=? and tipo_bonus = ?");
$tipo_bonus_recon='Reconhecimento '.$cargo;
$stmt->execute(array($_SESSION['id_usuario'],$tipo_bonus_recon));
$ver=$stmt->rowCount();
if($ver <=0){
	//nunca foi reconhecido nesse cargo
	$mes_3_atras = $mes_passado-2;
	$ano_do_3_passado = $ano_do_mes_passado;
	if($mes_3_atras <=0){
		$mes_3_atras +=12;
		$ano_do_3_passado = $ano_do_mes_passado - 1;
	}
	$data_3_atras=$ano_do_3_passado.'-'.$mes_3_atras.'-'.'01';
	$mes_12_atras = $mes_3_atras - 9;
	if($mes_12_atras <=0){
		$mes_12_atras +=12;
		$ano_12_passado = $ano_do_3_passado -1;
	}
	$data_12_atras=$ano_12_passado.'-'.$mes_12_atras.'-'.'01';
	$stmt=$dbh->prepare("select * from bonus where id_recebedor=? and data_bonus>=? and data_bonus<=? and tipo_bonus = ?");
	$stmt->execute(array($_SESSION['id_usuario'],$data_3_atras,$data_fat2,$tipo_bonus));
	$ver=$stmt->rowCount();
	
	$stmt=$dbh->prepare("select * from bonus where id_recebedor=? and data_bonus>=? and data_bonus<=? and tipo_bonus like ?");
	$stmt->execute(array($_SESSION['id_usuario'],$data_12_atras,$data_fat2,$tipo_bonus));
	$ver2=$stmt->rowCount();
				
	if($ver == 3 or $ver2 >=6){
		//reconhecer no cargo
		$stmt=$dbh->prepare("select dado_1 from dadosgerais where nome_dado=?");
		$stmt->execute(array($tipo_bonus_recon));
		$ln=$stmt->fetch(PDO::FETCH_ASSOC);
		$bonus_recon=$ln['dado_1'];
		
		$stmt=$dbh->prepare("insert into bonus (id_bonus,id_recebedor,valor_bonus,data_bonus,data_efetivado,tipo_bonus) 
		values (default,?,?,?,?,?)");
		$stmt->execute(array($_SESSION['id_usuario'],$bonus_recon,date('Y-m-d'),date('Y-m-d'),$tipo_bonus_recon));

		$stmt=$dbh->prepare("select bonus from usuarios where id_usuario =?");
		$stmt->execute(array($_SESSION['id_usuario']));
		$ln=$stmt->fetch(PDO::FETCH_ASSOC);
		$bonus_atual=$ln['bonus']+$bonus_recon;
		
		$stmt=$dbh->prepare("update usuarios set bonus=? where id_usuario=?");
		$stmt->execute(array($bonus_atual,$_SESSION['id_usuario']));
	}
}