<?php

/*echo "<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
  ga('create', 'UA-104561894-1', 'auto');
  ga('require', 'displayfeatures');
  ga('send', 'pageview');
  ";
  if(isset($_COOKIE['id'])){
	$user_id=$_COOKIE['id'];
	echo "gtag('set', {'user_id': '".$user_id."'});
	"; // Defina o ID de usuário usando o user_id conectado.
	echo "ga('set', 'userId', ".$user_id.");"; // Defina o ID de usuário usando o user_id conectado.
  }	
echo "</script>";
*/
if(!isset($_COOKIE['tipo_ped'])){
	setcookie('tipo_ped','PE',time()+(5*12*30*24*3600));
	$tipo_ped='PE';
}else{
	$tipo_ped=$_COOKIE['tipo_ped'];
}

echo '<header id="cabecalho">
		<div id="div_dados">';
		if( ($_SERVER['PHP_SELF']=='/PCP/catalogo.php')or($_SERVER['PHP_SELF']=='/rolfmodas.com.br/PCP/catalogo.php') ){
			echo '<div id="barratopo">
				<b><marquee id="textTopo" height="20px" scrollamount="1000" scrolldelay="10000" >Pronta Entrega: Próximo dia útil 
				&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
				&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Pedido a Prazo: após 30 dias
				&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
				&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
				&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
				&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspPronta Entrega: Próximo dia útil &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
				&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
				&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Pedido a Prazo: após 30 dias</marquee></b>
			</div>';
		}
			
			if( ($_SERVER['PHP_SELF']=='/PCP/catalogo.php')or($_SERVER['PHP_SELF']=='/rolfmodas.com.br/PCP/catalogo.php') ){
				echo '<div id="icone">
					<!--<img src="../_imagens/icon_menu.png" style="height: 20px; width: 20px;" />-->
					<img src="../_imagens/icon_menu.png" style="height: 40px; width: 40px; float: left; margin-right: 4px;"/>
					<h1 style="font-size: 100%; vertical-align: middle; font-weight: bold; float: left;">MENU</h1>

				</div>';
			}
			echo '<div id="div_logo">';
			if(is_file('../_imagens/logo.png')){
				echo '<a href="../index.php"><img id="logo_header" src="../_imagens/logo.png"/></a>';
			}else{
				echo '<a href="../index.php"><img id="logo_header" src="_imagens/logo.png"/></a>';
			}
			echo '</div>';
			$stmt=$dbh->prepare("select * from dadosgerais where nome_dado=?");
			$stmt->execute(array('Grupos'));
			$ln=$stmt->fetch(PDO::FETCH_ASSOC);
			$nome_grupos=explode(';',$ln['dado_1']);

		
			
			

			if( ($_SERVER['PHP_SELF']=='/PCP/catalogo.php')or($_SERVER['PHP_SELF']=='/rolfmodas.com.br/PCP/catalogo.php') ){
				
				echo '
				
				<div id="buscar" style="display: none">
					<input type="text" id="busca" onkeyup="proc_produto_catalog(this.value)" placeholder="Busque aqui !" autocomplete="off"/>
					<div id="ls_produto_catalog"></div>
				</div>';
				
				/*echo '
				<div id="espacobuscar" class="fl">
					<input type="text" id="buscaprod" onkeyup="proc_produto_catalog(this.value)" placeholder="Buscar"/>
					<div id="ls_produto_catalogo"></div>
				</div
				<div id="tipo_ped">
					<span class="space">
					<input name="tipo_ped" id="tipo_ped_PE" onclick="set_tipo_ped_PE()" type="radio"';if($tipo_ped=='PE'){echo ' checked';}echo '/>
					<label for="tipo_ped_PE">Pronta Entrega</label></span>
					<span class="space"><input name="tipo_ped" id="tipo_ped_Pedido" onclick="set_tipo_ped_Pedido()" type="radio"';
					if($tipo_ped=='Pedido'){echo ' checked';}
					echo '/><label for="tipo_ped_Pedido">Pedido</label></span>
					<span class="space"><input name="tipo_ped" id="tipo_ped_Ambos" onclick="set_tipo_ped_Ambos()" type="radio"';
					if($tipo_ped=='Ambos'){echo ' checked';}
					echo '/><label for="tipo_ped_Ambos">Ambos</label></span>
				</div>';*/
			}
			

			if(isset($_SESSION['id_usuario'])){
				if(strpos($_SESSION['acesso'],'adm') === false){
					echo '<link rel="stylesheet" type="text/css" href="../_css/acesso.css" />';
					
					$expl=explode('/',$_SERVER['PHP_SELF']);
					$c=count($expl);
					$urlend='';
					for($i=1;$i<=($c-2);$i++){
						$urlend .='/'.$expl[$i];
					}
					
					
					if( !(($_SERVER['PHP_SELF']=='/PCP/catalogo.php')or($_SERVER['PHP_SELF']=='/rolfmodas.com.br/PCP/catalogo.php'))
					and	!(($urlend=='/Usuario')or($urlend=='/rolfmodas.com.br/Usuario')) 
					and	!(($urlend=='/Checkout')or($urlend=='/rolfmodas.com.br/Checkout')) ){
						unset($_SESSION);
						unset($_COOKIE['id']);
						header('Location:../index.php');
					}
				}
				if( ($_SERVER['PHP_SELF']=='/PCP/catalogo.php')or($_SERVER['PHP_SELF']=='/rolfmodas.com.br/PCP/catalogo.php') ){
					echo '<div id="espacoheaderdireito" class="fr">';
				}else{
					echo '<div id="espacoheaderdireito" style="color: black;" class="fr">';
				}
				if(strpos($_SESSION['acesso'],'adm') !== false){
					if( ($_SERVER['PHP_SELF']=='/PCP/catalogo.php')or($_SERVER['PHP_SELF']=='/rolfmodas.com.br/PCP/catalogo.php') ){
						echo '
						<div class="dropdown">
							<img class="iconLogin" src="../_imagens/icon_menu.png"/>						
							<div class="areas">
								<a href="../PCP/pedido.php">PCP</a>
								<a href="../financeiro/extratos.php">Financeiro</a>
							</div>
						</div>';
					}else{
						echo '
						<div class="dropdown">
							<img class="iconLogin" src="../_imagens/icon_menu.png"/>						
							<div class="areas">
								<a href="../PCP/pedido.php">PCP</a>
								<a href="../financeiro/extratos.php">Financeiro</a>
							</div>
						</div>';
					}
					

				}else{
					$expl=explode('/',$_SERVER['PHP_SELF']);
					$c=count($expl);
					$urlend='';
					for($i=1;$i<=($c-2);$i++){
						$urlend .='/'.$expl[$i];
					}
					if( ($urlend=='/Usuario')or($urlend=='/rolfmodas.com.br/Usuario') ){
						echo ' &nbsp&nbsp <a href="../PCP/catalogo.php?default">Catálogo</a>';
					}	
				}
				echo' 
				<a class="a-black login" href="../Usuario/meuspedidos.php">
					<img class="iconLogin" src="../_imagens/minhaconta.png"/>
					<span class="log">Minha conta</span>
				</a>
				<a class="a-black login" href="../logout.php">
					<img class="iconLogin" src="../_imagens/sair.png"/>
					<span class="log">Sair</span>
				</a>';
					
				
				if( (isset($_SESSION['pedidope'])) or (isset($_SESSION['pedido_ped'])) ){
					/*echo '
					<a href="?acao=conferirped">Carrinho:
					Itens - R$ 0,00</a>
					';*/
					echo ' <a href="../PCP/catalogo.php?acao=conferirped"><img class="iconLogin" src="../_imagens/carrinhocompras.png"/><span class="log">Carrinho</span></a>';
				}
				echo '</div>';
			}else{
				echo '
				<a id="entrar" href="#">
					<img id="imgLog" src="../_imagens/login.png"/>
				</a>
				<!--<div id="espacologindireito">
					<form style="display:inline" class="fr" method="post" action="../login.php">
						<input type="text" name="id" placeholder="Id ou CNPJ" autocomplete="off" autofocus/>
						<input type="password" name="senha" placeholder="senha" autocomplete="foo"/><br>
						<input type="submit" name= "entrar" value="Entrar"/>
						<button type="button" onclick="window.location.href='."'../Usuario/precadastro.php'".'">Cadastre-se</button>
					</form>
				</div>
				<div id="loginButton" class="loginButton">
						<img id="icon-login" src="../_imagens/login.png"/>
						<label for="icon-login" style="float: right; margin-right: 3px; font-size: 150%;">Entrar</label>
				</div>-->

				<div id="login_mobile">
					<div id="opaco_fundo"></div>	
					<div id="headerdireito">
						<img id="fecharlog" src="../_imagens/canc.png"style="width: 9%; cursor: pointer; float: right; margin-right: -2%; margin-top: -2%;"/>
						
						<div id="arealogin">
							<h1 style="text-align: center;">Entrar</h1>
							<form id="formlogin" method="post" action="../login.php">
								<input class="input" type="text" name="id" id="id" placeholder="Id ou CNPJ" autocomplete="off" autofocus/><br>
								<input class="input" type="password" name="senha" placeholder="senha" autocomplete="foo"/><br>
								<input class="input" type="submit" name= "entrar" value="Entrar"/><br>
								<button class="input" type="button" onclick="window.location.href='."'../Usuario/precadastro.php'".'">Cadastre-se</button>
							</form>
						</div>
					</div>
				</div>';
				//não está logado
				
				$stmt=$dbh->prepare("select * from dadosgerais where nome_dado='Mark-up'");
				$stmt->execute();
				$markup=$stmt->fetch(PDO::FETCH_ASSOC);
				$markconsumidor = explode('=',$markup['dado_1']);
				$markconsumidor = $markconsumidor[1];
				$_SESSION['markup'] = $markconsumidor;
				if( !(($_SERVER['PHP_SELF']=='/PCP/catalogo.php')or($_SERVER['PHP_SELF']=='/rolfmodas.com.br/PCP/catalogo.php')
					or($_SERVER['PHP_SELF']=='/Usuario/fale_conosco.php')or($_SERVER['PHP_SELF']=='/rolfmodas.com.br/Usuario/fale_conosco.php')
					or($_SERVER['PHP_SELF']=='/CC/index.php')or($_SERVER['PHP_SELF']=='/rolfmodas.com.br/CC/index.php'))){
						unset($_SESSION);
						unset($_COOKIE['id']);
						header('Location:../index.php');
				}
				
				//fim parte nova
				
				//echo "<script>window.location.href='../index.php';</script>";
			}
		


		
		
?>