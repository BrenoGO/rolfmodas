
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
</head>
<body>

<?php

	session_start();
	require '../_config/conection.php'; 
	$con = conection();
	$dbh = new PDO('mysql:host='.$host.';dbname='.$db.';charset=utf8',$user,$pass);	
	header("access-control-allow-origin: https://pagseguro.uol.com.br");
	require '../header_geral.php';
	
	
	require 'functionsCC.php';
	
	
	
	if(!isset($_SESSION['checkout'])){
		$_SESSION['checkout']=array();
		$_SESSION['checkout']['conc_et']=	array(1=>true,
											   	  2=>true,
												  3=>true,
												  4=>true,
												  5=>false);
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
	
	
	
	$_SESSION['checkout']['valor_carrinho']=1000;
	$_SESSION['checkout']['valor_pag']=1000;
	$_SESSION['checkout']['forma_pag']='CC';
	$_SESSION['checkout']['num_doc']='1000';

	
	if(!isset($_GET['finalizar'])){
		if(!isset($_GET['etapa'])){
				echo '<script>window.location.href="?etapa=5"</script>';
		}else{
			
			
			if($_GET['etapa']==5){
				echo '<form method="post" action="?finalizar">
				<input type="hidden" name="sub_et_5" value="sub_5"/>';
				if(($_SESSION['checkout']['conc_et'][1] and $_SESSION['checkout']['conc_et'][2]
				and $_SESSION['checkout']['conc_et'][3] and $_SESSION['checkout']['conc_et'][4])){
					
				echo '
				<h3>Valor= R$ '.number_format($_SESSION['checkout']['valor_pag'],2,',','.').'</h3>
				
				<h2>Pagamento com Cartão de Crédito</h2>
				<label for="bandeira">Bandeira:</label>
				<select name="bandeira" id="bandeira">
				
				<option>Visa</option>
				<option value="Master">Master Card</option>
				<option value="Amex">American Express</option>
				<option>Elo</option>
				<option>Aura</option>
				<option>JCB</option>
				<option value="Diners">Diners Club</option>
				<option>Discover</option>
				<option>Hipercard</option>
				
				</select>
				</br>
				<label for="name">Nome: (igual consta no cartão) </label><input type="text" name="name" id="name"/></br>
				<label for="number">Número do cartão: </label><input type="text" name="number" id="number" /></br>
				<label for="code">Código de Segurança: </label><input type="text" name="code" id="code" size="3" /></br>
				Data de Validade do Cartão: <label for="mes">Mês(mm):</label><input type="text" name="mes" id="mes" size="1" />
				<label for="ano">Ano(AAAA):</label><input type="text" name="ano" id="ano" size="3" /></br></br>
				';
				echo '
				<input type="submit" value="Confirmar Forma de Pagamento"/>
				</form>';
				
				}else{
					echo 'Você deve confirmar as etapas 3 e 4, sobre frete e bônus, para preencher os dados do pagamento.';
				}
				
			}
		}
	}else{
		//está após finalizar.. 
	
		//dados e configurações (tem que dado os "use no inicio da página")
		$nCartao=$_POST['number'];
		$name=$_POST['name'];
		$code=$_POST['code'];
		$mes=$_POST['mes'];
		$ano=$_POST['ano'];
		$valor=$_SESSION['checkout']['valor_pag'];
		$num_doc=$_SESSION['checkout']['num_doc'];
		$bandeira=$_POST['bandeira'];
		
		$url='https://apisandbox.cieloecommerce.cielo.com.br/1/sales/';
		$request_headers = array();
		$request_headers[] = 'Content-Type: application/json';
		$request_headers[] = 'MerchantId: 4d670825-2286-47c0-959a-56132d31f323';
		$request_headers[] = 'MerchantKey: QUWBZOFGFZYAKKGJTHHOEZLTBBCHWMMQZPXCRURJ';
		$data=array();
		$data['MerchantOrderId']='1492';
		$data['Customer']['Name']=$name;
		$data['Payment']['Type']='CreditCard';
		$data['Payment']['Amount']=1000;
		$data['Payment']['Installments']=1;
		$data['Payment']['SoftDescriptor']='Rolf Modas';
		$data['Payment']['CreditCard']['CardNumber']=$nCartao;
		$data['Payment']['CreditCard']['Holder']=$name;
		$data['Payment']['CreditCard']['ExpirationDate']=$mes.'/'.$ano;
		$data['Payment']['CreditCard']['SecurityCode']=$code;
		$data['Payment']['CreditCard']['Brand']=$bandeira;
	
		$data=json_encode($data);
		
		//var_dump($data);
		
		$curl = curl_init($url);
		
	
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);  
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $request_headers);
			
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_VERBOSE, true);
		//curl_setopt($curl, CURLOPT_BINARYTRANSFER, true);
			
		$result = curl_exec($curl);
		
			
		curl_close($curl);
			
		$result=json_decode($result);
		
		$status=$result->Payment->Status;
		$DescStatus=StatusStmt($status);
		
		$RetCode=$result->Payment->ReturnCode;
		$DescRetCode=RetCodeStmt($RetCode);
		
		echo 'Status n° '.$status.': '.$DescStatus.'</br>';
		echo 'Código de Retorno: '.$RetCode.': '.$DescRetCode.'</br>';
		echo 'Tid (Id da transação na adquirente): '.$result->Payment->Tid.'</br>'; 
		echo 'ProofOfSale (Número da autorização, identico ao NSU): '.$result->Payment->ProofOfSale.'</br>';
		echo 'PaymentId (Campo Identificador do Pedido): '.$result->Payment->PaymentId.'</br>';
		echo 'AuthorizationCode (Código de autorização): '.$result->Payment->AuthorizationCode.'</br>';
		
		//var_dump($result);
		
			
			
	
	}
	echo '</div>';//final da DIV da área dos Forms..
	
	
	
	echo '</div>';
	require '../footer.php';
?>	
 
<script language="JavaScript" type="text/javascript">
TrustLogo("https://rolfmodas.com.br/_imagens/comodo_secure_seal_100x85_transp.png", "CL1", "none");
</script>
<a  href="https://www.positivessl.com/" id="comodoTL">Positive SSL</a>
</body>
</html>
 