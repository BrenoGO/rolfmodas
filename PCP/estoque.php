<!DOCTYPE html>
<html>
<head>
  <?php
	require '../head.php';
	?>
  <title>Estoque</title>
	
</head>
<body>
	<?php
	require '../config.php';
	require 'config.php';
	
	require '../header_geral.php';
	require 'menu.php';
	
	echo'
	<nav id="submenu">
	<a href="?acao=consulta"><button>Consulta Estoque</button></a>
	<a href="?acao=formaddlinhapcp"><button>Adicionar linha no PCP</button></a>
	<a href="?acao=alterest"><button>Alterar linha do PCP</button></a>
	<a href="?acao=verificfots"><button>Verifica prod sem foto</button></a>
	<a href="?acao=modAdv"><button>Mod Av</button></a>
	</nav>
	</header>
	<div class="corpo">';
	
	if(isset($_GET['acao'])){
		
		if($_GET['acao']=='consulta'){
			echo 
			'<form method="post" id="consultest" action="?acao=resultcon">
			<label for="ref">Ref:</label><input type="text" name="ref" id="ref" size="5" maxlength="6" autofocus/>
			<label for="cor">Cor:</label><input type="text" name="cor" id="cor" size="5"/>
			<input type="radio" name="tipo" id="disp" value="disp"/><label for="disp">Disponível</label>
			<input type="radio" name="tipo" id="total" value="total"/><label for="total">Total</label>
			<input type="submit" name="submit" value="Consultar estoque" />
			<input type="reset" id="limpar" name="limpar" value="Limpar"/>
			</form>	';
		}
		if($_GET['acao']=='resultcon'){
			$ref = $_POST['ref'];
			$cor = $_POST['cor'];
			if(isset($_POST['tipo'])){$tipo = $_POST['tipo'];}
			
			$qr="select sum(t1),sum(t2),sum(t3),sum(t4),sum(t5),sum(tot) from pcp where ref='$ref'";
			if(isset($tipo)){if($tipo == "disp"){$qr .=" and loc='estoque'";} }
			if($cor <> ""){$qr .=" and cor='$cor'";} 
			$qr .= " and situacao='S'";
			$sql=mysqli_query($con,$qr);
			$ln=mysqli_fetch_assoc($sql);
			$t1 = $ln['sum(t1)'];
			$t2 = $ln['sum(t2)'];
			$t3 = $ln['sum(t3)'];
			$t4 = $ln['sum(t4)'];
			$t5 = $ln['sum(t5)'];
			$tot = $ln['sum(tot)'];
				
			echo '<table>
			<tr><td>Ref</td>';
			if($cor <> ""){echo '<td>Cor</td>';} 
			echo '<td>P <span style="font-size:10pt">ou 4</span></td><td>M <span style="font-size:10pt">ou 6</span></td><td>G <span style="font-size:10pt">ou 8</span></td><td>GG <span style="font-size:10pt">ou 10</span></td><td>EG <span style="font-size:10pt">ou 12</span></td><td>Total</td></tr>
			<tr><td>'.$ref.'</td>';
			if($cor <> ""){echo '<td>'.$cor.'</td>';}
			echo '<td>'.$t1.'</td><td>'.$t2.'</td><td>'.$t3.'</td><td>'.$t4.'</td><td>'.$t5.'</td><td>'.$tot.'</td></tr></table>';
		}
		if($_GET['acao']=='formaddlinhapcp'){
			echo 
			'<table>
			<thead><tr>
			<td>Ref</td>
			<td>Cor</td>
			<td>P <span style="font-size:10pt">ou 4</span></td>
			<td>M <span style="font-size:10pt">ou 6</span></td>
			<td>G <span style="font-size:10pt">ou 8</span></td>
			<td>GG <span style="font-size:10pt">ou 10</span></td>
			<td>EG <span style="font-size:10pt">ou 12</span></td>
			<td>lote</td>
			<td>Situação</td>
			<td>Pedido</td>
			</tr></thead>
			<form method="post" id="formaddlinhapcp" action="?acao=addlinhapcp">
			<tr><td><input type="text" name="ref" id="ref" size="3" maxlength="6" autofocus/></td>
			<td><input type="text" name="cor" id="cor" size="5"/></td>
			<td><input class="center" type="number" name="t1" id="t1" value="0"/></td>
			<td><input class="center" type="number" name="t2" id="t2" value="0"/></td>
			<td><input class="center" type="number" name="t3" id="t3" value="0"/></td>
			<td><input class="center" type="number" name="t4" id="t4" value="0"/></td>
			<td><input class="center" type="number" name="t5" id="t5" value="0"/></td>
			<td><input class="center" type="number" name="lote" id="lote" value="0"/></td>
			<td><input type="text" name="situacao" id="situacao" size="3"/></td>
			<td><input type="text" name="pedido" id="pedido" size="3"/></td>
			</tr></table>
			<input type="submit" name="submit" value="Inserir linha" />
			<input type="reset" id="limpar" name="limpar" value="Limpar"/>
			</form>';
		}
		if($_GET['acao'] == 'addlinhapcp'){
			
			$ref=mb_strtoupper($_POST['ref'], 'UTF-8');
			$cor=mb_strtoupper($_POST['cor'], 'UTF-8');
			$t[1] = $_POST['t1'];
			$t[2] = $_POST['t2'];
			$t[3] = $_POST['t3'];
			$t[4] = $_POST['t4'];
			$t[5] = $_POST['t5'];
			$tot = $t[1]+$t[2]+$t[3]+$t[4]+$t[5];
			$lote = $_POST['lote'];
			$situacao = strtoupper($_POST['situacao']);
			if($situacao == ""){$situacao = 'A';}
			$pedido = $_POST['pedido'];
			if($pedido == ""){$pedido = 'estoque';}
			
			$query= "select * from produtos where ref ='$ref'";
			$verificaref= mysqli_query($con,$query);
			$query= "select * from cores where nomecor ='$cor'";
			$verificacor= mysqli_query($con,$query);
			$query= "select * from pedidos where pedido ='$pedido'";
			$verificapedido= mysqli_query($con,$query);
			if(mysqli_num_rows($verificaref)<=0){
				//verifica se referencia existe
				echo"<script language='javascript' type='text/javascript'>alert('Referência inexistente! Cadastre o produto antes de inserir a linha!');</script>";
				die();
			}elseif(mysqli_num_rows($verificacor)<=0){
				//verifica se cor existe
				echo"<script language='javascript' type='text/javascript'>alert('Cor inexistente! Cadastre a cor antes de inserir a linha!');</script>";
				die();
			}elseif(mysqli_num_rows($verificapedido)<=0){
				//verifica se cor existe
				echo"<script language='javascript' type='text/javascript'>alert('Pedido inexistente! Cadastre o pedido antes de inserir a linha!');</script>";
				die();
			}else{
				if($pedido <>'estoque'){				
					$qr="select preco,desconto from pcp where ref='$ref' and loc='$pedido'";
					$sql=mysqli_query($con,$qr);
					$ln=mysqli_fetch_assoc($sql);
					$preco=$ln['preco'];
					$desconto=$ln['desconto'];
					if(!isset($preco)){
						$qr="select preco from produtos where ref='$ref'";
						$sql=mysqli_query($con,$qr);
						$ln=mysqli_fetch_assoc($sql);
						$preco=$ln['preco'];
						$desconto=0;
					}
					
				}else{
					$qr="select preco from produtos where ref='$ref'";
					$sql=mysqli_query($con,$qr);
					$ln=mysqli_fetch_assoc($sql);
					$preco=$ln['preco'];
					$desconto=0;
				}
				$qr = "insert into pcp (ref,cor,t1,t2,t3,t4,t5,tot,loc,lote,dataalter,situacao,preco,desconto) values ('$ref','$cor',$t[1],$t[2],$t[3],$t[4],$t[5],$tot,'$pedido',$lote,default,'$situacao',$preco,$desconto)";
				$sql = mysqli_query($con,$qr);
				if($sql){echo '<h2>Linha inserida com sucesso</h2>';}
			}
		}
		if($_GET['acao'] == 'modAdv'){
			if(!isset($_GET['after'])){
				echo '<h2>Modificação de Ref, Cor, Preço ou desconto nas linhas do PCP.</h2>
				<h3>Trabalhe com cuidado esses dados...</h3>
				<form method="post" id="alterest" action="?acao=modAdv&after&List">
				<label for="ref">Ref:</label><input type="text" name="ref" id="ref" size="5" maxlength="6" autofocus/>
				<label for="cor">Cor:</label><input type="text" name="cor" id="cor" size="5"/>
				<label for="pedido">Pedido:</label><input type="text" name="pedido" id="pedido" size="5"/>
				<label for="lote">Lote:</label><input type="number" name="lote" id="lote" size="5"/></br>
				Data Entrega entre:<input type="date" name="dataentrini" id="dataentrini"/> e <input type="date" name="dataentrfin" id="dataentrfin"/></br>
				<input type="checkbox" name="sep" id="sep" value="S"/><label for="sep">Separado</label>
				<input type="checkbox" name="can" id="can" value="C"/><label for="can">Cancelado</label>
				<input type="checkbox" name="pro" id="pro" value="P"/><label for="pro">Programado</label>
				<input type="checkbox" name="agu" id="agu" value="A"/><label for="agu">Aguardando Programação</label>
				<input type="checkbox" name="ent" id="ent" value="E"/><label for="ent">Entregue</label></br></br>
				<input type="submit" name="submit" value="Verificar Linhas" />
				<input type="reset" id="limpar" name="limpar" value="Limpar"/>
				</form>	';
			} 
			if(isset($_GET['List'])){//lista
				$ref = $_POST['ref'];
				$cor = $_POST['cor'];
				$pedido = $_POST['pedido'];
				$pedido= check_zeros_pedido($pedido);
				$dataentrini = $_POST['dataentrini'];
				$dataentrfin = $_POST['dataentrfin'];
				$lote = $_POST['lote'];
				$z=0;
				if(isset($_POST['sep'])){$sep = $_POST['sep'];$z++;}
				if(isset($_POST['can'])){$can = $_POST['can'];$z++;}
				if(isset($_POST['pro'])){$pro = $_POST['pro'];$z++;}
				if(isset($_POST['agu'])){$agu = $_POST['agu'];$z++;}
				if(isset($_POST['ent'])){$ent = $_POST['ent'];$z++;}
							
				echo '<table>
				<thead><tr>
				<td>Ref</td>
				<td>Cor</td>
				<td>P <span style="font-size:10pt">ou 4</span></td>
				<td>M <span style="font-size:10pt">ou 6</span></td>
				<td>G <span style="font-size:10pt">ou 8</span></td>
				<td>GG <span style="font-size:10pt">ou 10</span></td>
				<td>EG <span style="font-size:10pt">ou 12</span></td>
				<td>Total</td>
				<td>lote</td>
				<td>Situação</td>
				<td>Pedido</td>
				<td>Preço</td>
				<td>Desconto</td>
				</tr></thead>';
				  
				
				$qr= "select pcp.ref,pcp.cor,pcp.t1,pcp.t2,pcp.t3,pcp.t4,pcp.t5,pcp.tot,pcp.lote,pcp.situacao,p.pedido,p.dataentrega,pcp.preco,pcp.desconto from pcp 
					join pedidos p on p.pedido = pcp.loc
					where ";
				if($ref <>""){$qr .= "pcp.ref='$ref' and ";}
				if($cor <>""){$qr .= "pcp.cor='$cor' and ";}
				if($lote <>""){$qr .= "pcp.lote='$lote' and ";}
				if($pedido <>""){$qr .= "pcp.loc='$pedido' and ";}
				if($dataentrini <>""){$qr .= "p.dataentrega >= '$dataentrini' and ";}
				if($dataentrfin <>""){$qr .= "p.dataentrega <= '$dataentrfin' and ";}

				if($z == 1){
					if(isset($sep)){$qr .="situacao='S' and ";}
					if(isset($can)){$qr .="situacao='C' and ";}
					if(isset($pro)){$qr .="situacao='P' and ";}
					if(isset($agu)){$qr .="situacao='A' and ";}
					if(isset($ent)){$qr .="situacao='E' and ";}
				}
				if($z >= 2){
					$w = 0;
					if(isset($sep)){$qr .="(situacao='S'";$w++;}
					if(isset($can)){
						if($w == 0){$qr .="(situacao='C'";$w++;
						}else{$qr .=" or situacao='C'";}
					}
					if(isset($pro)){
						if($w == 0){$qr .="(situacao='P'";$w++;
						}else{$qr .=" or situacao='P'";}
					}
					if(isset($agu)){
						if($w == 0){$qr .="(situacao='A'";$w++;
						}else{$qr .=" or situacao='A'";}
					}
					if(isset($ent)){
						$qr .=" or situacao='E'";
					}
					$qr .=") and ";
				}
				$qr .= "tot>0 order by ref,cor,dataentrega,loc";
				
				$values=array();
				$tablesql = fetchToArray($dbh,$qr,$values);
							
				echo '<form method="post" id="modlinhas" action="?acao=modAdv&after&Update">';
				
				$k = 0;
				foreach($tablesql as $linhasql){
					echo '<tr>
					<td><input class="center" name="novaRef'.$k.'" type="text" value="'.$linhasql['ref'].'" size="3"/>
					</td>
					<input type="hidden" name="ref'.$k.'" value="'.$linhasql['ref'].'"/>

					<td><input class="center" name="novaCor'.$k.'" type="text" value="'.$linhasql['cor'].'" size="7"/>
					</td>
					<input type="hidden" name="cor'.$k.'" value="'.$linhasql['cor'].'"/>
					<td>'.$linhasql['t1'].'</td>
					<td>'.$linhasql['t2'].'</td>
					<td>'.$linhasql['t3'].'</td>
					<td>'.$linhasql['t4'].'</td>
					<td>'.$linhasql['t5'].'</td>
					<td>'.$linhasql['tot'].'</td>
					
					<td>'.$linhasql['lote'].'</td>
					<input type="hidden" name="lote'.$k.'" value="'.$linhasql['lote'].'"/>

					<td>'.$linhasql['situacao'].'</td>
					<input type="hidden" name="situacao'.$k.'" value="'.$linhasql['situacao'].'"/>
					
					<td>'.$linhasql['pedido'].'</td>
					<input type="hidden" name="pedido'.$k.'" value="'.$linhasql['pedido'].'"/>

					<td><input class="center" name="preco'.$k.'" type="text" value="'.number_format($linhasql['preco'],2,',','.').'" size="7"/>
					</td>

					<td><input class="center" name="desconto'.$k.'" type="text" value="'.number_format($linhasql['desconto'],2,',','.').'" size="7"/>
					</td>

					</tr>';
					$k++;
				}
				echo '</table>';
				
				if(empty($tablesql)){
					echo '</br>Não existe a linha que procurou...';
				}else{ echo '<input type="submit" name="submit" value="Atualizar valores" />';}
				echo '</form>';
			}
			if(isset($_GET['Update'])){//atualiza BD
				for($k=0;isset($_POST['ref'.$k]);$k++){
					$ref = $_POST['ref'.$k];
					$novaRef = strtoupper($_POST['novaRef'.$k]);
					$novaCor = strtoupper($_POST['novaCor'.$k]);
					$cor = $_POST['cor'.$k];
					$lote = $_POST['lote'.$k];
					$situacao = $_POST['situacao'.$k];
					$pedido = $_POST['pedido'.$k];
					$pedido= check_zeros_pedido($pedido);
					$preco = str_replace(',','.',$_POST['preco'.$k]);
					$desconto = str_replace(',','.',$_POST['desconto'.$k]);

					//Verificações:
					if($ref <> $novaRef or $cor <> $novaCor){//terá alteração de cor ou ref..
						//Verificar se linha existe:
						$qr='select * from pcp where ref=? and cor=? and lote=? and loc=? and situacao=?';
						$values = array($novaRef,$novaCor,$lote,$pedido,$situacao);
						if(seQrExiste($dbh,$qr,$values)){
							echo '<b>Linha já Existente!!!</b>';
							die();
						}

						//Verificar se ref é cadastrada:
						$table= 'produtos';
						$ind = 'ref';
						$varVal = $novaRef;
						if(!seExiste($dbh,$table,$ind,$varVal)){
							echo '<b>Ref. não existe no cadastro de produtos!</b>';
							die();
						}
						//Verficar se Cor é cadastrada:
						$table= 'cores';
						$ind = 'nomecor';
						$varVal = $novaCor;
						if(!seExiste($dbh,$table,$ind,$varVal)){
							echo '<b>Cor não está no cadastro de cores!</b>';
							die();
						}
					}
					



					//Fim das Verificações

					$qr='update pcp set ref=?,cor=?,preco=?,desconto=? where ref=? and cor=? and lote=? and loc=? and situacao=?';
					$values=array($novaRef,$novaCor,$preco,$desconto,$ref,$cor,$lote,$pedido,$situacao);
					executeSQL($dbh,$qr,$values);
				}
				echo 'Dados atualizados';
			}
		}
		if($_GET['acao']=='alterest'){
			echo '<form method="post" id="alterest" action="?acao=formalter">
			<label for="ref">Ref:</label><input type="text" name="ref" id="ref" size="5" maxlength="6" autofocus/>
			<label for="cor">Cor:</label><input type="text" name="cor" id="cor" size="5"/>
			<label for="pedido">Pedido:</label><input type="text" name="pedido" id="pedido" size="5"/>
			<label for="lote">Lote:</label><input type="number" name="lote" id="lote" size="5"/></br>
			Data Entrega entre:<input type="date" name="dataentrini" id="dataentrini"/> e <input type="date" name="dataentrfin" id="dataentrfin"/></br>
			<input type="checkbox" name="sep" id="sep" value="S"/><label for="sep">Separado</label>
			<input type="checkbox" name="can" id="can" value="C"/><label for="can">Cancelado</label>
			<input type="checkbox" name="pro" id="pro" value="P"/><label for="pro">Programado</label>
			<input type="checkbox" name="agu" id="agu" value="A"/><label for="agu">Aguardando Programação</label>
			<input type="checkbox" name="ent" id="ent" value="E"/><label for="ent">Entregue</label></br></br>
			<input type="submit" name="submit" value="Verificar Linhas" />
			<input type="reset" id="limpar" name="limpar" value="Limpar"/>
			</form>	';
		}
		if($_GET['acao']=='formalter'){
			$ref = $_POST['ref'];
			$cor = $_POST['cor'];
			$pedido = $_POST['pedido'];
			$pedido= check_zeros_pedido($pedido);
			$dataentrini = $_POST['dataentrini'];
			$dataentrfin = $_POST['dataentrfin'];
			$lote = $_POST['lote'];
			$z=0;
			if(isset($_POST['sep'])){$sep = $_POST['sep'];$z++;}
			if(isset($_POST['can'])){$can = $_POST['can'];$z++;}
			if(isset($_POST['pro'])){$pro = $_POST['pro'];$z++;}
			if(isset($_POST['agu'])){$agu = $_POST['agu'];$z++;}
			if(isset($_POST['ent'])){$ent = $_POST['ent'];$z++;}
						
			echo '<table>
			<thead><tr>
			<td>Ref</td>
			<td>Cor</td>
			<td>P <span style="font-size:10pt">ou 4</span></td>
			<td>M <span style="font-size:10pt">ou 6</span></td>
			<td>G <span style="font-size:10pt">ou 8</span></td>
			<td>GG <span style="font-size:10pt">ou 10</span></td>
			<td>EG <span style="font-size:10pt">ou 12</span></td>
			<td>Total</td>
			<td>lote</td>
			<td>Situação</td>
			<td>Pedido</td>';
			/*<td>Cliente</td>*/
			echo '<td>Prazo</td>
			</tr></thead>';
			  
			
			$qr= "select pcp.ref,pcp.cor,pcp.t1,pcp.t2,pcp.t3,pcp.t4,pcp.t5,pcp.tot,pcp.lote,pcp.situacao,p.pedido,p.dataentrega from pcp 
				join pedidos p on p.pedido = pcp.loc
				where ";
			if($ref <>""){$qr .= "pcp.ref='$ref' and ";}
			if($cor <>""){$qr .= "pcp.cor='$cor' and ";}
			if($lote <>""){$qr .= "pcp.lote='$lote' and ";}
			if($pedido <>""){$qr .= "pcp.loc='$pedido' and ";}
			if($dataentrini <>""){$qr .= "p.dataentrega >= '$dataentrini' and ";}
			if($dataentrfin <>""){$qr .= "p.dataentrega <= '$dataentrfin' and ";}

			if($z == 1){
				if(isset($sep)){$qr .="situacao='S' and ";}
				if(isset($can)){$qr .="situacao='C' and ";}
				if(isset($pro)){$qr .="situacao='P' and ";}
				if(isset($agu)){$qr .="situacao='A' and ";}
				if(isset($ent)){$qr .="situacao='E' and ";}
			}
			if($z >= 2){
				$w = 0;
				if(isset($sep)){$qr .="(situacao='S'";$w++;}
				if(isset($can)){
					if($w == 0){$qr .="(situacao='C'";$w++;
					}else{$qr .=" or situacao='C'";}
				}
				if(isset($pro)){
					if($w == 0){$qr .="(situacao='P'";$w++;
					}else{$qr .=" or situacao='P'";}
				}
				if(isset($agu)){
					if($w == 0){$qr .="(situacao='A'";$w++;
					}else{$qr .=" or situacao='A'";}
				}
				if(isset($ent)){
					$qr .=" or situacao='E'";
				}
				$qr .=") and ";
			}
			$qr .= "tot>0 order by ref,cor,dataentrega,loc";
			
			$sql = mysqli_query($con,$qr);
			$tablesql = array();
			
			while($row = $sql -> fetch_assoc()){
				$tablesql[] = $row;
			}
			/*$tablesql = mysqli_fetch_all($sql,MYSQL_ASSOC);*/
						
			echo '<form method="post" id="modlinhas" action="?acao=modlinhas">';
			
			$k = 0;
			foreach($tablesql as $linhasql){
				echo '<tr>
				<td>'.$linhasql['ref'].'<input type="text" style="display: none" name="ref'.$k.'" value="'.$linhasql['ref'].'"/></td>
				<td>'.$linhasql['cor'].'<input type="text" style="display: none" name="cor'.$k.'" value="'.$linhasql['cor'].'"/></td>
				<td><input type="number" class="center" name="t1'.$k.'" value="'.$linhasql['t1'].'"/></td>
				<td><input type="number" class="center" name="t2'.$k.'" value="'.$linhasql['t2'].'"/></td>
				<td><input type="number" class="center" name="t3'.$k.'" value="'.$linhasql['t3'].'"/></td>
				<td><input type="number" class="center" name="t4'.$k.'" value="'.$linhasql['t4'].'"/></td>
				<td><input type="number" class="center" name="t5'.$k.'" value="'.$linhasql['t5'].'"/></td>
				<td>'.$linhasql['tot'].'</td>
				<td><input type="number" class="center" name="lotenovo'.$k.'" value="'.$linhasql['lote'].'"/><input type="text" style="display: none" name="lote'.$k.'" value="'.$linhasql['lote'].'"/></td>
				<td><input type="text" class="center" name="sitnova'.$k.'" value="'.$linhasql['situacao'].'" size="1"/><input type="text" style="display: none" name="situacao'.$k.'" value="'.$linhasql['situacao'].'"/></td>
				<td>'.$linhasql['pedido'].'<input type="text" style="display: none" name="pedido'.$k.'" value="'.$linhasql['pedido'].'"/></td>';
				/*<td>'.substr($linhasql['razaosocial'],0,15).'</td>*/
				echo '<td>';
				if($linhasql['pedido'] <> 'estoque'){echo date('d-m-Y',strtotime($linhasql['dataentrega']));}
				echo '</tr>';
				$k++;
			}
			echo '</table>';
			
			if(empty($tablesql)){
				echo '</br>Não existe a linha que procurou...';
			}else{ echo '<input type="submit" name="submit" value="Atualizar valores" />';}
			echo '</form>';
		}
		if($_GET['acao']=='modlinhas'){
			
			$k = 0;
			while (isset($_POST['ref'.$k])){
				
				$ref[$k] = $_POST['ref'.$k];
				$cor[$k] = $_POST['cor'.$k];
				$t1[$k] = $_POST['t1'.$k];
				$t2[$k] = $_POST['t2'.$k];
				$t3[$k] = $_POST['t3'.$k];
				$t4[$k] = $_POST['t4'.$k];
				$t5[$k] = $_POST['t5'.$k];
				$tot[$k] = $t1[$k]+$t2[$k]+$t3[$k]+$t4[$k]+$t5[$k];
				$lote[$k] = $_POST['lote'.$k];
				$lotenovo[$k] = $_POST['lotenovo'.$k];
				$situacao[$k] = $_POST['situacao'.$k];
				$sitnova[$k] = mb_strtoupper($_POST['sitnova'.$k], 'UTF-8');
				$pedido[$k] = $_POST['pedido'.$k];
				
				if($tot[$k] == 0){
					$qr="delete from pcp where ref='$ref[$k]' and cor='$cor[$k]' and situacao='$situacao[$k]' and loc='$pedido[$k]' and lote=$lote[$k]";
					$sql = mysqli_query($con,$qr);
				}else{
				$qr = "update pcp set t1=$t1[$k],t2=$t2[$k],t3=$t3[$k],t4=$t4[$k],t5=$t5[$k],tot=$tot[$k],lote=$lotenovo[$k],situacao='$sitnova[$k]' where ref='$ref[$k]' and cor='$cor[$k]' and situacao='$situacao[$k]' and loc='$pedido[$k]' and lote=$lote[$k]";
				$sql = mysqli_query($con,$qr);
				}
				
				$k++;
			}
			echo '</br>Linha(s) atualizada(s) com sucesso!';
		}	
		if($_GET['acao']=='verificfots'){
			$stmt = $dbh->prepare("select distinct(ref) from pcp where situacao = 'S'");
			$stmt->execute();
			$table=array();
			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				if(!file_exists('_fotos/'.$row['ref'].'-1.jpg')){
					echo $row['ref'].'</br>'; 
				}	
			}
		}
	}	
	?>
	</div>
</body>
</html>
 