<?php

require '../../_config/conection.php';
require '../../../functionsPDO.php';

if(!isset ($w)){$w='';}
if(!$boolEmOutroForm){
	echo '<form id="formProd" method="post" action="">';
}
if(isset($fornSP)){
	$qr="select max(ref) as ref from produtos where ref like ?";
	$values=array('SP%');
	$ln=fetchAssoc($dbhRPI,$qr,$values);
	$max_ref=$ln['ref'];
	$ex_ref=explode('P',$max_ref);
	$max_ref=$ex_ref[1];
	$max_ref++;
	while(strlen($max_ref)<=4){
		$max_ref = '0'.$max_ref;
	}
	$new_ref='SP'.$max_ref;
	
}else{
	$new_ref='';
}
echo '
<input type="hidden" name="bool_novo'.$w.'" id="bool_novo'.$w.'" value="true"/>
<label id="lab_ref'.$w.'" for="ref'.$w.'">Ref.: </label><input class="inputRef" type="text" name="ref'.$w.'" id="ref'.$w.'" value="'.$new_ref.'" size="3"/>
<label id="lab_desc'.$w.'" for="desc'.$w.'">Descrição: </label><input type="text" name="desc'.$w.'" id="desc'.$w.'"/></br>
<label id="lab_tamanhos'.$w.'" for="tamanhos'.$w.'">Tamanhos (separados por "/"):</label><input type="text" name="tamanhos'.$w.'" id="tamanhos'.$w.'"/>
<label id="lab_preco'.$w.'" for="preco'.$w.'">Preço: </label><input type="text" name="preco'.$w.'" id="preco'.$w.'" size="5"/>
<label id="lab_secao'.$w.'" for="secao'.$w.'">Seção: </label><input type="text" name="secao'.$w.'" id="secao'.$w.'"/></br>
<label id="lab_NCM'.$w.'" for="NCM'.$w.'">NCM: </label><input type="text" name="NCM'.$w.'" id="NCM'.$w.'"/></br>

';
if(!$boolEmOutroForm){
	echo '</form>';
}
?>

