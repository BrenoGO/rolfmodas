<?php
//get the q parameter from URL
$q=$_GET["q"];


require '../../_config/conection.php';
$con = conection();
mysqli_set_charset($con,"utf8");
	
$hint='';
$qr = "select * from clientes where razaosocial like '%$q%' or nomefantasia like '%$q%' or cidade like '%$q%' or cnpj like '%$q%'";
$result=mysqli_query($con,$qr);
$table = array();
while($row = $result -> fetch_assoc()){
	$table[] = $row;
}
foreach($table as $ln){
	$rsocial=$ln['razaosocial'];
	$idcliente=$ln['idclientes'];
	$nomefantasia=$ln['nomefantasia'];
	$cidade=$ln['cidade'];
	$hint .= '<a href="?acao=buscarareceber&idcliente='.$idcliente.'">'.$rsocial.' - '.$nomefantasia.' - '.$cidade.'</a></br>';
}

//output the response
echo $hint;
?>