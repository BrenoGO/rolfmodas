<?php



$CEP=$_GET['CEP'];
require '../functions.php';
$exp_CEP=CEP_curl($CEP);

if(isset($exp_CEP['cidade'])){
	if(isset($exp_CEP['bairro'])){
		echo $exp_CEP['cidade'].','.$exp_CEP['uf'].','.$exp_CEP['bairro'].','.$exp_CEP['logradouro'];
	}else{
		echo $exp_CEP['cidade'].','.$exp_CEP['uf'];
	}	
}else{
	echo 'erro,';
}
	

