<?php

$q=$_GET["q"];
if(isset($_GET['w'])){$w=$_GET['w'];}
if(!isset ($w)){$w='';}

if (strlen($q)>0) {
	
	require '../../_config/conection.php';
	
	echo '<span onclick="FormProd(0,'.$w.')" style="cursor: pointer;">Cadastrar novo Produto</span></br>';
	$stmt=$dbhRPI->prepare("select * from produtos where (descricao like ?) or (ref like ?)");
	$stmt->execute(array('%'.$q.'%','%'.$q.'%'));
	
	while($ln=$stmt->fetch(PDO::FETCH_ASSOC)){
		echo '<span onclick="FormProd('."'".$ln['ref']."'".','.$w.')" id="Prod" style="cursor: pointer;">Ref. '.$ln['ref'].': '.$ln['descricao'].'</span></br>';
	}

}

?>
