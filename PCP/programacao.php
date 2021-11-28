<!DOCTYPE html>
<html>
<head>
  <?php
	require '../head.php';
	?>
	<title>Programação</title>
	<script src="../_javascript/functions.js"></script>
	
</head>
<body>
	<?php
	
	require '../config.php';
	require 'config.php';
	
	require '../header_geral.php';
	require 'menu.php';
	
	
	echo '
	<nav id="submenu">
	<a href="?acao=vernece"><button>Verificar urgências</button></a>
	<a href="?acao=progmc"><button>Programação por material/cor/ref</button></a>
	<a href="?acao=consultlote"><button>Consultar Lote</button></a>
	<a href="?acao=atualizarlote"><button>Atualizar Lote</button></a>
	<a href="?acao=etiquetas"><button>Etiquetas</button></a>
	<a href="?acao=previsao"><button>Previsão</button></a>
	</nav>
	</header>
	<div class="corpo">';
	
	
	
	if(isset($_GET['acao'])){
		if($_GET['acao']=='vernece'){
			$qr = "select pcp.ref,pcp.cor,pcp.t1,pcp.t2,pcp.t3,pcp.t4,pcp.t5,pcp.tot,pcp.situacao,pedidos.pedido,pedidos.dataentrega 
			from pcp inner join pedidos 
			on pedidos.pedido = pcp.loc 
			where situacao = 'A'
			order by dataentrega";
			$sql = mysqli_query($con,$qr);
			$ln = mysqli_fetch_assoc($sql);
			$menordata= $ln['dataentrega'];
			$qr = "select pcp.ref,pcp.cor,pcp.t1,pcp.t2,pcp.t3,pcp.t4,pcp.t5,pcp.tot,pcp.situacao,pedidos.pedido,pedidos.dataentrega 
			from pcp inner join pedidos 
			on pedidos.pedido = pcp.loc 
			where situacao = 'A' and dataentrega='$menordata'";
			$sql = mysqli_query($con,$qr);
			$tablesql = array();
			while($row = $sql -> fetch_assoc()){
				$tablesql[] = $row;
			}
			/*$tablesql = mysqli_fetch_all($sql,MYSQL_ASSOC);
			*/
			echo '<table><thead><tr>
			<td>Ref</td>
			<td>Cor</td>
			<td>P <span style="font-size:10pt">ou 4</span></td>
			<td>M <span style="font-size:10pt">ou 6</span></td>
			<td>G <span style="font-size:10pt">ou 8</span></td>
			<td>GG <span style="font-size:10pt">ou 10</span></td>
			<td>EG <span style="font-size:10pt">ou 12</span></td>
			<td>Total</td>
			<td>Sit</td>
			<td>Pedido</td>
			<td>Prazo</td></tr></thead>';
			foreach ($tablesql as $ltable){
				echo '<tr>
				<td>'.$ltable['ref'].'</td>
				<td>'.$ltable['cor'].'</td>
				<td>'.$ltable['t1'].'</td>
				<td>'.$ltable['t2'].'</td>
				<td>'.$ltable['t3'].'</td>
				<td>'.$ltable['t4'].'</td>
				<td>'.$ltable['t5'].'</td>
				<td>'.$ltable['tot'].'</td>
				<td>'.$ltable['situacao'].'</td>
				<td>'.$ltable['pedido'].'</td>
				<td>'.$ltable['dataentrega'].'</td></tr>';
				
			}
			echo '</table>';
		}
		if($_GET['acao']=='progmc'){
			echo'<form method="post" id="programa" action="?acao=programa">
			<label for="cor">Cor:</label><input type="text" name="cor" id="cor" size="5" autofocus/>
			<label for="ref">Ref:</label><input type="text" name="ref" id="ref" size="5"/>
			<label for="dataentregamax">Data Entrega até:</label><input type="date" name="dataentregamax" id="dataentregamax"/></br>
			<input type="radio" name="material" id="viscolycralisa" value="VL"/><label for="viscolycralisa">Viscolycra Lisa</label>
			<input type="radio" name="material" id="viscolycraestamp" value="VE"/><label for="viscolycraestamp">Viscolycra estampada</label></br>
			<input type="radio" name="material" id="viscoselisa" value="CL"/><label for="viscoselisa">Viscose Lisa</label>
			<input type="radio" name="material" id="viscoseestamp" value="CE"/><label for="viscoseestamp">Viscose estampada</label></br>
			<input type="radio" name="material" id="suplexbodyliso" value="BL"/><label for="suplexbodyliso">Suplex Body Liso</label>
			<input type="radio" name="material" id="suplexbodyestamp" value="BE"/><label for="suplexbodyestamp">Suplex Body Estampado</label></br>
			<input type="radio" name="material" id="suplexcalcaliso" value="SL"/><label for="suplexcalcaliso">Suplex Calça Liso</label></br>
			<input type="radio" name="material" id="neopreneliso" value="NL"/><label for="neopreneliso">NeoPrene</label>
			<input type="radio" name="material" id="neopreneest" value="NE"/><label for="neopreneest">NeoPrene Est</label></br>
			<input type="radio" name="material" id="algodao" value="AL"/><label for="algodao">100% Algodão</label>
			<input type="radio" name="material" id="algelast" value="AE"/><label for="algelast">Algodão c Elastano</label></br>
			<input type="radio" name="material" id="linho" value="FL"/><label for="linho">Linho</label>
			<input type="radio" name="material" id="renda" value="RE"/><label for="renda">Renda</label>
			<input type="radio" name="material" id="invernoliso" value="IL"/><label for="invernoliso">Outros de Inverno Liso</label></br></br>
			<input type="submit" name="submit" value="Gerar Programa" />
			<input type="reset" id="limpar" name="limpar" value="Limpar"/>
			</form>';
		}
		if($_GET['acao']=='programa'){
			$cor= mb_strtoupper($_POST['cor'],'UTF-8');
			$ref= mb_strtoupper($_POST['ref'],'UTF-8');
			if(isset($_POST['material'])){$material= $_POST['material'];}
			$dataentregamax = $_POST['dataentregamax'];
			$qr= "select pcp.ref,pcp.cor,sum(t1),sum(t2),sum(t3),sum(t4),sum(t5),sum(tot)
				from pcp join produtos on pcp.ref = produtos.ref
				join pedidos on pedidos.pedido = pcp.loc
				where situacao ='A'";
				if($cor <> ""){$qr .= " and pcp.cor='$cor'";}
				if($ref <> ""){$qr .= " and pcp.ref='$ref'";}
				if($dataentregamax <> ""){$qr .= " and pedidos.dataentrega <= '$dataentregamax'";}
				if(isset($material)){$qr .= " and produtos.tipo like '$material%'";}
				$qr .= " group by ref,cor";
				
				
			$sql = mysqli_query($con,$qr);
			
			$tablesql = array();
			while($row = $sql -> fetch_assoc()){
				$tablesql[] = $row;
			}
			
			/*$tablesql = mysqli_fetch_all($sql,MYSQL_ASSOC);
			*/
			echo '<table><thead><tr>
			<td>Ref</td>
			<td>Cor</td>
			<td>P <span style="font-size:10pt">ou 4</span></td>
			<td>M <span style="font-size:10pt">ou 6</span></td>
			<td>G <span style="font-size:10pt">ou 8</span></td>
			<td>GG <span style="font-size:10pt">ou 10</span></td>
			<td>EG <span style="font-size:10pt">ou 12</span></td>
			<td>Total</td>
			</tr></thead>';
			foreach ($tablesql as $ltable){
				echo '<tr>
				<td>'.$ltable['ref'].'</td>
				<td>'.$ltable['cor'].'</td>
				<td>'.$ltable['sum(t1)'].'</td>
				<td>'.$ltable['sum(t2)'].'</td>
				<td>'.$ltable['sum(t3)'].'</td>
				<td>'.$ltable['sum(t4)'].'</td>
				<td>'.$ltable['sum(t5)'].'</td>
				<td>'.$ltable['sum(tot)'].'</td>
				</tr>';
				
			}
			echo '</table></br>';
			
			echo '<form method="post" id="programa" action="?acao=modApraP">
			<input type="text" style="display:none" name="cor" value="'.$cor.'"/>
			<input type="text" style="display:none" name="ref" value="'.$ref.'"/>
			<input type="date" style="display:none" name="dataentregamax" value="'.$dataentregamax.'"/>';
			if(isset($material)){echo '<input type="text" style="display:none" name="material" value="'.$material.'"/>';}
				
			echo '<input type="submit" name="submit" value="Add NOVO programa"/>
			<input type="submit" name="submit" value="Add a programa anterior"/>
			</form>';
			
		}if($_GET['acao']=='modApraP'){
			$novoprog=$_POST['submit'];
			$qr= "select max(lote) from pcp";
			$sql= mysqli_query($con,$qr);
			$ln=mysqli_fetch_assoc($sql);
			if($novoprog == 'Add NOVO programa'){
				$lote = $ln['max(lote)'] + 1;
			}else{
				$lote = $ln['max(lote)'];
			}
			$cor= $_POST['cor'];
			$ref= $_POST['ref'];
			if(isset($_POST['material'])){$material= $_POST['material'];}
			$dataentregamax = $_POST['dataentregamax'];
			$qr= "select pcp.ref,pcp.cor,sum(t1),sum(t2),sum(t3),sum(t4),sum(t5),sum(tot)
				from pcp join produtos on pcp.ref = produtos.ref
				join pedidos on pedidos.pedido = pcp.loc
				where situacao ='A'";
				if($cor <> ""){$qr .= " and pcp.cor='$cor'";}
				if($ref <> ""){$qr .= " and pcp.ref='$ref'";}
				if($dataentregamax <> ""){$qr .= " and pedidos.dataentrega <= '$dataentregamax'";}
				if(isset($material)){$qr .= " and produtos.tipo like '$material%'";}
				$qr .= " group by ref,cor";
				
			$sql = mysqli_query($con,$qr);
			$tablesql = array();
			while($row = $sql -> fetch_assoc()){
				$tablesql[] = $row;
			}
			
			/*$tablesql = mysqli_fetch_all($sql,MYSQL_ASSOC);
			*/
			echo '<table><thead><tr>
			<td>Ref</td>
			<td>Cor</td>
			<td>P <span style="font-size:10pt">ou 4</span></td>
			<td>M <span style="font-size:10pt">ou 6</span></td>
			<td>G <span style="font-size:10pt">ou 8</span></td>
			<td>GG <span style="font-size:10pt">ou 10</span></td>
			<td>EG <span style="font-size:10pt">ou 12</span></td>
			<td>Total</td>
			<td>Situação</td>
			<td>Lote</td>
			</tr></thead>';
			
			foreach ($tablesql as $ltable){
				echo '<tr>
				<td>'.$ltable['ref'].'</td>
				<td>'.$ltable['cor'].'</td>
				<td>'.$ltable['sum(t1)'].'</td>
				<td>'.$ltable['sum(t2)'].'</td>
				<td>'.$ltable['sum(t3)'].'</td>
				<td>'.$ltable['sum(t4)'].'</td>
				<td>'.$ltable['sum(t5)'].'</td>
				<td>'.$ltable['sum(tot)'].'</td>
				<td> P </td>
				<td>'.$lote.'</td>
				</tr>';
				$qr= 'update pcp join pedidos p
					on p.pedido= pcp.loc
					set situacao = "P",dataalter=default,lote='.$lote.'
					where situacao="A" and cor="'.$ltable['cor'].'"';
				if($dataentregamax<>""){$qr .= " and dataentrega <= '$dataentregamax'";}
				$qr .= ' and ref="'.$ltable['ref'].'"';
				$sql= mysqli_query($con,$qr);
			}
			echo '</table></br>';
		}
		if($_GET['acao']=='consultlote'){
			echo '<form method="post" id="consultlote" action="?acao=resultlote">
			<label for="lote">Lote:</label><input type="number" name="lote" size="5" autofocus/>
			<input type="submit" name="submit" value="Consultar lote" />
			';
		}	
		if($_GET['acao']=='resultlote'){
			$lote = $_POST['lote'];
			$qr= "select * from pcp join pedidos p on p.pedido = pcp.loc where lote = $lote order by ref,cor,p.dataentrega";
			$sql= mysqli_query($con,$qr);
			$tablesql = array();
			while($row = $sql -> fetch_assoc()){
				$tablesql[] = $row;
			}
			/*$tablesql= mysqli_fetch_all($sql,MYSQL_ASSOC);
			*/
			echo '<table><thead><tr>
			<td>Ref</td>
			<td>Cor</td>
			<td>P <span style="font-size:10pt">ou 4</span></td>
			<td>M <span style="font-size:10pt">ou 6</span></td>
			<td>G <span style="font-size:10pt">ou 8</span></td>
			<td>GG <span style="font-size:10pt">ou 10</span></td>
			<td>EG <span style="font-size:10pt">ou 12</span></td>
			<td>Total</td>
			<td>Situação</td>
			<td>Lote</td>
			<td>Pedido</td>
			<td>Prazo</td>
			</tr></thead>';
			
			foreach ($tablesql as $ltable){
				echo '<tr>
				<td>'.$ltable['ref'].'</td>
				<td>'.$ltable['cor'].'</td>
				<td>'.$ltable['t1'].'</td>
				<td>'.$ltable['t2'].'</td>
				<td>'.$ltable['t3'].'</td>
				<td>'.$ltable['t4'].'</td>
				<td>'.$ltable['t5'].'</td>
				<td>'.$ltable['tot'].'</td>
				<td>'.$ltable['situacao'].'</td>
				<td>'.$lote.'</td>
				<td>'.$ltable['pedido'].'</td>
				<td>'.$ltable['dataentrega'].'</td>
				</tr>';
			}
		}
		if($_GET['acao']=='atualizarlote'){
			$qr="select max(lote) from pcp";
			$sql=mysqli_query($con,$qr);
			$ln=mysqli_fetch_assoc($sql);
			$maxlote= $ln['max(lote)'];
			echo 'Próximo Lote: '.($maxlote+1);
			echo '
			<table><thead><tr>
			<td>Ref</td>
			<td>Cor</td>
			<td>P <span style="font-size:10pt">ou 4</span></td>
			<td>M <span style="font-size:10pt">ou 6</span></td>
			<td>G <span style="font-size:10pt">ou 8</span></td>
			<td>GG <span style="font-size:10pt">ou 10</span></td>
			<td>EG <span style="font-size:10pt">ou 12</span></td>
			<td>Lote</span></td>
			</tr></thead>
			
			<form method="post" id="atualote" action="?acao=uplote">
			<tr><td><input type="text" name="ref" size="5" autofocus/></td>
			<td><input type="text" name="cor" size="7"/></td>
			<td><input type="number" name="t1" size="2"/></td>
			<td><input type="number" name="t2" size="2"/></td>
			<td><input type="number" name="t3" size="2"/></td>
			<td><input type="number" name="t4" size="2"/></td>
			<td><input type="number" name="t5" size="2"/></td>
			<td><input type="number" name="lote" size="2"/></td>
			</tr></table>
			<input type="submit" value="Atualizar linha de programação"/></form>';
		}
		if($_GET['acao']=='uplote'){
			$ref = mb_strtoupper($_POST['ref'],'UTF-8');
			$cor = mb_strtoupper($_POST['cor'],'UTF-8');
			$t[1] = $_POST['t1'];
			$t[2] = $_POST['t2'];
			$t[3] = $_POST['t3'];
			$t[4] = $_POST['t4'];
			$t[5] = $_POST['t5'];
			$lote = $_POST['lote'];
			
			if($lote==""){echo '<script>alert("Favor inserir número do lote!");window.location.href="?acao=atualizarlote";</script>';die();}
		
			require '../_auxiliares/teste_produto_e_cor.php';
			
			
			//acrescentar etiquetas na session
			
			if(isset($_SESSION['etiqueta'])){
				$i = max(array_keys($_SESSION['etiqueta']))+1;
			}else{
				$i=0;
			}
			for($k=1;$k<=5;$k++){			
			if($t[$k] > 0){	
				$_SESSION['etiqueta'][$i]['ref']=$ref;
				$_SESSION['etiqueta'][$i]['cor']=$cor;
				$testeinf = substr($ref,-1);
				if($testeinf == 'I'){
					if($k==1){$_SESSION['etiqueta'][$i]['tam']="4";}
					if($k==2){$_SESSION['etiqueta'][$i]['tam']="6";}
					if($k==3){$_SESSION['etiqueta'][$i]['tam']="8";}
					if($k==4){$_SESSION['etiqueta'][$i]['tam']="10";}
					if($k==5){$_SESSION['etiqueta'][$i]['tam']="12";}
				}else{
					if($k==1){$_SESSION['etiqueta'][$i]['tam']="P";}
					if($k==2){$_SESSION['etiqueta'][$i]['tam']="M";}
					if($k==3){$_SESSION['etiqueta'][$i]['tam']="G";}
					if($k==4){$_SESSION['etiqueta'][$i]['tam']="GG";}
					if($k==5){$_SESSION['etiqueta'][$i]['tam']="EG";}
				}
				$_SESSION['etiqueta'][$i]['qnt']=$t[$k];
				$qr="select descricao from produtos where ref='$ref'";
				$sql=mysqli_query($con,$qr);
				$ln=mysqli_fetch_assoc($sql);
				$_SESSION['etiqueta'][$i]['desc']=$ln['descricao'];
				$i++;
			}
			}
			//fim do add etiquetas
			
			
			
			for($i=1;$i<=5;$i++){
			if($t[$i] > 0){
				//para cada tamanho
				//verificar quantidade do lote que já cadastrado
				$qr = "select sum(t$i) from pcp where ref='$ref' and cor='$cor' and situacao = 'P' and lote=$lote";
				$sql= mysqli_query($con,$qr);
				$lnsumti=mysqli_fetch_assoc($sql);
				$tamped = $lnsumti['sum(t'.$i.')'];
				if($t[$i] >= $tamped){
					//tamanho que foi cortado atual do lote maior que a quant do lote que estava programado, ou seja destinado a pedidos - Situacao Normal
					$t[$i] -= $tamped;
					if($t[$i]>0){
						$qr = "select sum(t$i) from pcp where ref='$ref' and cor='$cor' and situacao = 'A'";
						$sql= mysqli_query($con,$qr);
						$lnsumtin=mysqli_fetch_assoc($sql);
						$tampedn = $lnsumtin['sum(t'.$i.')'];
						if($tampedn > 0){//verifica se existe pedido com A 
							//existe pedido com A
							$qr = "select t$i,loc from pcp
									join pedidos p on p.pedido = pcp.loc
									where ref='$ref' and cor='$cor' and situacao='A' and t$i>0
									order by dataentrega;";
							$sql= mysqli_query($con,$qr);
							$table = array();
							while($row = $sql -> fetch_assoc()){
								$table[] = $row;
							}
							/*$table= mysqli_fetch_all($sql,MYSQL_ASSOC);*/
							foreach($table as $ltable){
								if($t[$i] > 0){
									$loc =$ltable['loc'];
									$qr="select * from pcp where loc='$loc' and ref='$ref' and cor='$cor'";
									$sql=mysqli_query($con,$qr);
									$ln=mysqli_fetch_assoc($sql);
									$preco=$ln['preco'];
									$desconto=$ln['desconto'];
									//t[i] para cada pedido
									//verificar se existe linha P do pedido com lote..
									$qr="select * from pcp where ref='$ref' and cor='$cor' and lote=$lote and situacao='P' and loc='$loc'";
									$ver=mysqli_query($con,$qr);
									if(mysqli_num_rows($ver)<=0){
										//nao existe a linha P do lote pedido. -> inserir linha
										$qr="insert into pcp (ref,cor,t1,t2,t3,t4,t5,tot,loc,lote,dataalter,situacao,preco,desconto) values ('$ref','$cor',0,0,0,0,0,0,'$loc',$lote,default,'P',$preco,$desconto)";
										$sql=mysqli_query($con,$qr);
									}
									//modificar linha P do lote,pedido
									$qr="select t$i from pcp where ref='$ref' and cor='$cor' and lote=$lote and situacao='P' and loc='$loc'";
									$sql=mysqli_query($con,$qr);
									$ln=mysqli_fetch_assoc($sql);
									
									if($t[$i] >= $ltable['t'.$i]){
											//t[i] maior do que se precisa no pedido da vez
										//retirar valor da linha A do pedido e criar/modificar linha P do pedido;;
										$tinovop = $ln['t'.$i] + $ltable['t'.$i];
										$qr="update pcp set t$i=$tinovop,dataalter=default where ref='$ref' and cor='$cor' and lote=$lote and situacao='P' and loc='$loc'";
										$sql=mysqli_query($con,$qr);
										//atualizar linha A do pedido
										$qr= "update pcp set t$i=0,dataalter=default where ref='$ref' and cor='$cor' and loc='$loc' and situacao='A'";
										$sql=mysqli_query($con,$qr);
										//retirar de t[i]
										$t[$i] -= $ltable['t'.$i];
									}else{
											//t[i] é menor que a necessidade do pedido, t[i] vai zerar se isso acontecer..
										$tinovop = $ln['t'.$i] + $t[$i];
										$qr="update pcp set t$i=$tinovop,dataalter=default where ref='$ref' and cor='$cor' and lote=$lote and situacao='P' and loc='$loc'";
										$sql=mysqli_query($con,$qr);
										//atualizar linha A do pedido
										$tinp = $ltable['t'.$i] - $t[$i];
										$qr= "update pcp set t$i=$tinp,dataalter=default where ref='$ref' and cor='$cor' and loc='$loc' and situacao='A'";
										$sql=mysqli_query($con,$qr);
										//retirar de t[i]
										$t[$i] = 0;
										//atualizar tot da situacao A
										$qr = "select * from pcp where ref='$ref' and cor='$cor' and loc='$loc' and situacao = 'A'";
										$sql = mysqli_query($con,$qr);
										$lna = mysqli_fetch_assoc($sql);
										$totna = $lna['t1']+$lna['t2']+$lna['t3']+$lna['t4']+$lna['t5'];
										if($totna == 0){
											//se tot = 0;deletar linha..
											$qr="delete from pcp where ref='$ref' and cor='$cor' and loc= '$loc' and situacao='A'";
											$sql = mysqli_query($con,$qr);
										}else{
											$qr= "update pcp set tot=$totna where ref='$ref' and cor='$cor' and loc='$loc' and situacao = 'A'";
											$sql = mysqli_query($con,$qr);
										}
										//atualizar tot da situacao P
										$qr = "select * from pcp where ref='$ref' and cor='$cor' and lote=$lote and situacao='P' and loc='$loc'";
										$sql = mysqli_query($con,$qr);
										$lna = mysqli_fetch_assoc($sql);
										$totna = $lna['t1']+$lna['t2']+$lna['t3']+$lna['t4']+$lna['t5'];
										if($totna == 0){
											//se tot = 0;deletar linha..
											$qr="delete from pcp where ref='$ref' and cor='$cor' and loc= '$loc' and situacao='P' and lote=$lote";
											$sql = mysqli_query($con,$qr);
										}else{	
											$qr= "update pcp set tot=$totna where ref='$ref' and cor='$cor' and loc='$loc' and lote=$lote and situacao='P'";
											$sql = mysqli_query($con,$qr);
										}
										break;
									}
									//atualizar tot da situacao A
									$qr = "select * from pcp where ref='$ref' and cor='$cor' and loc='$loc' and situacao = 'A'";
									$sql = mysqli_query($con,$qr);
									$lna = mysqli_fetch_assoc($sql);
									$totna = $lna['t1']+$lna['t2']+$lna['t3']+$lna['t4']+$lna['t5'];
									if($totna == 0){
										//se tot = 0;deletar linha..
										$qr="delete from pcp where ref='$ref' and cor='$cor' and loc= '$loc' and situacao='A'";
										$sql = mysqli_query($con,$qr);
									}else{
										$qr= "update pcp set tot=$totna where ref='$ref' and cor='$cor' and loc='$loc' and situacao = 'A'";
										$sql = mysqli_query($con,$qr);
									}
									//atualizar tot da situacao P
									$qr = "select * from pcp where ref='$ref' and cor='$cor' and lote=$lote and situacao='P' and loc='$loc'";
									$sql = mysqli_query($con,$qr);
									$lna = mysqli_fetch_assoc($sql);
									$totna = $lna['t1']+$lna['t2']+$lna['t3']+$lna['t4']+$lna['t5'];
									if($totna == 0){
										//se tot = 0;deletar linha..
										$qr="delete from pcp where ref='$ref' and cor='$cor' and loc= '$loc' and situacao='P' and lote=$lote";
										$sql = mysqli_query($con,$qr);
									}else{	
										$qr= "update pcp set tot=$totna where ref='$ref' and cor='$cor' and loc='$loc' and lote=$lote and situacao='P'";
										$sql = mysqli_query($con,$qr);
									}
								}	
							}
							if($t[$i]>0){
								//verifica se existe linha de estoque pro lote,ref,cor,situacaoP
								$qr="select * from pcp where ref='$ref' and cor='$cor' and lote=$lote and situacao='P' and loc='estoque'";
								$ver=mysqli_query($con,$qr);
								if(mysqli_num_rows($ver)<=0){
									//nao existe a linha P do lote e loc='estoque'. -> inserir linha
									$qr="select preco from produtos where ref='$ref'";
									$sql=mysqli_query($con,$qr);
									$ln=mysqli_fetch_assoc($sql);
									$precolnest=$ln['preco'];	
									$descontolnest=0;
									$qr="insert into pcp (ref,cor,t1,t2,t3,t4,t5,tot,loc,lote,dataalter,situacao,preco,desconto) values ('$ref','$cor',0,0,0,0,0,0,'estoque',$lote,default,'P',$precolnest,$descontolnest)";
									$sql=mysqli_query($con,$qr);
								}
								//modificar linha P do lote,loc='estoque'
								$qr="update pcp set t$i=$t[$i],dataalter=default where ref='$ref' and cor='$cor' and lote=$lote and situacao='P' and loc='estoque'";
								$sql=mysqli_query($con,$qr);
								$t[$i] = 0;
								//atualizar tot da linha do 'estoque'
								$qr = "select * from pcp where ref='$ref' and cor='$cor' and loc='estoque' and situacao = 'P' and lote=$lote";
								$sql = mysqli_query($con,$qr);
								$lna = mysqli_fetch_assoc($sql);
								$totna = $lna['t1']+$lna['t2']+$lna['t3']+$lna['t4']+$lna['t5'];
								$qr= "update pcp set tot=$totna where ref='$ref' and cor='$cor' and loc='estoque' and situacao = 'P' and lote=$lote";
								$sql = mysqli_query($con,$qr);
								
							}
							
						}else{
							//nao existe pedido com A neste t[i];
							//verificar se existe linha de P pra este lote.
							$qr="select * from pcp where lote=$lote and situacao='P' and loc='estoque' and ref='$ref' and cor='$cor'";
							$ver=mysqli_query($con,$qr);
							if(mysqli_num_rows($ver)<=0){
								//nao existe a linha de estoque P do lote. -> inserir linha
								$qr="select preco from produtos where ref='$ref'";
								$sql=mysqli_query($con,$qr);
								$ln=mysqli_fetch_assoc($sql);
								$precolnest=$ln['preco'];	
								$descontolnest=0;
								$qr="insert into pcp (ref,cor,t1,t2,t3,t4,t5,tot,loc,lote,dataalter,situacao,preco,desconto) values ('$ref','$cor',0,0,0,0,0,0,'estoque',$lote,default,'P',$precolnest,$descontolnest)";
								$sql=mysqli_query($con,$qr);
							}
							//atualizar t[i] na linha de estoque P do lote
							$qr="select * from pcp where lote='$lote' and situacao='P' and loc='estoque' and ref='$ref' and cor='$cor'";
							$sql=mysqli_query($con,$qr);
							$ln=mysqli_fetch_assoc($sql);
							$tinovopest = $ln['t'.$i] + $t[$i];
							$qr="update pcp set t$i=$tinovopest,dataalter=default where lote=$lote and situacao='P' and loc='estoque' and cor='$cor' and ref='$ref'";
							$sql=mysqli_query($con,$qr);
							$t[$i] = 0;
							//atualizar tot da linha do 'estoque'
							$qr = "select * from pcp where ref='$ref' and cor='$cor' and loc='estoque' and situacao = 'P' and lote=$lote";
							$sql = mysqli_query($con,$qr);
							$lna = mysqli_fetch_assoc($sql);
							$totna = $lna['t1']+$lna['t2']+$lna['t3']+$lna['t4']+$lna['t5'];
							$qr= "update pcp set tot=$totna where ref='$ref' and cor='$cor' and loc='estoque' and situacao = 'P' and lote=$lote";
							$sql = mysqli_query($con,$qr);
						}
					}	
				}else{
					//situacao anormal: foi cortado menos que a programacao previa
					$difret = $tamped - $t[$i];
					$qr = "select t$i,loc from pcp
					join pedidos p on p.pedido = pcp.loc
					where ref='$ref' and cor='$cor' and situacao='P' and lote=$lote
					order by dataentrega desc";
					$sql= mysqli_query($con,$qr);
					$table = array();
					while($row = $sql -> fetch_assoc()){
						$table[] = $row;
					}
					/*$table= mysqli_fetch_all($sql,MYSQL_ASSOC);*/
					foreach($table as $ltable){
						$loc = $ltable['loc'];
						$qr="select preco,desconto from pcp where loc='$loc' and ref='$ref'";
						$sql=mysqli_query($con,$qr);
						if(mysqli_num_rows($sql)>0){
							$ln=mysqli_fetch_assoc($sql);
							$preco=$ln['preco'];
							$desconto=$ln['desconto'];
						}else{
							$qr="select preco from produtos where and ref='$ref'";
							$sql=mysqli_query($con,$qr);
							$ln=mysqli_fetch_assoc($sql);
							$preco=$ln['preco'];
							$desconto=0;
							
						}
						
						//verificar se existe linha A do pedido, se pedido não for estoque..
						$qr = "select t$i from pcp where ref='$ref' and cor='$cor' and situacao='A' and loc='$loc'";
						$ver = mysqli_query($con,$qr);
						if((mysqli_num_rows($ver)<=0)and($loc<>'estoque')){
							//nao existe linha A ->criá-la
							$qr="insert into pcp (ref,cor,t1,t2,t3,t4,t5,tot,loc,lote,dataalter,situacao,preco,desconto) values ('$ref','$cor',0,0,0,0,0,0,'$loc',0,default,'A',$preco,$desconto)";
							$sql=mysqli_query($con,$qr);
						}
						$qr = "select t$i from pcp where ref='$ref' and cor='$cor' and situacao='A' and loc='$loc'";
						$sql = mysqli_query($con,$qr);
						$lna = mysqli_fetch_assoc($sql);
						
						if($ltable['t'.$i]<$difret){
							//diferenca é maior que qnt que estava P pra este pedido
							$tina = $lna['t'.$i] + $ltable['t'.$i];
							$qr="update pcp set t$i=$tina,dataalter=default where ref='$ref' and cor='$cor' and loc='$loc' and situacao='A'";
							$sql= mysqli_query($con,$qr);
							
							//zerar linha P do pedido que perdeu sua programação
							$qr="update pcp set t$i=0 where ref='$ref' and cor='$cor' and loc='$loc' and situacao='P' and lote=$lote"; 
							$sql= mysqli_query($con,$qr);
							
							$difret -= $ltable['t'.$i];
						}else{
							//P neste pedido maior que diferenca..este pedido vai perder sua programacao e diferenca vai zerar..
							$tina = $lna['t'.$i] + $difret;
							$qr="update pcp set t$i=$tina,dataalter=default where ref='$ref' and cor='$cor' and loc='$loc' and situacao='A'";
							$sql= mysqli_query($con,$qr);
							//atualizar linha P do pedido que diminuiu sua programação
							$qr = "select * from pcp where ref='$ref' and cor='$cor' and loc='$loc' and situacao = 'P' and lote=$lote";
							$sql = mysqli_query($con,$qr);
							$lna = mysqli_fetch_assoc($sql);
							$tinp = $lna['t'.$i] - $difret;
							$qr="update pcp set t$i=$tinp,dataalter=default where ref='$ref' and cor='$cor' and loc='$loc' and situacao='P' and lote=$lote"; 
							$sql= mysqli_query($con,$qr);
							$difret = 0;
						}
						//atualizar tot da situacao A
						$qr = "select * from pcp where ref='$ref' and cor='$cor' and loc='$loc' and situacao = 'A'";
						$sql = mysqli_query($con,$qr);
						$lna = mysqli_fetch_assoc($sql);
						$totna = $lna['t1']+$lna['t2']+$lna['t3']+$lna['t4']+$lna['t5'];
						if($totna == 0){
							//se tot = 0;deletar linha..
							$qr="delete from pcp where ref='$ref' and cor='$cor' and loc= '$loc' and situacao='A'";
							$sql = mysqli_query($con,$qr);
						}else{
							$qr= "update pcp set tot=$totna,dataalter=default where ref='$ref' and cor='$cor' and loc='$loc' and situacao = 'A'";
							$sql = mysqli_query($con,$qr);
						}	
						//atualizar tot da situacao P
						$qr = "select * from pcp where ref='$ref' and cor='$cor' and loc='$loc' and situacao='P' and lote=$lote";
						$sql = mysqli_query($con,$qr);
						$lna = mysqli_fetch_assoc($sql);
						$totna = $lna['t1']+$lna['t2']+$lna['t3']+$lna['t4']+$lna['t5'];
						if($totna == 0){
							//se tot = 0;deletar linha..
							$qr="delete from pcp where ref='$ref' and cor='$cor' and loc='$loc' and situacao='P' and lote=$lote";
							$sql = mysqli_query($con,$qr);
						}else{
							$qr= "update pcp set tot=$totna,dataalter=default where ref='$ref' and cor='$cor' and loc='$loc' and lote=$lote and situacao='P'";
							$sql = mysqli_query($con,$qr);
						}
					}
				}	
			}
			}
			echo '<script>window.location.href="?acao=atualizarlote";</script>';
		}
		if($_GET['acao']=='etiquetas'){
			if(isset($_GET['apagar'])){
				unset($_SESSION['etiqueta']);
			}
			if(isset($_GET['apagarlinha'])){
				$k = $_GET['apagarlinha'];
				unset($_SESSION['etiqueta'][$k]);
			}
			if(isset($_POST['ref'])){
				if(isset($_SESSION['etiqueta'])){
					$i = max(array_keys($_SESSION['etiqueta']))+1;
				}else{
					$i=0;
				}
				$ref=mb_strtoupper($_POST['ref'],'UTF-8');
				for($cont=1;$cont<=5;$cont++){
				if($_POST['t'.$cont]>0){
					$_SESSION['etiqueta'][$i]['ref']=$ref;
					$_SESSION['etiqueta'][$i]['cor']=mb_strtoupper($_POST['cor'],'UTF-8');
					$testinf = substr($ref,-1);
					if($testinf == "I"){
						if($cont == 1){$tam='4';} 
						elseif($cont == 2){$tam='6';}
						elseif($cont == 3){$tam='8';}
						elseif($cont == 4){$tam='10';}
						elseif($cont == 5){$tam='12';}
					}else{
						if($cont == 1){$tam='P';} 
						elseif($cont == 2){$tam='M';}
						elseif($cont == 3){$tam='G';}
						elseif($cont == 4){$tam='GG';}
						elseif($cont == 5){$tam='EG';}
					}	
					
					$_SESSION['etiqueta'][$i]['tam']=$tam;
					$_SESSION['etiqueta'][$i]['qnt']=$_POST['t'.$cont];
					$qr="select descricao from produtos where ref='$ref'";
					$sql=mysqli_query($con,$qr);
					$ln=mysqli_fetch_assoc($sql);
					$_SESSION['etiqueta'][$i]['desc']=$ln['descricao'];
					$i++;
				}
				}
				
				
			}
			if(isset($_SESSION['etiqueta'])){
				echo '<div class="underline">
				Existem etiquetas na memória para serem impressas:
				<form method="post" action="?acao=etiquetas&apagar=s">
				<input type="submit" value="Apagar Etiquetas"/>
				</form>
				</div>
				</br>
				<div class="underline">
				<table>
				<thead>
				<td>Ref.</td>
				<td>Descrição</td>
				<td>Cor</td>
				<td>Tam</td>
				<td>Qnt</td>
				<td></td>
				</thead>
				<tbody>';
				$total=0;
				$contador=0;
				$keys=array_keys($_SESSION['etiqueta']);
				foreach($_SESSION['etiqueta'] as $prod){
					echo '
					<tr>
					<td>'.$prod['ref'].'</td>
					<td>'.$prod['desc'].'</td>
					<td>'.$prod['cor'].'</td>
					<td>'.$prod['tam'].'</td>
					<td>'.$prod['qnt'].'</td>
					<td><a href=?acao=etiquetas&apagarlinha='.$keys[$contador].'>Apagar linha</a></td>
					</tr>';
					$total += $prod['qnt'];
					$contador ++;
				}
				echo '
				<tr>
				<td></td>
				<td></td>
				<td colspan="2"><b>Total:</b></td>
				<td><b>'.$total.'</b></td>
				</tr>
				</tbody>
				</table>	
				';
				
				echo '<form method="post" action="etiqueta.php" target="_blank">
				<input type="submit" value="Gerar arquivo de impressão"/>
				</form></div>
				</br>
				';
			}
			echo 
			'<form method="post" action="?acao=etiquetas">
			<label for="ref">Ref.:</label><input type="text" name="ref" id="ref" size="3" autofocus/>
			<label for="cor">Cor:</label><input type="text" name="cor" id="cor" size="10"/>
			<label for="t1">P<span style="font-size:10pt;">(ou 4)</span>:</label><input type="number" name="t1" id="t1" size="1"/>
			<label for="t2">M<span style="font-size:10pt;">(ou 6)</span>:</label><input type="number" name="t2" id="t2" size="1"/>
			<label for="t3">G<span style="font-size:10pt;">(ou 8)</span>:</label><input type="number" name="t3" id="t3" size="1"/>
			<label for="t4">GG<span style="font-size:10pt;">(ou 10)</span>:</label><input type="number" name="t4" id="t4" size="1"/>
			<label for="t5">EG<span style="font-size:10pt;">(ou 12)</span>:</label><input type="number" name="t5" id="t5" size="1"/>
			<input type="submit" value="Add etiquetas"/>
			';
		}
		if($_GET['acao']=='previsao'){
			$stmt = $dbh->prepare("SELECT SUM( pcp.tot * p.tempo ) /60 FROM pcp JOIN produtos p ON p.ref = pcp.ref WHERE situacao =  'P'");
			$stmt->execute();
			$ln=$stmt->fetchAll();
			echo 'O tempo de costura para toda programação é de '. number_format($ln[0][0],2,',','.').' horas';
		}
	}
	else{
		//parte para teste de código
		echo date('d/m/Y');
	}
		
	?>
	</div>
</body>
</html>
 