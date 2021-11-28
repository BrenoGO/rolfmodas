<?php

$w=$_GET['w'];
$id_forn=$_GET['id_forn'];

if($id_forn == '2'){
	$fornSP=true;
}
echo '						
	Produto '.$w.': <input type="text" id="inputTextLsProd'.$w.'" onkeyup="lsbuscaprod(this.value,'.$w.')"/>
	<div id="lsbuscaprod'.$w.'"></div>
	<span style="margin-top: -20px; float: right; cursor: pointer;" onclick="deleteProd('.$w.')">X</span>
	<div id="cadastProd'.$w.'" style="display: none;">';
		$boolEmOutroForm=true;
		require '../_auxiliares/FormCadastProd.php';
		echo '<label id="lab_Qnt'.$w.'" for="Qnt'.$w.'">Quant.: </label><input type="number" name="Qnt'.$w.'" id="Qnt'.$w.'"/></br>
		<label for="tam'.$w.'">Tamanho: </label><input type="text" name="tam'.$w.'" id="tam'.$w.'" size="3"/>
		<label for="cor'.$w.'">Cor: </label><input type="text" name="cor'.$w.'" id="cor'.$w.'" size="7"/>	
		<input type="button" id="ButFinCompraProd" value="Confirmar compra deste produto" onclick="FinalCompraProd('.$w.')"/>
	</div>';
?>