<?php

$num_parcs=$_GET["num_parcs"];
$ini_num_doc = $_GET["ini_num_doc"];

for($p=1;$p<=$num_parcs;$p++){
	/*if($p == $parcelas){
		$valorparc=number_format($valorparc,2);
		$parcialparcelas=$valorparc*($parcelas-1);
		$valorparc=$total - $parcialparcelas;
	}
	*/
	echo '<input type="date" name="dataparc'.$p.'" id="dataparc'.$p.'"/> - 
	R$ <input type="text" size="4" name="valorparc'.$p.'" id="valorparc'.$p.'"/> - 
	Doc n.:<input type="text" size="8" name="docparc'.$p.'" id="docparc'.$p.'" value="Re'.$ini_num_doc.'/0';
	if($p>=10){echo $p;}else{echo '0'.$p;}
	echo '"/>';
	if($comissao>0){echo 'Comissão: R$ <input type="text" size="8" name="comiparc'.$p.'" id="comiparc'.$p.'" value="'.number_format($comissao/100*$valorparc,2,',','.').'" size=3/>';}
	
	echo '</br>';
}



?>