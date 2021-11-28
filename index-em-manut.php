<?php
session_start();


require '_config/conection.php'; 
$dbh = new PDO('mysql:host='.$host.';dbname='.$db.';charset=utf8',$user,$pass);
if(isset($_COOKIE['id'])){
	$stmt = $dbh->prepare("select * from usuarios where id_usuario = ?");
	$stmt->execute(array($_COOKIE['id']));
	$array=$stmt->fetch(PDO::FETCH_ASSOC);
	$contconect=$array['contconect'];
	if($contconect=='S'){
		$_SESSION['id_usuario']=$array['id_usuario'];
		$_SESSION['acesso']=$array['acesso'];
		$_SESSION['nomefantasia']=$array['nomefantasia'];
		setcookie('id',$_SESSION['id_usuario'],time()+(5*12*30*24*3600));
		
				
		if($_SESSION['acesso'] == 'adm'){
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
  
  <title>Acesso de usuário</title>
  <script type="text/javascript"> //<![CDATA[ 
var tlJsHost = ((window.location.protocol == "https:") ? "https://secure.comodo.com/" : "http://www.trustlogo.com/");
document.write(unescape("%3Cscript src='" + tlJsHost + "trustlogo/javascript/trustlogo.js' type='text/javascript'%3E%3C/script%3E"));
//]]>
</script>
</head>
<body>
<?php
	echo '<img id="logo_header" src="_imagens/logo.png"/>';
	if(isset($_SESSION['usuario'])){
		if($_SESSION['acesso']=='adm'){
			echo "<script>window.location.href='PCP/pedido.php';</script>";
		}
		else{
			echo "<script>window.location.href='PCP/catalogo.php';</script>";
		}
		var_dump($_SESSION);
	}
?>

<div id="boxlogin">
	<img src="_imagens/manutencao.jpg" style="width: 220px;"/>
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
 