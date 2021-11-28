<?php

$parcelas=$_GET["parcelas"];
$total = $_GET["total"];
$total=str_replace(',','.',$total);
$difprazo = $_GET["difprazo"];
$pedido = $_GET["pedido"];
$comissao = $_GET["comissao"];
$comissao=str_replace(',','.',$comissao);

$valorparc=$total/$parcelas;

for($p=1;$p<=$parcelas;$p++){
	if($p == $parcelas){
		$valorparc=number_format($valorparc,2);
		$parcialparcelas=$valorparc*($parcelas-1);
		$valorparc=$total - $parcialparcelas;
	}
	$datavenc[$p]= date("Y-m-d",mktime (0, 0, 0, date("m"), date("d")+$difprazo*$p, date("Y")));
	echo '<input type="date" name="dataparc'.$p.'" id="dataparc'.$p.'" value="'.$datavenc[$p].'"/> - 
	R$ <input type="text" size="4" name="valorparc'.$p.'" id="valorparc'.$p.'" value="'.number_format($valorparc,2,',','.').'"/> - 
	Doc n.:<input type="text" size="8" name="docparc'.$p.'" id="docparc'.$p.'" value="'.$pedido.'/0';
	if($p>=10){echo $p;}else{echo '0'.$p;}
	echo '"/>';
	if($comissao>0){echo ' - Comissão: R$ <input type="text" size="8" name="comiparc'.$p.'" id="comiparc'.$p.'" value="'.number_format($comissao/100*$valorparc,2,',','.').'" size=3/>'.$comissao.'%';}
	
	echo '</br>';
}



?>