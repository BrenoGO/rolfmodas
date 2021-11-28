<?php
session_start();
require '../../_config/conection.php'; 
$dbh = new PDO('mysql:host='.$host.';dbname='.$db.';charset=utf8',$user,$pass);
	
$acao=$_GET["acao"];
$num_doc=$_GET["num_doc"];

if($acao=='unset'){
	unset($_SESSION['baixadocs']);
	echo '';
}else{
	if($acao=='add'){
		if(!isset($_SESSION['baixadocs'])){
			$_SESSION['baixadocs']=array();
		}	
		if(!in_array($num_doc,$_SESSION['baixadocs'])){
			$_SESSION['baixadocs'][]=$num_doc;
		}
	}
	if($acao=='del'){
		$_SESSION['baixadocs']=array_diff($_SESSION['baixadocs'],array($num_doc));
	}
	
	require("../_auxiliares/areceberdocsprabaixar.php");
}


?>