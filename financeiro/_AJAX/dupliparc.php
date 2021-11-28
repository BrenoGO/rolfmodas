<?php
//get the q parameter from URL
$cod=$_GET["cod"];
$pedido = $_GET["pedido"];
$total = $_GET["total"];
$total=str_replace(',','.',$total);
$comissao = $_GET["comissao"];
$comissao=str_replace(',','.',$comissao);

echo '<div style="border:1px">';
$datavencvista = date("Y-m-d",mktime (0, 0, 0, date("m")  , date("d")+5, date("Y")));

if($cod == 0){
	echo '
	1 Parcela - R$ <input type="text" name="valorparc1" id="valorparc1" value="'.number_format($total,2,',','.').'" size=5/>
	- Vencimento: <input type="date" name="dataparc1" value="'.$datavencvista.'" id="dataparc1"/> 
	- Doc N°.:<input type="text" size="8" name="docparc1" id="docparc1" value="'.$pedido.'"/>';
	if($comissao>0){echo' - Comissão: R$ <input type="text" size="8" name="comiparc1" id="comiparc1" value="'.number_format($comissao/100*$total,2,',','.').'" size=5/>';}
}
if($cod == 1){
	echo '
	
	Intervalo:<input size="2" type="text" name="difprazo" id="difprazo" value="30" onkeyup="numparcelas(document.getElementById('."'nparcelas'".').value,'.$total.',this.value,'."'".$pedido."'".','.$comissao.')"/>dias - Parcelas:
	<select name="nparcelas" id="nparcelas" onchange="numparcelas(this.value,'.$total.',document.getElementById('."'difprazo'".').value,'."'".$pedido."'".','.$comissao.')">
	';
	for($z=1;$z<=12;$z++){
		echo '<option>'.$z.'</option>';
	}
	echo '</select>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<button type="button" onclick="parcmanualmente('.$total.',document.getElementById('."'nparcelas'".').value)">Definir parcelas manualmente</button>
	';
}
echo '</div>';
?>