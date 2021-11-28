<!DOCTYPE html>
<html>
<head>
  <?php
	require '../head.php';
	?>
  <title>Produção</title>
</head>
<body>
	<?php
	
	require '../config.php';
	require 'config.php';
	
	require '../header_geral.php';
	
	require 'menu.php';
	echo'
	<nav id="submenu">
	<a href="?acao=prod"><button>Gerar Produção</button></a>
	<a href="?acao=verifprod"><button>Verificar Produção</button></a>
	</nav>
	</header>
	
	<div class="corpo">';
	
	
	if(isset($_GET['acao'])){
		
		if($_GET['acao']=='prod'){
			echo '<form method="post" id="addprod" action="?acao=produc">
				<label for="ref">Ref:</label><input type="text" name="ref" id="ref" size="5" maxlength="6" autofocus/>
				<label for="cor">Cor:</label><input type="text" name="cor" id="cor" size="5"/>
				<label for="t1">P <span style="font-size:10pt">ou 4</span>:</label><input type="number" name="t1" id="t1" value="0"/>
				<label for="t2">M <span style="font-size:10pt">ou 6</span>:</label><input type="number" name="t2" id="t2" value="0"/>
				<label for="t3">G <span style="font-size:10pt">ou 8</span>:</label><input type="number" name="t3" id="t3" value="0"/>
				<label for="t4">GG <span style="font-size:10pt">ou 10</span>:</label><input type="number" name="t4" id="t4" value="0"/>
				<label for="t5">EG <span style="font-size:10pt">ou 12</span>:</label><input type="number" name="t5" id="t5" value= "0"/>
				<input type="submit" name="submit" value="Gerar produção" />
				<input type="submit" name="submit" value="Add sem gerar prod" />
			</form>	';
		}
	
	
	
		//Gerar produção
		if($_GET['acao']=='produc'){
			
			$ref=mb_strtoupper($_POST['ref'], 'UTF-8');
			$cor=mb_strtoupper($_POST['cor'], 'UTF-8');
			$t[1]= $_POST['t1'];
			$t[2]= $_POST['t2'];
			$t[3]= $_POST['t3'];
			$t[4]= $_POST['t4'];
			$t[5]= $_POST['t5'];
			$boolprod = $_POST['submit'];			
			gerar_prod($dbh,$ref,$cor,$t,$boolprod);
			
		}
		if($_GET['acao']=='verifprod'){
			echo '<form method="post" id="verifprod" action="?acao=resultverprod"/>
			Data de Produção entre:<input type="date" name="dataprodini" id="dataprodini" autofocus/> e <input type="date" name="dataprodfin" id="dataprodfin"/></br></br>
			<label for="hrstrab">Total de horas trabalhadas:(núm de cost. x Dias x 8): </label><input type="number" name="hrstrab" id="hrstrab" /></br>	
			<input type="submit" name="submit" value="Verificar Produção do período" />
			<input type="reset" id="limpar" name="limpar" value="Limpar"/>
			</form>';
		}
		if($_GET['acao']=='resultverprod'){
			$dataprodini = $_POST['dataprodini'];
			$dataprodfin = $_POST['dataprodfin'];
			$hrstrab = $_POST['hrstrab'];
			
			$qr="select sum(m.tempotot),sum(m.qtd),sum(p.preco*m.qtd) from medprod m
			join produtos p on p.ref = m.ref 
			where dataprod >= '$dataprodini' and dataprod <= '$dataprodfin 23:59:59'";
			$sql = mysqli_query($con,$qr);
			$ln= mysqli_fetch_assoc($sql);
			
			$tempotot = $ln['sum(m.tempotot)'];
			$qtdtot = $ln['sum(m.qtd)'];
			$tempohrs = $tempotot/60;
			$porcentagem= $tempohrs/$hrstrab*100;
			$valorprod = $ln['sum(p.preco*m.qtd)'];
			
			echo '<h3>Segue produção entre os dias '.$dataprodini.' e '.$dataprodfin.', considerando que foram trabalhadas '.$hrstrab.' horas:</h3>';
			echo '<p>O total de peças foi de: '.$qtdtot.' unidades.</p>';
			echo '<p>O total da produção foi de: '.number_format($tempohrs,2,",",".").' horas</p>';
			echo '<p>Em porcentagem: '.number_format($porcentagem,2,",",".").'%</p>';
			echo '<p>Em valor: R$ '.number_format($valorprod,2,",",".").'</p>';	
		}
	}else{
		echo '<script>window.location.href="?acao=prod"</script>';
	}
?>
	
</div>
</body>
</html>
 