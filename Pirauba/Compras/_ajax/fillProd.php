<?php

$ref=$_GET['ref'];
require '../../_config/conection.php';
$stmt=$dbhRPI->prepare("select * from produtos where ref=?");
$stmt->execute(array($ref));
$ln=$stmt->fetch(PDO::FETCH_ASSOC);
$return=$ln['ref'].';'.$ln['descricao'].';'.$ln['tamanhos'].';'.$ln['preco'].';'.$ln['secao'].';'.$ln['NCM'];

echo $return;


?>