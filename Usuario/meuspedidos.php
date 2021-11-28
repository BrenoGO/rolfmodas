<!DOCTYPE html>
<html>
<head>
    <?php require('../head.php') ?> 
    <title>Area do Usuário</title>
	<script src="_javascript/functionsgeral.js"></script>
	<script src="../_javascript/functions.js"></script>
	
	<script type="text/javascript"> //<![CDATA[ 
var tlJsHost = ((window.location.protocol == "https:") ? "https://secure.comodo.com/" : "http://www.trustlogo.com/");
document.write(unescape("%3Cscript src='" + tlJsHost + "trustlogo/javascript/trustlogo.js' type='text/javascript'%3E%3C/script%3E"));
//]]>
</script>
</head>
<body>
	<?php
	require '../config.php';
	require '../PCP/config.php';
	require '../header_geral.php'; 
	require 'menu.php';
	
	echo '<nav id="submenu">
	
	<a href="?acao=consultped"><button>Consultar situação dos Pedidos</button></a>
	</nav>
	</header>
	<div class="corpo">
	
	';	
	
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
	if(!isset($_GET['acao'])){
		header("Location:?acao=consultped");
		
	}else{
		if($_GET['acao'] == 'consultped'){

			echo '<div id="faturados" onclick="ShowFat()" style="background-color: #f2f2f2; cursor: pointer; clear: both; border: 1px solid black; float: left; margin-left: 17%; border-radius: 5px; ">
				<span style="text-alignt: center; padding: 0px 30px 0px 5px;">FATURADOS
				</span>
				<span id="+1" style="float: right; font-size: 15px; padding: 1px;">+</span>
			</div>

	  		<table id="TableFat" style="display: none; border:none; margin-left: 40px; float: left;">
					<tr>
						<td>Pedido</td>
						<td>Situação</td>
						<td>Valor</td>
						
					</tr>';
					$stmt=$dbh->prepare("select pedido, situacao, sum(tot), preco, desconto, data_entrega from pcp join pedidos p on pcp.loc=p.pedido where id_cliente=? group by p.pedido;");
					$stmt->execute(array($_SESSION['id_usuario']));
					//var_dump($_SESSION['id_usuario']);
					while($ln=$stmt->fetch(PDO::FETCH_ASSOC)){
						$array_ped=explode('-',$ln['pedido']);
						if(isset($array_ped[1])){
							$num_ped=number_format($array_ped[1],0,',','.');
						}else{
							$num_ped=$ln['pedido'];
						}						
						$preco = $ln['preco'];
						$desc = $ln['desconto'];
						$sumtot = $ln['sum(tot)'];
						$precoTotal = ($preco - $preco * ($desc / 100)) * $sumtot ;
						if($ln['situacao'] <> 'S'){
							/*$stmt=$dbh->prepare("select situacao,sum(tot) from pcp where loc=? group by situacao");
							$stmt->execute(array($num_ped)); */
							/*$ln2 = array();
							while($row =$stmt->fetch(PDO::FETCH_ASSOC)){
								$ln2[] = $row;
							}
							
							$tot=0;$P=0;$A=0;$S=0;$E=0;$C=0;$O=0;$D=0;
							foreach($ln2 as $ln3){
								if($ln3['situacao']=='P'){$P = $ln3['sum(tot)'];$tot += $P;}
								elseif($ln3['situacao']=='A'){$A = $ln3['sum(tot)'];$tot += $A;}
								elseif($ln3['situacao']=='C'){$C = $ln3['sum(tot)'];$tot += $C;}
								elseif($ln3['situacao']=='S'){$S = $ln3['sum(tot)'];$tot += $S;}
								elseif($ln3['situacao']=='O'){$O = $ln3['sum(tot)'];$tot += $O;}
								elseif($ln3['situacao']=='E'){$E = $ln3['sum(tot)'];$tot += $E;}
								elseif($ln3['situacao']=='D'){$D = $ln3['sum(tot)'];$tot += $D;}
							}
				
				
							$Sporc = $S/($tot - $E - $C)*100;*/
							$sit = $ln['situacao'];
							if($sit=='C'){$sit='Cancelado';}
							elseif($sit=='P'){$sit='Programado';}
							elseif($sit=='E'){$sit='Faturado';}
							elseif($sit=='O'){$sit='Consignado';}								
							echo '<tr>';
							echo '<td>'.$num_ped.'</td>';
							echo '<td>'.$sit.'</td>';
							echo '<td>R$'.number_format($precoTotal, 2, '.', ',').'</td>';
							/*echo '<td>'.$Sporc.'</td>';*/
							echo '</tr>';
						}
	
					}

			echo '</table>
			<div id="nfaturados" onclick="ShowNFat()" style="background-color: #f2f2f2; cursor: pointer; border: 1px solid black; float: left; margin-left: 17%; border-radius: 5px;">
			<span style="text-alignt: center; padding: 0px 30px 0px 5px;">NÃO FATURADOS
			</span>
					<span id="+2" style="float: right; font-size: 15px; padding: 1px;">+</span>
			</div>
			<table id="TableNFat" style="display: none; border:none; margin-left: 40px;">
					<tr>
						<td>Pedido</td>
						<td>Situação</td>
						<td>Valor</td>
						<td>Completo</td>
					</tr>';
					/*$stmt=$dbh->prepare("select pedido, situacao, sum(tot), preco, desconto, data_entrega from pcp join pedidos p on pcp.loc=p.pedido where id_cliente=? group by p.pedido;");
					$stmt->execute(array($_SESSION['id_usuario']));
					while($ln=$stmt->fetch(PDO::FETCH_ASSOC)){
						//$ln2[] = $ln;
						$tot=0;$P=0;$A=0;$S=0;$E=0;$C=0;$O=0;$D=0;
						foreach($ln as $ln3){
							if($ln3['situacao']=='P'){$P = $ln3['sum(tot)'];$tot += $P;}
							elseif($ln3['situacao']=='A'){$A = $ln3['sum(tot)'];$tot += $A;}
							elseif($ln3['situacao']=='C'){$C = $ln3['sum(tot)'];$tot += $C;}
							elseif($ln3['situacao']=='S'){$S = $ln3['sum(tot)'];$tot += $S;}
							elseif($ln3['situacao']=='O'){$O = $ln3['sum(tot)'];$tot += $O;}
							elseif($ln3['situacao']=='E'){$E = $ln3['sum(tot)'];$tot += $E;}
							elseif($ln3['situacao']=='D'){$D = $ln3['sum(tot)'];$tot += $D;}
						}
				
				
						$Sporc = $S/($tot - $E - $C)*100;
						$array_ped=explode('-',$ln['pedido']);
						if(isset($array_ped[1])){
							$num_ped=number_format($array_ped[1],0,',','.');
						}else{
							$num_ped=$ln['pedido'];
						}
						$preco = $ln['preco'];
						$desc = $ln['desconto'];
						$precoTotal = $preco - $preco * ($desc / 100);
						if($ln['situacao'] == 'S' ){ 
							$sitSO = $ln['situacao'];
							/*if($sitSO == 'O'){$sitSO = 'Consignado';}
							elseif($sitSO =='S'){$sitSO = 'Separado';}
							echo '<tr>';
							echo '<td>'.$num_ped.'</td>';
							echo '<td>'.$sitSO.'</td>';
							echo '<td>R$'.number_format($precoTotal, 2, '.', ',').'</td>';
							echo '<td>'.$Sporc.'%</td>';
							echo '</tr>';
						}
					}*/
					$stmt=$dbh->prepare("select pedido, situacao, sum(tot), preco, desconto, data_entrega from pcp join pedidos p on pcp.loc=p.pedido where id_cliente=? group by p.pedido;");
					$stmt->execute(array($_SESSION['id_usuario']));
					//var_dump($_SESSION['id_usuario']);
					while($ln=$stmt->fetch(PDO::FETCH_ASSOC)){
						$array_ped=explode('-',$ln['pedido']);
						if(isset($array_ped[1])){
							$num_ped=number_format($array_ped[1],0,',','.');
						}else{
							$num_ped=$ln['pedido'];
						}
						$preco = $ln['preco'];
						$desc = $ln['desconto'];
						$situacao = $ln['situacao'];
						$sumtot = $ln['sum(tot)'];
						$precoTotal = ($preco - $preco * ($desc / 100)) * $sumtot ;
						
						$ln2 = array();
						$ln2[] = $ln;
						$tot=0;$P=0;$A=0;$S=0;$E=0;$C=0;$O=0;$D=0;
						foreach($ln2 as $ln3){
							if($ln3['situacao']=='P'){$P = $ln3['sum(tot)'];$tot += $P;}
							elseif($ln3['situacao']=='A'){$A = $ln3['sum(tot)'];$tot += $A;}
							elseif($ln3['situacao']=='C'){$C = $ln3['sum(tot)'];$tot += $C;}
							elseif($ln3['situacao']=='S'){$S = $ln3['sum(tot)'];$tot += $S;}
							elseif($ln3['situacao']=='O'){$O = $ln3['sum(tot)'];$tot += $O;}
							elseif($ln3['situacao']=='E'){$E = $ln3['sum(tot)'];$tot += $E;}
							elseif($ln3['situacao']=='D'){$D = $ln3['sum(tot)'];$tot += $D;}
						}
						if($situacao == 'S'){
							
							
				
				
							$Sporc = $S/($tot - $E - $C)*100;
							$sit = $ln['situacao'];
							if($sit=='S'){$sit='Separado';}						
							echo '<tr>';
							echo '<td>'.$num_ped.'</td>';
							echo '<td>'.$sit.'</td>';
							echo '<td>R$'.number_format($precoTotal, 2, '.', ',').'</td>';
							echo '<td>'.$Sporc.'%</td>';
							echo '</tr>';
						}
	
					}
			echo '</table>';
		}	
		if($_GET['acao']=='realizarpag'){
			$pedido=$_GET['pedido'];
			$action='?acao=efetivarpag&pedido='.$pedido.'';
			$boolemoutroforn=false;
			require("../_auxiliares/pagamento.php");
			
		}
		if($_GET['acao']=='efetivarpag'){
			if($_POST['tipo_pagamento']=='pagseguro'){
				$pedido=$_GET['pedido'];
				
				$stmt=$dbh->prepare("select sum( (100-desconto)/100*preco*tot )as sum from pcp where loc=? and situacao='S';");
				$stmt->execute(array($pedido));
				$ln2=$stmt->fetch(PDO::FETCH_ASSOC);
				
				$stmt=$dbh->prepare("select frete,desconto_pedido,codigo_pagseguro from pedidos where pedido=?");
				$stmt->execute(array($pedido));
				$ln3=$stmt->fetch(PDO::FETCH_ASSOC);
				
				$valor_pagamento=$ln2['sum']+$ln3['frete']-$ln3['desconto_pedido'];
				
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
				$codigo_pagseguro=$ln3['codigo_pagseguro'];
				if($codigo_pagseguro == null){
					$codigo_pagseguro='';
				}
				require("../pagseguro/checkout.php");
			}
		}
	}
	echo '</div>';	
	require '../footer.php';
?>	
 
<script language="JavaScript" type="text/javascript">
TrustLogo("http://rolfmodas.com.br/_imagens/comodo_secure_seal_100x85_transp.png", "CL1", "none");
</script>
<a  href="https://www.positivessl.com/" id="comodoTL">Positive SSL</a>
</body>
</html>
 