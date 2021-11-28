<?php
require '../../_config/conection.php';
$con = conection();
mysqli_set_charset($con,"utf8");

$CC = $_GET['CC'];
echo '<label for="forn">Selecione o fornecedor:</forn> 
<select id="forn" name="id_forn">';



if($CC <> '0'){
	echo '<option>Selecione...</option>';
	$qr="select id_forn,forn from forn where id_CC = $CC";
	$sql=mysqli_query($con,$qr);
	$tableForn=array();
	while($row = $sql ->fetch_assoc()){
		$tableForn[] = $row;
	}


	foreach($tableForn as $lnForn){
		echo '<option value="'.$lnForn['id_forn'].'">'.$lnForn['forn'].'</option>';
	}
}else{
	echo '<option>Selecione o CC</option>';
}
echo '</select>
<input type="button" value="Novo Fornecedor" onclick="formcadastForn()"/>';

?>