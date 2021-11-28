<?php
require '../../_config/conection.php';
$con = conection();
mysqli_set_charset($con,"utf8");

$parcelas = $_GET['parcelas'];
$valor=$_GET['valor'];
$valor=str_replace(',','.',$valor);
if(($parcelas <> '') and ($parcelas > 0)){
	$valorparc = round($valor/$parcelas,2);
	$dif = $valor - $valorparc*$parcelas;
	for($i=1;$i<=$parcelas;$i++){
		if($i == 1){$valorparc = number_format($valorparc+$dif,2,',','.');}
		else{$valorparc= number_format($valorparc,2,',','.');}
		$dataparc=date('Y-m-d',mktime(0,0,0,date('m'),date('d')+30*$i,date('Y')));
		
		echo '<label for="valorparc'.$i.'">Parcela '.$i.':</label> 
		<input type="text" id="valorparc'.$i.'" name="valorparc'.$i.'" value="'.$valorparc.'" size="5"/>
		- <label for="dataparc'.$i.'">Data:</label>
		<input type="date" id="dataparc'.$i.'" name="dataparc'.$i.'" value="'.$dataparc.'"/></br>'; 
		$valorparc = round($valor/$parcelas,2);
	}
}
?>