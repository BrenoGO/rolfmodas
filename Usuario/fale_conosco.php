
<!DOCTYPE html>
<html>
<head>
    <?php require('../head.php') ?> 
    <title>Area do Usuário</title>
	<script src="../_javascript/functions.js"></script>
	
	<script type="text/javascript"> //<![CDATA[ 
var tlJsHost = ((window.location.protocol == "https:") ? "https://secure.comodo.com/" : "http://www.trustlogo.com/");
document.write(unescape("%3Cscript src='" + tlJsHost + "trustlogo/javascript/trustlogo.js' type='text/javascript'%3E%3C/script%3E"));
//]]>
</script>
</head>
<body id="falaconosco">
	<?php
	require '../config.php';
	require '../header_geral.php'; 
	
	if(!isset($_POST['msg'])){
		echo '<div class="corpo">
		<div id="title_fale_conosco">
		<h1>Escolha um dos meios abaixo para entrar em contato conosco</h1>
		</div>
		<div id="contac_email">
		<h3>E-mail: rolf@rolfmodas.com.br</h3>
		</div>
		<div id="contac_tel">
		<h3>Telefone: (32) 3571-1010</h3>
		</div>
		
		<div id="contac_msg">
		<form method="post" action="">
		<h3>Deixe aqui sua mensagem que retornaremos em breve:</h3>';
		
		echo '<label for="email">Email*: </label><input type="text" name="email" ';
		if(isset($_SESSION['id_usuario'])){
			$stmt=$dbh->prepare("select email from usuarios where id_usuario=?");
			$stmt->execute(array($_SESSION['id_usuario']));
			$ln=$stmt->fetch(PDO::FETCH_ASSOC);
			$email=$ln['email'];
			echo 'value="'.$email.'" ';
			$id_usuario=$_SESSION['id_usuario'];
		}
		echo 'id="email" required/></br>
		Digite sua mensagem*:</br>
		<textarea name="msg" cols="50" rows="6" required></textarea></br>';
		if(isset($id_usuario)){
			echo '<input type="hidden" value="'.$id_usuario.'" name="id_usuario"/>';
		}
		echo '<span style="font-size:10pt;">*preenchimento obrigatório</br></span></br><input type="submit" value="Enviar Mensagem"/>
		
		</div>
		
		</div>';//class="corpo"
	}else{
		//tem POST, então mandar e-mail pra rolf@rolfmodas.com.br com a msg..
		$text=$_POST['msg'];
		$email=$_POST['email'];
		if(isset($_POST['id_usuario'])){
			$id_usuario=$_POST['id_usuario'];
		}else{
			$id_usuario='Não foi logado';
		}
		$message = '
		<html>
		<head><title>Mensagem do "Fale Conosco"</title></head>
		<body>
		<h2>Mensagem enviada em '.date("d/m/Y").' às '.date("H:i:s").'</h2>
		<p>Id do Usuário: '.$id_usuario.'. E-mail: '.$email.' </p>
		<p>Mensagem:</p>
		<p>'.$text.'</p>
		</body>
		</html>
		';
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "From: Rolf Modas <rolf@rolfmodas.com.br>" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		$to = 'rolf@rolfmodas.com.br';
		
		$subject = 'Mensagem de '.$email;
		mail($to,$subject,$message,$headers);
		//fim do mandar email
		echo '</br></br></br></br></br></br><h2>Obrigado por nos enviar sua mensagem! Em breve retornaremos o contato.</h2>';
	}
	
	
	require '../footer.php';
?>	
 
<script language="JavaScript" type="text/javascript">
TrustLogo("http://rolfmodas.com.br/_imagens/comodo_secure_seal_100x85_transp.png", "CL1", "none");
</script>
<a  href="https://www.positivessl.com/" id="comodoTL">Positive SSL</a>
</body>
</html>
 