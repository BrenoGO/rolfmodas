<?php

require '../_config/conection.php';
$dbh = new PDO('mysql:host='.$host.';dbname='.$db.';charset=utf8',$user,$pass);


$stmt=$dbh->prepare("select bonus from usuarios where id_usuario=?");
$stmt->execute(array($_SESSION['id_usuario']));
$ln=$stmt->fetch(PDO::FETCH_ASSOC);
$bonus=$ln['bonus'];
$resto_a_pg=$totalgeral;
if($bonus > 0){
	if($bonus>$totalgeral){
		$thisvalue=$totalgeral;
	}else{
		$thisvalue=$bonus;
	}
	$resto_a_pg=$totalgeral-$thisvalue;
}
	
echo number_format($totalgeral,2,',','.');