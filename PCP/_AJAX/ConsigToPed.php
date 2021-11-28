<?php
//get the q parameter from URL

require '../../_config/conection.php'; 
$dbh = new PDO('mysql:host='.$host.';dbname='.$db.';charset=utf8',$user,$pass);

$ref=$_GET["ref"];
$cor=$_GET['cor'];
$i=$_GET['tam'];
$pedCons=$_GET['pedCons'];
$Ped=$_GET['Ped'];
$Sit=$_GET['Sit'];
$Qnt=$_GET['Qnt'];

//Atualizar linha S do Pedido Consignado
$qr=$dbh->prepare("select * from pcp where ref=? and cor=? and loc=? and situacao='S'");
$qr->execute(array($ref,$cor,$pedCons));
$row=$qr->fetch(PDO::FETCH_ASSOC);
$preco=$row['preco'];
$desconto=$row['desconto'];
$novoT=$row['t'.$i]-$Qnt;
$novoTot=$row['tot']-$Qnt;
if($novoTot==0){
	$qr=$dbh->prepare("delete from pcp where ref=? and cor=? and loc=? and situacao='S'");
	$qr->execute(array($ref,$cor,$pedCons));
}else{
	$query='update pcp set t'.$i.'=?,tot=? where ref=? and cor=? and loc=? and situacao="S"'; 
	$qr=$dbh->prepare($query);
	$qr->execute(array($novoT,$novoTot,$ref,$cor,$pedCons));
}
//Atualizar linha A do Pedido Consignado
	//testar se existe linha A 
	$qr=$dbh->prepare("select * from pcp where ref=? and cor=? and loc=? and situacao='A'");
	$qr->execute(array($ref,$cor,$pedCons));
	if($qr->rowCount()<=0){
		$qr=$dbh->prepare("insert into pcp (ref,cor,t1,t2,t3,t4,t5,tot,loc,lote,dataalter,situacao,preco,desconto) 
							values (?,?,0,0,0,0,0,0,?,0,default,'A',?,?)");
		$qr->execute(array($ref,$cor,$pedCons,$preco,$desconto));
	}
$qr=$dbh->prepare("select * from pcp where ref=? and cor=? and loc=? and situacao='A'");
$qr->execute(array($ref,$cor,$pedCons));
$row=$qr->fetch(PDO::FETCH_ASSOC);
$novoT=$row['t'.$i]+$Qnt;
$novoTot=$row['tot']+$Qnt;
$query='update pcp set t'.$i.'=?,tot=? where ref=? and cor=? and loc=? and situacao="A"'; 
$qr=$dbh->prepare($query);
$qr->execute(array($novoT,$novoTot,$ref,$cor,$pedCons));

//Atualizar linha (A ou P) que vai produtos
$qr=$dbh->prepare("select * from pcp where ref=? and cor=? and loc=? and situacao=?");
$qr->execute(array($ref,$cor,$Ped,$Sit));
$row=$qr->fetch(PDO::FETCH_ASSOC);
$preco=$row['preco'];
$desconto=$row['desconto'];
$novoT=$row['t'.$i]-$Qnt;
$novoTot=$row['tot']-$Qnt;
if($novoTot==0){
	$qr=$dbh->prepare("delete from pcp where ref=? and cor=? and loc=? and situacao=?");
	$qr->execute(array($ref,$cor,$Ped,$Sit));
}else{
	$query='update pcp set t'.$i.'=?,tot=? where ref=? and cor=? and loc=? and situacao=?'; 
	$qr=$dbh->prepare($query);
	$qr->execute(array($novoT,$novoTot,$ref,$cor,$Ped,$Sit));
}


//Atualizar linha S do Pedido que vai produtos
	//Verificar se existe linha S
	$qr=$dbh->prepare("select * from pcp where ref=? and cor=? and loc=? and situacao='S'");
	$qr->execute(array($ref,$cor,$Ped));
	if($qr->rowCount()<=0){
		
		$qr=$dbh->prepare("insert into pcp (ref,cor,t1,t2,t3,t4,t5,tot,loc,lote,dataalter,situacao,preco,desconto) 
							values (?,?,0,0,0,0,0,0,?,0,default,'S',?,?)");
		$qr->execute(array($ref,$cor,$Ped,$preco,$desconto));
	}
$qr=$dbh->prepare("select * from pcp where ref=? and cor=? and loc=? and situacao='S'");
$qr->execute(array($ref,$cor,$Ped));
$row=$qr->fetch(PDO::FETCH_ASSOC);
$novoT=$row['t'.$i]+$Qnt;
$novoTot=$row['tot']+$Qnt;
$query='update pcp set t'.$i.'=?,tot=? where ref=? and cor=? and loc=? and situacao="S"'; 
$qr=$dbh->prepare($query);
$qr->execute(array($novoT,$novoTot,$ref,$cor,$Ped));

echo 'Atualizado';
