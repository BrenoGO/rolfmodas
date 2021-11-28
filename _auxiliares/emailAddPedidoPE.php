<?php
//pra utilizar precisa:$loc e $tipodopedido e ter $_SESSION[$tipodopedido]
//PCP/functions.php tem que ter sido required antes de chamar o emailAddPedidoPE

$pedido_echo=pedido_echo($loc);

//Mandar e-mail
$message = '
	<html>
	<head>
	<title>Pedido Rolf</title>
	<style>
		table, tr, td, th {
		border: 1px solid #606060;
		border-spacing: 0px;
	}
	</style>
	
	</head>
	<body>
	<p>Confira seu pedido abaixo, qualquer modificação favor entrar em contato conosco';
	if(isset($booljuntar)){
		$message .= '</br><b>Novo pedido adicionado ao pedido '.$pedido_echo.'</b>';
	}
	$message .= '</p>
	<table>
		<thead>
			<tr>
				<th>Ref.</th>
				<th>Produto</th>
				<th>Cor</th>
				<th>P (ou 4)</th>
				<th>M (ou 6)</th>
				<th>G (ou 8)</th>
				<th>GG (ou 10)</th>
				<th>EG (ou 12)</th>
				<th>Qtd</th>
				<th>Preco</th>';
				$sumdescontos = 0;
				$refs=array_keys($_SESSION[$tipodopedido]);
				foreach($refs as $ref){
					if(isset($_SESSION[$tipodopedido][$ref]['desconto'])){
						$sumdescontos += $_SESSION[$tipodopedido][$ref]['desconto'];
					}
				}
				if($sumdescontos > 0){
					$message .= '<th>Desconto(%)</th>';
				}
				$message .= '<th>Subtotal</th>
				<th>Loc.</th>
			</tr>
		</thead>
		<tbody>';
			$totalgeral = 0;
			$refs=array_keys($_SESSION[$tipodopedido]);
			foreach($refs as $ref){
				$stmt=$dbh->prepare("select descricao,preco,loc_estoque from produtos where ref=?");
				$stmt->execute(array($ref));
				$ln=$stmt->fetch(PDO::FETCH_ASSOC);
				$loc_estoque=$ln['loc_estoque'];
				$desc=$ln['descricao'];
				$preco=round($ln['preco']*$_SESSION['markup'],1);
				$fator=1;
				if(isset($_SESSION[$tipodopedido][$ref]['desconto'])){
					$desconto = $_SESSION[$tipodopedido][$ref]['desconto'];
					$fator = 1-$desconto/100;
				}
				$cores=array_keys($_SESSION[$tipodopedido][$ref]);
				foreach($cores as $cor){
				if($cor <> 'desconto'){
					for($i=1;$i<=5;$i++){
						$t[$i]=$_SESSION[$tipodopedido][$ref][$cor]['t'.$i];
					}	
					$tot = array_sum($t);
					$subtotal = $tot * $preco * $fator;
					$totalgeral += $subtotal;
					$message .= '<tr>
						<td>'.$ref.'</td>
						<td>'.$desc.'</td>
						<td>'.$cor.'</td>
						<td>'.$t[1].'</td>
						<td>'.$t[2].'</td>
						<td>'.$t[3].'</td>
						<td>'.$t[4].'</td>
						<td>'.$t[5].'</td>
						<td>'.$tot.'</td>
						<td>'.number_format($preco,2,",",".").'</td>';
						if($sumdescontos <> 0){
							$message .= '<td>'.$desconto.'</td>';
						}
						$message .= '<td>'.number_format($subtotal,2,",",".").'</td>
						<td>'.$loc_estoque.'</td>
					</tr>';
				}
				}
			}
											
			$message .='	
	</tbody>
	</table>
	<p>O Total do seu pedido foi de R$ '.number_format($totalgeral,2,",",".").'</p>
	</body>
	</html>
';
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "From: Rolf Modas <rolf@rolfmodas.com.br>" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$to = '';
if(isset($_POST['emailcliente'])){$to .= $_POST['emailcliente'].", ";}
if(isset($_POST['emailusuario'])){$to .= $_POST['emailusuario'].", ";}
if(isset($_POST['emailrolf'])){$to .= $_POST['emailrolf'].", ";}
if(isset($_POST['emailnovo1'])){$to .= $_POST['emailnovo1'].", ";}
if(isset($_POST['emailnovo2'])){$to .= $_POST['emailnovo2'].", ";}
if(isset($_POST['emailnovo3'])){$to .= $_POST['emailnovo3'];}
if(isset($emailrolf)){$to .= $emailrolf.", ";}
//if(isset($emailcliente)){$to .= $emailcliente.", ";}
if(isset($emailusuario)){$to .= $emailusuario.", ";}

$subject = 'Pedido';
if($_SESSION['acesso']=='revendedor'){$subject .=' para REVENDEDOR da';}
$subject .=' Rolf: '.$pedido_echo;
mail($to,$subject,$message,$headers);
//fim do mandar email

