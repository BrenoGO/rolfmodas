<?php

require '../_config/conection.php';
$con = conection();
mysqli_set_charset($con,"utf8");

$estado = $_GET['estado'];
echo '
	<select class="fl" id="cidade" name="cidade" required>
		<option>Selecione sua cidade</option>';
	$qr="select c.nome_cidade from 
		cidade c join estado e on e.id_estado=c.id_estado where uf_estado='$estado' order by c.nome_cidade";
	$sql=mysqli_query($con,$qr);
	$tablesql = array();
	while($row = $sql->fetch_assoc()){
		$tablesql[] = $row;
	}
	foreach($tablesql as $ln){
		echo '<option>'.$ln['nome_cidade'].'</option>';
	}
	
	
	echo '</select>';
?>