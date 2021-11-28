<?php

require '../../_config/conection.php';
require '../../../functionsPDO.php';
//PRINT_R($_POST);
$w=$_POST['w'];
$bool_novo=$_POST['bool_novo'.$w];
$ref=$_POST['ref'.$w];
$Qnt=$_POST['Qnt'.$w];
$cor=$_POST['cor'.$w];
$tam=$_POST['tam'.$w];

$id_forn=$_POST['id_forn'];

$preco=$_POST['preco'.$w];
$preco=str_replace(',','.',$preco);



if($bool_novo == "true"){
	if(seExiste($dbhRPI,'produtos','ref',$ref)){
		echo 'ERRO: Referência já Existe! ';
		die();
	}
	$desc=$_POST['desc'.$w];
	$tamanhos=$_POST['tamanhos'.$w];
	
	$secao=$_POST['secao'.$w];
	$NCM=$_POST['NCM'.$w];
	
	IF($NCM == ""){
		$NCM = null;
	}
	
	$table="produtos";
	$cols="(ref,descricao,tamanhos,preco,secao,NCM,id_forn)";
	$qrValues="(?,?,?,?,?,?,?)";
	$values=array($ref,$desc,$tamanhos,$preco,$secao,$NCM,$id_forn);
	$qr="insert into ".$table." ".$cols." values ".$qrValues;
	insertRow($dbhRPI,$table,$cols,$qrValues,$values);
	
	echo '<p>Produto Cadastrado</p>';
	
}
$table="movimentacao";
$cols="(id_mov,tipo_mov,bool_est,id_forn,ref,cor,tamanho,quantidade,data_op,preco_ent)";
$qrValues="(default,'Compra','S',?,?,?,?,?,default,?)";
$values=array($id_forn,$ref,$cor,$tam,$Qnt,$preco);
//$qr="insert into ".$table." ".$cols." values ".$qrValues;
//executeSQL($dbhRPI,$qr,$values);
insertRow($dbhRPI,$table,$cols,$qrValues,$values);

echo '<p>Compra Realizada</p>';



?>