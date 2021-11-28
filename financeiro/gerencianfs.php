<!DOCTYPE html>
<html>
<head>
  <?php
	require '../head.php';
	?>
  <title>Geranciar Nfs</title>
  <script src="../_javascript/functions.js"></script>
</head>
<body>
	<?php
	
	require '../config.php';
	require '../header_geral.php';
	require 'menu.php';
	echo '
	<nav id="submenu">
	<a href="?acao=consultanotasefaz"><button>Consulta Nota Sefaz</button></a>
	<a href="?acao=formcancelar"><button>Cancelar NF!</button></a>
	<a href="?acao=formdownloadxml"><button>Download de XML</button></a>
	<a href="?acao=formnotadevolucao"><button>Nota de Devolução</button></a>
	<a href="?acao=inutNums"><button>Inutilizar Número(s)</button></a>
	</nav>
	
	</header>
	
	<div class="corpo">
	';
	
	
	
	
	require '../_nfe/config/config.php';
	require_once '../_nfe/vendor/autoload.php';
	
	use NFePHP\NFe\Tools;
	use NFePHP\Common\Certificate;
	use NFePHP\NFe\Common\Standardize;
	use NFePHP\NFe\Complements;
	
	$tools = new NFePHP\NFe\Tools($configJson, Certificate::readPfx($certificadoDigital, $certPassword));
	$tools->model('55');//55 para NFe (65 seria para NFCe, não sei nem se pode usar 65..)
			
			
	if(!isset($_GET['acao'])){
		echo '<form method="post" action="?acao=detalhenf">
		Num NF de <input type="number" name="nnfini" id="nnfini"/> a <input type="number" name="nnffin" id="nnffin"/></br>
		<input type="submit" value="Consultar NFs"/>
		</form>
		';
	}else{
		if($_GET['acao']=='detalhenf'){
			$nfini = $_POST['nnfini'];
			$nffin = $_POST['nnffin'];
			$stmt=$dbh->prepare("select nf.num_nf,u.razaosocial,nf.chave,nf.situacao from nf 
								join usuarios u on u.id_usuario = nf.id_usuario
								where num_nf >= ? and num_nf <= ?");
			$stmt->execute(array($nfini,$nffin));
			$table = array();
			while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
				$table[] = $row;
			}
			
			echo '
			Sequencia: XML Gerado -> Nota Assinada -> Nota Válida -> Nota enviada a SEFAZ -> Nota Autorizada -> Nota Gerada</br>
			</br><table>
			<tr>
			<td>Num NF</td>
			<td>Razão Social</td>
			<td>Chave da NF</td>
			<td>Situação</td>
			</tr>';
			foreach ($table as $ln){
				echo '<tr>
				<td>'.$ln['num_nf'].'</td>
				<td>'.$ln['razaosocial'].'</td>
				<td>'.$ln['chave'].'</td>
				<td>'.$ln['situacao'].'</td>';
				
				if($ln['situacao']=='Nota Válida'){
					echo '<td><a href="?acao=envialote&n='.$ln['num_nf'].'">Enviar Lote(VAZIO, NÃO USAR)</a></td>';
				}elseif($ln['situacao']=='Lote em Processamento'){
					echo '<td><a href="?acao=consultarecibo&n='.$ln['num_nf'].'">Consultar Recibo</a></td>';
				}elseif($ln['situacao']=='Nota Autorizada'){
					echo '<td><a href="?acao=addprot_danfe&n='.$ln['num_nf'].'">Protocolar e DANFE(VAZIO, NÃO USAR)</a></td>';
				}
				echo '</tr>';
			}
			echo '</table>';
		}
		if($_GET['acao']=='envialote'){
		}
		if($_GET['acao']=='consultarecibo'){
			$num_nf=$_GET['n'];
			$stmt=$dbh->prepare("select situacao,chave,recibo from nf where num_nf=?");
			$stmt->execute(array($num_nf));
			$ln=$stmt->fetch(PDO::FETCH_ASSOC);
			if($ln['recibo']==''){
				echo 'Não é possível consultar o recibo pois não possuímos seu número!!!';
			}
			echo '<b>Nota Fiscal N.: '.$num_nf.'</b></br>
			Situação: '.$ln['situacao'].'</br>
			Chave: '.$ln['chave'].'</br>
			Recibo: '.$ln['recibo'].'
			<form method="post" action="../_nfe/ConsultaRecibo.php">
			<input type="hidden" name="nNF" value="'.$num_nf.'"/>
			<input type="hidden" name="chave" value="'.$ln['chave'].'"/>
			<input type="hidden" name="recibo" value="'.$ln['recibo'].'"/>
			<input type="submit" value="Consultar Recibo"/>
			</form>';
			
			
		}
		if($_GET['acao']=='addprot_danfe'){
		
		}
		if($_GET['acao']=='consultanotasefaz'){
			echo '<form method="post" action="?acao=resultconsultanotasefaz">
				<label for="num">Número da Nota: </label><input type="number" name="num" id="num" size=3/>
				<input type="submit" value="Consulta Nota"/>
				</form>';
		}
		if($_GET['acao']=='resultconsultanotasefaz'){
			
			$num_nf = $_POST['num'];
			$stmt=$dbh->prepare("select chave from nf where num_nf=?");
			$stmt->execute(array($num_nf));
			$ln=$stmt->fetch(PDO::FETCH_ASSOC);
			$chave=$ln['chave'];
			
			$response = $tools->sefazConsultaChave($chave);
			
			
			
			
			$stdCl = new Standardize($response);
			$std = $stdCl->toStd();
			$arr = $stdCl->toArray();
			
			if($arr['protNFe']['infProt']['cStat'] == '100'){
				echo '<h1>Autorizado o Uso da NF-e</h1>';
			}
			
			var_dump($arr);
			
			
		}
		if($_GET['acao']=='formcancelar'){
			echo '<form method="post" action="?acao=cancelamentonf">
				<label for="num">Número da Nota: </label><input type="number" name="num" id="num" size=3/></br>
				<label for="just">Justificativa: </label><input type="text" name="just" id="just" size=50/></br>
				<input type="submit" value="CANCELAR NOTA!" onclick="return confirm('."'Tem certeza?'".');"/>
				</form>';
		}
		if($_GET['acao']=='cancelamentonf'){
			$nNF = $_POST['num'];
			$xJust = $_POST['just'];
			
			$stmt=$dbh->prepare("select chave,nprot from nf where num_nf=?");
			$stmt->execute(array($nNF));
			$ln=$stmt->fetch(PDO::FETCH_ASSOC);
			$chave=$ln['chave'];
			$nProt=$ln['nprot'];
			try{
				$response = $tools->sefazCancela($chave, $xJust, $nProt);
				$stdCl = new Standardize($response);
				$std = $stdCl->toStd();
				if ($std->cStat != 128) {
					echo 'houve alguma falha no primeiro if.., o evento não foi processado!';
				} else {
					$cStat = $std->retEvento->infEvento->cStat;
					if ($cStat == '101' || $cStat == '135') {
						//SUCESSO PROTOCOLAR A SOLICITAÇÂO ANTES DE GUARDAR
						$xmlCancelamento = Complements::toAuthorize($tools->lastRequest, $response);
						
						$stmt=$dbh->prepare("update nf set situacao=? where num_nf=?");
						$stmt->execute(array('NF Cancelada!',$nNF));
						
						$iniPath='../_nfe/';
						$pastatest=$iniPath.'xmls/NF-e/'.$ambiente.'/canceladas/'.date("Y").date("m");
						if(!file_exists($pastatest)){
							mkdir($pastatest,0777);
						}
						
						$pathXmlCancelamento=$iniPath.'xmls/NF-e/'.$ambiente.'/canceladas/'.date("Y").date("m").'/'.$chave.'-CancNFe-procEvento.xml';
						file_put_contents($pathXmlCancelamento,$xmlCancelamento);
						ECHO 'Evento registrado e vinculado a NF-e. Consulte na SEFAZ para confirmar registro de cancelamento.';
						
					} elseif($cStat == '155'){
						echo 'Cancelamento homologado fora de prazo... verificar o que fazer..';
					}else{
						echo 'Houve falha no evento...segundo if..';
						var_dump($cStat);
						var_dump($std);
					}
				}    
			} catch (\Exception $e) {
				
				echo 'Chave: '.$chave.'</br>
				nProt: '.$nProt.'</br>
				xJust: '.$xJust.'</br>
				Catch: :( </br>'.$e->getMessage();
				
			}
		}
		if($_GET['acao']=='formdownloadxml'){
			echo 'Digite o mês e ano que deseja fazer o download do xml.</br>
			<form method="post" action="?acao=downloadxml">
			<label for="mes">Mês(MM): </label><input type="text" name="mes" id="mes" size="1" value="'.(date('m')) .'"/> 
			<label for="ano">Ano(AAAA): </label><input type="text" name="ano" id="ano" size="2" value="'.date('Y').'"/> 
			<input type="submit" value="Download"/>
			</form>';
		}
		if($_GET['acao']=='downloadxml'){
			$mes = $_POST['mes'];
			$ano=$_POST['ano'];
			$pasta=$ano.$mes;
			$diretorio="../_nfe/xmls/NF-e/producao/enviadas/aprovadas/$ano$mes/";
			if(is_dir($diretorio)){
				$zip = new ZipArchive();
				$files=scandir($diretorio);
				$files=array_diff($files,array('..','.'));
				var_dump($files);
				if(count($files)<=0){
					echo 'Não existe NFs aprovadas neste mês';
				}else{
					$res = $zip->open('xmls'.$ano.$mes.'.zip', ZIPARCHIVE::CREATE);
					if($res === TRUE){
						var_dump($res);
						foreach($files as $file){
							if(substr($file,-3)=='xml'){
								$zip ->addFile($diretorio.$file,$file);
							}
							
						}
					}
				}
				
				$zip->close();
				rename("xmls$ano$mes.zip",$diretorio."xmls$ano$mes.zip");
				$file=$diretorio.'xmls'.$ano.$mes.'.zip';
				if(file_exists($file)){
					header('Content-Disposition: attachment; filename="'.basename($file).'"');
					readfile($file);
					exit;
				}
			}else{
				echo 'Não existe NFs aprovadas neste mês';
			}
		}
		if($_GET['acao']=='formnotadevolucao'){
			echo '
			<form method="post" action="../_nfe/NFeDevolucao.php">
			<h3>Dados do Fornecedor</h3>Não precisa preencher: Nome Fantasia e Data CNPJ</br></br>';
			$boolemoutroforn=true;
			$boolnovocliente=true;
			//$actionFornCadastPessoa ->não precisa pois booloutroforn = true..
			
			require "../_auxiliares/formcadastpessoa.php";
			
			echo '<h3>Dados do Produto</h3>
			<input type="hidden" name="count" id="count" value="1"/>
			<label for="Cod1">Cód. Prod:</label><input type="text" name="Cod1" id="Cod1" size="3"/>
			<label for="desc1">Descrição do Produto:</label><input type="text" name="desc1" id="desc1"/>
			<label for="NCM1">NCM:</label><input type="text" name="NCM1" id="NCM1" size="8"/></br>
			<label for="Unid1">Unid (Un, kg):</label><input type="text" name="Unid1" id="Unid1" size="8"/>
			<label for="Qtde1">Qtde.:</label><input type="text" name="Qtde1" id="Qtde1" size="8"/>
			<label for="preco1">Valor Unit.:</label><input type="text" name="preco1" id="preco1" size="8"/>
			<label for="desconto1">Desconto:</label><input type="text" name="desconto1" id="desconto1" size="8"/>
			<button type="button" onclick="add_prod_nf_dev(document.getElementById("count").value)">Adicionar produto</button>
			';//Não desenvolvi botão para adicionar produto pq no momento não tive necessidade..
			echo '</br></br>
			<label for="numdevolumes">Número de Volumes: </label><input type="text" name="numdevolumes" id="numdevolumes" size="8"/>
			<label for="tipovolume">Espécie de Volume: </label><input type="text" name="tipovolume" id="tipovolume" size="8"/></br>
			<label for="chave_referenciada">Chave da NF Referenciada: </label><input type="text" name="chave_referenciada" id="chave_referenciada" size="40"/></br>
			<label for="infCpl">Informações Complementares da NF (preencher ao menos onde estão as interrogações)</label></br>
			<textarea name="infCpl" id="infCpl" cols="80" rows="5">Devolução (parcial ou integral?) da NF (?) do dia (?). / Bc. Icms= (?) - Vl. Icms= (?). / Empresa optante pelo simples nacional, não gera direito a crédito de ICMS
			</textarea></br>
			<input type="submit" value="Gerar NF de Devolução"/>';
		}
		if($_GET['acao']=='inutNums'){
			if(isset($_POST['inutSubmit'])){
				require '../_nfe/inutilizacao.php';
			} else{
				echo '<h3>Informe o início e fim dos número a inutilizar:</h3>
				<form method="post" action="?acao=inutNums">
				<label for="nIni">Início:</label>
				<input type="text" name="nIni" id="nIni" size="4"/></br>
				
				<label for="nFin">Fim:</label>
				<input type="text" name="nFin" id="nFin" size="4"/></br>
				
				<label for="xJust">Justificativa: </label>
				<input type="text" name="xJust" id="xJust" size="60"/></br>
				
				<input type="submit" name="inutSubmit" value="Inutilizar"/>
				</form>';
			}
		}
	}
	
	require '../footer.php';
?>
</div>
</body>
</html>
 