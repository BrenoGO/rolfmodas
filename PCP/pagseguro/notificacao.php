<?php
	header("access-control-allow-origin: https://pagseguro.uol.com.br");
	require_once("PagSeguro.class.php");
	require '../_config/conection.php'; 
	$dbh = new PDO('mysql:host='.$host.';dbname='.$db.';charset=utf8',$user,$pass);
	require '../PCP/functions.php'; 
	
	if(isset($_POST['notificationType']) && $_POST['notificationType'] == 'transaction'){
		$PagSeguro = new PagSeguro();
		$response = $PagSeguro->executeNotification($_POST);
		$status=$response->status;
		
		//atualizar banco de dados que recebeu notificacao do pagseguro...TEMPORARIO, PRA TESTAR
		$nota='Notificação recebida pelo pagseguro';
		$codigo_pagseguro=$response->code;
		$pedido=$response->reference;
		$valor=$response->netamount;
		$stmt=$dbh->prepare("insert into dadosgerais (nome_dado,dado_1,dado_2) values (?,?,?)");
		$stmt->execute(array($nota,$codigo_pagseguro,$pedido));

		//atualizar o pedido
		$stmt=$dbh->prepare("update pedidos set status_pagamento=?,codigo_pagseguro=? where pedido=?");
		$stmt->execute(array($status,$codigo_pagseguro,$pedido));

		//atualizar faturamentos, bonus, talvez receber..
		if($status == 3 || $status == 4){
			//Pagamento Realizado
			$stmt=$dbh->prepare("update pcp set situacao=? where situacao=? and loc=?");
			$stmt->execute(array('E','S',$pedido));
			require('../_auxiliares/faturamento.php');
			
			
		}
		
		*/	
	}
	
	/*
	if(isset($_GET['transaction_id'])){
		require("../pagseguro/PagSeguro.class.php");
		$PagSeguro= new PagSeguro();
		
		$pagamento=$PagSeguro->getStatusByReference($_GET['pedido']);
		$pagamento->codigo_pagseguro=$_GET['transaction_id'];
		$transaction_id=$_GET['transaction_id'];
		$status = $pagamento->status;
		$stmt=$dbh->prepare("update pedidos set status_pagamento=?,codigo_pagseguro=?");
		$stmt->execute(array($status,$transaction_id));
		if($status==3 || $status==4){
			$tipo='Venda MMN';
			echo 'Pagamento realizado';
			$stmt=$dbh->prepare("insert into faturamentos (id_fat,num_docs,id_usuario,data_fat,valor,tipo,dataalter) values
								(default,?,?,?,?,?,default)");
			$stmt->execute(array($transaction_id,$_SESSION['id_usuario'],date('Y-m-d'),$valor,$tipo))	;
			//$stmt=$dbh->
		}
		
	}
	*/
	/*
	if(isset($_POST['notificationType']) && $_POST['notificationType'] == 'transaction'){
		$PagSeguro = new PagSeguro();
		$response = $PagSeguro->executeNotification($_POST);
		if( $response->status==3 || $response->status==4 ){
        	//PAGAMENTO CONFIRMADO
			//ATUALIZAR O STATUS NO BANCO DE DADOS
			
		}else{
			//PAGAMENTO PENDENTE
			echo $PagSeguro->getStatusText($PagSeguro->status);
		}
	}
	*/
?>