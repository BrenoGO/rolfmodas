<?php
//get the q parameter from URL
$q=$_GET["q"];

//lookup all links from the xml file if length of q>0
if (strlen($q)>0) {
	
	require '../../_config/conection.php';
	$con = conection();
	mysqli_set_charset($con,"utf8");
	
	$hint='<a href="?acao=cadastcor"><b>Cadastrar nova Cor</b></a></br>';
	$qr = "select * from cores where refcor like '%$q%' or nomecor like '%$q%'";
	$result=mysqli_query($con,$qr);
	$table = array();
	while($row = $result -> fetch_assoc()){
		$table[] = $row;
	}
	foreach($table as $ln){
		$ref=$ln['refcor'];
		$desc=$ln['nomecor'];
		$hint .= '<a href="?acao=resbuscacor&busca='.$ref.'">'.$ref.' - '.$desc.'</a></br>';
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