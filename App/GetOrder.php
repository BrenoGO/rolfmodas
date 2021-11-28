<?php
	include '../_config/conection.php';
	include '../functionsPDO.php';

	$json = file_get_contents('php://input');
	$return = '';
	$returnArray = [];
	$dataArray = json_decode($json, true);
	//pegar id_vendedor:
	$arrayIdVendedor = (array) $dataArray[1];
	$id_vendedor = $arrayIdVendedor['idVendedor'];
	
	//pegar id_cliente, dataped, dataentrega e obs
	
	$arrayPedido = (array) $dataArray[0];
	$id_cliente = $arrayPedido['Cliente']['id'];
	$dataped = $arrayPedido['inf']['dataEmissao'];
	$dataentrega = $arrayPedido['inf']['dataEntrega'];
	$prazopag = $arrayPedido['inf']['prazopag'];
	$obs = $arrayPedido['inf']['obs'];

	//Gerar número do Pedido (pd-?)
	$qr="select max(pedido) from pedidos where pedido like 'PD%'";
	$values=[];
	$ln=fetchAssoc($dbh,$qr,$values);
	$PD=$ln['max(pedido)'];
	$expPD=explode('-',$PD);
	$numPD=$expPD[1];
	$num_novo_PD=$numPD+1;
	if($num_novo_PD<10){
		$loc_pd='PD-00000000'.$num_novo_PD;
	}elseif($num_novo_PD<100){
		$loc_pd='PD-0000000'.$num_novo_PD;
	}elseif($num_novo_PD<1000){
		$loc_pd='PD-000000'.$num_novo_PD;
	}elseif($num_novo_PD<10000){
		$loc_pd='PD-00000'.$num_novo_PD;
	}elseif($num_novo_PD<100000){
		$loc_pd='PD-0000'.$num_novo_PD;
	}elseif($num_novo_PD<1000000){
		$loc_pd='PD-000'.$num_novo_PD;
	}elseif($num_novo_PD<10000000){
		$loc_pd='PD-00'.$num_novo_PD;
	}elseif($num_novo_PD<100000000){
		$loc_pd='PD-0'.$num_novo_PD;
	}elseif($num_novo_PD<1000000000){
		$loc_pd='PD-'.$num_novo_PD;
	}

	//inserir na tabela Pedidos:
	$qr = 'insert into pedidos (pedido, id_cliente, id_vendedor, dataped, dataentrega, prazopag, obs) values (?, ?, ?, ?, ?, ?, ?)';
	$values = [$loc_pd, $id_cliente, $id_vendedor, $dataped, $dataentrega, $prazopag, $obs];
	

	
	$stmt = executeSql($dbh, $qr, $values);
	if(is_string($stmt)){//deu erro no mysql..
		$return .= $stmt.' /';
	}else{
		$return .= 'Inserido na tabela pedido'.' /';
	}
	

	//produtos na tabela pcp:
	$qrped = [];
	foreach ($arrayPedido['produtos'] as $prod) {
		$ref= $prod['ref'];
		$preco = $prod['preco'];
		$tams = [];
		foreach($prod['corQnt'] as $cor => $corQnt) {
			foreach($corQnt as $tam => $tamQnt) {
				if ($tam == 'P' or $tam == '4') {
					$tams[1] = $tamQnt;
				} elseif ($tam == 'M' or $tam == '6') {
					$tams[2] = $tamQnt;
				} elseif ($tam == 'G' or $tam == '8') {
					$tams[3] = $tamQnt;
				} elseif ($tam == 'GG' or $tam == '10') {
					$tams[4] = $tamQnt;
				} elseif ($tam == 'EG' or $tam == '12') {
					$tams[5] = $tamQnt;
				}
			}
			for ($i=1; $i <= 5; $i++) { 
				if(!isset($tams[$i])) {$tams[$i] = 0;}
			}
			$qrped[]= $ref.','.$cor.','.$tams[1].','.$tams[2].','.$tams[3].','.$tams[4].','.$tams[5].','.$preco.',0';
		}
	}
	$origem = 'catalogo';
	$pedido = $loc_pd;
	$dataentr = $dataentrega;
	
	
	require '../_auxiliares/addPedido_ped.php';
	$return .= 'Passou pelo addPedido_ped.php / NUMPED'.$pedido.'NUMPED';
	echo json_encode($return);
	
?>