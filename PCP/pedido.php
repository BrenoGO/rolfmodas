<!DOCTYPE html>
<html>
<head>
  <?php
	require '../head.php';
	?>
  <title>Pedidos</title>
  <script src="_javascript/functionspedido.js"></script>
  <script src="../_javascript/functions.js"></script>
</head>
<body id="pedido">
	<?php
	
	require '../config.php';
	require 'config.php';
	
	require '../header_geral.php';
	require 'menu.php';
	
	
	
	echo '
	</header>	
	<nav id="submenu">
	<a href="?acao=formaddpedido"><button>Adicionar Pedido</button></a>
	<a href="?acao=consultpedido"><button>Consultar Pedido</button></a>
	<a href="?acao=gerarromaneio"><button>Gerar Romaneio</button></a>
	<a href="?acao=formsepararsortido"><button>Separar Sortido</button></a>
	<a href="?acao=editpedido"><button>Editar Pedido</button></a>
	<a href="?acao=juntarpedidos"><button>Juntar Pedidos</button></a>
	<div class="hoverDrop">
		<button style="color: black;">Outros <img id="down_o" src="../_imagens/down.png" /></button>
		<div class="grupoHidden_o">
			<div class="a_out"><a href="?acao=consignados"><button>Consignados</button></a></div>
			<div class="a_out"><a href="?acao=urgencias"><button>Urgências</button></a></div>
			<div class="a_out"><a href="?acao=editPedPrazo"><button>Pedido a Prazo</button></a></div>
			<div class="a_out"><a href="?acao=testeProdConsig"><button>Teste Produtos no Consig</button></a></div>
		</div>
	</div></br>
	</nav>
	<div class="conteudo">';
	
	if(isset($_GET['acao'])){
		if($_GET['acao']=='formaddpedido'){
			echo '<div class="underline">'; 
			$boolemoutroforn=true;
			echo 
			'<form method="post" id="addped" action="?acao=addpedido';
		
			if(isset($_GET['id'])){if($_GET['id']<>'novo'){echo '&id_usuario='.$_GET['id'];}}
			echo '"><div class=underline>';
			
			if(!isset($_GET['id'])){
				echo '<label for="cliente">Cliente:</label><input type="text" name="cliente" id="cliente" size="10" onkeyup="lsbuscausuario(this.value,'."'acao=formaddpedido'".')" autofocus/>
				<div id="livesearch"></div>';
			}else{
				if($_GET['id']<>'novo'){
					$boolnovocliente=false;
				}else{
					$boolnovocliente=true;
				}
				if(!$boolnovocliente){
					$id_usuario=$_GET['id'];
				}
				$boolcadastsenha=false;
				require("../_auxiliares/formcadastpessoa.php");
				
			}	
			echo '</div>
			<label for="pedido">Pedido N.:</label><input type="text" name="pedido" id="pedido" size="5" maxlength="8"/>';
			$stmt=$dbh->prepare("select id_usuario,nomefantasia from usuarios where acesso like '%representante%'");
			$stmt->execute();
			$ln=$stmt->fetchAll();
			echo '<select name="representante">
			<option value="0">Sem representante</option>';
			foreach($ln as $row){
				echo '<option';
				if($_SESSION['id_usuario'] == $row['id_usuario']){echo ' selected="select"';}
				echo ' value="'.$row['id_usuario'].'">'.$row['nomefantasia'].'</option>';
			}
			echo '</select></br>
			<label for="prazo_pag">Prazo de Pagamento: </label><input type="text" name="prazo_pag" id="prazo_pag"/>
			<label for="obs">Obs.:</label><input type="text" name="obs" id="obs" size="50"/></br>
			<label for="dataped">Data Pedido:</label><input type="date" name="dataped" id="dataped"/>
			<label for="dataentr">Data Entrega:</label><input type="date" name="dataentr" id="dataentr"/></br>
			<span>Cole abaixo cada linha do pedido na formatação: ref,cor,t1,t2,t3,t4,t5,preco,desconto;</span></br>
			<textarea name="ped" id="ped" cols="80" rows="20" placeholder="Cole aqui o pedido na formatação: ref,cor,t1,t2,t3,t4,t5,preco,desconto;"></textarea>
			<input type="submit" name="submit" value="Inserir Pedido" />
			<input type="reset" id="limpar" name="limpar" value="Limpar"/>
			</form>	';
		}
		if($_GET['acao']=='addpedido'){
			$ped = $_POST['ped'];
			$tiraespacos = str_replace(' ','',$ped);
			$tiratabs = str_replace('	','',$tiraespacos);
			$qrped= explode(';',$tiratabs);
			$pedido = $_POST['pedido'];
			$id_vendedor = $_POST['representante'];
			
			require("../_auxiliares/cadastpessoa.php");
			
			
			$dataped = $_POST['dataped'];
			$dataentr = $_POST['dataentr'];
			if(($pedido == "")or($rsocial == "")){
				echo"<script language='javascript' type='text/javascript'>alert('Faltou colocar qual número do pedido ou nome do cliente!');window.location.href='pedido.php?acao=formaddpedido';</script>";
				die();
			}
			//pegar id do cliente
			$stmt=$dbh->prepare("select id_usuario from usuarios where razaosocial=?");
			$stmt->execute(array($rsocial));
			$ln=$stmt->fetch(PDO::FETCH_ASSOC);
			$id_cliente=$ln['id_usuario'];
			
			$prazo_pag = $_POST['prazo_pag'];
			$obs = $_POST['obs'];
			$qrpedido = "insert into pedidos (pedido,id_cliente,id_vendedor,dataped,dataentrega,prazopag,obs) values(?,?,";
			if($id_vendedor == '0'){$qrpedido .= "NULL";}else{$qrpedido .="?";}
			$qrpedido .=",?,?,?,?)";
			$stmtped=$dbh->prepare($qrpedido);
			if($id_vendedor == '0'){
				$arrayexe=array($pedido,$id_cliente,$dataped,$dataentr,$prazo_pag,$obs);
			}else{
				$arrayexe=array($pedido,$id_cliente,$id_vendedor,$dataped,$dataentr,$prazo_pag,$obs);
			}
			$stmtped->execute($arrayexe);
			$origem='pcp';
			require '../_auxiliares/addPedido_ped.php';
		}
		if($_GET['acao']=='consultpedido'){
			echo 
			'<form method="post" id="consultped" action="?acao=resultconsultped">
			<label for="pedido">Pedido N.:</label><input type="text" name="pedido" id="pedido" size="5" maxlength="8" autofocus/>
			<label for="cliente">Cliente:</label><input type="text" name="cliente" id="cliente" size="10"/>
			<label for="cidade">Cidade:</label><input type="text" name="cidade" id="cidade" size="10"/>
			<label for="dataped">Data Pedido:</label><input type="date" name="dataped" id="dataped"/>
			<label for="dataentrega">Prazo:</label><input type="date" name="dataentrega" id="dataentrega"/></br>
			<input type="checkbox" name="sep" id="sep" value="S"/><label for="sep">Separado</label>
			<input type="checkbox" name="can" id="can" value="C"/><label for="can">Cancelado</label>
			<input type="checkbox" name="pro" id="pro" value="P"/><label for="pro">Programado</label>
			<input type="checkbox" name="agu" id="agu" value="A"/><label for="agu">Aguardando Programação</label>
			<input type="checkbox" name="ent" id="ent" value="E"/><label for="ent">Faturado</label></br>
			<input type="checkbox" name="cns" id="cns" value="O"/><label for="cns">Consignado</label>
			<input type="checkbox" name="dev" id="dev" value="D"/><label for="dev">Devolução Consig</label>
			<input type="checkbox" name="apg" id="apg" value="Q"/><label for="apg">Aguardando Pagamento</label></br></br>
			<input type="radio" style="display:none" name="valor" id="valor" value="val"/><label style="display:none" for="valor">Mostrar Valor</label></br>
			<input type="submit" name="submit" value="Consulta"/>
			<input type="reset" id="limpar" name="limpar" value="Limpar"/>
			</form></br>';
		}	
		if($_GET['acao']=='resultconsultped'){
			if(isset($_POST['pedido'])){$pedido= $_POST['pedido'];}
			if(isset($_GET['pedido'])){$pedido= $_GET['pedido'];}
			if(!isset($pedido)){$pedido=='';}
			if(isset($_POST['cliente'])){$cliente= $_POST['cliente'];}
			if(isset($_POST['cidade'])){$cidade= $_POST['cidade'];}
			if(isset($_POST['dataped'])){$dataped= $_POST['dataped'];}
			if(isset($_POST['dataentrega'])){$dataentrega= $_POST['dataentrega'];}
			if(isset($_POST['valor'])){$valor = $_POST['valor'];}
			if(isset($_GET['pedido'])){$pedido = $_GET['pedido'];}
			if(isset($_GET['data_entrega'])){$data_entrega=$_GET['data_entrega'];}
			
			$z=0;
			if(isset($_POST['sep'])){$sep = $_POST['sep'];$z++;}
			if(isset($_POST['can'])){$can = $_POST['can'];$z++;}
			if(isset($_POST['pro'])){$pro = $_POST['pro'];$z++;}
			if(isset($_POST['agu'])){$agu = $_POST['agu'];$z++;}
			if(isset($_POST['ent'])){$ent = $_POST['ent'];$z++;}
			if(isset($_POST['cns'])){$cns = $_POST['cns'];$z++;}
			if(isset($_POST['dev'])){$cns = $_POST['dev'];$z++;}
			if(isset($_POST['apg'])){$cns = $_POST['apg'];$z++;}
			
			
			if($pedido <>""){
				$qr= "select u.razaosocial,u.cidade,p.dataentrega,p.dataped,p.prazopag,p.obs from pedidos p
				join usuarios u on p.id_cliente=u.id_usuario
				where p.pedido='$pedido'";
				$sql= mysqli_query($con,$qr);
				$ln = mysqli_fetch_assoc($sql);
				$cliente = $ln['razaosocial'];
				$cidade = $ln['cidade'];
				$dataentrega = $ln['dataentrega'];
				$dataped = $ln['dataped'];
				$prazopag = $ln['prazopag'];
				$obs = $ln['obs'];
				echo "</br>Pedido: $pedido - Cliente: $cliente - Cidade: $cidade 
				</br>Prazo: ".date('d-m-Y',strtotime($dataentrega))." - Data do pedido: ".date('d-m-Y',strtotime($dataped))."</br>";
				if(strlen($prazopag)>0){echo  "Prazo de pagamento: $prazopag";}if(strlen($obs)>0){echo "- Obs.: $obs";}
				echo '</br><button type="button" onclick="window.location.href='."'?acao=editpedido&formped&pedido=".$pedido."'".'">Editar Dados do Pedido</button> 
				</br>';
				
				$qr = "select p.pedido,u.razaosocial,u.cidade,p.dataped,p.dataentrega,pcp.ref,pcp.cor,pcp.t1,pcp.t2,pcp.t3,pcp.t4,pcp.t5,pcp.tot,pcp.situacao,pcp.lote,pcp.data_entrega
				from pedidos p
				join pcp on p.pedido = pcp.loc
				join usuarios u on p.id_cliente = u.id_usuario
				where ";
				if($pedido <>""){$qr .="p.pedido = '$pedido' and ";}
				if(isset($cliente)){if($cliente<>""){$qr .=	"( (u.razaosocial like '%$cliente%')or(u.nomefantasia like '%$cliente%') ) and ";}}
				if(isset($cidade)){if($cidade<>""){$qr .=	"u.cidade = '$cidade' and ";}}
				if(isset($dataped)){if($dataped<>""){$qr .=	"p.dataped = '$dataped' and ";}}
				if(isset($dataentrega)){if($dataentrega<>""){$qr .= "p.dataentrega = '$dataentrega' and ";}}
				if(isset($data_entrega)){if($data_entrega<>""){$qr .= "pcp.data_entrega = '$data_entrega' and ";}
				else{$qr .= "pcp.data_entrega is null and ";}}
				
				if($z == 1){
					if(isset($sep)){$qr .="situacao='S' and ";}
					if(isset($can)){$qr .="situacao='C' and ";}
					if(isset($pro)){$qr .="situacao='P' and ";}
					if(isset($agu)){$qr .="situacao='A' and ";}
					if(isset($ent)){$qr .="situacao='E' and ";}
					if(isset($cns)){$qr .="situacao='O' and ";}
					if(isset($sit_dev)){$qr .="situacao='D' and ";}
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
					if(isset($cns)){
						if($w == 0){$qr .="(situacao='O'";$w++;
						}else{$qr .=" or situacao='O'";}
					}
					if(isset($sit_dev)){
						if($w == 0){$qr .="(situacao='D'";$w++;
						}else{$qr .=" or situacao='D'";}
					}
					if(isset($ent)){
						$qr .=" or situacao='E'";
					}
					$qr .=") and ";
				}
				
				$qr .= "tot > 0 order by loc,ref";
				
				$sql = mysqli_query($con,$qr);
				
				$tablesql = array();
				while($row = $sql->fetch_assoc()){
					$tablesql[] = $row;
				}
				
				
				
				echo '<table><tr>';
				if($pedido == ""){
					echo '<td>Pedido</td>
					<td>Cliente</td>
					<td>Cidade</td>
					<td>Data pedido</td>
					<td>Prazo</td>';
				}
				echo '<td>ref</td>
				<td>cor</td>
				<td>P <span style="font-size:10pt">ou 4</span></td>
				<td>M <span style="font-size:10pt">ou 6</span></td>
				<td>G <span style="font-size:10pt">ou 8</span></td>
				<td>GG <span style="font-size:10pt">ou 10</span></td>
				<td>EG <span style="font-size:10pt">ou 12</span></td>
				<td>Total</td>
				<td>Lote</td>
				<td>Situação</td>
				<td>Entrega</td>
				</tr>';
				
				foreach ($tablesql as $ltable){
					echo '<tr>';
					if($pedido == ""){	
						echo '<td>'.$ltable['pedido'].'</td>
						<td>'.$ltable['cliente'].'</td>
						<td>'.$ltable['cidade'].'</td>
						<td>'.date('d-m-Y',strtotime($ltable['dataped'])).'</td>
						<td>'.date('d-m-Y',strtotime($ltable['dataentrega'])).'</td>';
					}
					$sit=nome_situacao($ltable['situacao']);
					echo '<td>'.$ltable['ref'].'</td>
					<td>'.$ltable['cor'].'</td>
					<td>'.$ltable['t1'].'</td>
					<td>'.$ltable['t2'].'</td>
					<td>'.$ltable['t3'].'</td>
					<td>'.$ltable['t4'].'</td>
					<td>'.$ltable['t5'].'</td>
					<td>'.$ltable['tot'].'</td>
					<td>'.$ltable['lote'].'</td>
					<td>'.nome_situacao($ltable['situacao']).'</td>';
					if($ltable['situacao']=='E'){echo '<td>'.date('d-m-Y',strtotime($ltable['data_entrega'])).'</td>';}
					echo '</tr>';
				}
				echo '</table>';
			
				echo '</br>Detalhando pedido '.$pedido.':</br>';
				$qr = "select situacao,sum(tot) from pcp where loc='$pedido' group by situacao";
				$sql=mysqli_query($con,$qr);
				$ln2 = array();
				while($row = $sql -> fetch_assoc()){
					$ln2[] = $row;
				}
				
				$tot=0;$P=0;$A=0;$S=0;$E=0;$C=0;$O=0;$D=0;
				foreach($ln2 as $ln3){
					if($ln3['situacao']=='P'){$P = $ln3['sum(tot)'];$tot += $P;}
					elseif($ln3['situacao']=='A'){$A = $ln3['sum(tot)'];$tot += $A;}
					elseif($ln3['situacao']=='C'){$C = $ln3['sum(tot)'];$tot += $C;}
					elseif($ln3['situacao']=='S'){$S = $ln3['sum(tot)'];$tot += $S;}
					elseif($ln3['situacao']=='O'){$O = $ln3['sum(tot)'];$tot += $O;}
					elseif($ln3['situacao']=='E'){$E = $ln3['sum(tot)'];$tot += $E;}
					elseif($ln3['situacao']=='D'){$D = $ln3['sum(tot)'];$tot += $D;}
				}
				
				
				$Eporc = $E/$tot*100;
				$Cporc = $C/$tot*100;
				$Dporc = $D/$tot*100;
				
				if($E <> 0){echo number_format($Eporc,2,',','.').'% do pedido '.$pedido.' já entregue</br>';}
				if($C<>0){echo number_format($Cporc,2,',','.').'% do pedido '.$pedido.' cancelado pela Rolf</br>';}
				if($D<>0){echo number_format($Dporc,2,',','.').'% do pedido '.$pedido.' devolvido pelo cliente</br>';}
				if($E == 0){echo 'O pedido não teve nenhuma entrega</br>';}
				if($C == 0){echo 'O pedido não teve nenhum cancelamento</br></br>';}
				if($Eporc+$Cporc+$Dporc <> 100){
					$Sporc = $S/($tot - $E - $C)*100;
					$Oporc = $O/($tot - $E - $C)*100;
					$Pporc = $P/($tot - $E - $C)*100;
					$Aporc = $A/($tot - $E - $C)*100;
					$NSporc = $Aporc + $Pporc;
					if($Oporc > 0){
						echo 'Existem peças Consignadas.';
						echo '<a href="?acao=finalizar&pedido='.$pedido.'" onclick="return confirm('."'Tem certeza?'".');"><button>Finalizar</button></a>: considerar consignados vendidos.</br>
						<a href="?acao=devolconsig&pedido='.$pedido.'"><button>Devolver peças</button></a></br>';
					}
					else{
						if($E <> 0 or $C <> 0){echo 'Desconsiderando o que foi entregue e/ou cancelado, temos:</br></br>';}
						echo number_format($Sporc,2,',','.').'% do pedido separado</br>';
						if($Sporc<>100){
							echo number_format($Pporc,2,',','.').'% do pedido programado</br>';  
							echo number_format($Aporc,2,',','.').'% do pedido aguardando programacao. ou seja -> '.number_format($NSporc,2,',','.').'% do pedido '.$pedido.' NÃO separado</br>';
						}
						echo '<a href="?acao=finalizar&pedido='.$pedido.'" onclick="return confirm('."'Tem certeza?'".');"><button>Finalizar Pedido</button></a>
						<a href="?acao=finalizar&praconsignado&pedido='.$pedido.'" onclick="return confirm('."'Tem certeza?'".');"><button>Enviar Consignado</button></a>
						';
						if(($Sporc > 0)and($Sporc<>100)){echo '<a href="?acao=parcial&pedido='.$pedido.'" onclick="return confirm('."'Tem certeza?'".');"><button>Enviar Parcial</button></a>';}
					}
					echo '<a href="?acao=cancelar&pedido='.$pedido.'" onclick="return confirm('."'Tem certeza que deseja CANCELAR o pedido?'".');"><button>Cancelar pedido</button></a>';
					if($Oporc > 0){
						echo '</br><b>Cancelamento de Consignado significa que os produtos consignados voltarão para o estoque!</b>';
					}
				}
			}else{
				$qr = "select distinct(p.pedido),u.razaosocial,p.dataped,pcp.data_entrega
				from pedidos p 
				join pcp on p.pedido = pcp.loc
				join usuarios u on p.id_cliente = u.id_usuario
				where ";
				if(isset($cliente)){if($cliente<>""){$qr .=	"( (u.razaosocial like '%$cliente%') or (u.nomefantasia like '%$cliente%') ) and ";}}
				if(isset($cidade)){if($cidade<>""){$qr .=	"u.cidade = '$cidade' and ";}}
				if(isset($dataped)){if($dataped<>""){$qr .=	"p.dataped = '$dataped' and ";}}
				if(isset($dataentrega)){if($dataentrega<>""){$qr .= "p.dataentrega = '$dataentrega' and ";}}
				
				if($z == 1){
					if(isset($sep)){$qr .="pcp.situacao='S' and ";}
					if(isset($can)){$qr .="pcp.situacao='C' and ";}
					if(isset($pro)){$qr .="pcp.situacao='P' and ";}
					if(isset($agu)){$qr .="pcp.situacao='A' and ";}
					if(isset($ent)){$qr .="pcp.situacao='E' and ";}
					if(isset($cns)){$qr .="pcp.situacao='O' and ";}
				}
				if($z >= 2){
					$w = 0;
					if(isset($sep)){$qr .="(pcp.situacao='S'";$w++;}
					if(isset($can)){
						if($w == 0){$qr .="(pcp.situacao='C'";$w++;
						}else{$qr .=" or pcp.situacao='C'";}
					}
					if(isset($pro)){
						if($w == 0){$qr .="(pcp.situacao='P'";$w++;
						}else{$qr .=" or pcp.situacao='P'";}
					}
					if(isset($agu)){
						if($w == 0){$qr .="(pcp.situacao='A'";$w++;
						}else{$qr .=" or pcp.situacao='A'";}
					}
					if(isset($cns)){
						if($w == 0){$qr .="(pcp.situacao='O'";$w++;
						}else{$qr .=" or pcp.situacao='O'";}
					}
					if(isset($ent)){
						$qr .=" or pcp.situacao='E'";
					}
					$qr .=") and ";
				}
				
				$qr .= "pcp.tot > 0 order by pcp.loc,pcp.ref";
				
				$sql = mysqli_query($con,$qr);
				echo '<table>
				<tr>
				<td>Pedido</td>
				<td>Cliente</td>
				<td>Data Pedido</td>
				<td>Data Entrega</td>
				</tr>';
				
				$tablesql = array();
				while($row = $sql->fetch_assoc()){
					echo '<tr>
					<td><a href="?acao=resultconsultped&pedido='.$row['pedido'].'&data_entrega='.$row['data_entrega'].'">'.$row['pedido'].'</a></td>
					<td>'.$row['razaosocial'].'
					</td>
					<td>'.date('d-m-Y',strtotime($row['dataped'])).'</td>
					<td>';
					if($row['data_entrega']>'1980-01-01'){echo date('d-m-Y',strtotime($row['data_entrega']));}else{echo '';}
					echo '</td>
					</tr>';
				}
				echo '</table>';
				
			}
			
		}
		if($_GET['acao']=='finalizar'){
			$pedido = $_GET['pedido'];
			$hoje=date('Y-m-d');
			$qr = "select distinct situacao from pcp where loc='$pedido' and (situacao ='P' or situacao = 'A')"; 
			$ver=mysqli_query($con,$qr);
			if(mysqli_num_rows($ver) > 0){
				echo 'Existe(m) produto(s) aguardando produção!';
				if(isset($_GET['praconsignado'])){
					if(isset($_GET['mant'])){
						$qr = "update pcp set situacao='O',dataalter=default,data_entrega='$hoje' where loc='$pedido' and (situacao ='S')"; 
						$sql=mysqli_query($con,$qr);
						echo '<script>window.location.href="pedido.php?acao=romaneio&cns&pedido='.$pedido.'"</script>';
					}else{	
						echo '</br></br><a href="?acao=finalizar&praconsignado&mant&pedido='.$pedido.'">Enviar separados em consignação e manter restante como está</a> 
						</br>ou</br>
						<a href="?acao=finalizarcanc&praconsignado&pedido='.$pedido.'">Cancelar o que não está separado e enviar o separado em consignação</a>';
					}	
				}else{
					echo '<a href="?acao=finalizarcanc&pedido='.$pedido.'" onclick="return confirm('."'Tem certeza?'".');"><button>Cancelar linhas AGUARDANDO e PROGRAMADAS</button></a>';
				}	
				
			}else{
				if(isset($_GET['praconsignado'])){
					$qr = "update pcp set situacao='O',dataalter=default,data_entrega='$hoje' where loc='$pedido' and (situacao ='S')"; 
					$sql=mysqli_query($con,$qr);
					echo '<script>window.location.href="pedido.php?acao=romaneio&cns&pedido='.$pedido.'"</script>';
				}else{
					$qr = "update pcp set situacao='E',dataalter=default,data_entrega='$hoje' where loc='$pedido' and (situacao ='S' or situacao='O')"; 
					$sql=mysqli_query($con,$qr);
					echo '<script>window.location.href="pedido.php?acao=romaneio&pedido='.$pedido.'"</script>';
				}
			}	
		}
		if($_GET['acao']=='finalizarcanc'){
			$pedido = $_GET['pedido'];
			$stmt=$dbh->prepare("update pcp set situacao='C',dataalter=default,lote=0 where loc=? and (situacao ='P' or situacao = 'A')");
			$stmt->execute(array($pedido));
			if(isset($_GET['praconsignado'])){
				echo '<script>window.location.href="?acao=finalizar&praconsignado&pedido='.$pedido.'"</script>';
			}else{				
				echo '<script>window.location.href="?acao=finalizar&pedido='.$pedido.'"</script>';
			}	
		}
		if($_GET['acao']=='parcial'){
			$hoje=date('Y-m-d');
			$pedido = $_GET['pedido'];
			$qr = "update pcp set situacao='E',dataalter=default,data_entrega='$hoje' where loc='$pedido' and situacao ='S'"; 
			$sql=mysqli_query($con,$qr);
			echo '<script>window.location.href="pedido.php?acao=romaneio&pedido='.$pedido.'"</script>';	
		}
		if($_GET['acao']=='cancelar'){
			$pedido = $_GET['pedido'];
			//verifica se tem bonus no pedido.
			$stmt=$dbh->prepare("select bonus_utilizado,id_cliente from pedidos where pedido=?");
			$stmt->execute(array($pedido));
			$ln=$stmt->fetch(PDO::FETCH_ASSOC);
			$id_cliente=$ln['id_cliente'];
			if($ln['bonus_utilizado']>0){
				$bonus=$ln['bonus_utilizado'];
				$stmt=$dbh->prepare("select bonus from usuarios where id_usuario=?");
				$stmt->execute(array($id_cliente));
				$ln=$stmt->fetch(PDO::FETCH_ASSOC);
				$bonus_usuario=$ln['bonus'];
				$new_bonus=$bonus_usuario+$bonus;
				$stmt=$dbh->prepare("update usuarios set bonus =? where id_usuario=?");
				$stmt->execute(array($new_bonus,$id_cliente));
			}
			//
			
			
			$qr = "update pcp set situacao='S' where loc='$pedido' and situacao ='O'"; 
			$sql=mysqli_query($con,$qr);
			
			
			
			$qrS= "select * from pcp where loc='$pedido' and situacao='S'";
			$sqlS= mysqli_query($con,$qrS);
			$tablesqlS = array();
			while($rowS = $sqlS->fetch_assoc()){
				$tablesqlS[] = $rowS;
			}
			$qrP= "select * from pcp where loc='$pedido' and situacao='P'";
			$sqlP= mysqli_query($con,$qrP);
			$tablesqlP = array();
			while($rowP = $sqlP->fetch_assoc()){
				$tablesqlP[] = $rowP;
			}
			
			$qr = "update pcp set situacao='C',lote=0,dataalter=default where loc='$pedido' and situacao <>'E'"; 
			$sql=mysqli_query($con,$qr);
			
			
			
			foreach($tablesqlS as $rowS){
				$ref = $rowS['ref'];
				$cor = $rowS['cor'];
				$t[1] = $rowS['t1'];
				$t[2] = $rowS['t2'];
				$t[3] = $rowS['t3'];
				$t[4] = $rowS['t4'];
				$t[5] = $rowS['t5'];
				$boolprod='';
				gerar_prod($dbh,$ref,$cor,$t,$boolprod);
			}
			foreach($tablesqlP as $rowP){
				$ref = $rowP['ref'];
				$cor = $rowP['cor'];
				$t[1] = $rowP['t1'];
				$t[2] = $rowP['t2'];
				$t[3] = $rowP['t3'];
				$t[4] = $rowP['t4'];
				$t[5] = $rowP['t5'];
				$tot = array_sum($t);
				$lote = $rowP['lote'];
				
				$qr="select preco from produtos where ref='$ref'";
				$sql=mysqli_query($con,$qr);
				$ln=mysqli_fetch_assoc($sql);
				$preco=$ln['preco'];
				
				$qr="select ref,cor,sum(t1),sum(t2),sum(t3),sum(t4),sum(t5),sum(tot) from pcp 
				where situacao ='A' and cor='$cor' and ref='$ref'";
				$sql= mysqli_query($con,$qr);
				$ln=mysqli_fetch_assoc($sql);
				$sumt[1]=$ln['sum(t1)'];
				$sumt[2]=$ln['sum(t2)'];
				$sumt[3]=$ln['sum(t3)'];
				$sumt[4]=$ln['sum(t4)'];
				$sumt[5]=$ln['sum(t5)'];
				for($i=1;$i<=5;$i++){
					if($t[$i] > 0){
						if($sumt[$i] > 0){
							//existe necessidade de pedido
							$qr="select pcp.ref,pcp.cor,pcp.t$i,pcp.loc,p.dataped from pcp 
								join pedidos p on p.pedido = pcp.loc
								where situacao ='A' and pcp.cor='$cor' and pcp.ref='$ref' and t$i>0 order by p.dataentrega";
							$sql=mysqli_query($con,$qr);
							$tablesql = array();
							while($row = $sql -> fetch_assoc()){
								$tablesql[] = $row;
							}
							foreach($tablesql as $row){
								if($t[$i]>0){
									$loc = $row['loc'];
									$qr="select preco,desconto from pcp where loc='$loc' and ref='$ref' and cor='$cor'";
									$sql=mysqli_query($con,$qr);
									if(mysqli_num_rows($sql)>0){
										$ln=mysqli_fetch_assoc($sql);
										$precopednovo=$ln['preco'];
										$descontonovo=$ln['desconto'];
									}else{
										$precopednovo=null;
										$descontonovo=0;
									}
									//verificar se existe linha P do pedido novo com lote..
									$qrselect="select * from pcp where ref='$ref' and cor='$cor' and lote=$lote and situacao='P' and loc='$loc'";
									$ver=mysqli_query($con,$qrselect);
									if(mysqli_num_rows($ver)<=0){
										//nao existe a linha P do lote pedido novo. -> inserir linha
										$qr="insert into pcp (ref,cor,t1,t2,t3,t4,t5,tot,loc,lote,dataalter,situacao,preco,desconto) values ('$ref','$cor',0,0,0,0,0,0,'$loc',$lote,default,'P',$precopednovo,$descontonovo)";
										$sql=mysqli_query($con,$qr);
									}
									//recolher qnt que tem na linha P
									$sql=mysqli_query($con,$qrselect);
									$ln=mysqli_fetch_assoc($sql);
									$tantp=$ln['t'.$i];
									
									if($t[$i] >= $row['t'.$i]){
										//atualizar linha p do pedido, lote
										$tnovo = $row['t'.$i] + $tantp;
										$qr="update pcp set t$i = $tnovo where ref='$ref' and cor='$cor' and lote=$lote and situacao='P' and loc='$loc'";
										$sql=mysqli_query($con,$qr);
										//atualizar tot
										$sql=mysqli_query($con,$qrselect);
										$ln=mysqli_fetch_assoc($sql);
										$totnovo = $ln['t1']+$ln['t2']+$ln['t3']+$ln['t4']+$ln['t5'];
										$qr="update pcp set tot = $totnovo where ref='$ref' and cor='$cor' and lote=$lote and situacao='P' and loc='$loc'";
										$sql=mysqli_query($con,$qr);
										//atualizar linha A do pedido (ficou zerada)
										$qr="update pcp set t$i = 0 where ref='$ref' and cor='$cor' and situacao='A' and loc='$loc'";
										$sql=mysqli_query($con,$qr);
										//atualizar tot da linha A
										$qr="select * from pcp where ref='$ref' and cor='$cor' and situacao='A' and loc='$loc'";
										$sql=mysqli_query($con,$qr);
										$ln=mysqli_fetch_assoc($sql);
										$totn=$ln['t1']+$ln['t2']+$ln['t3']+$ln['t4']+$ln['t5'];
										if($totn == 0){
											$qr="delete from pcp where ref='$ref' and cor='$cor' and situacao='A' and loc='$loc'";
											$sql=mysqli_query($con,$qr);
										}else{
											$qr="update pcp set tot=$totn where ref='$ref' and cor='$cor' and situacao='A' and loc='$loc'";
											$sql=mysqli_query($con,$qr);
										}
										$t[$i] -= $row['t'.$i];
										
									}else{
										$tnovo = $t[$i] + $tantp;
										//atualizar linha p do pedido, lote
										$qr="update pcp set t$i = $tnovo where ref='$ref' and cor='$cor' and lote=$lote and situacao='P' and loc='$loc'";
										$sql=mysqli_query($con,$qr);
										//atualizar tot
										$sql=mysqli_query($con,$qrselect);
										$ln=mysqli_fetch_assoc($sql);
										$totnovo = $ln['t1']+$ln['t2']+$ln['t3']+$ln['t4']+$ln['t5'];
										$qr="update pcp set tot = $totnovo where ref='$ref' and cor='$cor' and lote=$lote and situacao='P' and loc='$loc'";
										$sql=mysqli_query($con,$qr);
										//atualizar linha A do pedido
										$dif = $row['t'.$i] - $t[$i];
										$qr="update pcp set t$i = $dif where ref='$ref' and cor='$cor' and situacao='A' and loc='$loc'";
										$sql=mysqli_query($con,$qr);
										//atualizar tot (nao tem como ele ficar zerado...)
										$qr="select * from pcp where ref='$ref' and cor='$cor' and situacao='A' and loc='$loc'";
										$sql=mysqli_query($con,$qr);
										$ln=mysqli_fetch_assoc($sql);
										$totn = $ln['t1']+$ln['t2']+$ln['t3']+$ln['t4']+$ln['t5'];
										$qr="update pcp set tot = $totn where ref='$ref' and cor='$cor' and situacao='A' and loc='$loc'";
										$sql=mysqli_query($con,$qr);
										$t[$i] = 0;
										break;
									}
								}
							}
						}
						//nao tem mais necessidade, entao o que sobrou de ti vai pra linha P de estoque..
						if($t[$i] > 0){
							//verifica se existe linha P pro estoque deste lote
							$qrselect="select * from pcp where ref='$ref' and cor='$cor' and lote=$lote and loc='estoque' and situacao='P'";
							$ver=mysqli_query($con,$qrselect);
							if(mysqli_num_rows($ver)<=0){
								//nao existe a linha P do lote estoque. -> inserir linha
								$qr="insert into pcp (ref,cor,t1,t2,t3,t4,t5,tot,loc,lote,dataalter,situacao) values ('$ref','$cor',0,0,0,0,0,0,'estoque',$lote,default,'P')";
								$sql=mysqli_query($con,$qr);
							}
							$sql=mysqli_query($con,$qrselect);
							$ln=mysqli_fetch_assoc($sql);
							$tnovo = $t[$i] + $ln['t'.$i];
							$qr="update pcp set t$i = $tnovo where ref='$ref' and cor='$cor' and lote=$lote and loc='estoque' and situacao='P'";
							$sql=mysqli_query($con,$qr);
							//atualizar tot (nao tem como ele ficar zerado...)
							$sql=mysqli_query($con,$qrselect);
							$ln=mysqli_fetch_assoc($sql);
							$totn = $ln['t1']+$ln['t2']+$ln['t3']+$ln['t4']+$ln['t5'];
							$qr="update pcp set tot = $totn where ref='$ref' and cor='$cor' and lote=$lote and loc='estoque' and situacao='P'";
							$sql=mysqli_query($con,$qr);
						}
					}
				}				
			}
		}
		if($_GET['acao']=='gerarromaneio'){
			echo 
			'<form method="post" id="gerarromaneio" action="?acao=romaneio">
			<label for="pedido">Pedido N.:</label><input type="text" name="pedido" id="pedido" size="8" autofocus/>
			<label for="Data">Data de Entrega: </label><input type="date" name="data" id="data"/></br>
			<input type="checkbox" name="sep" id="sep" value="S"/><label for="sep">Separado</label>
			<input type="checkbox" name="can" id="can" value="C"/><label for="can">Cancelado</label>
			<input type="checkbox" name="pro" id="pro" value="P"/><label for="pro">Programado</label>
			<input type="checkbox" name="agu" id="agu" value="A"/><label for="agu">Aguardando Programação</label>
			<input type="checkbox" name="ent" id="ent" value="E" checked/><label for="ent">Entregue</label>
			<input type="checkbox" name="cns" id="cns" value="O"/><label for="cns">Consignado</label></br></br>
			<input type="radio" style="display:none" name="valor" id="valor" value="val"/><label style="display:none" for="valor">Mostrar Valor</label></br>
			<input type="submit" name="submit" value="Consulta"/>
			<input type="reset" id="limpar" name="limpar" value="Limpar"/>
			</form></br>';
		}
		if($_GET['acao']=='romaneio'){
			if(isset($_POST['pedido'])){$pedido = strtoupper($_POST['pedido']);}
			elseif(isset($_GET['pedido'])){$pedido = strtoupper($_GET['pedido']);}
			
			$pedido=check_zeros_pedido($pedido);
			
			
			if(isset($_GET['data'])){$data = $_GET['data'];}
			elseif(isset($_POST['data'])){$data = $_POST['data'];}
			else{$data='';}
			
			if($data == ''){
			$qr="select distinct(data_entrega) from pcp where loc='$pedido' and situacao='E' order by data_entrega";
				$sql=mysqli_query($con,$qr);
				if(mysqli_num_rows($sql)>1){
					echo 'Temos mais que uma entrega para o mesmo pedido:</br>';
					$table=array();
					while($row = $sql->fetch_assoc()){
						$table[] = $row;
					}
					foreach($table as $dia){
						$d=date('d-m-Y',strtotime($dia['data_entrega']));
						$dlink=date('Y-m-d',strtotime($dia['data_entrega']));
						echo '<a href="?acao=romaneio&pedido='.$pedido.'&data='.$dlink.'">Dia: '.$d.'</a></br>'; 
					}
					echo 'No Romaneio abaixo estão todas entragas juntas.</br>';
				}
			}
			
			
			$z=0;
			if(isset($_POST['sep'])){$sep = $_POST['sep'];$z++;}
			if(isset($_POST['can'])){$can = $_POST['can'];$z++;}
			if(isset($_POST['pro'])){$pro = $_POST['pro'];$z++;}
			if(isset($_POST['agu'])){$agu = $_POST['agu'];$z++;}
			if(isset($_POST['ent'])){$ent = $_POST['ent'];$z++;}
			if(isset($_POST['cns'])){$cns = $_POST['cns'];$z++;}
			if(isset($_GET['sep'])){$sep = $_GET['sep'];$z++;}
			if(isset($_GET['can'])){$can = $_GET['can'];$z++;}
			if(isset($_GET['pro'])){$pro = $_GET['pro'];$z++;}
			if(isset($_GET['agu'])){$agu = $_GET['agu'];$z++;}
			if(isset($_GET['ent'])){$ent = $_GET['ent'];$z++;}
			if(isset($_GET['cns'])){$cns = $_GET['cns'];$z++;}
			if($z == 0){$ent = 'E';$z++;}
			
			$qr= "select u.razaosocial,u.cidade,u.id_usuario,p.dataentrega,p.dataped,p.prazopag,p.obs 
			from pedidos p
			join usuarios u on p.id_cliente=u.id_usuario
			where pedido='$pedido'";
			$sql= mysqli_query($con,$qr);
			$ln = mysqli_fetch_assoc($sql);
			$id_usuario=$ln['id_usuario'];
			$cliente=$ln['razaosocial'];
			$cidade = $ln['cidade'];
			$dataentrega = $ln['dataentrega'];
			$dataped = $ln['dataped'];
			$prazopag=$ln['prazopag'];
			$obs=$ln['obs'];
			echo "</br>Pedido: $pedido - Cliente: $cliente - Cidade: $cidade </br>Prazo: ".date('d-m-Y',strtotime($dataentrega))." - Data do pedido: ".date('d-m-Y',strtotime($dataped));
			if($data<>''){echo ' - Data de entrega: '.date('d-m-Y',strtotime($data));}
			echo '</br>';
			if($prazopag <> ''){echo 'Prazo de Pagamento: '.$prazopag;}
			if($obs <>''){echo '- Observações: '.$obs.'</br></br>';}else{echo '</br>';}
			
			$qr = "select pcp.ref,sum(pcp.tot),pcp.preco,pcp.desconto,pcp.preco*(1-pcp.desconto/100)*sum(pcp.tot) from pcp 
			where loc='$pedido' and ";
			if($data <> ""){$qr .= "data_entrega >='$data' and data_entrega <='$data 23:59:59' and ";}
			if($z == 1){
				if(isset($sep)){$qr .="situacao='S' and ";}
				if(isset($can)){$qr .="situacao='C' and ";}
				if(isset($pro)){$qr .="situacao='P' and ";}
				if(isset($agu)){$qr .="situacao='A' and ";}
				if(isset($ent)){$qr .="situacao='E' and ";}
				if(isset($cns)){$qr .="situacao='O' and ";}
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
				if(isset($cns)){
					if($w == 0){$qr .="(situacao='O'";$w++;
					}else{$qr .=" or situacao='O'";}
				}
				if(isset($ent)){
					$qr .=" or situacao='E'";
				}
				$qr .=") and ";
			}
			$qr .= "tot>0 group by ref order by ref";
			
			$sql = mysqli_query($con,$qr);
			$table = array();

			while($row = $sql -> fetch_assoc()){
				$table[] = $row;
			}
			$qr="select sum(desconto) from pcp where loc='$pedido'";
			$sql=mysqli_query($con,$qr);
			$ln=mysqli_fetch_assoc($sql);
			$sumdesc=$ln['sum(desconto)'];
			
			echo '<table><tr>
			<td>ref</td>
			<td>Descrição</td>
			<td>Qtd</td>
			<td>Valor Unit.</td>';
			if($sumdesc > 0){
				echo '<td>Desconto(%)</td>';
			}
			echo '<td>Subtotal</td>
			</tr>';
			$total = 0;
			$pecas = 0;
			foreach($table as $linha){
				$ref=$linha['ref'];
				$stmt=$dbh->prepare("select descricao from produtos where ref=?");
				$stmt->execute(array($ref));
				$ln=$stmt->fetch(PDO::FETCH_ASSOC);
				$desc=$ln['descricao'];
				$subtotal = $linha['pcp.preco*(1-pcp.desconto/100)*sum(pcp.tot)'];
				echo '<tr>
				<td>'.$ref.'</td>
				<td>'.$desc.'</td>
				<td>'.$linha['sum(pcp.tot)'].'</td>
				<td>'.number_format($linha['preco'],2,',','.').'</td>';
				if($sumdesc > 0){echo '<td>'.number_format($linha['desconto'],1,',','.').'</td>';}
				echo '<td>'.number_format($subtotal,2,',','.').'</td>
				</tr>';
				$total += $subtotal;
				$pecas += $linha['sum(pcp.tot)'];
			}
			echo '<tr>
			<td colspan="';
			if($sumdesc>0){echo '3';}else{echo '2';}
			echo '"><b>Peças: '.$pecas.'</b></td>
			<td colspan="2"><b>Total: R$ '.number_format($total,2,',','.').'</b></td>
			</tr></table>';
			$stmt=$dbh->prepare("select desconto_pedido,bonus_utilizado,frete from pedidos where pedido=?");
			$stmt->execute(array($pedido));
			$ln=$stmt->fetch(PDO::FETCH_ASSOC);
			
			$desconto_pedido=$ln['desconto_pedido'];
			$bonus_utilizado=$ln['bonus_utilizado'];
			$frete=$ln['frete'];
			
			if($desconto_pedido > 0){
				$total=$total-$desconto_pedido;
				echo 'Desconto no pedido: R$ '.number_format($desconto_pedido,2,',','.').'</br>';
			}
			if($frete > 0){
				$total=$total+$frete;
				echo 'Valor do frete: R$ '.number_format($frete,2,',','.').'</br>';
			}
			if($bonus_utilizado > 0){
				$total=$total-$bonus_utilizado;
				echo 'Pagamento em Bônus no pedido: R$ '.number_format($bonus_utilizado,2,',','.').'</br>';
			}
			if(($desconto_pedido+$frete+$bonus_utilizado)<>0){
				echo '<b>Total: R$ '.number_format($total,2,',','.').'</b></br>';
			}
			
			echo '<a style="font-size:10pt" href="?acao=adddescontopedido&pedido='.$pedido;
			if($data <> ''){echo '&data='.$data;}
			echo '">Dar desconto</a></br>';
			
			
			if(isset($ent)){
				if(isset($_SESSION['NFe-geral'])){unset($_SESSION['NFe-geral']);}
				if(isset($_SESSION['NFe-resum'])){unset($_SESSION['NFe-resum']);}
				echo '<a href="nfe.php?tprods=geral&pedido='.$pedido;
				if($data <> ""){echo '&data='.$data;}
				echo '"><button>NFe</button></a>';
				echo '<a href="../financeiro/areceber.php?acao=gerardupl&total='.$total.'&id_cliente='.$id_usuario.'&pedido='.$pedido;
				if($desconto_pedido > 0){echo '&descPed='.$desconto_pedido;}
				echo '">&nbsp<button>Gerar a Receber</button></a>';
			}echo '<a href="pdfromaneio.php?pedido='.$pedido;
			if($data<>''){
				echo '&data='.$data;
			}
			if(isset($sep)){echo '&sep';}
			if(isset($can)){echo '&can';}
			if(isset($pro)){echo '&pro';}
			if(isset($agu)){echo '&agu';}
			if(isset($ent)){echo '&ent';}
			if(isset($cns)){echo '&cns';}
			echo '" target="_blank">&nbsp<button>PDF</button></a>';
			$stmt=$dbh->prepare("select status_pagamento from pedidos where pedido=?");
			$stmt->execute(array($pedido));
			$ln=$stmt->fetch(PDO::FETCH_ASSOC);
			if($ln['status_pagamento'] <> 3 || $ln['status_pagamento'] <> 4){
				echo '&nbsp<a href="?acao=confirmpagamento&pedido='.$pedido.'"><button>Confirmar pagamento</button></a>';
			}
			
			
		}
		if($_GET['acao']=='adddescontopedido'){
			$pedido=$_GET['pedido'];
			if(isset($_GET['data'])){$data=$_GET['data'];}else{$data='';}
			$maisdeum=false;
			if($data == ''){
			
			$qr="select distinct(data_entrega) from pcp where loc='$pedido' and situacao='E' order by data_entrega";
				$sql=mysqli_query($con,$qr);
				if(mysqli_num_rows($sql)>1){
					echo 'Temos mais que uma entrega para o mesmo pedido:</br>';
					$maisdeum=true;
					$table=array();
					while($row = $sql->fetch_assoc()){
						$table[] = $row;
					}
					foreach($table as $dia){
						$d=date('d-m-Y',strtotime($dia['data_entrega']));
						$dlink=date('Y-m-d',strtotime($dia['data_entrega']));
						echo '<a href="?acao=romaneio&pedido='.$pedido.'&data='.$dlink.'">Dia: '.$d.'</a></br>'; 
					}
					echo 'Selecione uma data ou dará desconto em porcentagem para todas as entregas abaixo:</br>';
				}
			}
			$qr="select pcp.ref,produtos.descricao,sum(pcp.tot),pcp.preco,pcp.desconto,pcp.preco*(1-pcp.desconto/100)*sum(pcp.tot) from pcp
			join produtos on pcp.ref=produtos.ref
			where loc='$pedido' and ";
			if($data <> ""){$qr .= "data_entrega >='$data' and data_entrega <='$data 23:59:59' and ";}
			$qr .= "tot>0 group by ref order by ref";
			$sql=mysqli_query($con,$qr);
			$table=array();
			while($row = $sql -> fetch_assoc()){
				$table[] = $row;
			}
			$qr="select sum(desconto) from pcp where loc='$pedido'";
			$sql=mysqli_query($con,$qr);
			$ln=mysqli_fetch_assoc($sql);
			$total = 0;
			$totaldescontos = 0;
			$totalsemdescontos = 0;
			$sumdesc=$ln['sum(desconto)'];
			foreach($table as $linha){
				$subtotal = $linha['pcp.preco*(1-pcp.desconto/100)*sum(pcp.tot)'];
				$total += $subtotal;
				$totaldescontos += $linha['preco']*$linha['desconto']/100*$linha['sum(pcp.tot)'];
				$totalsemdescontos +=  $linha['preco']*$linha['sum(pcp.tot)'];
			}
			
			echo '<h3>Total do Pedido';
			if($total == $totalsemdescontos){echo ' : R$ '.number_format($total,2,',','.');}
			else{echo ' (sem desconto): R$ '.number_format($totalsemdescontos,2,',','.');}
			if($maisdeum){
				echo ' - Data Entrega: '.date('d-m-Y',strtotime($data));
			}
			echo '</h3>';
			if($sumdesc > 0){
				echo 'Pedido possui desconto total de R$ '.number_format($totaldescontos,2,',','.').' em porcentagens nos produto</br></br>';
			}
			$qr="select desconto_pedido from pedidos where pedido='$pedido'";
			$sql=mysqli_query($con,$qr);
			$ln=mysqli_fetch_assoc($sql);
			$desconto_pedido=$ln['desconto_pedido'];
			if($desconto_pedido > 0){
				echo 'Pedido possui desconto em valor de R$ '.number_format($desconto_pedido,2,',','.').'</br></br>';
			}
			
			if(!$maisdeum){
				echo '<form method="post" action="?acao=dardesconto&pedido='.$pedido.'&tipo=porvalorpedido"><label for="descvalor">';
				if($desconto_pedido>0){echo 'Substituir d';}else{echo 'D';}
				echo 'esconto por valor: R$ </label><input type="text" name="descvalor" id="descvalor" size=5/>
				<input type="submit" value="Descontar no orçamento"/></br></br>
				</form>';
			}
			echo '<div id="desapbutton">';
			echo '<form method="post" action="?acao=dardesconto&pedido='.$pedido;
			if($maisdeum){echo '&data='.$data;} 
			echo'&tipo=porctodosprods"><label for="descporcprods">';
			if($totaldescontos>0){echo 'Substituir d';}else{echo 'D';}
			echo 'esconto porcentagem em todos produtos: </label><input type="text" name="descporcprods" id="descporcprods" size=5/>% 
			<input type="submit" value="Descontar em todos produtos"/>
			</form></br>
			</div>
			<button onclick="showlistprod()">Desconto por produto</button>
			<div id="listprod" style="display:none">
			<form method="post" action="?acao=dardesconto&pedido='.$pedido;
			if($maisdeum){echo '&data='.$data;}
			echo'&tipo=cadaprod">
			<table>
			<tr>
			<td>Ref</td>
			<td>Descrição</td>
			<td>Preço</td>
			<td>Desconto(%)</td>
			<td>Novo Preço</td>
			</tr>';
			foreach($table as $linha){
				
				echo '<tr>
				<td>'.$linha['ref'].'</td>
				<td>'.$linha['descricao'].'</td>
				<td>'.number_format($linha['preco'],2,',','.').'</td>
				<td><input type="text" name="desconto-'.$linha['ref'].'" id="desconto-'.$linha['ref'].'" onkeyup="calc_desc(this.value,'."'".$linha['ref']."'".','.$linha['preco'].')" size=5 value="'.number_format($linha['desconto'],2,',','.').'"/></td>
				<td><input type="text" size=6 id="preconovo-'.$linha['ref'].'" name="preconovo-'.$linha['ref'].'" value="'.number_format($linha['preco']-$linha['preco']*$linha['desconto']/100,2,',','.').'" onkeyup="calc_porcent_mud_valor(this.value,'."'".$linha['ref']."'".','.$linha['preco'].')"/></td>
				</tr>';
			}
			echo '</table>
			<input type="submit" value="Descontos em produtos"/>';
			echo'</form></div>';
			
		}
		if($_GET['acao'] == 'dardesconto'){
			$pedido = $_GET['pedido'];
			if($_GET['tipo']=='porvalorpedido'){
				$desconto=str_replace(',','.',$_POST['descvalor']);
				$qr="update pedidos set desconto_pedido=$desconto where pedido='$pedido'";
				$sql=mysqli_query($con,$qr);
			}
			if($_GET['tipo']=='porctodosprods'){
				$desconto=str_replace(',','.',$_POST['descporcprods']);
				if(isset($_GET['data'])){$data=$_GET['data'];}else{$data='';}
				$qr="update pcp set desconto=$desconto where loc='$pedido'";
				if($data <>''){
					$qr .= " and data_entrega >= '$data 00:00:00' and data_entrega <= '$data 23:59:59'";
				}
				$sql=mysqli_query($con,$qr);
			}
			if($_GET['tipo']=='cadaprod'){
				$pedido = $_GET['pedido'];
				if(isset($_GET['data'])){$data=$_GET['data'];}else{$data='';}
				$qr="select ref from pcp
				where loc='$pedido' and ";
				if($data <> ""){$qr .= "data_entrega >='$data' and data_entrega <='$data 23:59:59' and ";}
				$qr .= "tot>0 group by ref order by ref";
				$sql=mysqli_query($con,$qr);
				$table=array();
				while($row = $sql -> fetch_assoc()){
					$table[] = $row;
				}
				//var_dump($table);
				foreach($table as $ln){
					//var_dump($ln);
					$ref= $ln['ref'];
					$desconto=str_replace(',','.',$_POST['desconto-'.$ref]);
					if($desconto >= 0){
						$qr="update pcp set desconto=$desconto where loc='$pedido' and ref='$ref'";
						if($data <>''){
							$qr .= " and data_entrega >= '$data 00:00:00' and data_entrega <= '$data 23:59:59'";
						}
						$sql=mysqli_query($con,$qr);
						if(!$sql){echo 'erro no sql linha 1564';}						
					}
					else{
						$preco_novo=str_replace(',','.',$_POST['preconovo-'.$ref]);
						$qr="update pcp set preco=$preco_novo where loc='$pedido' and ref='$ref'";
						if($data <>''){
							$qr .= " and data_entrega >= '$data 00:00:00' and data_entrega <= '$data 23:59:59'";
						}
						$sql=mysqli_query($con,$qr);
						if(!$sql){echo 'erro no sql linha 1573';}						
					}
									
				}
			}
			echo '<script>window.location.href="?acao=romaneio&pedido='.$pedido.'"</script>';
		}
		if($_GET['acao']=='formsepararsortido'){
			echo '<form method="post" action="?acao=separarsortido">
				<label for="ref">Ref:</label><input type="text" name="ref" id="ref" size="5" maxlength="6" autofocus/>
				<label for="cor">Cor:</label><input type="text" name="cor" id="cor" size="5"/>
				<label for="t1">P <span style="font-size:10pt">ou 4</span>:</label><input type="number" name="t1" id="t1" value="0"/>
				<label for="t2">M <span style="font-size:10pt">ou 6</span>:</label><input type="number" name="t2" id="t2" value="0"/>
				<label for="t3">G <span style="font-size:10pt">ou 8</span>:</label><input type="number" name="t3" id="t3" value="0"/>
				<label for="t4">GG <span style="font-size:10pt">ou 10</span>:</label><input type="number" name="t4" id="t4" value="0"/>
				<label for="t5">EG <span style="font-size:10pt">ou 12</span>:</label><input type="number" name="t5" id="t5" value= "0"/></br>
				<label for="pedido">Para o Pedido:</label><input type="text" name="pedido" id="pedido" size="5"/></br>
				<input type="submit" name="submit" value="Separar sortido" />
			</form>	';
		}
		if($_GET['acao']=='separarsortido'){
			$ref=mb_strtoupper($_POST['ref'], 'UTF-8');
			$cor=mb_strtoupper($_POST['cor'], 'UTF-8');
			$t[1]=$_POST['t1'];
			$t[2]=$_POST['t2'];
			$t[3]=$_POST['t3'];
			$t[4]=$_POST['t4'];
			$t[5]=$_POST['t5'];
			$tot = array_sum($t);
			$pedido=$_POST['pedido'];
			
			$qr="select preco,desconto from pcp where ref='$ref' and loc='$pedido'";
			$sql=mysqli_query($con,$qr);
			if(mysqli_num_rows($sql)>0){
				$ln=mysqli_fetch_assoc($sql);
				$preco=$ln['preco'];
				$desconto=$ln['desconto'];
			}else{
				$preco=null;
				$desconto=0;
			}
			
			
			$query= "select * from pedidos where pedido ='$pedido'";
			$verificapedido= mysqli_query($con,$query);
			if(mysqli_num_rows($verificapedido)<=0){
				//verifica se pedido existe
				echo"<script language='javascript' type='text/javascript'>alert('Pedido inexistente!');window.location.href='?acao=formsepararsortido';</script>";
				die();
			}
			//verifica se pedido precisa de todos os sortidos separados
			$qr= "select sum(t1),sum(t2),sum(t3),sum(t4),sum(t5) from pcp where ref='$ref' and cor='SORTIDO' and loc='$pedido' and (situacao='A' or situacao='P')";
			$sql= mysqli_query($con,$qr);
			$ln=mysqli_fetch_assoc($sql);
			for($i=1;$i<=5;$i++){
				if($t[$i]>$ln['sum(t'.$i.')']){
				echo"<script language='javascript' type='text/javascript'>alert('Foi separado mais ";
				if($i == 1){echo 'P ou 4 ';} 
				elseif($i == 2){echo 'M ou 6 ';}
				elseif($i == 3){echo 'G ou 8 ';}
				elseif($i == 4){echo 'GG ou 10 ';}
				elseif($i == 5){echo 'EG ou 12 ';}
				echo "sortido que o pedido precisa. Revise e refaça a operação');window.location.href='?acao=formsepararsortido';</script>";
				die();
				}
			}
			//verifica se pedido tem a ref com cor "SORTIDO"
			$qr= "select * from pcp where ref='$ref' and cor='SORTIDO' and loc='$pedido' and (situacao='A' or situacao='P')";
			$sql = mysqli_query($con,$qr);
			if(mysqli_num_rows($sql) <= 0){
				echo"<script language='javascript' type='text/javascript'>alert('Neste pedido não tem esta referência com cor sortido.');window.location.href='?acao=formsepararsortido';</script>";
				die();
			}
			//retirar unidades da linha S do estoque
			$qr= "select * from pcp where ref='$ref' and cor='$cor' and loc='estoque' and situacao='S' and t1>=$t[1] and t2>=$t[2] and t3>=$t[3] and t4>=$t[4] and t5>=$t[5]";
			$result = mysqli_query($con,$qr);
			if(mysqli_num_rows($result) <=0){
				echo"<script language='javascript' type='text/javascript'>alert('Não consta no estoque esta quantidade nesta referência e cor... Acerte o estoque e refaça a operação!');window.location.href='?acao=formsepararsortido';</script>";
				die();
			}
			$ln = mysqli_fetch_assoc($result);
			$t1n = $ln['t1'] - $t[1];
			$t2n = $ln['t2'] - $t[2];
			$t3n = $ln['t3'] - $t[3];
			$t4n = $ln['t4'] - $t[4];
			$t5n = $ln['t5'] - $t[5];
			$totn = $t1n + $t2n + $t3n + $t4n + $t5n;
			if($totn == 0){
				$qr= "delete from pcp where ref='$ref' and cor='$cor' and loc='estoque' and situacao='S'";
				$sql=mysqli_query($con,$qr);
			}
			else{
				$qr= "update pcp set t1=$t1n,t2=$t2n,t3=$t3n,t4=$t4n,t5=$t5n,tot=$totn where ref='$ref' and cor='$cor' and loc='estoque' and situacao='S'";
				$sql= mysqli_query($con,$qr);
			}
			//add unidades no pedido: retirar da linha sortido situacao A e/ou P e passar pra linha S
			//add na linha S
			//verificar se linha S existe
			$qr = "select * from pcp where ref='$ref' and cor='$cor' and loc='$pedido' and situacao='S'";
			$sql = mysqli_query($con,$qr);
			$ln = mysqli_fetch_assoc($sql);
			if(mysqli_num_rows($sql) <= 0){
				//criar linha S
				$qr= "insert into pcp (ref,cor,t1,t2,t3,t4,t5,tot,loc,lote,dataalter,situacao,preco,desconto) values ('$ref','$cor',$t[1],$t[2],$t[3],$t[4],$t[5],$tot,'$pedido',0,default,'S',$preco,$desconto)";
				$sql= mysqli_query($con,$qr);
			}else{
				$tn[1]= $ln['t1'] + $t[1];
				$tn[2]= $ln['t2'] + $t[2];
				$tn[3]= $ln['t3'] + $t[3];
				$tn[4]= $ln['t4'] + $t[4];
				$tn[5]= $ln['t5'] + $t[5];
				$tot = array_sum($tn);
				$qr= "update pcp set t1=$tn[1],t2=$tn[2],t3=$tn[3],t4=$tn[4],t5=$tn[5],tot=$tot where ref='$ref' and cor='$cor' and loc='$pedido' and situacao='S'";
				$sql= mysqli_query($con,$qr);
			}	
			//retirar da linha P
			$qr= "select * from pcp where ref='$ref' and cor='SORTIDO' and loc='$pedido' and situacao='P'";
			$sql = mysqli_query($con,$qr);
			$ln = mysqli_fetch_assoc($sql);
			if(mysqli_num_rows($sql) > 0){
				for($i=1;$i<=5;$i++){
					if($t[$i] <= $ln['t'.$i]){
						$tn[$i] = $ln['t'.$i] - $t[$i];
						$t[$i]=0;
					}else{
						$tn[$i] = 0;
						$t[$i] -= $ln['t'.$i];
					}	
				}
				$totn = array_sum($tn);
				if($totn == 0){
					$qr= "delete from pcp where ref='$ref' and cor='SORTIDO' and loc='$pedido' and situacao='P'";
					$sql=mysqli_query($con,$qr);
				}else{
					$qr= "update pcp set t1=$tn[1],t2=$tn[2],t3=$tn[3],t4=$tn[4],t5=$tn[5],tot=$totn where ref='$ref' and cor='SORTIDO' and loc='$pedido' and situacao='P'";
					$sql= mysqli_query($con,$qr);
				}
			}	
			//retirar da linha A
			$qr= "select * from pcp where ref='$ref' and cor='SORTIDO' and loc='$pedido' and situacao='A'";
			$sql = mysqli_query($con,$qr);
			$ln = mysqli_fetch_assoc($sql);
			if(mysqli_num_rows($sql) > 0){
				for($i=1;$i<=5;$i++){
					if($t[$i] <= $ln['t'.$i]){
						$tn[$i] = $ln['t'.$i] - $t[$i];
						$t[$i]=0;
					}else{
						$tn[$i] = 0;
						$t[$i] -= $ln['t'.$i];
					}	
				}
				$totn = array_sum($tn);
				if($totn == 0){
					$qr= "delete from pcp where ref='$ref' and cor='SORTIDO' and loc='$pedido' and situacao='A'";
					$sql=mysqli_query($con,$qr);
				}else{
					$qr= "update pcp set t1=$tn[1],t2=$tn[2],t3=$tn[3],t4=$tn[4],t5=$tn[5],tot=$totn where ref='$ref' and cor='SORTIDO' and loc='$pedido' and situacao='A'";
					$sql= mysqli_query($con,$qr);
				}
			}	
			header('Location:?acao=formsepararsortido');
		}

		if($_GET['acao']=='consignados'){
			$qr="select distinct(p.loc),u.razaosocial from pcp p 
			join pedidos pe on p.loc=pe.pedido
			join usuarios u on pe.id_cliente=u.id_usuario
			where p.situacao='O'";
			$sql=mysqli_query($con,$qr);
			$tablecons=array();
			while($row = $sql ->fetch_assoc()){
				echo '<a href="?acao=resultconsultped&pedido='.$row['loc'].'">'.$row['loc'].' - '.$row['razaosocial'].'</a></br>';
			}
			
		}
		if($_GET['acao']=='editpedido'){
			if(isset($_GET['formped'])){
				if(isset($_POST['pedido'])){$pedido=$_POST['pedido'];}
				else{$pedido=$_GET['pedido'];}
				$stmt=$dbh->prepare("
				select u.id_usuario,u.razaosocial,p.id_vendedor,p.dataped,p.dataentrega,p.prazopag,p.obs from pedidos p
				join usuarios u on p.id_cliente = u.id_usuario 
				where pedido = ?
				");
				$stmt->execute(array($pedido));
				$ln=$stmt->fetch(PDO::FETCH_ASSOC);
				$cliente=$ln['razaosocial'];
				$id_vendedor=$ln['id_vendedor'];
				
				$stmt=$dbh->prepare("select razaosocial from usuarios where id_usuario=?");
				$stmt->execute(array($id_vendedor));
				$ln2=$stmt->fetch(PDO::FETCH_ASSOC);
				
				$stmt=$dbh->prepare("select id_usuario,nomefantasia from usuarios where acesso like '%representante%'");
				$stmt->execute();
				$ln3=$stmt->fetchAll();
				
				echo '
				<form method="post" action="?acao=editpedido">
				Pedido núm.:<input type="text" value="'.$pedido.'" name="pedido" readonly size="5"/> 
				<input type="hidden" id="id_cliente" name="id_cliente" value="'.$ln['id_usuario'].'"/>
				<label for="cliente">Cliente: </label><input type="text" id="cliente" name="cliente" value="'.$cliente.'" readonly/>
				<a href="?acao=editpedido&modcliente&ped='.$pedido.'"><button type="button">Modificar cliente</button></a></br>
				<label for="vendedor">Vendedor: </label>
				
				<select name="representante">
				<option value="0">Sem representante</option>';
				foreach($ln3 as $row){
					echo '<option';
					if($id_vendedor == $row['id_usuario']){echo ' selected="select"';}
					echo ' value="'.$row['id_usuario'].'">'.$row['nomefantasia'].'</option>';
				}
				echo '</select></br>
				
				
				<label for="dataped">Data do pedido: </label><input type="date" name="dataped" id="dataped" value="'.$ln['dataped'].'"/>
				<label for="dataentrega">Prazo de Entrega: </label><input type="date" name="dataentrega" id="dataentrega" value="'.$ln['dataentrega'].'"/></br>
				<label for="prazopag">Prazo de Pagamento:</label><input type="text" id="prazopag" name="prazopag" value="'.$ln['prazopag'].'" size="15"/></br>
				<label for="obs">Obs.:</label><input type="text" id="obs" name="obs" value="'.$ln['obs'].'" size="60"/></br>
				<input type="hidden" name="moddemaisdados"/>
				</br><input type="submit" value="Alterar Pedido"/>
				</form>
				';
				
			}elseif(isset($_GET['modcliente'])){
				$pedido=$_GET['ped'];
				if(!isset($_GET['id'])){
					echo '
					Pedido núm.:<input type="text" value="'.$pedido.'" name="pedido" readonly size="5"/> 
					<label for="cliente">Cliente: </label>
					<input type="text" id="cliente" name="cliente" onkeyup="lsbuscausuario(this.value,'."'acao=editpedido;modcliente;ped=$pedido'".')"/>
					<div id="livesearch"></div>
					<input type="submit" value="Alterar Cliente do Pedido"/>
					</form>
					';
				}else{
					$id_cliente=$_GET['id'];
					$stmt=$dbh->prepare("select razaosocial from usuarios where id_usuario=?");
					$stmt->execute(array($id_cliente));
					$ln=$stmt->fetch(PDO::FETCH_ASSOC);
					$cliente=$ln['razaosocial'];
					echo '
					<form method="post" action="?acao=editpedido">
					Pedido núm.:<input type="text" value="'.$pedido.'" name="pedido" readonly size="5"/> 
					<input type="hidden" name="editclienteped" value="true"/>
					<input type="hidden" name="id_cliente" value="'.$id_cliente.'"/>
					<label for="cliente">Cliente: </label>
					<input type="text" id="cliente" name="cliente" value="'.$cliente.'" readonly/></br>
					<input type="submit" value="Alterar Cliente do Pedido"/>
					</form>
					';
				}
			}elseif(isset($_POST['editclienteped'])){
				$pedido=$_POST['pedido'];
				$id_cliente=$_POST['id_cliente'];
				$stmt=$dbh->prepare("update pedidos set id_cliente=? where pedido=?");
				$stmt->execute(array($id_cliente,$pedido));
				if($stmt){echo 'Cliente alterado com sucesso no pedido '.$pedido;}
				else{echo 'Erro ao tentar alterar cliente no pedido '.$pedido;}
			}elseif(isset($_POST['moddemaisdados'])){
				$pedido=$_POST['pedido'];
				$id_vendedor=$_POST['representante'];
				$dataped=$_POST['dataped'];
				$dataentrega=$_POST['dataentrega'];
				$prazopag=$_POST['prazopag'];
				$obs=$_POST['obs'];
				$stmt=$dbh->prepare("update pedidos set id_vendedor=?,dataped=?,dataentrega=?,prazopag=?,obs=? where pedido=?");
				if($stmt->execute(array($id_vendedor,$dataped,$dataentrega,$prazopag,$obs,$pedido))){
					echo 'Dados do pedido '.$pedido.' alterados com sucesso.';
				}else{echo 'Erro ao tentar alterar dados do pedido '.$pedido;}
				
			}else{
				
				echo 
				'<form method="post" action="?acao=editpedido&formped">
				<label for="pedido">Número do Pedido: </label><input id="pedido" name="pedido" type="text" ';
				if(isset($_GET['pedido'])){echo 'value="'.$_GET['pedido'].'" ';}
				echo 'size="5" autofocus/>
				<input type="submit" value="Editar pedido"/>
				</form>';
			}
			
		}
		if($_GET['acao']=='devolconsig'){
			$pedido=$_GET['pedido'];
			$data_entrega=date('Y-m-d');
			$stmt=$dbh->prepare("select * from pcp where loc=? and situacao='O' order by ref");
			$stmt->execute(array($pedido));
			$table=$stmt->fetchAll();
			if(!isset($_GET['efidev'])){
				echo '
				<form method="post" action="?acao=devolconsig&pedido='.$pedido.'&efidev">
				<table>
				<tr>
				<td>Ref.</td>
				<td>Cor</td>
				<td>P (ou 4)</td>
				<td>M (ou 6)</td>
				<td>G (ou 8)</td>
				<td>GG(ou 10)</td>
				<td>EG(ou 12)</td>
				<td>Total</td>
				</tr>
				';
				$n_ref=0;
				foreach($table as $ln){
					$n_ref++;
					echo'
					<tr>
					<td>'.$ln['ref'].'</td>
					<td>'.$ln['cor'].'</td>
					<td>'.$ln['t1'].'</td>
					<td>'.$ln['t2'].'</td>
					<td>'.$ln['t3'].'</td>
					<td>'.$ln['t4'].'</td>
					<td>'.$ln['t5'].'</td>
					<td>'.$ln['tot'].'</td>
					</tr>
					<tr>
					<td colspan="2">Devolução:</td>';
					for($i=1;$i<=5;$i++){
						echo '<td>';
						if($ln['t'.$i] > 0){
							$zeta=0;
							echo '<select name="li'.$n_ref.'/t'.$i.'">';
							while($zeta <=$ln['t'.$i]){
								echo '<option>'.$zeta.'</option>';
								$zeta++;
							}
							echo '</select>';
						}else{
							echo '-';
						}
						echo '</td>';
					}
					echo '
					</tr>
					<tr height="15px;" style="border-left:0;"><td colspan="8"></td></tr>';
				}
				echo '
				</table>
				<input type="submit" value="Efetivar devoluções"/>
				</form>
				';
			}else{
				//echo 'entrou no else do !isset efidev</br>';
				$n_ref=0;
				foreach($table as $ln){
					$n_ref++;
					for($i=1;$i<=5;$i++){
						$atual[$i]=$ln['t'.$i];
						if(isset($_POST['li'.$n_ref.'/t'.$i.''])){
							$zeta[$i]=$_POST['li'.$n_ref.'/t'.$i.''];
							$novo[$i]=$atual[$i]-$zeta[$i];
						}else{
							$zeta[$i]=0;
							$novo[$i]=$atual[$i];
						}	
					}
					
					
					$totnovo=array_sum($novo);
					$totvoltou=array_sum($zeta);
					
					if($totvoltou>0){
				
						if($totnovo==0){
							$stmt=$dbh->prepare("delete from pcp where ref=? and cor=? and situacao='O' and loc=?");
							if($stmt->execute(array($ln['ref'],$ln['cor'],$pedido))){
								//echo '1-ok</br>';
							}else{
								//echo 'erro 1</br>';
							}
						}else{
							$stmt=$dbh->prepare("update pcp set t1=?,t2=?,t3=?,t4=?,t5=?,tot=?,data_entrega=?,dataalter=default where ref=? and cor=? and situacao='O' and loc=?");
							if($stmt->execute(array($novo[1],$novo[2],$novo[3],$novo[4],$novo[5],$totnovo,$data_entrega,$ln['ref'],$ln['cor'],$pedido))){
								//echo '2-ok</br>';
							}else{
								//echo 'erro 2</br>';
							}
						}
						//echo 'entrou no if do voltou > 0</br>';
						//Testar se já não existe a linha 'D' pra esta ref, cor, pedido
						$stmt2=$dbh->prepare("select * from pcp where situacao='D' and ref=? and cor=? and loc=?");
						$stmt2->execute(array($ln['ref'],$ln['cor'],$pedido));
						$ver=$stmt2->rowCount();
						if($ver ==0){
							$lote=0;
							$sit='D';
						//	echo '//Não existe a linha D, criar..</br>';
							$stmt=$dbh->prepare("insert into pcp (ref,cor,t1,t2,t3,t4,t5,tot,loc,lote,dataalter,situacao,preco,desconto,data_entrega) values (?,?,?,?,?,?,?,?,?,?,default,?,?,?,?)");
							if($stmt->execute(array($ln['ref'],$ln['cor'],$zeta[1],$zeta[2],$zeta[3],$zeta[4],$zeta[5],$totvoltou,$pedido,$lote,$sit,$ln['preco'],$ln['desconto'],$data_entrega))){
								//echo '3-ok</br>';
							}else{
								echo 'erro 3</br>';
							}
						}else{
						//	echo '//Existe a linha "D", criar..</br>';
							$ln2=$stmt2->fetch(PDO::FETCH_ASSOC);
							for($i=1;$i<=5;$i++){$devs[$i]=$ln2['t'.$i]+$zeta[$i];}
							$totdevs=array_sum($devs);
							$stmt=$dbh->prepare("update pcp set t1=?,t2=?,t3=?,t4=?,t5=?,tot=? where loc=? and ref=? and cor=? and situacao='D'");
							if($stmt->execute(array($devs[1],$devs[2],$devs[3],$devs[4],$devs[5],$totdevs,$pedido,$ln['ref'],$ln['cor']))){
								echo '4-ok</br>';
							}else{
								echo 'erro 4</br>';
							}
						}
						//Voltar com produto pro estoque..
						$boolprod=false;
						gerar_prod($dbh,$ln['ref'],$ln['cor'],$zeta,$boolprod);
					}
				}
			}
		}	
		
		if($_GET['acao']=='confirmpagamento'){
			$pedido=$_GET['pedido'];
			$valor=valor_pedido($pedido,$dbh,'E');
			if(!isset($_POST['valor_pag'])){
				echo '<form method="post" action="?acao=confirmpagamento&pedido='.$pedido.'">
				<label for="valor_pag">Valor do pagamento: </label><input id="valor_pag" name="valor_pag" type="text" value="'.number_format($valor,2,',','.').'" size="8"/></br>
				<label>Local de Recebimento: </label><select name="local" id="local"/><option>CEF</option><option>Gaveta</option></select></br>
				<input type="submit" value="Confirmar"/>
				</form>';
			}else{
				$valor_pag=$_POST['valor_pag'];
				$valor_pag=str_replace('.','',$valor_pag);
				$valor_pag=str_replace(',','.',$valor_pag);
				$local=$_POST['local'];
				$valor=$valor_pag;
				require('../_auxiliares/faturamento.php');
				
				$stmt=$dbh->prepare("select saldo from caixa where num_mov=(select max(num_mov) from caixa where local=?)");
				$stmt->execute(array($local));
				$ln=$stmt->fetch(PDO::FETCH_ASSOC);
				$saldoanterior = $ln['saldo'];
				$novosaldo = $saldoanterior + $valor;
				$desc='Venda a Vista: '.$num_doc;
				$stmt=$dbh->prepare("insert into caixa (num_mov,local,mov,`desc`,valor,saldo,dataalter) values
				(default,?,?,?,?,?,default)");
				if($stmt->execute(array($local,'E',$desc,$valor,$novosaldo))){
					echo 'Saldo da '.$local.' atualizado';
					
				}else{
					echo 'Erro ao atualizar saldo em '.$local;
				}
			}
		}
		if($_GET['acao']=='juntarpedidos'){
			if(!isset($_POST['pedidoFinal'])){
				echo '<form method="post" action="?acao=juntarpedidos">
				Passar o pedido: <input type="text" name="pedido1" id="pedido1" autofocus/></br>
				Para o pedido: <input type="text" name="pedidoFinal" id="pedidoFinal"/>
				<input type="submit" value="Juntar"/>
				</form>';
			}else{
				$pedido1=$_POST['pedido1'];
				$pedidoFinal=$_POST['pedidoFinal'];
				$stmt=$dbh->prepare("select * from pcp where loc=?");
				$stmt->execute(array($pedido1));
				while($ln=$stmt->fetch(PDO::FETCH_ASSOC)){
					$ref=$ln['ref'];
					$cor=$ln['cor'];
					$t1=$ln['t1'];
					$t2=$ln['t2'];
					$t3=$ln['t3'];
					$t4=$ln['t4'];
					$t5=$ln['t5'];
					$tot=$ln['tot'];
					$lote=$ln['lote'];
					$situacao=$ln['situacao'];
					$preco=$ln['preco'];
					$desconto=$ln['desconto'];
					$data_entrega=$ln['data_entrega'];
					$stmt2=$dbh->prepare("insert into pcp (ref,cor,t1,t2,t3,t4,t5,tot,loc,lote,dataalter,situacao,preco,desconto,data_entrega) 
					values (?,?,?,?,?,?,?,?,?,?,default,?,?,?,?)");
					$stmt2->execute(array($ref,$cor,$t1,$t2,$t3,$t4,$t5,$tot,$pedidoFinal,$lote,$situacao,$preco,$desconto,$data_entrega));
					$stmt2=$dbh->prepare("update pcp set situacao='C' where loc=?");
					$stmt2->execute(array($pedido1));
				}
				ECHO 'Pedido '.$pedido1.' adicionado no pedido '.$pedidoFinal;
			}		
		}	
		if($_GET['acao']=='urgencias'){
			
			$stmt=$dbh->prepare("select dado_1 from dadosgerais where nome_dado='pedidos_urgentes'");
			$stmt->execute();
			$ln=$stmt->fetch(PDO::FETCH_ASSOC);
			/*if(strpos($ln,'/')!== false){
				//Diferente de falso, então existe / no $ln
				$exp=explode($ln);
				foreach
			}*/

			echo 'Digite no campo os pedidos urgêntes (separados por "/"): 
			<form action=?acao=urgencias method="post">
			<input type="text" name="peds_urgs" id="peds_urgs" value="'.$ln['dado_1'].'"/>
			<input type="submit" name="up_ped_urg" value="Atualizar"/>
			</form>';
			
			if(isset($_POST['up_ped_urg'])){
				//atualizar valores no dados_gerais e atualizar a página com window.location.href
				$pedidos=$_POST['peds_urgs'];
				$stmt=$dbh->prepare("update dadosgerais set dado_1=? where nome_dado='pedidos_urgentes'");
				$stmt->execute(array($pedidos));
				echo '<script>window.location.href="?acao=urgencias"</script>';
			}
			if(isset($ln['dado_1'])){
				
				$exp=explode('/',$ln['dado_1']);
				foreach($exp as $ped){
					$stmt=$dbh->prepare(
					"select p.dataentrega,u.razaosocial,u.cidade from pedidos p 
					join usuarios u on p.id_cliente = u.id_usuario
					where p.pedido=?");
					$stmt->execute(array($ped));
					$ln=$stmt->fetch(PDO::FETCH_ASSOC);
					$dataentrega=$ln['dataentrega'];
					$razaosocial=$ln['razaosocial'];
					$cidade=$ln['cidade'];
					
					$stmt=$dbh->prepare("
						select ref,cor,t1,t2,t3,t4,t5,tot,lote from pcp 
						where loc=? and (situacao='P' or situacao='A')
						order by lote;
					");
					$stmt->execute(array($ped));
					
					echo '<h1>'.$ped.': '.$razaosocial.' - '.$cidade.' / '.date_format(date_create($dataentrega),'d-m-Y').'</h1>
					<table>
					<tr style="font-weight:bold">
					<td>Ref</td>
					<td>Cor</td>
					<td>P <span style="font-size:10pt">ou 4</span></td>
					<td>M <span style="font-size:10pt">ou 6</span></td>
					<td>G <span style="font-size:10pt">ou 8</span></td>
					<td>GG <span style="font-size:10pt">ou 10</span></td>
					<td>EG <span style="font-size:10pt">ou 12</span></td>
					<td>Total</td>
					<td>Lote</td>
					</tr>';
					while($ln=$stmt->fetch(PDO::FETCH_ASSOC)){	
						
						echo '<tr>
							<td>'.$ln['ref'].'</td>
							<td>'.$ln['cor'].'</td>
							<td>'.$ln['t1'].'</td>
							<td>'.$ln['t2'].'</td>
							<td>'.$ln['t3'].'</td>
							<td>'.$ln['t4'].'</td>
							<td>'.$ln['t5'].'</td>
							<td>'.$ln['tot'].'</td>
							<td>'.$ln['lote'].'</td>
						</tr>';
					}
					echo '</table></br></br>';
					
					
				}
			}
			
		}
		
		if($_GET['acao']=='testeProdConsig'){
			$stmt=$dbh->prepare("
			select pcp.ref,pcp.cor,pcp.t1,pcp.t2,pcp.t3,pcp.t4,pcp.t5,pcp.tot,p.pedido from pcp 
			join pedidos p on p.pedido=pcp.loc
			where p.prazopag like '%CONSIGNADO%' and pcp.situacao='S' and  data_entrega is null
			order by pcp.loc;
			");
			$stmt->execute();
			$ped_atual='';
			
			$boolTable=false;
			$numRow=0;
			while($ln=$stmt->fetch(PDO::FETCH_ASSOC)){
				$pedido=$ln['pedido'];
				
				if($pedido <> $ped_atual){
					if( ($ped_atual <> '')and($boolTable) ){echo '</table>';}
					$boolTable=false;
				}
				$ped_atual=$pedido;
				for($i=1;$i<=5;$i++){
					if($ln['t'.$i] > 0){
						
						$stmt2=$dbh->prepare(" select * from pcp
								 join pedidos p on p.pedido=pcp.loc
								 where pcp.t".$i." > 0 and ref='".$ln['ref']."' and cor='".$ln['cor']."' and (situacao = 'P' or situacao = 'A') and p.prazopag not like '%CONSIGNADO%' 
								 ORDER by p.dataentrega,p.pedido;");
						$stmt2->execute();
						if($stmt2->rowCount()>0){
							if(!$boolTable){
								echo '<h3>Pedido Consignado '.$pedido.'</h3>
								<table>
								<tr>
								<td>Ref</td>
								<td>Cor</td>
								<td>Tamanho</td>
								<td>Qtd.</td>
								<td>Linha</td>
								<td>Pedido</td>
								</tr>
								';
								$boolTable=true;
							}
							$Qnt=$ln['t'.$i];
							while(($ln2=$stmt2->fetch(PDO::FETCH_ASSOC))and($Qnt>0)){							
								if($Qnt <= $ln2['t'.$i]){
									$numRow++;
									echo '<tr>
									<td>'.$ln['ref'].'</td>
									<td>'.$ln['cor'].'</td>
									<td>'.Tamanho($ln['ref'],$i).'</td>
									<td>'.$Qnt.'</td>
									<td>'.$ln2['situacao'].'</td>
									<td>'.$ln2['pedido'].'</td>
									<td>
										<span id="Linha'.$numRow.'" style="color:blue;cursor:pointer"
										onclick="ConsigToPed('."'".$ln['ref']."','".$ln['cor']."',".$i.",'".$pedido."','".$ln2['pedido']."','Linha".$numRow."','".$ln2['situacao']."',".$Qnt.')">
										Atualizar!</spam>
									</td>
									</tr>';
									$Qnt=0;
								}else{	
									$numRow++;
									echo '<tr>
									<td>'.$ln['ref'].'</td>
									<td>'.$ln['cor'].'</td>
									<td>'.Tamanho($ln['ref'],$i).'</td>
									<td>'.$ln2['t'.$i].'</td>
									<td>'.$ln2['pedido'].'</td>
									<td>
										<span id="Linha'.$numRow.'" style="color:blue;cursor:pointer"
										onclick="ConsigToPed('."'".$ln['ref']."','".$ln['cor']."',".$i.",'".$pedido."','".$ln2['pedido']."','Linha".$numRow."',".$ln2['t'.$i].')">
										Atualizar!</spam>
									</td>
									</tr>';
									$Qnt = $Qnt-$ln2['t'.$i];
								}
							}	
						}
					}
				}
			
				
			}
			echo '</table>';	
		}
		if($_GET['acao']=='editPedPrazo'){
			
			echo '<label for="lsDivProds">Editar Produto: </label><input type="text" id="lsProdEditPed"/>
			<div id="lsDivProds"></div>
			';
			if(isset($_GET['editRef'])){
				$ref=$_GET['editRef'];
				$qr='select * from tabela_pedidos where ref=?';
				$values=array($ref);
				$ln=fetchAssoc($dbh,$qr,$values);
				echo '
				<form method="post" action="?acao=editPedPrazo&update">
				</br>
				Ref.: '.$ln['ref'].'</br>
				<input type="hidden" name="ref" id="ref" value="'.$ln['ref'].'"/>
				<label for="refs_cores">Cores: </label><input type="text" name="refs_cores" id="refs_cores" value="'.$ln['refs_cores'].'" size="150"/></br>
				<label for="tamanhos">Tamanhos: </label>Tamanhos: <input type="text" name="tamanhos" id="tamanhos" value="'.$ln['tamanhos'].'" size="15"/></br>
				<input type="submit" value="Atualizar"/>
				</form>
				';
			}
			if(isset($_GET['update'])){
			
				$qr="update tabela_pedidos set refs_cores=?,tamanhos=? where ref=?";
				$values=array($_POST['refs_cores'],$_POST['tamanhos'],$_POST['ref']);
				$stmt=executeSQL($dbh,$qr,$values);
				if(is_string($stmt)){
					echo $stmt;
				}else{
					echo 'Atualizado com sucesso..';
				}
				//var_dump($stmt);
			}
			if(isset($_GET['formAdd'])){
				echo '
				</br>
				<form method="post" action="?acao=editPedPrazo&Addref">
				<label for="ref">Ref.:</label><input type="text" name="ref" id="ref"/></br>
				<label for="refs_cores">Cores: </label></br><input type="text" name="refs_cores" id="refs_cores" size="150" placeholder="Separados por vírgula: BRANCO,PRETO,MAGIC"/></br>
				<label for="tamanhos">Tamanhos: </label><input type="text" name="tamanhos" id="tamanhos" placeholder="No modelo /P/M/G/" size="15"/></br>
				<input type="submit" value="Adicionar"/>
				</form>';
			}
			if(isset($_GET['Addref'])){
				$qr="insert into tabela_pedidos (ref,refs_cores,tamanhos) values (?,?,?)";
				$values=array($_POST['ref'],$_POST['refs_cores'],$_POST['tamanhos']);
				$stmt=executeSQL($dbh,$qr,$values);
				if(is_string($stmt)){
					echo $stmt;
				}else{
					echo 'Adicionado com sucesso..';
				}
			}
			
		}
	}
	else{
		$qr= "select distinct(loc) from pcp 
			join pedidos p on p.pedido = pcp.loc
			where situacao <> 'E' and situacao <> 'C' and situacao <> 'O' and situacao <> 'D' and loc <> 'estoque' 
			group by loc
			order by p.dataentrega,p.pedido";
		$sql=mysqli_query($con,$qr);
		
		$lnall = array();
		while($row = $sql->fetch_assoc()){
			$lnall[] = $row;
		}
		foreach($lnall as $ln){
			$pedido = $ln['loc'];
			$qr="select p.id_cliente,u.cidade,u.razaosocial from pedidos p
			join usuarios u on p.id_cliente = u.id_usuario
			where pedido='$pedido'";
			$sql=mysqli_query($con,$qr);
			$ln=mysqli_fetch_assoc($sql);
			$cliente=$ln['razaosocial'];
			$cidade=$ln['cidade'];
			$qr = "select pcp.situacao,sum(tot),p.dataentrega from pcp join pedidos p on p.pedido=pcp.loc
			where loc='$pedido' 
			group by situacao";
			$sql=mysqli_query($con,$qr);
			$ln2 = array();
			while($row = $sql -> fetch_assoc()){
				$ln2[] = $row;
			}
			$tot=0;$P=0;$A=0;$S=0;$E=0;$C=0;$O=0; 
			foreach($ln2 as $ln3){
				$dataentrega=$ln3['dataentrega'];
				if($ln3['situacao']=='P'){$P = $ln3['sum(tot)'];$tot += $P;}
				elseif($ln3['situacao']=='A'){$A = $ln3['sum(tot)'];$tot += $A;}
				elseif($ln3['situacao']=='C'){$C = $ln3['sum(tot)'];$tot += $C;}
				elseif($ln3['situacao']=='S'){$S = $ln3['sum(tot)'];$tot += $S;}
				elseif($ln3['situacao']=='O'){$O = $ln3['sum(tot)'];$tot += $O;}
				elseif($ln3['situacao']=='E'){$E = $ln3['sum(tot)'];$tot += $E;}
			}
			$Sporc = $S/($tot - $E - $C)*100;
			
			$pedido_echo=pedido_echo($pedido);
			
			echo '<a href=?acao=resultconsultped&pedido='.$pedido.'>';
			if($Sporc==100){echo '[CONFERIR]';}else{echo '<span style="font-size:11pt">&nbsp&nbsp&nbsp';} 
			echo  'Pedido '.$pedido_echo.' -> '.number_format($Sporc,2,',','.').'%';
			echo ' - Prazo: '.date_format(date_create($dataentrega),'d-m-Y');
			echo ' - '.$cliente.' - '.$cidade;
			if($Sporc<>100){echo '</span>';}
			echo '</a></br>';
		}
	}
	echo '</div>';
	require '../footer.php';
	?>
	
</body>
</html>
 