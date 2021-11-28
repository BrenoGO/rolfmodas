<?php

require '../_config/conection.php';
$con = conection();
mysqli_set_charset($con,"utf8");

$frete = $_GET['frete'];
$totalgeral=$_GET['totalgeral'];

$frete=explode('/',$frete);
$frete=$frete[0];

$totalgeral += $frete;
echo number_format($totalgeral,2,',','.');