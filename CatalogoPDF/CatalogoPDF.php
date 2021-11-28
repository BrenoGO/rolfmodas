<?php
	require 'makeCatalogoPDF.php';
	require '../_config/conection.php';
	
	if (isset($_POST['submitCatalogo'])) {
		$array = [];
		$c = 0;
		for( $i=1 ; isset($_POST['ref'.$i]) ; $i++ ){
				$array[]=$_POST['ref'.$i];
				/*$stmt=$dbh->prepare("select descricao from produtos where ref=?");
				$stmt->execute(array($array[$c]));
				while($ln=$stmt->fetch(PDO::FETCH_ASSOC)){
					$desc[] = $ln['descricao'];
					$descricao = implode(':', $desc);
				}
				$c++;*/
		}
		//$ar= unserialize(base64_decode($_POST['ArrImgs']));
		$pdf = new makeCatalogoPDF('P', 'mm', array(100,150));
		$pdf->ImgsPages($array,$dbh);
	}
?>