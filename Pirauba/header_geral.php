<?php

echo '<header id="cabecalho">
		';	
echo '
	<div id="div_dados">';

	if(isset($_SESSION['id_usuario'])){
		if(strpos($_SESSION['acesso'],'adm') === false){
			header('Location:../../index.php');
		}				
		echo '
		<div id="espacoheaderdireito" class="fr">
		Bem vindo '.$_SESSION['nomefantasia'];
		echo '
			<div class="dropdown">
				<img id="icon_menu" src="../../_imagens/icon_menu.png"/>						
				<div class="areas">
					<a href="../../PCP/pedido.php">PCP</a>
					<a href="../../financeiro/extratos.php">Financeiro</a>
				</div>
			</div>';
				
		echo' <a class="a-black login" href="../../Usuario/usuario.php"><img id="icon-minhaconta" src="../../_imagens/minhaconta.png"/><span class="log">Minha conta</span></a>
		<a class="a-black login" href="../logout.php"><img id="icon-sair" src="../../_imagens/sair.png"/><span class="log">Sair</span></a>';
		
		echo '</div>';
	}else{	
		header('Location:../../index.php');
	}
	echo '</div>';
				

		echo '<div id="div_logo">';
		if(is_file('../../_imagens/logo.png')){
			echo '<a href="../index.php"><img id="logo_header" src="../../_imagens/logo.png"/></a>';
		}else{
			echo '<a href="../index.php"><img id="logo_header" src="../_imagens/logo.png"/></a>';
		}
		echo '</div>';
	
	
	require '../menu.php';
	echo '</header>';
?>