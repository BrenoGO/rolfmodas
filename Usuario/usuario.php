

<!DOCTYPE html>
<html>
<head>
    <?php require('../head.php') ?> 
    <title>Area do Usuário</title>
	<script src="../_javascript/functions.js"></script>
	
	
</head>
<body>
	<?php
	require '../config.php';
	require '../header_geral.php'; 
	require 'menu.php';
	
	echo '<nav id="submenu">
	
	<a href="?acao=dadosacesso"><button>Dados de Acesso</button></a>
	<a href="?acao=dadospessoais"><button>Meus Dados</button></a>
	
	</nav>

	</header>	
	';	
	$qr="select * from usuarios where id_usuario='".$_SESSION['id_usuario']."'";
	$sql=mysqli_query($con,$qr);
	$ln=mysqli_fetch_assoc($sql);
	
	if(!isset($_GET['acao'])){
		
		
	}else{
		if($_GET['acao'] == 'dadosacesso'){
			echo '<div class="underline">';
			echo '<h2>Dados de Acesso</h2>
			<form method="post" action="?acao=editarusuario">
			<label for="id_usuario">ID:</label><input type="text" id="id_usuario" name="id_usuario" value="'.$ln['id_usuario'].'" size="5" readonly/>
			<label for="cnpj">';
			if($ln['tipo_pessoa']=='F'){
				echo 'CPF: ';
			}elseif($ln['tipo_pessoa']=='J'){
				echo 'CNPJ: ';
			}else{
				echo 'CPF ou CNPJ: ';
			}
			echo '</label><input type="text" id="cnpj" name="cnpj" value="'.$ln['cnpj'].'" size="16" readonly/></br>		
			<label for="emailnovo">E-mail:</label><input type="mail" id="emailnovo" name="emailnovo" value="'.$ln['email'].'"/></br>
			<input type="hidden" id="email" name="email" value="'.$ln['email'].'"/>
			<label for="nsenha">Nova Senha:</label><input type="password" id="nsenha" name="nsenha" size=5/>
			<label for="confnsenha">Confirme Nova Senha:</label><input type="password" id="confnsenha" name="confnsenha" size=5/></br></br>
			<input type="submit" value="Modificar dados de acesso"/>
			</form>';
		
			echo '</div>';
		}	
		if($_GET['acao'] == 'editarusuario'){
			$id_usuario=$_POST['id_usuario'];
			$emailnovo=$_POST['emailnovo'];
			$email=$_POST['email'];
			$senha= $_POST['nsenha'];
			if(strlen($senha)<4){
				echo "<script language='javascript' type='text/javascript'>alert('Senha deve ter no mínimo 4 dígitos');window.location.href='?default';</script>";
			}
			$senha= MD5($senha);
			$senha2= MD5($_POST['confnsenha']);
			if($senha <> $senha2){
				echo "<script language='javascript' type='text/javascript'>alert('Senha deve ser igual à confirma senha');window.location.href='?default';</script>";
			}else{	
				$qr="update usuarios set email='$emailnovo',senha='$senha' where id_usuario='$id_usuario'";
				$sql=mysqli_query($con,$qr);
				if($sql){
					echo 'Cadastro editado com sucesso';
				}else{
					echo 'erro no sql...';
				}
			}
		}
		if($_GET['acao'] == 'dadospessoais'){
			if(!isset($_GET['atualizar'])){
				$boolemoutroforn=false;
				$boolnovocliente=false;
				$id_usuario=$_SESSION['id_usuario'];
				$actionFornCadastPessoa='?acao=dadospessoais&atualizar';
				$boolcadastsenha=false;
				require("../_auxiliares/formcadastpessoa.php");
			}else{
				require("../_auxiliares/cadastpessoa.php");
			}
			
			
		}
		
	}
		
	require '../footer.php';
?>	
 

</body>
</html>
 