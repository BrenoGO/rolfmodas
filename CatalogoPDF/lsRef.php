<?php

$q=$_GET['q'];
if($_GET['h']){ $h = $_GET['h'];}
if(strlen($q) > 0){
	$exp=explode(' ',$q);
	if(isset($exp[1])){
		$q = $exp[0];
		$w = $exp[1];
	}
	require '../_config/conection.php';
	$stmt=$dbh->prepare("select ref,descricao from produtos
						where ( (ref like ?) or (descricao like ?));");
	if(isset($w)){
		$stmt->execute(array('%'.$q.'%','%'.$w.'%'));
	}else{
		$stmt->execute(array('%'.$q.'%','%'.$q.'%'));
	}
	while($ln=$stmt->fetch(PDO::FETCH_ASSOC)){
		$ref = $ln['ref'];
		$desc = $ln['descricao'];
		 
		echo '<span onclick="AddInput();LsValue('.$h.','."'".$ref."'".')" style="cursor:pointer">
		Ref.: '.$ref.' - '.$ln['descricao'].'</span></br>';
	}
}

?>