<?php
require '../../_config/conection.php'; 
$dbh = new PDO('mysql:host='.$host.';dbname='.$db.';charset=utf8',$user,$pass);	

$bonus=$_GET['bonus'];
$valor_pedido=$_GET['valor_pedido'];
$bonus=str_replace('.','',$bonus);
$bonus=str_replace(',','.',$bonus);
$rest=$valor_pedido-$bonus;
echo number_format($rest,2,',','.');
