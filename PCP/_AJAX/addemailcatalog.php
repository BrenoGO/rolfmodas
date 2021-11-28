<?php

$emailnovo=$_GET["q"];
$i=$_GET["i"];

if (strlen($emailnovo)>0) {
	
	require '../../_config/conection.php';
	$con = conection();
	
	$hint='<input type="checkbox" name="emailnovo'.$i.'" id="emailnovo'.$i.'" value="'.$emailnovo.'" checked/><label for="emailnovo'.$i.'">'.$emailnovo.'</label>';
	
}

if(isset($hint)){echo $hint;}
?>