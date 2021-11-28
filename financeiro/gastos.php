<!DOCTYPE html>
<html>
<head>
  <?php
	require '../head.php';
	?>
  <title>Gastos</title>
  <script src="_javascript/functionsgastos.js"></script>
  
</head>
<body>
	<?php

	require '../config.php';
	require '../header_geral.php';
	require 'menu.php';
	echo '
	<nav id="submenu">
	<a href="?acao=novogasto"><button>Novo Gasto</button></a>
	<a href="?acao=resumogastos"><button>Resumo Gastos</button></a>
	<a href="?acao=pagamentos"><button>Pagamentos</button></a>
	</nav>
	</header>
	
	<div class="corpo">
	';
	
	if(!isset($_GET['acao'])){
		header('Location:?acao=novogasto');
	}else{
		switch ($_GET['acao']) {
			case 'novogasto':
				require_once 'gastos/novogasto.php';
				break;
			case 'cadastnovogasto':
				require_once 'gastos/cadastnovogasto.php';
				break;
			default:
				echo 'Caiu no default do switch, avisar Breno...';
				break;
		}
		if($_GET['acao']=='pagamentos'){
			if( (!isset($_GET['id_apg'])) and (!isset($_GET['efetua_pag'])) ){
				$stmt=$dbh->prepare("select * from gastos where data_pag is null and data_venc <= ?");
				$stmt->execute(array(date('Y-m-d')));
				$table=$stmt->fetchAll();
				echo '<table>
				<tr>
					<td>Vencimento</td>
					<td>Valor</td>
					<td>Fornecedor</td>
					<td>Descrição</td>
					<td>Tipo Pag</td>
					<td>Data Compra</td>
				</tr>';
				
				foreach($table as $ln){
					$stmt=$dbh->prepare("select `forn` from `forn` where `id_forn` =?");
					$stmt->execute(array($ln['id_forn']));
					$lnforn=$stmt->fetch(PDO::FETCH_ASSOC);
					$forn=$lnforn['forn'];
					echo '<tr>
						<td>'.date('d-m-y',strtotime($ln['data_venc'])).'</td>
						<td>'.number_format($ln['valor'],2,',','.').'</td>
						<td>'.$forn.'</td>
						<td>'.$ln['desc'].'</td>
						<td>'.$ln['tipo_pg'].'</td>
						<td>'.date('d-m-y',strtotime($ln['data_gasto'])).'</td>
						<td><a href="?acao=pagamentos&id_apg='.$ln['id_apg'].'">Pagar!</a></td>
					</tr>';
				}
				echo '</table>';
			}elseif(isset($_GET['id_apg'])){
				$id_apg=$_GET['id_apg'];
				$stmt=$dbh->prepare("select * from gastos where id_apg = ?");
				$stmt->execute(array($id_apg));
				$ln=$stmt->fetch(PDO::FETCH_ASSOC);
				
				$stmt=$dbh->prepare("select `forn` from `forn` where `id_forn` =?");
				$stmt->execute(array($ln['id_forn']));
				$lnforn=$stmt->fetch(PDO::FETCH_ASSOC);
				$forn=$lnforn['forn'];
				
				if(!is_null($ln['data_pag'])){
					echo 'Pagamento já efetuado em '.date('d-m-y',strtotime($ln['data_pag']));
				}else{
					echo '<table>
					<tr>
						<td>Vencimento</td>
						<td>Valor</td>
						<td>Fornecedor</td>
						<td>Descrição</td>
						<td>Tipo Pag</td>
						<td>Data Compra</td>
					</tr>
					<tr>
						<td>'.date('d-m-y',strtotime($ln['data_venc'])).'</td>
						<td>'.number_format($ln['valor'],2,',','.').'</td>
						<td>'.$forn.'</td>
						<td>'.$ln['desc'].'</td>
						<td>'.$ln['tipo_pg'].'</td>
						<td>'.date('d-m-y',strtotime($ln['data_gasto'])).'</td>
					</tr>
					</table>
					</br>
					<form method="post" action="?acao=pagamentos&efetua_pag">
					<input type="hidden" name="id_apg" value="'.$id_apg.'"/>
					<input type="hidden" name="valor_ori" value="'.$ln['valor'].'"/>
					<input type="hidden" name="desc" value="'.$ln['desc'].'"/>
					<input type="hidden" name="tipo_pg" value="'.$ln['tipo_pg'].'"/>
					<label for="valor_pago">Valor:</label>
					<input type="text" id="valor_pago" name="valor_pago" size="6" value="'.number_format($ln['valor'],2,',','.').'"/></br>
					<label for="data_pag">Data Pagamento:</label>
					<input type="date" id="data_pag" name="data_pag" value="'.date('Y-m-d').'"/></br>
					<label for="local">Local de Retirada:</label>
					<select name="local">';
					$stmt=$dbh->prepare("select * from dadosgerais where nome_dado=?");
					$stmt->execute(array('Contas Fluxo de Caixa'));
					$ln=$stmt->fetch(PDO::FETCH_ASSOC);
					foreach($ln as $dado){
						if( ($dado <> 'Contas Fluxo de Caixa')and($dado<>'') ){
							echo '
							<option>'.$dado.'</option>
							';
						}
					}
					echo '</select></br>
					<input type="submit" value="Pagar!"/>';
				}
			}elseif(isset($_GET['efetua_pag'])){
				$id_apg=$_POST['id_apg'];
				$valor_ori=$_POST['valor_ori'];
				$valor_pago=str_replace(',','.',$_POST['valor_pago']);
				$data_pag=$_POST['data_pag'];
				$desc=$_POST['desc'];
				$local=$_POST['local'];
				$tipo_pg=$_POST['tipo_pg'];
				$dif=$valor_pago-$valor_ori;
				if($dif < -1){
					//Considerado pagamento parcial..
					
					$obs='Pagamento parcial!';
					$stmt=$dbh->prepare("update gastos set data_pag=?,valor=?,obs=?,dataalter=default where id_apg = ?");
					$stmt->execute(array($data_pag,$valor_pago,$obs,$id_apg));
					
					//Criar novo gasto, copiado do primeiro
					$novo_valor=$valor_ori-$valor_pago;
					$stmt=$dbh->prepare("select * from gastos where id_apg=?");
					$stmt->execute(array($id_apg));
					$ln=$stmt->fetch(PDO::FETCH_ASSOC);
					$obs='Parte que faltou do pagamento parcial do id_apg: '.$id_apg;
					$stmt=$dbh->prepare("insert into gastos (id_apg,id_forn,`desc`,valor,tipo_pg,data_gasto,data_venc,parcela,obs,dataalter) values
					(default,?,?,?,?,?,?,?,?,default)");
					$stmt->execute(array($ln['id_forn'],$ln['desc'],$novo_valor,$ln['tipo_pg'],$ln['data_gasto'],$ln['data_venc'],$ln['parcela'],$obs));
					//ajuste Caixa					
					$stmt=$dbh->prepare("select saldo from caixa where num_mov=(select max(num_mov) from caixa where local=?)");
					$stmt->execute(array($local));
					$lnLocal=$stmt->fetch(PDO::FETCH_ASSOC);
					$saldofin=$lnLocal['saldo'];
					$saldo=$saldofin-$valor_pago;
					$mov='S';
					$descsaldo='Pagamento parcial de '.$desc.'. Id_apg : '.$id_apg;
					$stmt=$dbh->prepare("insert into caixa (`num_mov`,`local`,`mov`,`desc`,`valor`,`saldo`,`data_mov`,`dataalter`) values (default,?,?,?,?,?,?,default)");
					$stmt->execute(array($local,$mov,$descsaldo,$valor_pago,$saldo,$data_pag));
					
				}else{
					$stmt=$dbh->prepare("update gastos set data_pag=? where id_apg = ?");
					$stmt->execute(array($data_pag,$id_apg));
					$stmt=$dbh->prepare("select saldo from caixa where num_mov=(select max(num_mov) from caixa where local=?)");
					$stmt->execute(array($local));
					$lnLocal=$stmt->fetch(PDO::FETCH_ASSOC);
					$saldofin=$lnLocal['saldo'];
					$saldo=$saldofin-$valor_ori;
					$mov='S';
					$desc='Pagamento de '.$desc.'. Id_apg : '.$id_apg;
					$stmt=$dbh->prepare("insert into caixa (`num_mov`,`local`,`mov`,`desc`,`valor`,`saldo`,`data_mov`,`dataalter`) values (default,?,?,?,?,?,?,default)");
					$stmt->execute(array($local,$mov,$desc,$valor_ori,$saldo,$data_pag));
					if($dif<>0){
						$id_forn=52;
						$parcela='1/1';
						$desc='Diferença entre valores. Id_apg: '.$id_apg;
						$stmt=$dbh->prepare("insert into gastos (id_apg,id_forn,`desc`,valor,tipo_pg,data_gasto,data_venc,data_pag,parcela,dataalter) 
						values (default,?,?,?,?,?,?,?,?,default)");
						$stmt->execute(array($id_forn,$desc,$dif,$tipo_pg,$data_pag,$data_pag,$data_pag,$parcela));
						
						$stmt=$dbh->prepare("select saldo from caixa where num_mov=(select max(num_mov) from caixa where local=?)");
						$stmt->execute(array($local));
						$lnLocal=$stmt->fetch(PDO::FETCH_ASSOC);
						$saldofin=$lnLocal['saldo'];
						$saldo=$saldofin-$dif;
						$mov='S';
						$stmt=$dbh->prepare("insert into caixa (`num_mov`,`local`,`mov`,`desc`,`valor`,`saldo`,`data_mov`,`dataalter`) values (default,?,?,?,?,?,?,default)");
						$stmt->execute(array($local,$mov,$desc,$dif,$saldo,$data_pag));
					}
				}
				echo 'Pagamento efetuado!';
			}
		}
	}
	
	?>
	</div>
</body>
</html>
 