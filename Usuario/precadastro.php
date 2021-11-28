<!DOCTYPE html>
<html>
<head>
  <?php
	require '../head.php';
	?>

	<script src="../_javascript/functions.js"></script>
	
	<style>
	body{
		font-family: Arial,Helvetica Neue,Helvetica,sans-serif; 
	}
	p{
		text-indent:0;
	}
	table{
		border:0;
	}
	table td{
		border:0;
		text-align:right;
	}
	</style>
	
  <title>Pré Cadastro</title>
 
</head>
<body id="precadastro">
	<?php
	session_start();
	
	require '../_config/conection.php'; 
	$dbh = new PDO('mysql:host='.$host.';dbname='.$db.';charset=utf8',$user,$pass);
	
	//require 'header_geral.php';
	echo '
	<div id="div_logo">
		<a href="../index.php"><img id="logo_header" src="../_imagens/logo.png"/></a>
	</div>
	<div class="corpo" style="clear:both">
	';
	if(!isset($_GET['acao'])){
		echo '<h1>Cadastre-se</h1>';
		$actionFornCadastPessoa='?acao=cadast';
		$boolemoutroforn=false;
		$boolnovocliente=true;
		$boolcadastsenha=true;
		
		require '../_auxiliares/formcadastpessoa.php';
	}else{
		if($_GET['acao']=='cadast'){
			require '../_auxiliares/cadastpessoa.php';
			//$id_novo e $senha vem do cadastpessoa...
			echo '<h1>Parabéns pelo Cadastro! </h1>
			<h2><span style="color:red">ID de acesso: <b>'.$id_novo.'</b></span></h2>
			<button onclick="window.location.href='."'../login.php?senha=".$senha."&id=".$id_novo."'".'">Já gravei meu Id.</button>';
		}
	}
	echo '</div>';
		
	
	require '../footer.php';
	?>
	
</body>
</html>
 