<?php
$total = $_GET['total'];
$parcelas = $_GET['parcelas'];
echo 'Valor a cobrar: <input type="text" size="5" id="valoracob" value="'.number_format($total,2,',','.').'" readonly /> </br>';
for($p=1;$p<=$parcelas;$p++){
	echo'
	<input type="date" name="dataparc'.$p.'" id="dataparc'.$p.'" /> - 
	<input type="text" size="4" name="valorparc'.$p.'" id="valorparc'.$p.'" onchange="mudavaloracobrar('.$parcelas.','.$total.')"/> - 
	Doc n.:<input type="text" size="8" name="docparc'.$p.'" id="docparc'.$p.'"/>
	Comissão:<input type="text" size="8" name="comiparc'.$p.'" id="comiparc'.$p.'"/>
	</br>';
}

?>