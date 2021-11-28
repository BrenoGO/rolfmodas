<?php
	session_start();
	require '../_config/conection.php'; 
	$dbh = new PDO('mysql:host='.$host.';dbname='.$db.';charset=utf8',$user,$pass);
	require_once("PagSeguro.class.php");

	if(isset($_GET['pedido'])){
		$pedido=$_GET['pedido'];
		$PagSeguro = new PagSeguro();
		$P = $PagSeguro->getStatusByReference($pedido);
		$out=array();
		foreach ( (array) $P as $index => $node ){
			$out[$index] = $node;
		}
		if(!isset($out[0])){
			$out[0]=1;
		}
		
		if($out[0] == 3 or $out[0] == 4){
			$stmt=$dbh->prepare("update pedidos set status_pagseguro=? where pedido=?");
			$stmt->execute(array($out[0],$pedido));
			$stmt=$dbh->prepare("update pcp set situacao=? where situacao=? and loc=?");
			$stmt->execute(array('E','S',$pedido));
			require '../PCP/funtions.php';
			$valor=valor_pedido($pedido,$dbh,'E');
			require '../_auxiliares/faturamento.php';
		}
	}else{
	    echo "Parâmetro \"reference\" não informado!";
	}
	$msg= $PagSeguro->getStatusText($out[0]);
	echo '<script>alert("'.$msg.'");window.location.href="../Usuario/meuspedidos.php?acao=consultped"</script>';

?>