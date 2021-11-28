
<!DOCTYPE html>
<html>
<head>
    <?php
	require '../head.php';
	?>
    <title>Concluir pedido</title>
	<script src="../_javascript/functions.js"></script>
	<script src="_javascript.js"></script>
	<script type="text/javascript"> //<![CDATA[ 
var tlJsHost = ((window.location.protocol == "https:") ? "https://secure.comodo.com/" : "http://www.trustlogo.com/");
document.write(unescape("%3Cscript src='" + tlJsHost + "trustlogo/javascript/trustlogo.js' type='text/javascript'%3E%3C/script%3E"));
//]]>
</script>

<?php

echo'
</head>
<body>';
	session_start();
	require '../_config/conection.php'; 
	$con = conection();
	$dbh = new PDO('mysql:host='.$host.';dbname='.$db.';charset=utf8',$user,$pass);	
	header("access-control-allow-origin: https://pagseguro.uol.com.br");
	require '../header_geral.php';
	
	
	require '../PCP/functions.php';
	
	
	
	if(!isset($_SESSION['checkout'])){
		$_SESSION['checkout']=array();
		$_SESSION['checkout']['conc_et']=	array(1=>false,
											   	  2=>false,
												  3=>false,
												  4=>false,
												  5=>false);
	}
	if(isset($_GET['total_carrinho'])){
		$_SESSION['checkout']['conc_et'][3]=false;
		$_SESSION['checkout']['conc_et'][4]=false;
		$_SESSION['checkout']['conc_et'][5]=false;
		$_SESSION['checkout']['valor_carrinho']=$_GET['total_carrinho'];
	}
	if(isset($_GET['total_carrinho_ped'])){
		$_SESSION['checkout']['conc_et'][3]=false;
		$_SESSION['checkout']['conc_et'][4]=false;
		$_SESSION['checkout']['conc_et'][5]=false;
		$_SESSION['checkout']['valor_carrinho_ped']=$_GET['total_carrinho_ped'];
	}
	
	if( ($_SESSION['checkout']['conc_et'][4]==false) ){
		$stmt=$dbh->prepare("select bonus from usuarios where id_usuario=?");
		$stmt->execute(array($_SESSION['id_usuario']));
		$ln=$stmt->fetch(PDO::FETCH_ASSOC);
		$bonus=$ln['bonus'];
		if($bonus<=0){
			$_SESSION['checkout']['conc_et'][4]=true;
			$_SESSION['checkout']['uso_bonus']=0;
			$_SESSION['checkout']['uso_bonus_pe']=0;
			$_SESSION['checkout']['uso_bonus_ped']=0;
		}
		
	}
	
		
	
	if(isset($_POST['sub_et_1'])){
		//foi preenchido dados pessoais
		require '../_auxiliares/cadastpessoa.php';
		//if( $stmt->execute(array()) ){
			$_SESSION['checkout']['conc_et'][1]=true;
		//}else{
			//echo 'erro pra confirmar dados pessoais';
		//}	
	}
	if(isset($_POST['sub_et_2'])){
		//foi preenchido Entrega
		if($_POST['enderecos']=='cadast'){
			//utilizar end cadastrado
			$stmt=$dbh->prepare("select cidade,estado,CEP,logradouro,num,complemento,bairro from usuarios where id_usuario=?");
			$stmt->execute(array($_SESSION['id_usuario']));
			$ln_cadast=$stmt->fetch(PDO::FETCH_ASSOC);
			//$_SESSION['checkout']['endereco']['checked']='cadast';
			$_SESSION['checkout']['endereco']['cidade']=$ln_cadast['cidade'];
			$_SESSION['checkout']['endereco']['estado']=$ln_cadast['estado'];
			$_SESSION['checkout']['endereco']['logradouro']=$ln_cadast['logradouro'];
			$_SESSION['checkout']['endereco']['num']=$ln_cadast['num'];
			$_SESSION['checkout']['endereco']['bairro']=$ln_cadast['bairro'];
			$_SESSION['checkout']['endereco']['CEP']=$ln_cadast['CEP'];
			$_SESSION['checkout']['endereco']['complemento']=$ln_cadast['complemento'];
			$_SESSION['checkout']['conc_et'][3]=false;
		}elseif($_POST['enderecos']=='novo'){
			//cadastrar endereco novo..
			$_SESSION['checkout']['endereco']['cidade']=$cidade=$_POST['cidade'];
			$_SESSION['checkout']['endereco']['estado']=$estado=$_POST['estado'];
			$_SESSION['checkout']['endereco']['logradouro']=$logradouro=$_POST['logradouro'];
			$_SESSION['checkout']['endereco']['num']=$num=$_POST['num'];
			$_SESSION['checkout']['endereco']['bairro']=$bairro=$_POST['bairro'];
			$_SESSION['checkout']['endereco']['CEP']=$CEP=$_POST['CEP'];
			$_SESSION['checkout']['endereco']['complemento']=$complemento=$_POST['complemento'];
			
			$stmt=$dbh->prepare("insert into enderecos_entrega (id_end_entrega,id_usuario,CEP,cidade,estado,logradouro,num,complemento,bairro) 
			values (default,?,?,?,?,?,?,?,?)");
			$stmt->execute(array($_SESSION['id_usuario'],$CEP,$cidade,$estado,$logradouro,$num,$complemento,$bairro));
			$stmt=$dbh->prepare("select max(id_end_entrega) from enderecos_entrega where id_usuario=?");
			$stmt->execute(array($_SESSION['id_usuario']));
			$ln=$stmt->fetch(PDO::FETCH_ASSOC);
			$_SESSION['checkout']['endereco']['checked']=$ln['max(id_end_entrega)'];
			$_SESSION['checkout']['conc_et'][3]=false;
		}elseif($_POST['enderecos']=='fabrica'){
			$_SESSION['checkout']['endereco']['CEP']='rolf';
			$_SESSION['checkout']['frete']['valor']=0;
			$_SESSION['checkout']['frete']['tipo']='Fábrica';
			$_SESSION['checkout']['endereco']['checked']='rolf';
			$_SESSION['checkout']['conc_et'][3]=true;
			echo '<script>window.location.href="?etapa=4"</script>';
		}else{
			//endereco é algum da tabela enderecos_entrega
			$id_end_entrega=$_POST['enderecos'];
			$stmt=$dbh->prepare("select * from enderecos_entrega where id_end_entrega=?");
			$stmt->execute(array($_POST['enderecos']));
			$ln=$stmt->fetch(PDO::FETCH_ASSOC);
			$_SESSION['checkout']['endereco']['cidade']=$ln['cidade'];
			$_SESSION['checkout']['endereco']['estado']=$ln['estado'];
			$_SESSION['checkout']['endereco']['logradouro']=$ln['logradouro'];
			$_SESSION['checkout']['endereco']['num']=$ln['num'];
			$_SESSION['checkout']['endereco']['bairro']=$ln['bairro'];
			$_SESSION['checkout']['endereco']['CEP']=$ln['CEP'];
			$_SESSION['checkout']['endereco']['complemento']=$ln['complemento'];
			
			$_SESSION['checkout']['endereco']['checked']=$id_end_entrega;
			$_SESSION['checkout']['conc_et'][3]=false;
		}
		if(isset($_POST['junto_separado'])){
			if(isset($_SESSION['checkout']['junto_separado'])){
				if($_SESSION['checkout']['junto_separado'] <> $_POST['junto_separado']){
					$_SESSION['checkout']['junto_separado']=$_POST['junto_separado'];
					$_SESSION['checkout']['conc_et'][3]=false;
					$_SESSION['checkout']['conc_et'][4]=false;
					$_SESSION['checkout']['conc_et'][5]=false;
					if($_SESSION['checkout']['junto_separado']=='junto'){
						if(isset($_SESSION['checkout']['frete_pe'])){
							unset($_SESSION['checkout']['frete_pe']);
						}
						if(isset($_SESSION['checkout']['frete_ped'])){
							unset($_SESSION['checkout']['frete_ped']);
						}
						if(isset($_SESSION['checkout']['uso_bonus_pe'])){
							unset($_SESSION['checkout']['uso_bonus_pe']);
						}
						if(isset($_SESSION['checkout']['uso_bonus_ped'])){
							unset($_SESSION['checkout']['uso_bonus_ped']);
						}
						if(isset($_SESSION['checkout']['forma_pag_pe'])){
							unset($_SESSION['checkout']['forma_pag_pe']);
						}
						if(isset($_SESSION['checkout']['forma_pag_ped'])){
							unset($_SESSION['checkout']['forma_pag_ped']);
						}
						if(isset($_SESSION['checkout']['valor_pag_pe'])){
							unset($_SESSION['checkout']['valor_pag_pe']);
						}
						if(isset($_SESSION['checkout']['valor_pag_ped'])){
							unset($_SESSION['checkout']['valor_pag_ped']);
						}
					}else{
						if(isset($_SESSION['checkout']['frete_junto'])){
							unset($_SESSION['checkout']['frete_junto']);
						}
						if(isset($_SESSION['checkout']['valor_pag'])){
							unset($_SESSION['checkout']['valor_pag']);
						}
						if(isset($_SESSION['checkout']['uso_bonus'])){
							unset($_SESSION['checkout']['uso_bonus']);
						}
						if(isset($_SESSION['checkout']['forma_pag'])){
							unset($_SESSION['checkout']['forma_pag']);
						}
					}
				}
			}else{
				$_SESSION['checkout']['junto_separado']=$_POST['junto_separado'];
			}
		}else{
			$_SESSION['checkout']['junto_separado']='separado';
		}
		$_SESSION['checkout']['conc_et'][2]=true;
	
	//fim da etapa 2(entrega)
	}
	if(isset($_POST['sub_et_3'])){
		//frete
		if(isset($_POST['frete_junto'])){
			$array_frete=explode('/',$_POST['frete_junto']);
			$_SESSION['checkout']['frete_junto']['valor']=$array_frete[0];
			$_SESSION['checkout']['frete_junto']['tipo']=$array_frete[1];
			$_SESSION['checkout']['conc_et'][3]=true;
		}else{
			if(isset($_SESSION['checkout']['frete_junto'])){
				unset($_SESSION['checkout']['frete_junto']);
			}
		}
		if(isset($_POST['frete_pe'])){
			$array_frete=explode('/',$_POST['frete_pe']);
			$_SESSION['checkout']['frete_pe']['valor']=$array_frete[0];
			$_SESSION['checkout']['frete_pe']['tipo']=$array_frete[1];
			$_SESSION['checkout']['conc_et'][3]=true;
		}else{
			if(isset($_SESSION['checkout']['frete_pe'])){
				unset($_SESSION['checkout']['frete_pe']);
			}
		}
		if(isset($_POST['frete_ped'])){
			$array_frete=explode('/',$_POST['frete_ped']);
			$_SESSION['checkout']['frete_ped']['valor']=$array_frete[0];
			$_SESSION['checkout']['frete_ped']['tipo']=$array_frete[1];
			$_SESSION['checkout']['conc_et'][3]=true;
		}else{
			if(isset($_SESSION['checkout']['frete_ped'])){
				unset($_SESSION['checkout']['frete_ped']);
			}
		}
		
		if($_SESSION['checkout']['conc_et'][4]){
			echo '<script>window.location.href="?etapa=5"</script>';
		}
	}
	if(isset($_POST['sub_et_4'])){
		//bonus
		if(isset($_POST['uso_bonus'])){
			$bonus=$_POST['uso_bonus'];
			$bonus=str_replace('.','',$bonus);
			$bonus=str_replace(',','.',$bonus);
			if($bonus>$_SESSION['max_bonus']){
				echo '<script>alert("Bonus maior que permitido!");window.location.href="?etapa=4";</script>';
				die();
			}else{
				$_SESSION['checkout']['uso_bonus']=$bonus;
			}
			
		}elseif( (isset($_POST['uso_bonus_pe'])) or (isset($_POST['uso_bonus_ped'])) ){
			if(isset($_SESSION['checkout']['uso_bonus'])){unset($_SESSION['checkout']['uso_bonus']);}
			if( (isset($_POST['uso_bonus_pe'])) and (isset($_POST['uso_bonus_ped'])) ){
				$bonus_pe=$_POST['uso_bonus_pe'];
				$bonus_pe=str_replace('.','',$bonus_pe);
				$bonus_pe=str_replace(',','.',$bonus_pe);
				$bonus_ped=$_POST['uso_bonus_ped'];
				$bonus_ped=str_replace('.','',$bonus_ped);
				$bonus_ped=str_replace(',','.',$bonus_ped);
				$soma_bonus=$bonus_pe+$bonus_ped;
				if($soma_bonus>$_SESSION['max_bonus']){
					echo '<script>alert("Bonus maior que permitido!");window.location.href="?etapa=4";</script>';
					die();
				}
			}
			if(isset($_POST['uso_bonus_pe'])){
				$bonus_pe=$_POST['uso_bonus_pe'];
				$bonus_pe=str_replace('.','',$bonus_pe);
				$bonus_pe=str_replace(',','.',$bonus_pe);
				$_SESSION['checkout']['uso_bonus_pe']=$bonus_pe;
			}
			if(isset($_POST['uso_bonus_ped'])){
				$bonus_ped=$_POST['uso_bonus_ped'];
				$bonus_ped=str_replace('.','',$bonus_ped);
				$bonus_ped=str_replace(',','.',$bonus_ped);
				$_SESSION['checkout']['uso_bonus_ped']=$bonus_ped;
			}
		}else{
			$_SESSION['checkout']['uso_bonus']=0;
		}
		$_SESSION['checkout']['conc_et'][4]=true;
		
	//	}else{
	//		echo 'erro pra confirmar dados pessoais';
	//	}	
	}
	if(isset($_POST['sub_et_5'])){
		//Pagamento
		if($_SESSION['checkout']['junto_separado']=='junto'){
			$_SESSION['checkout']['forma_pag']=$_POST['tipo_pagamento'];
			$_SESSION['checkout']['valor_pag']=$_SESSION['checkout']['valor_carrinho']+$_SESSION['checkout']['valor_carrinho_ped']+$_SESSION['checkout']['frete_junto']['valor']-$_SESSION['checkout']['uso_bonus'];
		}else{
			if(isset($_SESSION['checkout']['forma_pag'])){
				unset($_SESSION['checkout']['forma_pag']);
			}
			if(isset($_SESSION['checkout']['valor_pag'])){
				unset($_SESSION['checkout']['valor_pag']);
			}
			if(isset($_POST['tipo_pagamento_pe'])){
				$_SESSION['checkout']['forma_pag_pe']=$_POST['tipo_pagamento_pe'];
				$_SESSION['checkout']['valor_pag_pe']=$_SESSION['checkout']['valor_carrinho']+$_SESSION['checkout']['frete_pe']['valor']-$_SESSION['checkout']['uso_bonus_pe'];
			}
			if(isset($_POST['tipo_pagamento_ped'])){
				$_SESSION['checkout']['forma_pag_ped']=$_POST['tipo_pagamento_ped'];
				$_SESSION['checkout']['valor_pag_ped']=$_SESSION['checkout']['valor_carrinho_ped']+$_SESSION['checkout']['frete_ped']['valor']-$_SESSION['checkout']['uso_bonus_ped'];
			}
		}
		$_SESSION['checkout']['conc_et'][5]=true;
	}
	echo '<br></br>';
	
	echo '</br></br></br>';
	//var_dump($_SESSION['checkout']);
	
	
	if(isset($_GET['fim_CO'])){
		if($_SESSION['checkout']['junto_separado']=='junto'){
			
			//juntar o pedidope no pedido_ped
			$refs=array_keys($_SESSION['pedidope']);
			foreach($refs as $ref){
				if(!isset($_SESSION['pedido_ped'][$ref])){
					$cores=array_keys($_SESSION['pedidope'][$ref]);
					foreach($cores as $cor){
						for($i=1;$i<=5;$i++){
							$_SESSION['pedido_ped'][$ref][$cor]['t'.$i] = $_SESSION['pedidope'][$ref][$cor]['t'.$i];
						}
					}
				}else{
					$cores=array_keys($_SESSION['pedidope'][$ref]);
					foreach($cores as $cor){
						if(!isset($_SESSION['pedido_ped'][$ref][$cor])){
							for($i=1;$i<=5;$i++){
								$_SESSION['pedido_ped'][$ref][$cor]['t'.$i] = $_SESSION['pedidope'][$ref][$cor]['t'.$i];
							}
						}else{
							for($i=1;$i<=5;$i++){
								$_SESSION['pedido_ped'][$ref][$cor]['t'.$i] += $_SESSION['pedidope'][$ref][$cor]['t'.$i];
							}
						}
					}
				}
			}
			unset($_SESSION['pedidope']);
			//fim do juntar pedidope no pedido_ped
			
			$valor_pagamento=$_SESSION['checkout']['valor_pag'];
			$stmt=$dbh->prepare("select max(pedido) from pedidos where pedido like 'PD%'");
			$stmt->execute();
			$ln=$stmt->fetch(PDO::FETCH_ASSOC);
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
			$status_pagamento=0;
			$frete=$_SESSION['checkout']['frete_junto']['valor'];	
			$tipo_frete=$_SESSION['checkout']['frete_junto']['tipo'];
			$bonus_utilizado=$_SESSION['checkout']['uso_bonus'];
			$obs='Pagamento: '.$_SESSION['checkout']['forma_pag'];
			
			$stmt=$dbh->prepare("select dado_1 from dadosgerais where nome_dado='Tempo pra faturar pedido'");
			$stmt->execute();
			$tempo_p_faturar=$stmt->fetch(PDO::FETCH_ASSOC);
			$tempo_p_faturar='+'.($tempo_p_faturar['dado_1']-3).' days';
			$dataentr=date('Y-m-d',strtotime($tempo_p_faturar,strtotime($dataped)));
			
			$stmt=$dbh->prepare("insert into pedidos (pedido,id_cliente,dataped,dataentrega,frete,tipo_frete,status_pagamento,bonus_utilizado,obs) values(?,?,?,?,?,?,?,?,?)");
			if($stmt->execute(array($loc_pd,$_SESSION['id_usuario'],date('Y-m-d'),$dataentr,$frete,$tipo_frete,$status_pagamento,$bonus_utilizado,$obs))){
				
			}else{
				$arr=$stmt->errorInfo();
				print_r($arr);
				echo 'Erro ao cadastrar pedido. Favor entrar em contato com suporte.';
			}
			if($bonus_utilizado>0){
				$stmt=$dbh->prepare("select bonus from usuarios where id_usuario=?");
				$stmt->execute(array($_SESSION['id_usuario']));
				$ln=$stmt->fetch(PDO::FETCH_ASSOC);
				$bonus_usuario=$ln['bonus'];
				$new_bonus=$bonus_usuario-$bonus_utilizado;
				$stmt=$dbh->prepare("update usuarios set bonus =? where id_usuario=?");
				$stmt->execute(array($new_bonus,$_SESSION['id_usuario']));
			}
			
			$origem='catalogo';
			$pedido=$loc_pd;
			$loc=$loc_pd;
			$tipodopedido='pedido_ped';
			require("../_auxiliares/emailAddPedidoPE.php");
			require("../_auxiliares/addPedido_ped.php");			
			
		
		}else{
			if(isset($_SESSION['pedidope'])){
				$stmt=$dbh->prepare("select max(loc) from pcp where loc like 'PE%'");
				$stmt->execute();
				$ln=$stmt->fetch(PDO::FETCH_ASSOC);
				
				$numpe=intval(substr($ln['max(loc)'],2,5));
				$numpenovo=$numpe+1;
				if($numpenovo<10){
					$loc_pe='PE000'.$numpenovo;
				}elseif($numpenovo<100){
					$loc_pe='PE00'.$numpenovo;
				}elseif($numpenovo<1000){
					$loc_pe='PE0'.$numpenovo;
				}elseif($numpenovo<10000){
					$loc_pe='PE'.$numpenovo;
				}
				$dataped=date('Y-m-d');
				$dataentr=$dataped;
				
				$status_pagamento=0;
				$frete=$_SESSION['checkout']['frete_pe']['valor'];	
				$tipo_frete=$_SESSION['checkout']['frete_pe']['tipo'];
				$bonus_utilizado=$_SESSION['checkout']['uso_bonus_pe'];
				$obs='Pagamento: '.$_SESSION['checkout']['forma_pag_pe'];
				
				$stmt=$dbh->prepare("insert into pedidos (pedido,id_cliente,dataped,dataentrega,frete,tipo_frete,status_pagamento,bonus_utilizado,obs) values(?,?,?,?,?,?,?,?,?)");
				if($stmt->execute(array($loc_pe,$_SESSION['id_usuario'],$dataped,$dataentr,$frete,$tipo_frete,$status_pagamento,$bonus_utilizado,$obs))){
					
				}else{
					$arr=$stmt->errorInfo();
					print_r($arr);
					echo 'Erro ao cadastrar pedido. Favor entrar em contato com suporte.';
				}
				if($bonus_utilizado>0){
					$stmt=$dbh->prepare("select bonus from usuarios where id_usuario=?");
					$stmt->execute(array($_SESSION['id_usuario']));
					$ln=$stmt->fetch(PDO::FETCH_ASSOC);
					$bonus_usuario=$ln['bonus'];
					$new_bonus=$bonus_usuario-$bonus_utilizado;
					$stmt=$dbh->prepare("update usuarios set bonus =? where id_usuario=?");
					$stmt->execute(array($new_bonus,$_SESSION['id_usuario']));
				}
				
				$tipodopedido='pedidope';
				$loc=$loc_pe;
				require("../_auxiliares/emailAddPedidoPE.php");
				require("../_auxiliares/addPedidoPE.php");
				unset($_SESSION['pedidope']);
				
			}
			if(isset($_SESSION['pedido_ped'])){

				$stmt=$dbh->prepare("select max(pedido) from pedidos where pedido like 'PD%'");
				$stmt->execute();
				$ln=$stmt->fetch(PDO::FETCH_ASSOC);
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
				$status_pagamento=0;
				$frete=$_SESSION['checkout']['frete_ped']['valor'];	
				$tipo_frete=$_SESSION['checkout']['frete_ped']['tipo'];
				$bonus_utilizado=$_SESSION['checkout']['uso_bonus_ped'];
				$obs='Pagamento: '.$_SESSION['checkout']['forma_pag_ped'];
				
				$dataped=date('Y-m-d');
				$dataentr=date('Y-m-d',strtotime('+25 days',strtotime($dataped)));
				
				$stmt=$dbh->prepare("insert into pedidos (pedido,id_cliente,dataped,dataentrega,frete,tipo_frete,status_pagamento,bonus_utilizado,obs) values(?,?,?,?,?,?,?,?,?)");
				if($stmt->execute(array($loc_pd,$_SESSION['id_usuario'],$dataped,$dataentr,$frete,$tipo_frete,$status_pagamento,$bonus_utilizado,$obs))){
					
				}else{
					$arr=$stmt->errorInfo();
					print_r($arr);
					echo 'Erro ao cadastrar pedido. Favor entrar em contato com suporte.';
				}
				if($bonus_utilizado>0){
					$stmt=$dbh->prepare("select bonus from usuarios where id_usuario=?");
					$stmt->execute(array($_SESSION['id_usuario']));
					$ln=$stmt->fetch(PDO::FETCH_ASSOC);
					$bonus_usuario=$ln['bonus'];
					$new_bonus=$bonus_usuario-$bonus_utilizado;
					$stmt=$dbh->prepare("update usuarios set bonus =? where id_usuario=?");
					$stmt->execute(array($new_bonus,$_SESSION['id_usuario']));
				}
				$loc=$loc_pd;
				$tipodopedido='pedido_ped';
				$origem='catalogo';
				$pedido=$loc_pd;
				require("../_auxiliares/emailAddPedidoPE.php");	
				require("../_auxiliares/addPedido_ped.php");
				
				
			}
		}
		if(isset($_SESSION['checkout']['forma_pag_pe'])){
			if($_SESSION['checkout']['forma_pag_pe']=='pagseguro'){
				$pedido=$loc_pe;
				$stmt=$dbh->prepare("select razaosocial,contato,email,logradouro,num,CEP,bairro,cidade,estado from usuarios where id_usuario=?");
				$stmt->execute(array($_SESSION['id_usuario']));
				$ln=$stmt->fetch(PDO::FETCH_ASSOC);
					
				$rsocial = $ln['razaosocial'];
				$email = $ln['email'];
				$contato = $ln['contato'];
				$xLgr = $ln['logradouro'];
				$nro = $ln['num'];
				$bairro = $ln['bairro'];
				$cidade = $ln['cidade'];
				$estado = $ln['estado'];
				$CEP = $ln['CEP'];
				$codigo_pagseguro='';
				
				$valor_pagamento=$_SESSION['checkout']['valor_pag_pe'];
				
				unset($_SESSION['checkout']);
				require("../pagseguro/checkout.php");
				
			}else{
				
				//echo '<script>window.location.href="../Usuario/meuspedidos.php"</script>';
				//die();
			}
		}
		unset($_SESSION['checkout']);
		echo '<script>window.location.href="../index.php"</script>';
	}
	if(isset($_SESSION['checkout'])){
		echo '<div>
		<nav id="menu_checkout">	
		<ul type="disc">
			<li><a href="?etapa=1"><div class="';
			if($_SESSION['checkout']['conc_et'][1]){
				echo ' id="1-CO"><img id="checked" src="../_imagens/checked.png"/>';
			}elseif(isset($_GET['etapa'])){
				if($_GET['etapa']==1){
					echo 'item_menu_CO_ativo" id="1-CO">1';
				}else{
					echo 'item_menu_CO" id="1-CO">1';
				}
			}else{
				echo 'item_menu_CO" id="1-CO">1';
			}
			echo '</div><div class="text-CO">Dados Pessoais</div></a></li>
			<li><a href="?etapa=2"><div class="';
			if($_SESSION['checkout']['conc_et'][2]){
				echo ' id="2-CO"><img id="checked" src="../_imagens/checked.png"/>';
			}elseif(isset($_GET['etapa'])){
				if($_GET['etapa']==2){
					echo 'item_menu_CO_ativo" id="2-CO">2';
				}else{
					echo 'item_menu_CO" id="2-CO">2';
				}
			}else{
				echo 'item_menu_CO" id="2-CO">2';
			}
			echo '</div><div class="text-CO">Entrega</div></a></li>
			<li><a href="?etapa=3"><div class="';
			if($_SESSION['checkout']['conc_et'][3]){
				echo ' id="3-CO"><img id="checked" src="../_imagens/checked.png"/>';
			}elseif(isset($_GET['etapa'])){
				if($_GET['etapa']==3){
					echo 'item_menu_CO_ativo" id="3-CO">3';
				}else{
					echo 'item_menu_CO" id="3-CO">3';
				}
			}else{
				echo 'item_menu_CO" id="3CO">3';
			}
			echo '</div><div class="text-CO">Frete</div></a></li>
			<li><a href="?etapa=4"><div class="';
			if($_SESSION['checkout']['conc_et'][4]){
				echo ' id="4-CO"><img id="checked" src="../_imagens/checked.png"/>';
			}elseif(isset($_GET['etapa'])){
				if($_GET['etapa']==4){
					echo 'item_menu_CO_ativo" id="4-CO">4';
				}else{
					echo 'item_menu_CO" id="4-CO">4';
				}
			}else{
				echo 'item_menu_CO" id="4-CO">4';
			}
			echo '</div><div class="text-CO">Bônus</div></a></li>
			<li><a href="?etapa=5"><div class="';
			if($_SESSION['checkout']['conc_et'][5]){
				echo ' id="5-CO"><img id="checked" src="../_imagens/checked.png"/>';
			}elseif(isset($_GET['etapa'])){
				if($_GET['etapa']==5){
					echo 'item_menu_CO_ativo" id="5-CO">5';
				}else{
					echo 'item_menu_CO" id="5-CO">5';
				}
			}else{
				echo 'item_menu_CO" id="5-CO">5';
			}
			echo '</div><div class="text-CO">Pagamento</div></a></li>';
			
		echo '	
		</ul>
		</nav>
		</div>';
	}
	echo '</header>';
	
	echo '<div class="corpo">
	
	<div id="area_forms_checkout">';
	
	
	if(!isset($_GET['etapa'])){
		
	}else{
		if($_GET['etapa']==1){
			echo '<b>Confira seus dados pessoais:</b>
			<form method="post" action="?etapa=2">
			<input type="hidden" name="sub_et_1" value="sub_1"/>';
			$boolemoutroforn=true;
			$boolnovocliente=false;
			$boolcadastsenha=false;
			$id_usuario=$_SESSION['id_usuario'];
			require '../_auxiliares/formcadastpessoa.php';
			
			echo '<input type="submit" value="Confirmar Dados Pessoais"/>
			</form>';
		}
		if($_GET['etapa']==2){
			$stmt=$dbh->prepare("select cidade,estado,CEP,logradouro,num,complemento,bairro from usuarios where id_usuario=?");
			$stmt->execute(array($_SESSION['id_usuario']));
			$ln_cadast=$stmt->fetch(PDO::FETCH_ASSOC);
			$stmt=$dbh->prepare("select id_end_entrega,cidade,estado,CEP,logradouro,num,complemento,bairro from enderecos_entrega where id_usuario=?");
			$stmt->execute(array($_SESSION['id_usuario']));
			while($ln_ends[]=$stmt->fetch(PDO::FETCH_ASSOC));
			$num_ends=count($ln_ends);
			//var_dump($ln_ends);
			echo '<form method="post" action="?etapa=3">';
			if( (isset($_SESSION['pedidope'])) and (isset($_SESSION['pedido_ped'])) ){
				echo '<b>Você possui pedidos de Pronta Entrega e também pedido com prazo. Favor escolher uma das opções abaixo:</b>
				</br><input type="radio" name="junto_separado" id="separado" value="separado"';
				if(isset($_SESSION['checkout']['junto_separado'])){
					if($_SESSION['checkout']['junto_separado']=='separado'){echo ' checked';}
				}else{
					echo ' checked';
				}
				echo '/><label for="separado">Enviar Pedido de Pronta Entrega no próximo dia útil e o Pedido com prazo quando ficar pronto.</label></br>
				<input type="radio" name="junto_separado" id="junto" value="junto"';
				if(isset($_SESSION['checkout']['junto_separado'])){
					if($_SESSION['checkout']['junto_separado']=='junto'){echo ' checked';}
				}
				echo '/><label for="junto">Enviar ambos os pedidos juntos quando ficarem completos.</label></br></br>';
			}
			echo '<b>Confirme seu endereço de entrega: </b>
			
			<input type="hidden" name="sub_et_2" value="sub_2"/>
			<label for="end_cadast">Utilizar endereço cadastrado: </label>
			<input type="radio" checked name="enderecos" id="end_cadast" value="cadast" onclick="displaynone_novo_end()"/><label for="end_cadast">
			'.$ln_cadast['logradouro'].', '.$ln_cadast['num'].'. '.$ln_cadast['cidade'].'</label></br>
			<label for="fabrica">Retirar na fábrica (Rio Pomba/MG)</label>
			<input type="radio"';
			if(isset($_SESSION['checkout']['endereco']['checked'])){
				if($_SESSION['checkout']['endereco']['checked'] == 'rolf' ){echo ' checked ';}
			}
			echo 'name="enderecos" id="fabrica" value="fabrica" onclick="displaynone_novo_end()"/></br>';
			if($num_ends>0){
				//var_dump($num_ends);
				if( ($num_ends==2) and ($ln_ends[0] !== false) ){
					echo '<label for="outro_end_1">Outro Endereço: </label>
					<input type="radio"';
				if(isset($_SESSION['checkout']['endereco']['checked'])){
					if($_SESSION['checkout']['endereco']['checked'] == $ln_ends[0]['id_end_entrega'] ){echo ' checked ';}
				}
					echo 'name="enderecos" id="outro_end_1" value="'.$ln_ends[0]['id_end_entrega'].'" onclick="displaynone_novo_end()"/>
					<label for="outro_end_1">
					'.$ln_ends[0]['logradouro'].', '.$ln_ends[0]['num'].'. '.$ln_ends[0]['cidade'].'</label></br>';
				}elseif($num_ends>2){
					echo 'Outros Endereços: </br>';
					foreach($ln_ends as $endereco){
						if($endereco !== false){
							echo '<input type="radio"';
							if(isset($_SESSION['checkout']['endereco']['checked'])){
								if($_SESSION['checkout']['endereco']['checked'] == $endereco['id_end_entrega'] ){echo ' checked ';}
							}
							echo 'name="enderecos" onclick="displaynone_novo_end()" id="outro_end_'.$endereco['id_end_entrega'].'" value="'.$endereco['id_end_entrega'].'"/><label for="outro_end_'.$endereco['id_end_entrega'].'">
							'.$endereco['logradouro'].', '.$endereco['num'].'. '.$endereco['cidade'].'</label></br>';
						}
					}
				}
			}
			echo '
			<label for="novo">Cadastrar novo endereço: </label><input type="radio" name="enderecos" onclick="form_endereco()" id="novo" value="novo"/></br>
			<div id="form_end_novo" style="display:none"></div>
			<input type="submit" value="Confirmar Entrega"/>
			</form>';
			
		}
		if($_GET['etapa']==3){
			if(!isset($_SESSION['checkout']['junto_separado'])){
				$_SESSION['checkout']['junto_separado']='separado';
			}
			if(isset($_SESSION['checkout']['endereco']['CEP'])){
				echo '<form method="post" action="?etapa=4">
				<input type="hidden" name="sub_et_3" value="sub_3"/>';
				if($_SESSION['checkout']['endereco']['CEP']=='rolf'){
					echo '<b>Frete: </b></br>
					<select name="frete" id="frete")">
					<option value="0.00/Combinado">Retirar na fábrica</option>
					</select></form>';
				}else{
					if($_SESSION['checkout']['junto_separado']=='junto'){
						echo '<b>Frete dos pedidos juntos: </b></br>
						<select name="frete_junto" id="frete_junto")">';
						$somadospedidos=$_SESSION['checkout']['valor_carrinho']+$_SESSION['checkout']['valor_carrinho_ped'];
						if($somadospedidos>=300){
							echo '
							<option value="0.00/Grátis">Frete Grátis!</option>
							';
						}
						$boollogisticarolf=false;
						$cidadeUF=$_SESSION['checkout']['endereco']['cidade'].'/'.$_SESSION['checkout']['endereco']['estado'];
						$stmt=$dbh->prepare("select dado_1 from dadosgerais where nome_dado=? and dado_1 like ?");
						$stmt->execute(array('Logística Rolf','%'.$cidadeUF.'%'));

						$boollogisticarolf=( (($stmt->rowCount()) > 0) and ($cidadeUF<>'/'));
						if($boollogisticarolf){
							echo '<option value="5.00/Logística Rolf">Logística Rolf: R$ 5,00 - até 10 dias úteis</option>
							';
						}
						if($somadospedidos <= 100){
							$data['nVlComprimento'] = '28';
							$data['nVlAltura'] = '4';
							$data['nVlLargura'] = '26';
							$data['nVlPeso'] = '1';
						}else{
							$data['nVlComprimento'] = '41';
							$data['nVlAltura'] = '17';
							$data['nVlLargura'] = '26';
							$data['nVlPeso'] = '4';
						}
									
									
						$data['nVlValorDeclarado'] = number_format($_SESSION['checkout']['valor_carrinho'],0);
						if($data['nVlValorDeclarado'] <= 20){
							$data['nVlValorDeclarado']=20;
						}
								
						
						$data['sCepDestino'] = $_SESSION['checkout']['endereco']['CEP'];
						require("../_auxiliares/frete.php");
							
						foreach($frete as $frete_servico){
							//frete estava dando muito caro, diminui um pouco por conta da rolf..
							$frete_servico['valor']=round((0.7*$frete_servico['valor']),1);
							echo '<option value="'.$frete_servico['valor'].'/'.$frete_servico['nomeServico'].'">'.$frete_servico['nomeServico'].': R$ '.number_format($frete_servico['valor'],2,',','.').' - até '.($frete_servico['PrazoEntrega']+1).' dias úteis</option>';
						}

						//var_dump($result);
						if(!isset($frete)){		
							echo '</br>Não foi possível calcular seu frete com o CEP cadastrado. Vá em "Minha conta -> Meus dados" e atualize seu endereço.';
						}
						echo '</select></br>';
					}else{
						if(isset($_SESSION['checkout']['valor_carrinho'])){
							echo '<b>Frete da Pronta Entrega: </b></br>
							<select name="frete_pe" id="frete_pe")">';
							if($_SESSION['checkout']['valor_carrinho']>=300){
								echo '
								<option value="0.00/Grátis">Frete Grátis!</option>
								';
							}
							$boollogisticarolf=false;
							$cidadeUF=$_SESSION['checkout']['endereco']['cidade'].'/'.$_SESSION['checkout']['endereco']['estado'];
							$stmt=$dbh->prepare("select dado_1 from dadosgerais where nome_dado=? and dado_1 like ?");
							$stmt->execute(array('Logística Rolf','%'.$cidadeUF.'%'));

							$boollogisticarolf=( (($stmt->rowCount()) > 0) and ($cidadeUF<>'/'));
							if($boollogisticarolf){
								echo '<option value="5.00/Logística Rolf">Logística Rolf: R$ 5,00 - até 10 dias úteis</option>
								';
							}
							if($_SESSION['checkout']['valor_carrinho'] <= 100){
								$data['nVlComprimento'] = '28';
								$data['nVlAltura'] = '4';
								$data['nVlLargura'] = '26';
								$data['nVlPeso'] = '1';
							}else{
								$data['nVlComprimento'] = '41';
								$data['nVlAltura'] = '17';
								$data['nVlLargura'] = '26';
								$data['nVlPeso'] = '4';
							}
										
										
							$data['nVlValorDeclarado'] = number_format($_SESSION['checkout']['valor_carrinho'],0);
							if($data['nVlValorDeclarado'] <= 20){
								$data['nVlValorDeclarado']=20;
							}
									
							
							$data['sCepDestino'] = $_SESSION['checkout']['endereco']['CEP'];
							require("../_auxiliares/frete.php");
								
							foreach($frete as $frete_servico){
								//frete estava dando muito caro, diminui um pouco por conta da rolf..
								$frete_servico['valor']=round((0.7*$frete_servico['valor']),1);
								echo '<option value="'.$frete_servico['valor'].'/'.$frete_servico['nomeServico'].'">'.$frete_servico['nomeServico'].': R$ '.number_format($frete_servico['valor'],2,',','.').' - até '.($frete_servico['PrazoEntrega']+1).' dias úteis</option>';
							}
									
							
							

							//var_dump($result);
							if(!isset($frete)){		
								echo '</br>Não foi possível calcular seu frete com o CEP cadastrado. Vá em "Minha conta -> Meus dados" e atualize seu endereço.';
							}
							echo '</select></br>';
						}
						if(isset($_SESSION['checkout']['valor_carrinho_ped'])){
							echo '<b>Frete do Pedido com prazo: </b></br>
							<select name="frete_ped" id="frete_ped")">';
							if($_SESSION['checkout']['valor_carrinho_ped']>=300){
								echo '
								<option value="0.00/Grátis">Frete Grátis!</option>
								';
							}
							$boollogisticarolf=false;
							$cidadeUF=$_SESSION['checkout']['endereco']['cidade'].'/'.$_SESSION['checkout']['endereco']['estado'];
							$stmt=$dbh->prepare("select dado_1 from dadosgerais where nome_dado=? and dado_1 like ?");
							$stmt->execute(array('Logística Rolf','%'.$cidadeUF.'%'));

							$boollogisticarolf=( (($stmt->rowCount()) > 0) and ($cidadeUF<>'/'));
							if($boollogisticarolf){
								echo '<option value="5.00/Logística Rolf">Logística Rolf: R$ 5,00 - até 10 dias úteis</option>
								';
							}
							if($_SESSION['checkout']['valor_carrinho_ped'] <= 100){
								$data['nVlComprimento'] = '28';
								$data['nVlAltura'] = '4';
								$data['nVlLargura'] = '26';
								$data['nVlPeso'] = '1';
							}else{
								$data['nVlComprimento'] = '41';
								$data['nVlAltura'] = '17';
								$data['nVlLargura'] = '26';
								$data['nVlPeso'] = '4';
							}
										
										
							$data['nVlValorDeclarado'] = number_format($_SESSION['checkout']['valor_carrinho'],0);
							if($data['nVlValorDeclarado'] <= 20){
								$data['nVlValorDeclarado']=20;
							}
									
							
							$data['sCepDestino'] = $_SESSION['checkout']['endereco']['CEP'];
							require("../_auxiliares/frete.php");
								
							foreach($frete as $frete_servico){
								//frete estava dando muito caro, diminui um pouco por conta da rolf..
								$frete_servico['valor']=round((0.7*$frete_servico['valor']),1);
								echo '<option value="'.$frete_servico['valor'].'/'.$frete_servico['nomeServico'].'">'.$frete_servico['nomeServico'].': R$ '.number_format($frete_servico['valor'],2,',','.').' - até '.($frete_servico['PrazoEntrega']+1).' dias úteis</option>';
							}
									
							
							

							//var_dump($result);
							if(!isset($frete)){
								echo '</br>Não foi possível calcular seu frete com o CEP cadastrado. Vá em "Minha conta -> Meus dados" e atualize seu endereço.';
							}
							echo '</select></br>';
						}
					}
				}
					
				
				echo '<input type="submit" value="Escolher Frete"/>
				</form>';
			}else{
				echo 'Favor confirmar endereço de entrega na etapa 2 antes de escolher o frete.';
			}
			
		}
		if($_GET['etapa']==4){
			if($_SESSION['checkout']['junto_separado']=='junto'){
				if(isset($_SESSION['checkout']['frete_junto'])){
					$valor_pedido=$_SESSION['checkout']['frete_junto']['valor']+$_SESSION['checkout']['valor_carrinho']+$_SESSION['checkout']['valor_carrinho_ped'];
				}else{
					$valor_pedido=$_SESSION['checkout']['valor_carrinho']+$_SESSION['checkout']['valor_carrinho_ped'];
				}
				echo '<b>Bônus</b></br>
				<form method="post" action="?etapa=5">';
				
				$stmt=$dbh->prepare("select bonus from usuarios where id_usuario=?");
				$stmt->execute(array($_SESSION['id_usuario']));
				$ln=$stmt->fetch(PDO::FETCH_ASSOC);
				$bonus=$ln['bonus'];
				$_SESSION['checkout']['max_bonus']=$bonus;
				$stmt=$dbh->prepare("select sum(valor_bonus) as bonus_inativo from bonus where data_efetivado is null and id_recebedor=?");
				$stmt->execute(array($_SESSION['id_usuario']));
				$ln=$stmt->fetch(PDO::FETCH_ASSOC);
				$bonus_inativo=$ln['bonus_inativo'];
				if($bonus<=0){
					echo 'Você não possui bônus. Não deixe de indicar a Rolf Modas e receber comissão em bônus!</br>
					';
					if($bonus_inativo>0){
						'Você possui R$ '.number_format($bonus_inativo,2,',','.').' de bônus inativos.';
					}
					
				}else{
					echo 'Total do pedido: '.number_format($valor_pedido,2,',','.').'</br>';
					if(!isset($_SESSION['checkout']['uso_bonus'])){
						if($bonus>($_SESSION['checkout']['valor_carrinho']+$_SESSION['checkout']['frete_junto']['valor'])){
							$thisvalue=$_SESSION['checkout']['valor_carrinho']+$_SESSION['checkout']['valor_carrinho_ped']+$_SESSION['checkout']['frete_junto']['valor'];
						}else{
							$thisvalue=$bonus;
						}
					}else{
						$thisvalue=$_SESSION['checkout']['uso_bonus'];
					}
					
					echo '<label for="uso_bonus">Utilizar Bônus: </label>
					R$<input type="text" size="8" name="uso_bonus" id="uso_bonus" onkeyup="tot_bonus(this.value,'.$valor_pedido.')" value="'.number_format($thisvalue,2,',','.').'"/>
					&nbsp&nbsp Total disponível de Bônus: R$ '.number_format($bonus,2,',','.').'</br>
					</br>Restante a pagar: R$<input type="text" id="rest_pag" size="8" value="'.number_format($valor_pedido-$thisvalue,2,',','.').'" readonly/>';
					
				}
				echo '
				<input type="hidden" name="sub_et_4" value="sub_4"/>
				<input type="submit" value="Confirmar"/>
				</form>';
			}else{
				echo '<form method="post" action="?etapa=5">';
				if(isset($_SESSION['pedidope'])){
					if(isset($_SESSION['checkout']['frete_pe'])){
						$valor_pedido=$_SESSION['checkout']['frete_pe']['valor']+$_SESSION['checkout']['valor_carrinho'];
					}else{
						$valor_pedido=$_SESSION['checkout']['valor_carrinho'];
					}
					echo '<b>Bônus para Pronta Entrega</b></br>';
					
					
					$stmt=$dbh->prepare("select bonus from usuarios where id_usuario=?");
					$stmt->execute(array($_SESSION['id_usuario']));
					$ln=$stmt->fetch(PDO::FETCH_ASSOC);
					$bonus=$ln['bonus'];
					$_SESSION['max_bonus']=$bonus;
					$stmt=$dbh->prepare("select sum(valor_bonus) as bonus_inativo from bonus where data_efetivado is null and id_recebedor=?");
					$stmt->execute(array($_SESSION['id_usuario']));
					$ln=$stmt->fetch(PDO::FETCH_ASSOC);
					$bonus_inativo=$ln['bonus_inativo'];
					if($bonus<=0){
						echo 'Você não possui bônus. Não deixe de indicar a Rolf Modas e receber comissão em bônus!</br>
						';
						if($bonus_inativo>0){
							'Você possui R$ '.number_format($bonus_inativo,2,',','.').' de bônus inativos. Ou seja, se efetuar compras acima de R$100,00 reais neste mês este valor cairá na sua conta virtual da Rolf.';
						}
						
					}else{
						echo 'Total do pedido de Pronta Entrega: '.number_format($valor_pedido,2,',','.').'</br>';
						if(!isset($_SESSION['checkout']['uso_bonus_pe'])){
							if($bonus>($_SESSION['checkout']['valor_carrinho']+$_SESSION['checkout']['frete_pe']['valor'])){
								$thisvalue=$_SESSION['checkout']['valor_carrinho']+$_SESSION['checkout']['frete_pe']['valor'];
							}else{
								$thisvalue=$bonus;
							}
						}else{
							$thisvalue=$_SESSION['checkout']['uso_bonus_pe'];
						}
						
						echo '<label for="uso_bonus_pe">Utilizar Bônus: </label>
						R$<input type="text" size="8" name="uso_bonus_pe" id="uso_bonus_pe" onkeyup="tot_bonus_pe(this.value,'.$valor_pedido.')" value="'.number_format($thisvalue,2,',','.').'"/>
						&nbsp&nbsp Total disponível de Bônus: R$ '.number_format($bonus,2,',','.').'</br>
						</br>Restante a pagar: R$<input type="text" id="rest_pag_pe" size="8" value="'.number_format($valor_pedido-$thisvalue,2,',','.').'" readonly/>';
						
					}
				}
				if(isset($_SESSION['pedido_ped'])){
					if(isset($_SESSION['checkout']['frete_ped'])){
						$valor_pedido=$_SESSION['checkout']['frete_ped']['valor']+$_SESSION['checkout']['valor_carrinho_ped'];
					}else{
						$valor_pedido=$_SESSION['checkout']['valor_carrinho_ped'];
					}
					if(isset($_SESSION['pedidope'])){
						echo '</br></br>';
					}	
					echo '<b>Bônus para Pronta com prazo</b></br>';
					
					
					$stmt=$dbh->prepare("select bonus from usuarios where id_usuario=?");
					$stmt->execute(array($_SESSION['id_usuario']));
					$ln=$stmt->fetch(PDO::FETCH_ASSOC);
					$bonus=$ln['bonus'];
					$_SESSION['max_bonus']=$bonus;
					$stmt=$dbh->prepare("select sum(valor_bonus) as bonus_inativo from bonus where data_efetivado is null and id_recebedor=?");
					$stmt->execute(array($_SESSION['id_usuario']));
					$ln=$stmt->fetch(PDO::FETCH_ASSOC);
					$bonus_inativo=$ln['bonus_inativo'];
					if($bonus<=0){
						echo 'Você não possui bônus. Não deixe de indicar a Rolf Modas e receber comissão em bônus!</br>
						';
						if($bonus_inativo>0){
							'Você possui R$ '.number_format($bonus_inativo,2,',','.').' de bônus inativos. Ou seja, se efetuar compras acima de R$100,00 reais neste mês este valor cairá na sua conta virtual da Rolf.';
						}
						
					}else{
						echo 'Total do pedido com prazo: '.number_format($valor_pedido,2,',','.').'</br>';
						if(!isset($_SESSION['checkout']['uso_bonus_ped'])){
							$thisvalue=0;
						}else{
							$thisvalue=$_SESSION['checkout']['uso_bonus_ped'];
						}
						
						echo '<label for="uso_bonus_ped">Utilizar Bônus: </label>
						R$<input type="text" size="8" name="uso_bonus_ped" id="uso_bonus_ped" onkeyup="tot_bonus_ped(this.value,'.$valor_pedido.')" value="'.number_format($thisvalue,2,',','.').'"/>
						&nbsp&nbsp Total disponível de Bônus: R$ '.number_format($bonus,2,',','.').'</br>
						</br>Restante a pagar: R$<input type="text" id="rest_pag_ped" size="8" value="'.number_format($valor_pedido-$thisvalue,2,',','.').'" readonly/>';
						
					}
				}
				
				echo '
				</br></br>
				<input type="hidden" name="sub_et_4" value="sub_4"/>
				<input type="submit" value="Confirmar"/>
				</form>';
			}
			
			
		}
		if($_GET['etapa']==5){
			echo '<form method="post" action="?finalizar">
			<input type="hidden" name="sub_et_5" value="sub_5"/>';
			if(($_SESSION['checkout']['conc_et'][1] and $_SESSION['checkout']['conc_et'][2]
			and $_SESSION['checkout']['conc_et'][3] and $_SESSION['checkout']['conc_et'][4])){
				if( (isset($_SESSION['pedidope'])) and (isset($_SESSION['pedido_ped'])) and ($_SESSION['checkout']['junto_separado']=='junto') ){
					$valor_pag=$_SESSION['checkout']['valor_carrinho']+$_SESSION['checkout']['valor_carrinho_ped']+$_SESSION['checkout']['frete_junto']['valor']-$_SESSION['checkout']['uso_bonus'];
					
						if($valor_pag>0){
							if(isset($_SESSION['checkout']['forma_pag'])){
								if($_SESSION['checkout']['forma_pag']=='deposito'){
									$dep=true;
									$din=false;
									$boleto=false;
								}elseif($_SESSION['checkout']['forma_pag']=='dinheiro'){
									$din=true;
									$dep=false;
									$boleto=false;
								}elseif($_SESSION['checkout']['forma_pag']=='boleto'){
									$din=false;
									$dep=false;
									$boleto=true;
								}else{
									$dep=false;
									$din=false;
									$boleto=false;
								}
							}else{
								$dep=true;
								$din=false;
								$boleto=false;
							}	
							echo '
							<b>Valor do Pagamento: R$ '.number_format($valor_pag,2,',','.').'</b></br></br>
							<input type="radio" id="tipo_pag_pagseguro" name="tipo_pagamento" value="pagseguro" onclick="check_tipo_pag(1,0,0,0)" checked><label for="tipo_pag_pagseguro">Pag Seguro</label>
							<input type="radio" id="tipo_pag_deposito" name="tipo_pagamento" value="deposito"';
							if($dep){echo ' checked ';}
							echo ' onclick="check_tipo_pag(0,1,0,0)"><label for="tipo_pag_deposito">Depósito/Transferência</label>
							<input type="radio" id="tipo_pag_dinheiro" name="tipo_pagamento" value="dinheiro"';
							if($din){echo ' checked ';}
							echo 'onclick="check_tipo_pag(0,0,1,0)"><label for="tipo_pag_dinheiro">Dinheiro (válido apenas para retiradas na fábrica)</label>';
							if(strpos($_SESSION['acesso'],'Cliente') !== false){
								echo '<input type="radio" id="tipo_pag_boleto" name="tipo_pagamento" value="boleto"';
								if($boleto){echo ' checked ';}
								echo 'onclick="check_tipo_pag(0,0,0,1)"><label for="tipo_pag_boleto">Boleto </label>';
							}
							
								
							echo '
							</br></br>
							<div id="pagamento_pagseguro" ';if($dep or $din or $boleto){echo 'style="display:none"';}
							echo '>
							Quando o pedido estiver pronto entraremos em contato para que seja efetuado o pagamento pelo PagSeguro';

							echo '
							</div>
							<div id="pagamento_deposito" ';
							if($dep){ echo '';}else{echo 'style="display:none"';}
							echo '>
							<h3>Pagamento por depósito ou transferência em conta:</h3>

							Rolf Modas Ltda</br>
							Caixa Econômica Federal</br>
							Agência: 1123</br>
							Conta: 1501-2</br>
							Operação: 003
							</div>

							<div id="pagamento_dinheiro" ';
							if($din){ echo '';}else{echo 'style="display:none"';}
							echo '>
							<h3>Pagamento em mãos na fábrica com dinheiro</h3>
							Praça Dr. Último de Carvalho, 02</br>
							Centro</br>
							Rio Pomba / MG</br>
							Telefone: (32)3571-1010
							</div>
							
							<div id="pagamento_boleto" ';
							
							if($boleto){ echo '';}else{echo 'style="display:none"';}
							echo '>
							<h3>Pagamento por Boleto Bancário</h3>
							Os boletos serão emitidos e enviados juntos da mercadoria quando o pedido estiver pronto.
							</div>
							</br>';
						}else{
							echo 'Parabéns! Com a utilização do seu bonus essa compra vai sair de graça!';
						}
					
				}else{
					if(isset($_SESSION['pedidope'])){
						echo '<div class="underline"><b>Pagamento do Pedido de Pronta Entrega</b></br></br>';
						$valor_pag=$_SESSION['checkout']['valor_carrinho']+$_SESSION['checkout']['frete_pe']['valor']-$_SESSION['checkout']['uso_bonus_pe'];
						
						if($valor_pag>0){
							if(isset($_SESSION['checkout']['forma_pag_pe'])){
								if($_SESSION['checkout']['forma_pag_pe']=='deposito'){
									$dep=true;
									$din=false;
									$boleto=false;
								}elseif($_SESSION['checkout']['forma_pag_pe']=='dinheiro'){
									$din=true;
									$dep=false;
									$boleto=false;
								}elseif($_SESSION['checkout']['forma_pag_pe']=='boleto'){
									$din=false;
									$dep=false;
									$boleto=true;
								}else{
									$dep=false;
									$din=false;
									$boleto=false;
								}
							}else{
								$dep=false;
								$din=false;
								$boleto=false;
							}	
							echo '
							<b>Valor do Pagamento: R$ '.number_format($valor_pag,2,',','.').'</b></br></br>
							<input type="radio" id="tipo_pag_pagseguro_pe" name="tipo_pagamento_pe" value="pagseguro" onclick="check_tipo_pag_pe(1,0,0,0)" checked><label for="tipo_pag_pagseguro_pe">Pag Seguro</label>
							<input type="radio" id="tipo_pag_deposito_pe" name="tipo_pagamento_pe" value="deposito"';
							if($dep){echo ' checked ';}
							echo ' onclick="check_tipo_pag_pe(0,1,0,0)"><label for="tipo_pag_deposito_pe">Depósito/Transferência</label>
							<input type="radio" id="tipo_pag_dinheiro_pe" name="tipo_pagamento_pe" value="dinheiro"';
							if($din){echo ' checked ';}
							echo 'onclick="check_tipo_pag_pe(0,0,1,0)"><label for="tipo_pag_dinheiro_pe">Dinheiro (válido apenas para retiradas na fábrica)</label>';
							if(strpos($_SESSION['acesso'],'Cliente') !== false){
								echo '<input type="radio" id="tipo_pag_boleto_pe" name="tipo_pagamento_pe" value="boleto"';
								if($boleto){echo ' checked ';}
								echo 'onclick="check_tipo_pag_pe(0,0,0,1)"><label for="tipo_pag_boleto_pe">Boleto </label>';
							}
								
									
							echo '
							</br></br>
							<div id="pagamento_pagseguro_pe" ';if($dep or $din or $boleto){echo 'style="display:none"';}
							echo '>
							Após "Finalizar Check-Out" você será redirecionado para realizar seu pagamento no PagSeguro.';

							echo '
							</div>
							<div id="pagamento_deposito_pe" ';
							if($dep){ echo '';}else{echo 'style="display:none"';}
							echo '>
							<h3>Pagamento por depósito ou transferência em conta:</h3>
							Rolf Modas Ltda</br>
							Caixa Econômica Federal</br>
							Agência: 1123</br>
							Conta: 1501-2</br>
							Operação: 003
							</div>
							<div id="pagamento_dinheiro_pe" ';
							if($din){ echo '';}else{echo 'style="display:none"';}
							echo '>
							<h3>Pagamento em mãos na fábrica com dinheiro</h3>
							Praça Dr. Último de Carvalho, 02</br>
							Centro</br>
							Rio Pomba / MG</br>
							Telefone: (32)3571-1010
							</div>';
							
							if(strpos($_SESSION['acesso'],'Cliente') !== false){
								echo '<div id="pagamento_boleto_pe" ';
								if($boleto){ echo '';}else{echo 'style="display:none"';}
								echo '>
								<h3>Pagamento por Boleto Bancário</h3>
								Os boletos serão enviados juntos da mercadoria.
								</div>';
							}
							echo '</br>';
						}else{
							echo 'Parabéns! Com a utilização do seu bonus essa compra vai sair de graça!';
						}
						echo '</div>';	
					}
					if(isset($_SESSION['pedido_ped'])){
						echo '<div class="underline"><b>Pagamento do Pedido com Prazo</b></br></br>';
						$valor_pag=$_SESSION['checkout']['valor_carrinho_ped']+$_SESSION['checkout']['frete_ped']['valor']-$_SESSION['checkout']['uso_bonus_ped'];
						
						if($valor_pag>0){
							if(isset($_SESSION['checkout']['forma_pag_ped'])){
								if($_SESSION['checkout']['forma_pag_ped']=='deposito'){
									$dep=true;
									$din=false;
									$boleto=false;
								}elseif($_SESSION['checkout']['forma_pag_ped']=='dinheiro'){
									$din=true;
									$dep=false;
									$boleto=false;
								}elseif($_SESSION['checkout']['forma_pag_ped']=='boleto'){
									$din=false;
									$dep=false;
									$boleto=true;
								}else{
									$dep=false;
									$din=false;
									$boleto=false;
								}
							}else{
								$dep=false;
								$din=false;
								$boleto=false;
							}	
							echo '
							<b>Valor do Pagamento: R$ '.number_format($valor_pag,2,',','.').'</b></br></br>
							<input type="radio" id="tipo_pag_pagseguro_ped" name="tipo_pagamento_ped" value="pagseguro" onclick="check_tipo_pag_ped(1,0,0,0)" checked><label for="tipo_pag_pagseguro_ped">Pag Seguro</label>
							<input type="radio" id="tipo_pag_deposito_ped" name="tipo_pagamento_ped" value="deposito"';
							if($dep){echo ' checked ';}
							echo ' onclick="check_tipo_pag_ped(0,1,0,0)"><label for="tipo_pag_deposito_ped">Depósito/Transferência</label>
							<input type="radio" id="tipo_pag_dinheiro_ped" name="tipo_pagamento_ped" value="dinheiro"';
							if($din){echo ' checked ';}
							echo 'onclick="check_tipo_pag_ped(0,0,1,0)"><label for="tipo_pag_dinheiro_ped">Dinheiro (válido apenas para retiradas na fábrica)</label>';
							if(strpos($_SESSION['acesso'],'Cliente') !== false){
								echo '<input type="radio" id="tipo_pag_boleto_ped" name="tipo_pagamento_ped" value="boleto"';
								if($boleto){echo ' checked ';}
								echo 'onclick="check_tipo_pag_ped(0,0,0,1)"><label for="tipo_pag_boleto_ped">Boleto </label>';
							}
								
									
							echo '
							</br></br>
							<div id="pagamento_pagseguro_ped" ';if($dep or $din or $boleto){echo 'style="display:none"';}
							echo '>
							Quando o pedido estiver pronto entraremos em contato para que seja efetuado o pagamento pelo PagSeguro';

							echo '
							</div>
							<div id="pagamento_deposito_ped" ';
							if($dep){ echo '';}else{echo 'style="display:none"';}
							echo '>
							<h3>Pagamento por depósito ou transferência em conta:</h3>
							Rolf Modas Ltda</br>
							Caixa Econômica Federal</br>
							Agência: 1123</br>
							Conta: 1501-2</br>
							Operação: 003
							</div>
							<div id="pagamento_dinheiro_ped" ';
							if($din){ echo '';}else{echo 'style="display:none"';}
							echo '>
							<h3>Pagamento em mãos na fábrica com dinheiro</h3>
							Praça Dr. Último de Carvalho, 02</br>
							Centro</br>
							Rio Pomba / MG</br>
							Telefone: (32)3571-1010
							</div>';
							
							if(strpos($_SESSION['acesso'],'Cliente') !== false){
								echo '<div id="pagamento_boleto_ped" ';
								if($boleto){ echo '';}else{echo 'style="display:none"';}
								echo '>
								<h3>Pagamento por Boleto Bancário</h3>
								Os boletos serão enviados juntos da mercadoria.
								</div>';
							}
							echo '</br>';
						}else{
							echo 'Parabéns! Com a utilização do seu bonus essa compra vai sair de graça!';
						}
						echo '</div>';
					}
				}
			}else{
				echo 'Você deve confirmar as etapas 3 e 4, sobre frete e bônus, para preencher os dados do pagamento.';
			}
			
			
			echo '
			<input type="submit" value="Confirmar Forma de Pagamento"/>
			</form>';
		}
	}
	echo '</div>';//final da DIV da área dos Forms..
	
	if(isset($_SESSION['checkout'])){
		if($_SESSION['checkout']['conc_et'][1] and $_SESSION['checkout']['conc_et'][2]
		and $_SESSION['checkout']['conc_et'][3] and $_SESSION['checkout']['conc_et'][4]
		and $_SESSION['checkout']['conc_et'][5]){
			$bool_ok_CO=true;
		}else{
			$bool_ok_CO=false;
		}
		if($bool_ok_CO){
			echo '
			<a href="?fim_CO">
			<div id="bot_fim_CO">
			
				Finalizar Check-Out
			
			</div></a>';
		}else{
			echo '<div id="bot_fim_CO_off">
			
				Finalizar Check-Out
			
			</div>';
		}
	}
	
	echo '</div>';
	require '../footer.php';
?>	
 
<script language="JavaScript" type="text/javascript">
TrustLogo("https://rolfmodas.com.br/_imagens/comodo_secure_seal_100x85_transp.png", "CL1", "none");
</script>
<a  href="https://www.positivessl.com/" id="comodoTL">Positive SSL</a>
</body>
</html>
 