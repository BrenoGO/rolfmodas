<?php
//get the q parameter from URL
$q=$_GET["q"];
$destino=$_GET['destino'];
$destino=str_replace(';','&',$destino);
//lookup all links from the xml file if length of q>0
if (strlen($q)>0) {
	
	require '../_config/conection.php';
	$con = conection();
	mysqli_set_charset($con,"utf8");
	
	$hint='<a href="?'.$destino.'&id=novo"><b>Cadastrar novo Usuário</b></a></br>';
	$qr = "select * from usuarios where razaosocial like '%$q%' or nomefantasia like '%$q%' or cnpj like '%$q%' or cidade like '%$q%'";
	$result=mysqli_query($con,$qr);
	$table = array();
	while($row = $result -> fetch_assoc()){
		$table[] = $row;
	}
	foreach($table as $ln){
		$rsocial=$ln['razaosocial'];
		$nfantasia=$ln['nomefantasia'];
		$cidade=$ln['cidade'];
		$id_usuario=$ln['id_usuario'];
		$hint .= '<a href="?'.$destino.'&id='.$id_usuario.'">'.$rsocial.' - '.$nfantasia.' - '.$cidade.' ('.$id_usuario.')</a></br>';
	}
}

echo $hint;
?>