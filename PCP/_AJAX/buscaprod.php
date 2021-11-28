<?php
//get the q parameter from URL
$q=$_GET["q"];

//lookup all links from the xml file if length of q>0
if (strlen($q)>0) {
	
	require '../../_config/conection.php';
	$con = conection();
	mysqli_set_charset($con,"utf8");
	
	$hint='<a href="?acao=cadastprod"><b>Cadastrar novo Produto</b></a></br>';
	$qr = "select * from produtos where descricao like '%$q%' or ref like '%$q%'";
	$result=mysqli_query($con,$qr);
	$table = array();
	while($row = $result -> fetch_assoc()){
		$table[] = $row;
	}
	foreach($table as $ln){
		$ref=$ln['ref'];
		$desc=$ln['descricao'];
		$preco=$ln['preco'];
		$hint .= '<a href="?acao=cadastprod&ref='.$ref.'">'.$ref.' - '.$desc.' - '.$preco.'</a></span></br>';
	}
}

// Set output to "no suggestion" if no hint was found
// or to the correct values
if ($hint=="") {
  $response="Nenhum produto encontrado...";
} else {
  $response=$hint;
}

//output the response
echo $response;
?>