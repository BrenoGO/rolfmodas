<?php

$recibo = $_POST['recibo'];
$chave = $_POST['chave'];
$nNF=$_POST['nNF'];

require 'config/config.php';
require '../_config/conection.php'; 


date_default_timezone_set('America/Sao_Paulo');


require_once 'vendor/autoload.php';
use NFePHP\Common\Certificate;
use NFePHP\NFe\Common\Standardize;


$tools = new NFePHP\NFe\Tools($configJson, Certificate::readPfx($certificadoDigital, $certPassword));

require 'loopProt.php';