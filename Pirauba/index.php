
<!DOCTYPE html>
<html>
<head>
<?php 
	session_start();
	require '_config/conection.php'; 
	if( is_file('_css/estilo-rpi-1.0.css') ){
		echo '<link rel="stylesheet" href="_css/estilo-rpi-1.0.css"/>';
	}else{
		echo '<link rel="stylesheet" href="../_css/estilo-rpi-1.0.css"/>';
	}
	date_default_timezone_set('America/Sao_Paulo');	
?>

	<title>Rolf Piraúba</title>
    <meta charset="UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"/>
	<meta name="description" content="O melhor site de compra atacado de moda feminina."/>
	<link rel="shortcut icon" href="../_imagens/logorolf.ico" type="image/x-icon" />
	<script type="text/javascript" src="_javascript/functionspirauba.js"></script>';

	
	echo '<script type="text/javascript"> ';
	echo 'var tlJsHost = ((window.location.protocol == "https:") ? "https://secure.comodo.com/" : "http://www.trustlogo.com/");';
	echo 'document.write(unescape("%3Cscript src='."'".'" + tlJsHost + "trustlogo/javascript/trustlogo.js'."'".' type='."'".'text/javascript'."'".'%3E%3C/script%3E"));';
	echo '</script>';
	

</head>
<body>

<?php
	echo '<header id="cabecalho">
		';	
echo '
	<div id="div_dados">';

	if(isset($_SESSION['id_usuario'])){
		if(strpos($_SESSION['acesso'],'adm') === false){
			header('Location:../index.php');
		}				
		echo '
		<div id="espacoheaderdireito" class="fr">
		Bem vindo '.$_SESSION['nomefantasia'];
		echo '
			<div class="dropdown">
				<img id="icon_menu" src="../_imagens/icon_menu.png"/>						
				<div class="areas">
					<a href="../PCP/pedido.php">PCP</a>
					<a href="../financeiro/extratos.php">Financeiro</a>
				</div>
			</div>';
				
		echo' <a class="a-black login" href="../Usuario/usuario.php"><img id="icon-minhaconta" src="../_imagens/minhaconta.png"/><span class="log">Minha conta</span></a>
		<a class="a-black login" href="../logout.php"><img id="icon-sair" src="../_imagens/sair.png"/><span class="log">Sair</span></a>';
	
		echo '</div>';
	}else{	
		header('Location:../index.php');
	}
				

		echo '<div id="div_logo">';
			echo '<a href="index.php"><img id="logo_header" src="../_imagens/logo.png"/></a>';
		echo '</div>';
	echo '</div>';
	echo '</header>';
	echo '
	<nav id="menu">	
			<ul type="disc">
				<span class="adm"><li><a href="../index.php">Rio Pomba</a></li></span>
			</ul>
		</nav>
		
	<nav id="submenu"></nav>
	<div id="botoes_centrais">
		<a href="Compras"><img class="CadVenda" id="cad" src="_imagens/compra_pirau.png"/></a>
		<a href="Vendas"><img class="CadVenda" id="venda" src="_imagens/venda_pirau.png"/></a>
	</div>';
	

	
	require '../footer.php';
?>
</body>
</html>
