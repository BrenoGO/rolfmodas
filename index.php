<?php
session_start();


require '_config/conection.php'; 
$dbh = new PDO('mysql:host='.$host.';dbname='.$db.';charset=utf8',$user,$pass);
if(isset($_COOKIE['id'])){
	$stmt = $dbh->prepare("select * from usuarios where id_usuario = ?");
	$stmt->execute(array($_COOKIE['id']));
	$array=$stmt->fetch(PDO::FETCH_ASSOC);
	$contconect=$array['contconect'];
	$acesso = $array['acesso'];
	$idusuario=$array['id_usuario'];
	$nomefantasia=$array['nomefantasia'];
	
	if($contconect=='S'){
		require '_auxiliares/define_session.php';
		setcookie('id',$_SESSION['id_usuario'],time()+(5*12*30*24*3600));
		if(strpos($_SESSION['acesso'],'adm') !== false){
			header("Location:PCP/pedido.php");
		}
		else{
			header("Location:PCP/catalogo.php?default");
		}
	}
}

?>
<!DOCTYPE html>
<html>
<head>
  <?php require('head.php') ?>
  
  <title>Rolf Modas</title>
  <script type="text/javascript"> //<![CDATA[ 
var tlJsHost = ((window.location.protocol == "https:") ? "https://secure.comodo.com/" : "http://www.trustlogo.com/");
document.write(unescape("%3Cscript src='" + tlJsHost + "trustlogo/javascript/trustlogo.js' type='text/javascript'%3E%3C/script%3E"));
//]]>
</script>
</head>
<body>
<?php
	echo '<img id="logo_header" src="_imagens/logo.png"/>';
	if(isset($_SESSION['id_usuario'])){
		if(strpos($_SESSION['acesso'],'adm') !== false){
			
			echo "<script>window.location.href='PCP/pedido.php';</script>";
		}
		else{
			
			echo "<script>window.location.href='PCP/catalogo.php';</script>";
		}
//		var_dump($_SESSION);
	}
	else{
		echo "<script>window.location.href='PCP/catalogo.php';</script>";
	}
?>

<div id="boxlogin">
	<form method="POST" action="login.php">
		<label for="id">Id ou CPF: </label><input type="text" name="id" id="id"/></br>
		<label for="senha">Senha: </label></br><input type="password" name="senha" id="senha"/></br>
		<span style="font-size:8pt"><input type="checkbox" name="contconect" id="contconect" value="TRUE"/><label for="contconect">Permanecer conectado</label></span></br>
		<input type="submit" value="Entrar" name= "entrar" id="entrar"/></br>
		<!--<a href="cadastros.php?acao=formcadastrousuario">Cadastre-se</a>
		-->
	</form>
</div>
<?php
	require 'footer.php';
?>
<script language="JavaScript" type="text/javascript">
TrustLogo("https://rolfmodas.com.br/_imagens/comodo_secure_seal_100x85_transp.png", "CL1", "none");
</script>
<a  href="https://www.positivessl.com/" id="comodoTL">Positive SSL</a>
</body>
</html>
 