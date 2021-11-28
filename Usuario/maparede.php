<!DOCTYPE html>
<html>
<head>
    <?php require('../head.php') ?> 
    <title>Mapa da Rede</title>
	<script src="../_javascript/functions.js"></script>
	<script type="text/javascript"> //<![CDATA[ 
var tlJsHost = ((window.location.protocol == "https:") ? "https://secure.comodo.com/" : "http://www.trustlogo.com/");
document.write(unescape("%3Cscript src='" + tlJsHost + "trustlogo/javascript/trustlogo.js' type='text/javascript'%3E%3C/script%3E"));
//]]>
</script>
</head>
<body>
	<?php
	require '../config.php';
	require '../header_geral.php'; 
	require 'menu.php';
	
	echo '<nav id="submenu">
	
	<a href="?acao=cadastconsult"><button>Cadastrar Usuário</button></a>
	<a href="?acao=mapa"><button>Mapa</button></a>
	<a href="?acao=bonus"><button>Bônus</button></a>
	</nav>

	</header>	
	';	
	$qr="select * from usuarios where id_usuario='".$_SESSION['id_usuario']."'";
	$sql=mysqli_query($con,$qr);
	$ln=mysqli_fetch_assoc($sql);
	
	if(!isset($_GET['acao'])){
		header("Location:?acao=mapa");
		
	}else{
		if($_GET['acao'] == 'mapa'){
			if(!isset($_POST['mes_ano'])){
				if(isset($_GET['mes_ano'])){
					$ano_mes=$_GET['mes_ano'];
					$exp_ano_mes=explode('-',$ano_mes);
					$mes=$exp_ano_mes[0];
					$ano=$exp_ano_mes[1];
				}else{
					$mes=date('m');
					$ano=date('Y');
				}
			}else{
				$ano_mes=$_POST['mes_ano'];
				$exp_ano_mes=explode('-',$ano_mes);
				$mes=$exp_ano_mes[0];
				$ano=$exp_ano_mes[1];
			}
			
			$data_fat1=$ano.'-'.$mes.'-01';
			$data_fat2=$ano.'-'.$mes.'-'.date('t',mktime(0,0,0,$mes,'01',$ano));

			require '../_auxiliares/desenho_rede.php';
			if($MMN[0][$_SESSION['id_usuario']]['num_diretos']<=0){
				echo 'Sua rede ainda não possui nenhuma pessoa. Não perca mais tempo, cadastre todos seus conhecidos e receba bônus!';
			}
				//var_dump($MMN);
				
			
			//fim do calc
			
			if(!isset($_GET['id_consult'])){
				$nivel=0;
				$id_nivel[$nivel]=$_SESSION['id_usuario'];
			}else{
				$nivel=$_GET['nivel'];
				$id_nivel[$nivel]=$_GET['id_consult'];
			}
			
			
			$stmt=$dbh->prepare("select rede,nomefantasia,qualificacao,acesso from usuarios where id_usuario=?");
			$stmt->execute(array($id_nivel[$nivel]));
			$ln=$stmt->fetch(PDO::FETCH_ASSOC);
			$rede=$ln['rede'];
			$exprede=explode('/',$rede);
			$num_diretos=count($exprede)-2;
			$nome=$ln['nomefantasia'];
			if($ln['qualificacao']<>null){
				$qualif_nivel=$ln['qualificacao'];
			}else{
				if(strpos($ln['acesso'],'Mini Franqueado')!==false){
					$qualif_nivel='Mini Franqueado';
				}
				if(strpos($ln['acesso'],'Consultor')!==false){
					$qualif_nivel='Consultor';
				}
				if(strpos($ln['acesso'],'Consumidor')!==false){
					$qualif_nivel='Consumidor';
				}
				
			}
			
			echo '
			<form method="post" action="">
			<select name="mes_ano" onchange="this.form.submit()">';
			$mes[0]=date('m');
			$ano[0]=date('Y');
			$num_meses=6;
			$i=1;
			$meses_no_ano=array('01'=>'Jan',
								'02'=>'Fev',
								'03'=>'Mar',
								'04'=>'Abr',
								'05'=>'Mai',
								'06'=>'Jun',
								'07'=>'Jul',
								'08'=>'Ago',
								'09'=>'Set',
								'10'=>'Out',
								'11'=>'Nov',
								'12'=>'Dez');
			$ano_array[0]=date('Y');
			$mes_array[0]=date('m');
			$temp_mes=$mes_array[0];
			$mes_let=$meses_no_ano[$temp_mes];
			echo '<option value="'.$mes_array[0].'-'.$ano_array[0].'">'.$mes_let.'/'.$ano_array[0].'</option>';
			while($i<=$num_meses){
				$mes_array[$i]=$mes_array[($i-1)]-1;
				if($mes_array[$i]==0){
					$ano_array[$i]=$ano_array[($i-1)]-1;
					$mes_array[$i]=12;
				}else{
					$ano_array[$i]=$ano_array[($i-1)];
				}
				if(strlen($mes_array[$i])==1){
					$mes_array[$i]='0'.$mes_array[$i];
				}
				$temp_mes=$mes_array[$i];
				$mes_let=$meses_no_ano[$temp_mes];
				echo '<option ';
				if( ($mes_array[$i].'-'.$ano_array[$i])==($mes.'-'.$ano) ){
					echo 'selected=selected';
				}
				echo 'value="'.$mes_array[$i].'-'.$ano_array[$i].'">'.$mes_let.'/'.$ano_array[$i].'</option>';
				$i++;
			}
			
			
			echo '</select>
			</form>';
			if(isset($MMN[1])){
				echo '
				<table>
				<tr>
				<td></td>
				<td>Nível</td>
				<td>ID</td>
				<td>Consultor</td>
				<td>VP</td>
				<td>VE</td>
				<td>Equipe</td>
				<td>Qualificação</td>
				</tr>
				<tr>
				<td>';
					
				echo '</td>
				<td>0</td>
				<td>'.$_SESSION['id_usuario'].'</td>
				<td>'.$_SESSION['nomefantasia'].'</td>
				<td>'.number_format($MMN[0][$_SESSION['id_usuario']]['VP'],2,',','.').'</td>
				<td>'.number_format($MMN[0][$_SESSION['id_usuario']]['VE'],2,',','.').'</td>
				<td>'.$MMN[0][$_SESSION['id_usuario']]['num_cons_tot'].'</td>
				<td>'.$qualif_nivel.'</td>
				</tr>
				';
				if($nivel>0){
					for($i=($nivel-1);$i>=1;$i--){
						$stmt=$dbh->prepare("select id_usuario from usuarios where rede like ?");
						$stmt->execute(array('%'.$id_nivel[$i+1].'%'));
						$ln=$stmt->fetch(PDO::FETCH_ASSOC);
						$id_nivel[$i]=$ln['id_usuario'];
						
					}
					for($i=1;$i<=$nivel;$i++){
						$stmt=$dbh->prepare("select nomefantasia,qualificacao,acesso from usuarios where id_usuario= ?");
						$stmt->execute(array($id_nivel[$i]));
						$ln=$stmt->fetch(PDO::FETCH_ASSOC);
						$nome_up=$ln['nomefantasia'];
						if($ln['qualificacao']<>null){
							$qualif_nivel=$ln['qualificacao'];
						}else{
							if(strpos($ln['acesso'],'Mini Franqueado')!==false){
								$qualif_nivel='Mini Franqueado';
							}
							if(strpos($ln['acesso'],'Consultor')!==false){
								$qualif_nivel='Consultor';
							}
							if(strpos($ln['acesso'],'Consumidor')!==false){
								$qualif_nivel='Consumidor';
							}
						}
						echo '
						<tr>
						<td>';
						if($i<$nivel){
							echo '<a href="?acao=mapa&nivel='.($i).'&id_consult='.$id_nivel[$i];
							if($mes <> date('m')){
								echo '&mes_ano='.$mes.'-'.$ano;
							}
							echo '"> - </a>';
						}	
						echo '</td>
						<td>'.$i.'</td>
						<td>'.$id_nivel[$i].'</td>
						<td>'.$nome_up.'</td>
						<td>'.number_format($MMN[$i][$id_nivel[$i]]['VP'],2,',','.').'</td>
						<td>'.number_format($MMN[$i][$id_nivel[$i]]['VE'],2,',','.').'</td>
						<td>'.$MMN[$i][$id_nivel[$i]]['num_cons_tot'].'</td>
						<td>'.$qualif_nivel.'</td>
						</tr>';
					}
				}
				
				$novo_nivel=$nivel+1;
				for($i=1;$i<=$num_diretos;$i++){
					$id_cons=$exprede[$i];
					
					$stmt=$dbh->prepare("select rede,nomefantasia,qualificacao,acesso from usuarios where id_usuario=? order by nomefantasia");
					$stmt->execute(array($id_cons));
					$ln=$stmt->fetch(PDO::FETCH_ASSOC);
					$rede1=$ln['rede'];
					$exprede1=explode('/',$rede1);
					$num_diretos1=count($exprede1)-2;
					$consultor=$ln['nomefantasia'];
					if($ln['qualificacao']<>null){
						$qualif_nivel=$ln['qualificacao'];
					}else{
						$qualif_nivel=$ln['acesso'];
					}
					echo '
					<tr>
					<td>';
					if($num_diretos1 >=1){echo '<a href="?acao=mapa&nivel='.($nivel+1).'&id_consult='.$id_cons;
						if($mes <> date('m')){
							echo '&mes_ano='.$mes.'-'.$ano;
						}
						echo '">+</a>';
					}
					echo '</td>
					<td>'.$novo_nivel.'</td>
					<td>'.$id_cons.'</td>
					<td>'.$consultor.'</td>
					<td>'.number_format($MMN[$novo_nivel][$id_cons]['VP'],2,',','.').'</td>
					<td>'.number_format($MMN[$novo_nivel][$id_cons]['VE'],2,',','.').'</td>
					<td>'.$MMN[$novo_nivel][$id_cons]['num_cons_tot'].'</td>
					<td>'.$qualif_nivel.'</td>
					</tr>
					';
				}
				
				
			
				echo '</table>';
			}
			
			
			
		}	
		if($_GET['acao'] == 'cadastconsult'){
			if(!isset($_GET['docad'])){
				echo '<h2>Novo Cadastro</h2>';
				$boolemoutroforn=true;
				$boolnovocliente=true;
				$actionFornCadastPessoa="?acao=cadastconsult&docad";
				$boolcadastsenha=true;
				echo '<form method="post" action="'.$actionFornCadastPessoa.'">';
				require("../_auxiliares/formcadastpessoa.php");
				echo '
				
				
				</br></br><input type="submit" value="Cadastrar"/>
				</form>';
			}else{
				require("../_auxiliares/cadastpessoa.php");
			}
		}	
		if($_GET['acao'] == 'bonus'){
			
			if(!isset($_POST['extrat_bonus_mes'])){
				$stmt=$dbh->prepare("select bonus from usuarios where id_usuario=?");
				$stmt->execute(array($_SESSION['id_usuario']));
				$ln=$stmt->fetch(PDO::FETCH_ASSOC);
				
				if($ln['bonus']>0){
					echo 'Você possui R$ '.number_format($ln['bonus'],2,',','.').' de bônus disponível na sua conta.</br>';
				}
				$mes=date('m');
				$ano=date('Y');
				$data_fat1=$ano.'-'.$mes.'-01';
				$data_fat2=$ano.'-'.$mes.'-'.date('t',mktime(0,0,0,$mes,'01',$ano));
				$stmt=$dbh->prepare("select sum(valor_bonus) as valor from bonus where id_recebedor=? and data_bonus >=? and data_bonus<=? and data_efetivado is not null");
				$stmt->execute(array($_SESSION['id_usuario'],$data_fat1,$data_fat2));
				$ln=$stmt->fetch(PDO::FETCH_ASSOC);
				
				if($ln['valor']>0){
					echo 'Neste mês você recebeu R$'.number_format($ln['valor'],2,',','.').' em bônus.</br>';
				}else{
					$stmt=$dbh->prepare("select sum(valor_bonus) as valor from bonus where id_recebedor=? and data_bonus >=? and data_bonus<=? and data_efetivado is null");
					$stmt->execute(array($_SESSION['id_usuario'],$data_fat1,$data_fat2));
					$ln=$stmt->fetch(PDO::FETCH_ASSOC);
					if($ln['valor']>0){
						echo 'Você possui R$ '.number_format($ln['valor'],2,',','.').' de bônus aguardando para cair na sua conta após sua ativação.</br>';
					}else{
						echo 'Está na hora de você indicar a Rolf para seus amigos para aproveitar as comissões que ela pode de dar.</br>';
					}
				}
				echo '</br>';
				echo '<b>Extrato de bônus: </b>
				<form method="post" action="">
				Período: <input type="date" id="data_ini" name="data_ini"/> a <input type="date" id="data_fim" name="data_fim"/></br>
				<input type="checkbox" name="sintet_analit" id="sintetico" value="sintetico"/>
				<label for="sintetico">Sintético</label>
				<input type="checkbox" name="sintet_analit" id="analitico" value="analitico"/>
				<label for="analitico">Analítico</label></br>
				<input type="submit" name="extrat_bonus_mes" value="Tirar extrato"/>
				</form>';
			}else{
				$data_ini=$_POST['data_ini'];
				$data_fim=$_POST['data_fim'];
				$sintet_analit=$_POST['sintet_analit'];
				if($sintet_analit=='analitico'){
					$ana=true;
					$sint=false;
				}else{
					$sint=true;
					$ana=false;
				}
				if($sint){
					$stmt=$dbh->prepare("select sum(valor_bonus) as valor from bonus where id_recebedor=? and data_bonus >=? and data_bonus<=? and data_efetivado is not null");
					$stmt->execute(array($_SESSION['id_usuario'],$data_ini,$data_fim));
					$ln=$stmt->fetch(PDO::FETCH_ASSOC);
					$bonus=$ln['valor'];
					echo 'Você recebeu R$'.number_format($ln['valor'],2,',','.').' em bônus neste período.</br>';
					
					$stmt=$dbh->prepare("select sum(valor_bonus) as valor from bonus where id_recebedor=? and data_bonus >=? and data_bonus<=? and data_efetivado is not null");
					$stmt->execute(array($_SESSION['id_usuario'],$data_ini,$data_fim));
					$ln=$stmt->fetch(PDO::FETCH_ASSOC);
					$bonus_nao_ativo=$ln['valor'];
					if($bonus_nao_ativo>0){
						echo 'Você deixou de ativar R$'.number_format($bonus_nao_ativo,2,',','.').' em bônus neste período.</br>';
					}
				}elseif($ana){
					$stmt=$dbh->prepare("select * from bonus where id_recebedor=? and data_bonus >=? and data_bonus<=?");
					$stmt->execute(array($_SESSION['id_usuario'],$data_ini,$data_fim));
					$array=array();
					while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
						if($row !== false){
							$array[]=$row;
						
							$index=$row['tipo_bonus'];
							$bonus_analit[$index][]=array('id_comprador' =>$row['id_comprador'],
															'pedido' => $row['pedido'],
															'valor_bonus' => $row['valor_bonus'],
															'data_bonus' => $row['data_bonus']);
						}								
					}
					var_dump($bonus_analit);
				}
				
			}
		}
		
		
	}
		
	require '../footer.php';
?>	
 
<script language="JavaScript" type="text/javascript">
TrustLogo("http://rolfmodas.com.br/_imagens/comodo_secure_seal_100x85_transp.png", "CL1", "none");
</script>
<a  href="https://www.positivessl.com/" id="comodoTL">Positive SSL</a>
</body>
</html>
 