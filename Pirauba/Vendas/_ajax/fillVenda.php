<?php

$id=$_GET['id'];

require '../../_config/conection.php';
$stmt=$dbhRPI->prepare("select m.ref,p.descricao,m.cor,m.tamanho,m.quantidade,m.preco_ent from movimentacao m
						join produtos p on p.ref=m.ref
						where m.id_mov=?");
$stmt->execute(array($id));
$ln=$stmt->fetch(PDO::FETCH_ASSOC);
$return=$ln['ref'].'/'.$ln['descricao'].'/'.$ln['cor'].'/'.$ln['tamanho'].'/'.$ln['preco_ent'];
$stmt=$dbhRPI->prepare("select sum(quantidade) as qnt from movimentacao where ref=? and cor=? and tamanho=? and bool_est='S'");
$stmt->execute(array($ln['ref'],$ln['cor'],$ln['tamanho']));
$ln2=$stmt->fetch(PDO::FETCH_ASSOC);
$qnt=$ln2['qnt'];

$return .='/'.$qnt;
echo $return;


?>