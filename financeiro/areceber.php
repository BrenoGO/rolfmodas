<!DOCTYPE html>
<html>
<head>
  <?php
	require '../head.php';
	?>
  <title>Gerar a Receber</title>
  <script src="_javascript/functionsarec.js"></script>
  <script src="_javascript/functionsgeral.js"></script>
  
</head>
<body>
	<?php
	
	require '../config.php';
	require '../header_geral.php';
	require 'menu.php';
	echo '
	<nav id="submenu">
	<a href="?acao=gerarduplsemdados"><button>Gerar a Receber</button></a>
	<a href="?acao=buscarareceber"><button>Pesquisa a Receber</button></a>
	<a href="?acao=baixadereceber"><button>Baixa de a Receber</button></a>
	<a href="?acao=resultbuscareceber&ini&tipo=cheque"><button>Cheques</button></a>
	</nav>
	</header>
	
	<div class="corpo">
	';
	
	
	if(!isset($_GET['acao'])){
		header("Location:?acao=resultbuscareceber&ini");
		
	}else{
		if($_GET['acao']=='gerardupl'){
			
			$total=$_GET['total'];
			$pedido = $_GET['pedido'];
			
			
			if(isset($_GET['descPed'])){$desconto_pedido = $_GET['descPed'];}else{$desconto_pedido=0;}
			$stmt=$dbh->prepare(
			"select p.id_vendedor,u.comissao,u.comissaofat from pedidos p
			join usuarios u on p.id_vendedor = u.id_usuario
			where p.pedido=?"
			);
			$stmt->execute(array($pedido));
			$ln=$stmt->fetch(PDO::FETCH_ASSOC);
			$id_vendedor=$ln['id_vendedor'];
			$comissaofat=$ln['comissaofat'];
			if($comissaofat==''){$comissaofat=0;}
			$comissao=$ln['comissao'];
			if($comissao==''){$comissao=0;}
			
			echo 'Total: R$ '.number_format($total,2,',','.');
			if($desconto_pedido>0){
				$total=$total-$desconto_pedido;
				echo ' - R$'.number_format($desconto_pedido,2,',','.').' = <b>R$ '.number_format($total,2,',','.').'</b></br>';
			}
			else{echo '</br>';}
			$id_cliente=$_GET['id_cliente'];
			if($id_cliente<>''){
				$qr="select razaosocial from usuarios where id_usuario=$id_cliente";
				$sql=mysqli_query($con,$qr);
				$ln=mysqli_fetch_assoc($sql);
				$rsocial=$ln['razaosocial'];
				echo 'Razão Social: '.$rsocial;
			}else{
				echo '</br><span class="redbold">**Erro: Cliente não cadastrado...</span>';
			}
			
			
			$dataparc1 = date("Y-m-d",mktime (0, 0, 0, date("m")  , date("d")+5, date("Y")));
			echo 
			'<div class="underline">
				<h3>Dados de Pagamento</h3>
				<form method="post" action="?acao=registroareceber">
				<input type="hidden" value="'.$pedido.'" name="pedido"/>
				<input type="hidden" value="'.$id_cliente.'" name="idcliente"/>
				<input type="hidden" value="'.$id_vendedor.'" name="id_vendedor"/>
				Vendedor: <select name="vendedor" onchange="mudarRepPed(this.value,'."'".$pedido."'".');window.location.reload();">
				<option value="0">Sem representante</option>';
				$qr="select id_usuario,nomefantasia,comissao from usuarios where acesso like '%Representante%'";
				$sql=mysqli_query($con,$qr);
				$usuarios = array();
				while($row = $sql -> fetch_assoc()){
					echo '<option value="'.$row['id_usuario'].'" ';
					if($row['id_usuario']==$id_vendedor){echo "selected=selected";}
					echo '>'.$row['nomefantasia'].'</option>';
				}
				echo '</select>
				<label for="data_venda"> &nbsp;&nbsp;&nbsp;&nbsp;
				Data de Faturamento: </label><input type="date" id="data_venda" name="data_venda" value="'.date('Y-m-d').'"/>
				</br>
				
				Forma de Pagamento:<select name="indPag" onchange="formpagam(this.value,'.$total.','."'".$pedido."'".','.$comissao.')">
				<option selected="selected" value="0">A Vista</option>
				<option value="1">A Prazo</option>
				</select></br>
				<select id="tipo_rec" name="tipo_rec">';
				$qr="select * from dadosgerais where nome_dado = 'Tipos de recebimento'";
				$sql=mysqli_query($con,$qr);
				$ln=mysqli_fetch_assoc($sql);
				foreach($ln as $dado){
					if($dado <> ''){
						echo '<option ';if($dado=='Boleto CEF'){echo 'selected=selected';}echo '>'.$dado.'</option>';
					}	
				}
				echo '</select></br></br>';
				if($comissaofat > 0){
					echo '
					- Comissão Fat: R$ <input type="text" size="8" name="comissaofat" id="comissaofat" value="'.number_format($comissaofat/100*$total,2,',','.').'" size=5/>';
				}
				echo '<div id="duplicatas">
					1 Parcela - R$ <input type="text" name="valorparc1" id="valorparc1" value="'.number_format($total,2,',','').'" size=5/>
					- Vencimento: <input type="date" name="dataparc1" value="'.$dataparc1.'" id="dataparc1"/> 
					- Doc N°.:<input type="text" size="8" name="docparc1" id="docparc1" value="'.$pedido.'"/>';
					if($comissao > 0){echo '- Comissão: R$ <input type="text" size="8" name="comiparc1" id="comiparc1" value="'.number_format($comissao/100*$total,2,',','.').'" size=5/>';}
					
				echo '</div>
				<div id="duplicaparceladas">
				</div>
				</br>
				<label for="obs">Observações:</label><input type="text" name="obs" id="obs" size=50/>
				<div id="serecebido">
					</br>Recebido em: <input type="radio" name="local" id="checkCEF" value="CEF"/><label for="checkCEF">CEF</label> - 
					<input type="radio" name="local" id="checkGaveta" value="Gaveta"/><label for="checkGaveta">Gaveta</label>
				</div>
				</br></br><input type="submit" value="Gerar a Receber"/> - <input type="reset" value="Apagar"/>
			</div>
			';
		}
		if($_GET['acao']=='registroareceber'){
			$pedido=$_POST['pedido'];
			$valor=0;
			for($kv=1;isset($_POST['valorparc'.$kv]);$kv++){
				$valorparc=str_replace(',','.',$_POST['valorparc'.$kv]);
				$valor += $valorparc;
				if($kv==1){
					$num_docs_fat=$_POST['docparc1'];
				}else{
					$num_docs_fat .= ';'.$_POST['docparc'.$kv];
				}
			}
			require '../_auxiliares/faturamento.php';
			require("/_auxiliares/GerarAReceber.php");
		}
		if($_GET['acao']=='gerarduplsemdados'){
			echo '
			
			<h3>Dados do cliente:</h3>
			<form method="post" action="?acao=registroareceber';
			
			if(isset($_GET['id'])){
				echo '&id='.$_GET['id'];
			}	
			echo '">';
			if(!isset($_GET['id'])){
				echo '<input type="text" name="cliente" id="cliente" size="10" onkeyup="lscliente(this.value)"/>
				<div id="lsclient"></div>
				';
			}else{	
				if($_GET['id']<>'novo'){
					$idcliente=$_GET['id'];
					$qr="select * from clientes where idclientes = $idcliente";
					$result=mysqli_query($con,$qr);
					$ln=mysqli_fetch_assoc($result);
					$rsocial=$ln['razaosocial'];
					$cnpj=$ln['cnpj'];
					$nfantasia=$ln['nomefantasia'];
					$cidade=$ln['cidade'];
					$estado=$ln['estado'];
					$contato=$ln['contato'];
					$outroscontatos=$ln['outroscontatos'];
					$email=$ln['email'];
					$ie=$ln['ie'];
					$xLgr=$ln['logradouro'];
					$nro=$ln['num'];
					$xCpl=$ln['complemento'];
					$xBairro=$ln['bairro'];
					$CEP=$ln['CEP'];
				}
				echo '
				<label for="rsocial">Razão Social:</label><input type="text" name="rsocial" id="rsocial" size="10"'; 
				if($_GET['id']<>'novo'){ echo 'value ="'.$rsocial;}
				echo '" required/>
				<label for="fantasia">Nome Fantasia:</label><input type="text" name="fantasia" id="fantasia" size="10"'; 
				if($_GET['id']<>'novo'){ echo 'value ="'.$nfantasia;}
				echo '"/>
				<label for="cnpj">CNPJ:</label><input type="text" name="cnpj" id="cnpj" size="12"'; 
				if($_GET['id']<>'novo'){ echo 'value ="'.$cnpj;}
				echo '" required/>
				<label for="ie">Inscrição Estadual:</label><input type="text" name="ie" id="ie" size="12"'; 
				if($_GET['id']<>'novo'){ echo 'value ="'.$ie;}
				echo '"/></br>
				<label for="estado">Estado:</label>
				<select id="estado" name="estado" onclick="selectcidade(this.value)" required>
					<option>Selecione...</option>';
					$qr="select uf_estado from estado order by uf_estado";
					$sql=mysqli_query($con,$qr);
					$tablesqluf = array();
					while($row = $sql->fetch_assoc()){
						$tablesqluf[] = $row;
					}
					foreach($tablesqluf as $lnuf){
						echo '<option value="'.$lnuf['uf_estado'].'" '; 
						if($_GET['id']<>'novo'){if($estado == $lnuf['uf_estado']){echo 'selected="selected"';}}
						echo '>'.$lnuf['uf_estado'].'</option>';
					}
					echo '</select>
					Cidade:
				<div style="display: inline" id="ajaxcidade">
				<select id="cidade" name="cidade" required>';
					if($_GET['id']<>'novo'){
						echo '<option>'.$cidade.'</option>';
					}else{
						echo '<option>Selecione seu estado</option>';
					}
				echo '</select>
				</div></br>
					
				<label for="logradouro">Logradouro:</label><input type="text" name="logradouro" id="logradouro" size="25"'; 
				if($_GET['id']<>'novo'){ echo 'value ="'.$xLgr;}
				echo '"/>
				<label for="num">Número:</label><input type="text" name="numero" id="numero" size="4"'; 
				if($_GET['id']<>'novo'){ echo 'value ="'.$nro;}
				echo '"/>
				<label for="complem">Complemento:</label><input type="text" name="complem" id="complem" size="4"'; 
				if($_GET['id']<>'novo'){ echo 'value ="'.$xCpl;}
				echo '"/></br>
				<label for="bairro">Bairro:</label><input type="text" name="bairro" id="bairro" size="12"'; 
				if($_GET['id']<>'novo'){ echo 'value ="'.$xBairro;}
				echo '"/>
				<label for="CEP">CEP:</label><input type="text" name="CEP" id="CEP" size="12"'; 
				if($_GET['id']<>'novo'){ echo 'value ="'.$CEP;}
				echo '"/></br>
				<label for="contato">Contato:</label><input type="text" name="contato" id="contato" size="12"'; 
				if($_GET['id']<>'novo'){ echo 'value ="'.$contato;}
				echo '" required/>
				<label for="email">E-mail:</label><input type="text" name="email" id="email" size="20"'; 
				if($_GET['id']<>'novo'){ echo 'value ="'.$email;}
				echo '"/></br>
				<label for="outroscontatos">Outros Contatos:</label><input type="text" name="outroscontatos" id="outroscontatos" size="24"'; 
				if($_GET['id']<>'novo'){ echo 'value ="'.$outroscontatos;}
				echo '"/></br>
				';
			}
			echo '';
			$dataparc1 = date("Y-m-d",mktime (0, 0, 0, date("m")  , date("d")+5, date("Y")));
			
			echo 
			'
				<h3>Dados de Pagamento</h3>
				<label for="total">Valor:</label><input type="text" id="total" name="total" size="5"/>
				<label for="doc">Doc:</label><input type="text" id="doc" name="doc" size="5" onkeyup="preencherdoc(this.value)"/></br>
				<form method="post" action="?acao=registroareceber">
				Vendedor: <select name="vendedor">
				<option selected="selected" value=""></option>';
				$qr="select id,usuario,comissao from usuarios where acesso='Representante'";
				$sql=mysqli_query($con,$qr);
				$usuarios = array();
				while($row = $sql -> fetch_assoc()){
					echo '<option value="'.$row['id'].'">'.$row['usuario'].'</option>';
				}
				echo '</select>
				<label for="comissao">Comissão:</label><input type="text" id="comissao" name="comissao" size="5" value="0"/></br>
				<label for="data_venda"> &nbsp;&nbsp;&nbsp;&nbsp;
				Data de Faturamento: </label><input type="date" id="data_venda" name="data_venda" value="'.date('Y-m-d').'"/>
				</br>
				
				Forma de Pagamento:<select name="indPag" onchange="formpagam(this.value,document.getElementById('."'total'".').value,document.getElementById('."'doc'".').value,document.getElementById('."'comissao'".').value)">
				<option selected="selected" value="0">A Vista</option>
				<option value="1">A Prazo</option>
				</select></br>
				<select id="tipo_rec" name="tipo_rec">';
				$qr="select * from dadosgerais where nome_dado = 'Tipos de recebimento'";
				$sql=mysqli_query($con,$qr);
				$ln=mysqli_fetch_assoc($sql);
				foreach($ln as $dado){
					if($dado <> ''){
						echo '<option ';if($dado=='Boleto CEF'){echo 'selected=selected';}echo '>'.$dado.'</option>';
					}	
				}
				echo '</select></br></br>
				<div id="duplicatas">
					1 Parcela - R$ <input type="text" name="valorparc1" id="valorparc1" size=5/>
					- Vencimento: <input type="date" name="dataparc1" id="dataparc1"/> 
					- Doc N°.:<input type="text" size="8" name="docparc1" id="docparc1"/>
					- Comissão: R$ <input type="text" size="8" name="comiparc1" id="comiparc1" size=5/>';
					
				echo '</div>
				<div id="duplicaparceladas">
				</div>
				</br>
				<label for="obs">Observações:</label><input type="text" name="obs" id="obs" size=50/>
				<div id="serecebido">
					</br>Recebido em: <input type="radio" name="local" id="checkCEF" value="CEF"/><label for="checkCEF">CEF</label> - 
					<input type="radio" name="local" id="checkGaveta" value="Gaveta"/><label for="checkGaveta">Gaveta</label>
				</div>
				</br></br><input type="submit" value="Gerar a Receber"/> - <input type="reset" value="Apagar"/>
			
			';
		}
		if($_GET['acao']=='buscarareceber'){
			
			echo '<form method="post" action="?acao=resultbuscareceber">';
			if(!isset($_GET['idcliente'])){
				echo '<label for="cliente">Cliente:</label>
			<input type="text" id="cliente" name="cliente" size="30" onkeyup="lsclientebuscarec(this.value)"/></br>
			<div id="lsclient"></div>'; 
			}else{
				$idcliente=$_GET['idcliente'];
				$qr="select razaosocial from clientes where idclientes = '$idcliente'";
				$sql=mysqli_query($con,$qr);
				$ln=mysqli_fetch_assoc($sql);
				$rsocial=$ln['razaosocial'];
				echo '<input type="hidden" name="id_cliente" value="'.$idcliente.'"
				<label for="cliente">Cliente:</label>
				<input type="text" id="cliente" name="cliente" size="30" onkeyup="lsclientebuscarec(this.value)" value="'.$rsocial.'"/></br>
				<div id="lsclient"></div>';
			}
			echo '<label for="num_doc">Número do documento:</label>
			<input type="text" id="num_doc" name="num_doc" size="7"/></br>
			<label for="data_min">Data Maior que: </label><input type="date" name="data_min" id="data_min"/> e/ou 
			<label for="data_max">Data Menor que:</label><input type="date" name="data_max" id="data_max"/></br>
			<label for="valor_min">Valor Maior que: </label> R$ <input type="text" name="valor_min" id="valor_min" size="6"/> e/ou 
			<label for="valor_max">Valor Menor que:</label> R$ <input type="text" name="valor_max" id="valor_max" size="6"/></br>
			<label for="baixados">Apenas Baixados</label><input type="radio" id="baixados" name="baixados" value="baixados"/></br>
			<label for="naobaixados">Apenas Não Baixados</label><input type="radio" id="naobaixados" name="baixados" value="naobaixados"/></br>
			<label for="ambos">Ambos</label><input type="radio" id="ambos" name="baixados" value="ambos" checked/></br>
			<input type="submit" value="Pesquisar"/>
			</form>';
		}	
		if($_GET['acao']=='resultbuscareceber'){
			if(isset($_GET['ini'])){
				$data_max=date('Y-m-d',mktime(0,0,0,date('m'),date('d')-1,date('Y')));
				$num_doc='';
				$data_min='1900-01-01';
				$valor_min='';
				$valor_max='';
				$baixados='naobaixados';
				if(isset($_GET['tipo'])){
					$tipo_rec=$_GET['tipo'];
					$data_max=date('Y-m-d',mktime(0,0,0,date('m'),date('d')+10,date('Y')));
				}
				
			}else{
				$num_doc=$_POST['num_doc'];
				if(isset($_POST['id_cliente'])){$id_cliente=$_POST['id_cliente'];}
				$data_min=$_POST['data_min'];
				$data_max=$_POST['data_max'];
				$valor_min=str_replace(',','.',$_POST['valor_min']);
				$valor_max=str_replace(',','.',$_POST['valor_max']);
				$baixados=$_POST['baixados'];
			}
						
			$k=0;
			$qr="select * from receber where ";
			if($num_doc <> ''){$qr .= "num_doc like '%$num_doc%' ";$k++;} 
			if(isset($id_cliente)){if($k>0){$qr.= 'and ';}$qr .= "idcliente = $id_cliente ";$k++;} 
			if($data_min <> ''){if($k>0){$qr.= 'and ';}$qr .= "data_vencimento >= '$data_min' ";$k++;} 
			if($data_max <> ''){if($k>0){$qr.= 'and ';}$qr .= "data_vencimento <= '$data_max' ";$k++;} 
			if($valor_min <> ''){if($k>0){$qr.= 'and ';}$qr .= "valor >= '$valor_min' ";$k++;} 
			if($valor_max <> ''){if($k>0){$qr.= 'and ';}$qr .= "valor <= '$valor_max' ";$k++;} 
			if(isset($tipo_rec)){if($k>0){$qr.= 'and ';}$qr .= "tipo_rec = '$tipo_rec' ";$k++;} 
			if($baixados == 'baixados'){if($k>0){$qr.= 'and ';}$qr .= "data_pag is not null";$k++;}
			if($baixados == 'naobaixados'){if($k>0){$qr.= 'and ';}$qr .= "data_pag is null";}
			$sql=mysqli_query($con,$qr);
			$table=array();
			while($row = $sql->fetch_assoc()){
				$table[]=$row;
				if($row['num_doc'] <>''){
					$docsreceber['num_doc']=$row['num_doc'];
					$docsreceber['tipo_rec']=$row['tipo_rec'];
					$docsreceber['idcliente']=$row['idcliente'];
					$docsreceber['data_venda']=$row['data_venda'];
					$docsreceber['data_vencimento']=$row['data_vencimento'];
					$docsreceber['valor']=$row['valor'];
					$docsreceber['vendedor']=$row['vendedor'];
					$docsreceber['data_pag']=$row['data_pag'];
					$docsreceber['parcela']=$row['parcela'];
					$docsreceber['obs']=$row['obs'];
					$docsreceber['situacao']=$row['situacao'];
				}
			}
			if(count($table)==0){
				if(isset($_GET['ini'])){
					echo 'Não existem a receber atrasados!';
				}else{
					echo 'Resultado da pesquisa zerado..';
				}
				
			}else{
				echo '<table>
				<tr>
				<td>N° Doc.</td>
				<td>Tipo Rec.</td>
				<td width="100px">Cliente</td>
				<td>Data Venda</td>
				<td>Data de Venc.</td>
				<td>Valor</td>
				<td>Vendedor</td>';
				if($baixados<>'naobaixados'){
					echo '<td>Data de Pagamento</td>';
				}
				echo '<td>Parcela</td>
				<td>Observações</td>
				<td>Ação</td>
				</tr>';
				$rcompara='';
				$boolrsocial = true;
				foreach($table as $linha){
					if(is_null($linha['data_pag'])){$boolpago=false;}else{$boolpago=true;}
					if( ($linha['data_vencimento']<date('Y-m-d'))and(!$boolpago) ){$vencido=true;}else{$vencido=false;}
					
					$idcliente=$linha['idcliente'];
					$qr="select razaosocial from clientes where idclientes = '$idcliente'";
					$sql=mysqli_query($con,$qr);
					$ln=mysqli_fetch_assoc($sql);
					$rsocial=$ln['razaosocial'];
					if($rcompara <> ''){
						if($rsocial <> $rcompara){
							$boolrsocial = false;
						}
					}
					echo '
					<tr';
					if($vencido){echo ' class="redbold" ';}
					echo '>
					<td>'.$linha['num_doc'].'</td>
					<td>'.$linha['tipo_rec'].'</td>
					<td>'.$rsocial.'</td>
					<td>'.date('d-m-Y',strtotime($linha['data_venda'])).'</td>
					<td>'.date('d-m-Y',strtotime($linha['data_vencimento'])).'</td>
					<td>'.number_format($linha['valor'],2,',','.').'</td>
					<td>'.$linha['vendedor'].'</td>';
					if($baixados<>'naobaixados'){
						echo '<td>';
						if(strtotime($linha['data_pag'])>0){echo date('d-m-Y',strtotime($linha['data_pag']));$boolrsocial = false;}
						echo '</td>';
					}
					echo '<td>'.$linha['parcela'].'</td>
					<td>'.$linha['obs'].'</td>';
					if(!$boolpago){
						echo '
						<td>
							<a href="?acao=selecdupls&num_doc='.$linha['num_doc'].'">Renegociar</a> / 
							<a href="?acao=baixadereceber&respesq&num_doc='.$linha['num_doc'].'">Baixar</a>
						</td>';
					}
					echo '
					</tr>';					
				}
				
				echo '
				</table></br>';
				if( ($boolrsocial == true)and(isset($idcliente)) ){
					echo '<a href="?acao=selecdupls&idcliente='.$idcliente.'"><button>Renegociar Recebimentos</button></a>';
				}
			}
		}	
		if($_GET['acao']=='selecdupls'){
			unset($_SESSION['doc']);
			//pode entrar na "selecdupls" com idcliente ou um num_doc..
			if(isset($_GET['num_doc'])){
				$num_doc=$_GET['num_doc'];
				$array_doc=explode('/',$num_doc);
				$ini_num_doc=$array_doc[0];
				$qr="select idcliente from receber where num_doc like '$ini_num_doc%' and data_pag is null";
				$sql=mysqli_query($con,$qr);
				$ln=mysqli_fetch_assoc($sql);
				$idcliente=$ln['idcliente'];
			}else{
				$idcliente=$_GET['idcliente'];
			}
			$stmt=$dbh->prepare("select razaosocial from clientes where idclientes=?");
			$stmt->execute(array($idcliente));
			$ln=$stmt->fetch(PDO::FETCH_ASSOC);
			$rsocial = $ln['razaosocial'];
			
			$stmt=$dbh->prepare("select * from receber where idcliente =? and data_pag is null");
			$stmt->execute(array($idcliente));
			$table=$stmt->fetchAll();
			$testcomis=false;
			
			foreach($table as $row){
				$stmt=$dbh->prepare("select id_comissao from comissoes where num_doc = ? and data_fat_comis is null");
				$stmt->execute(array($row['num_doc']));
				$test=$stmt->fetchAll();
				if(count($test)>0){$testcomis=true;}
			}
			
			echo '<b>Selecione os boletos que deseja renegociar: </b></br></br>
			<form method="post" action="?acao=renegociacao&idcliente='.$idcliente.'">
			Cliente: '.$rsocial;
			echo '<table><tr>
			<td>N° Doc</td>
			<td>Data de Venc.</td>
			<td>Valor</td>';
			if($testcomis){
				echo '<td>Vendedor</td>
					<td>Comissão</td>';
			}
			echo '
			<td>Renegociar?</td>
			</tr>';
			$k=1;
			foreach($table as $linha){
				$num_doc=$linha['num_doc'];
				$qr= "select valor_comis from comissoes where num_doc='$num_doc' and data_fat_comis is null";
				$sql=mysqli_query($con,$qr);
				$ln_valor_comis=$sql->fetch_assoc();
				
				if($linha['data_vencimento']<date('Y-m-d')){$vencido=true;}else{$vencido=false;}
				echo '<tr'; if($vencido){echo ' class="redbold"';}echo '>
				<td>'.$num_doc.'</td>
				<td>'.date('d-m-Y',strtotime($linha['data_vencimento'])).'</td>
				<td>'.number_format($linha['valor'],2,',','.').'</td>';
				if($testcomis){
					echo '<td>'.$linha['vendedor'].'</td>
					<input type="hidden" name="vendedor'.$k.'" value="'.$linha['vendedor'].'"/>
					<td>'.number_format($ln_valor_comis['valor_comis'],2,',','.').'</td>
					<input type="hidden" name="valor_comis'.$k.'" value="'.$ln_valor_comis['valor_comis'].'"/>';
				}
				echo '
				<td><input type="checkbox" name="doc'.$k.'" value="'.$num_doc.'"';
				if($vencido){echo ' checked';}
				//o input abaixo name valor... serve tanto como teste se existe este $k como passa o valor do doc..
				echo '/>
				<input type="hidden" name="valor'.$k.'" value="'.$linha['valor'].'"/></td>
				<input type="hidden" name="vencimento'.$k.'" value="'.$linha['data_vencimento'].'"/></td>
				</tr>';
				$k++;
			}
			
			echo'</table>
			
			
			<input type="submit" value="Renegociação"/>
			</form>';
		}
		if($_GET['acao']=='renegociacao'){
			$k=1;
			while( (isset($_POST['vendedor'.$k])) and (isset($_POST['doc'.$k])) ){
				if(($_POST['vendedor'.$k]<>'')){
					if(!isset($_POST['vendedor'.($k-1)])){
						$vendedor=$_POST['vendedor'.($k)];
					}else{
						if($_POST['vendedor'.($k-1)]<>$_POST['vendedor'.($k)]){
							echo "<script language='javascript' type='text/javascript'>alert('Não é possível renegociar boletos de representantes diferentes!');window.location.href='areceber.php'</script>";
							die;
						}
					}
				}
				$k++;
			}
			$idcliente=$_GET['idcliente'];
			$qr="select razaosocial from clientes where idclientes=$idcliente";
			$sql=mysqli_query($con,$qr);
			$ln=$sql->fetch_assoc();
			$rsocial = $ln['razaosocial'];
			
			$stmt=$dbh->prepare("select * from dadosgerais where nome_dado='Multa Renegociacao'");
			$stmt->execute();
			$ln=$stmt->fetch(PDO::FETCH_ASSOC);
			$multa=$ln['dado_1'];
				
			$stmt=$dbh->prepare("select * from dadosgerais where nome_dado='Juros Mensal Renegociacao'");
			$stmt->execute();
			$ln=$stmt->fetch(PDO::FETCH_ASSOC);
			$juros=$ln['dado_1'];
				
			$k=1;
			$i=0;
			$total=0;
			$total_c_juros=0;
			$v=0;
			$comissao=0;
			//$vendedor[$v]['valor_comis']=0;
		
			while(isset($_POST['valor'.$k])){
				if(isset($_POST['doc'.$k])){
					//Pegar o num_doc e o valor_doc e vencimento_doc
					$_SESSION['doc'][$i]['num_doc']=$_POST['doc'.$k];
					$_SESSION['doc'][$i]['valor']=$_POST['valor'.$k];
					$total += $_SESSION['doc'][$i]['valor'];
					$_SESSION['doc'][$i]['vencimento']=$_POST['vencimento'.$k];
					$hoje= new DateTime(date('Y-m-d'));
					$vencimento = new DateTime($_SESSION['doc'][$i]['vencimento']);
					$intervalo= $hoje->diff($vencimento);
					$_SESSION['doc'][$i]['atraso']=$intervalo->days;
					if($_SESSION['doc'][$i]['atraso']>0){
						$_SESSION['doc'][$i]['valor_at']=$_SESSION['doc'][$i]['valor']*$multa+$juros/30*$_SESSION['doc'][$i]['valor']*$_SESSION['doc'][$i]['atraso']+$_SESSION['doc'][$i]['valor'];
					}else{
						$_SESSION['doc'][$i]['valor_at']=$_SESSION['doc'][$i]['valor'];	
					}
					$total_c_juros += $_SESSION['doc'][$i]['valor_at'];
					
					if(isset($_POST['vendedor'.$k])){
						$_SESSION['doc'][$i]['vendedor']=$_POST['vendedor'.$k];
						$_SESSION['doc'][$i]['valor_comis']=$_POST['valor_comis'.$k];
					}
					$stmt=$dbh->prepare("select c.valor_comis from comissoes c
										join receber r on r.num_doc = c.num_doc
										where r.num_doc= ? 	and data_fat_comis is null");
					$stmt->execute(array($_SESSION['doc'][$i]['num_doc']));
					$ln=$stmt->fetchAll();
					
					
					$_SESSION['doc'][$i]['comissao']=0;
					if(isset($ln[0][0])){
						foreach($ln as $w){
							$_SESSION['doc'][$i]['comissao'] += $w[0];
							$comissao += $w[0];
						}
					}
					$i++;
				}
				$k++;
			}
			
			
			echo 'Cliente: '.$rsocial.'</br></br>
			Data de Vencimento: '.date('d-m-Y',strtotime($_SESSION['doc'][0]['vencimento'])).'</br>
			Valor total (sem juros): <b>R$ '.number_format($total,2,',','.').'</b></br>
			Valor total (com juros): <b>R$ '.number_format($total_c_juros,2,',','.').'</b></br></br>
			
			<form method="post" action="?acao=modificboletos">
			<input type="hidden" name="idcliente" id="idcliente" value="'.$idcliente.'"/>';
			if(isset($vendedor)){
				echo '<input type="hidden" name="vendedor" id="vendedor" value="'.$vendedor.'"/>';
			}
			
			$porccomis=number_format($comissao/$total*100,0);
			$new_num_doc=explode('/',$_SESSION['doc'][0]['num_doc']);
			
			echo '
			<label for="num_parcs">Parcelas:</label>
			<select name="num_parcs" id="num_parcs" 
			onchange="numparcelas(this.value,'.$total_c_juros.',document.getElementById('."'DiasEntreBoletos'".').value,'."'RE".$new_num_doc[0]."'".','.$porccomis.')"
			>';
			for($i=1;$i<=12;$i++){
				echo '<option>'.$i.'</option>';
			}
			echo '
			</select>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp <label for="DiasEntreBoletos">
			Intervalo entre boletos:</label>
			<input type="number" name="DiasEntreBoletos" id="DiasEntreBoletos" value="30"
			onkeyup="numparcelas(document.getElementById('."'num_parcs'".').value,'.$total_c_juros.',this.value,'."'RE".$new_num_doc[0]."'".','.$porccomis.')"
			/>dias
			<div id="duplicaparceladas">
			<input type="date" name="dataparc1" id="dataparc1" value="'.date('Y-m-d').'"/> - 
			R$ <input type="text" size="4" name="valorparc1" id="valorparc1" value="'.number_format($total_c_juros,2,',','.').'"/> - 
			Doc n.:<input type="text" size="8" name="docparc1" id="docparc1" value="RE'.$new_num_doc[0].'/001"/></br>';
			if($comissao>0){echo ' - Comissão: R$ <input type="text" size="3" name="comiparc1" id="comiparc1" value="'.number_format($comissao/$total*$total_c_juros,2,',','.').'"  /> - '.number_format($comissao/$total*100,1,',','.') .'%';}
			echo '</div>
			<label for="tipos_recebimento">Tipos de recebimento: </label>
			';
			$stmt=$dbh->prepare("select * from dadosgerais where nome_dado='Tipos de Recebimento'");
			$stmt->execute();
			$tipos=$stmt->fetch(PDO::FETCH_ASSOC);
			echo '<select name="tipo_rec">';
			
			foreach($tipos as $tipo){
				if(($tipo <> 'Tipos de Recebimento')and($tipo<> null)){
					echo 
					'<option ';
					if($tipo == 'Boleto CEF'){echo 'selected=selected';}
					echo '>
					'.$tipo.'
					</option>
					';	
				}
			}
			echo '
			</select></br>
			<input type="submit" value="Renegociar"/>';	
		}
		if($_GET['acao']=='modificboletos'){
			if(isset($_POST['vendedor'])){
				$vendedor=$_POST['vendedor'];
			}
			$idcliente=$_POST['idcliente'];
			$data_reneg=date('Y-m-d');
			$tipo_rec=$_POST['tipo_rec'];
			$totalparcs=0;
			for($i=1;isset($_POST['docparc'.$i]);$i++){
				$totalparcs++;
			}	
			$situacao='Aberto';
			$tipo='Renegociação';
			
			for($i=1;isset($_POST['docparc'.$i]);$i++){
				$num_doc=$_POST['docparc'.$i];
				$valor=$_POST['valorparc'.$i];
				$data_vencimento=$_POST['dataparc'.$i];	
				$parcela=$i.'/'.$totalparcs;
				$obs='Boleto vindo de renegociação';
				if(!isset($_POST['comiparc'.$i])){
					$qr='insert into receber (num_doc,tipo_rec,idcliente,data_venda,data_vencimento,valor,parcela,obs,situacao,tipo,dataalter) values (?,?,?,?,?,?,?,?,?,?,default)';
					$executed=array($num_doc,$tipo_rec,$idcliente,$data_reneg,$data_vencimento,$valor,$parcela,$obs,$situacao,$tipo);
					$stmt=$dbh->prepare($qr);				
					$stmt->execute($executed);
				}else{
					$qr='insert into receber (num_doc,tipo_rec,idcliente,data_venda,data_vencimento,valor,vendedor,parcela,obs,situacao,tipo,dataalter) values (?,?,?,?,?,?,?,?,?,?,?,default)';
					$executed=array($num_doc,$tipo_rec,$idcliente,$data_reneg,$data_vencimento,$valor,$vendedor,$parcela,$obs,$situacao,$tipo);
					$stmt=$dbh->prepare($qr);				
					$stmt->execute($executed);
					$comissao=str_replace(',','.',$_POST['comiparc'.$i]);
					$tipo_comis='Reneg:'.$i.'/'.$totalparcs;
					$obs='Comissão de boleto renegociado';
					$qr='insert into comissoes (id_comissao,num_doc,valor_comis,data_prev,tipo_comis,obs) values (default,?,?,?,?,?)';
					$executed=array($num_doc,$comissao,$data_vencimento,$tipo_comis,$obs);
					$stmt=$dbh->prepare($qr);				
					$stmt->execute($executed);
				}	
			}	
			var_dump($_SESSION['doc']);
			$docmax=explode('/',$num_doc);
			$resmdoc=$docmax[0];
			$datapag=date('Y-m-d');
	
			foreach($_SESSION['doc'] as $row){
				$num_doc=$row['num_doc'];
				$stmt=$dbh->prepare("update receber set obs='Renegociado docs: $resmdoc',situacao='Renegociado',data_pag=? where num_doc=?");
				$stmt->execute(array($datapag,$num_doc));
				$stmt=$dbh->prepare("select * from comissoes where num_doc=?");
				$stmt->execute(array($num_doc));
				$ver=$stmt->rowCount();
				if($ver>0){
					$stmt=$dbh->prepare("update comissoes set id_apg=0,obs='Documento renegociado para N. $resmdoc',data_fat_comis=? where num_doc=? and data_fat_comis is null");
					$stmt->execute(array($data_reneg,$num_doc));
				}
			}
			unset($_SESSION['doc']);
		}
		if($_GET['acao']=='baixadereceber'){
			if(!isset($_GET['baixa'])){
				echo '
				<form method="post" action="?acao=baixadereceber&respesq">
				<label for="num_doc">Núm. do Doc: </label>
				<input type="text" name="num_doc" id="num_doc" size="5"/></br>
				<label for="cliente">Cliente: </label>
				<input type="text" name="cliente" id="cliente" size="10"/></br>
				<input type="submit" value="Pesquisar a receber"/>
				</form>
				';
				if(isset($_GET['respesq'])){
					if(isset($_POST['num_doc'])){$num_doc=$_POST['num_doc'];}
					if(isset($_POST['cliente'])){$cliente=$_POST['cliente'];}
					if(isset($_GET['num_doc'])){$num_doc=$_GET['num_doc'];}
					if(isset($_GET['cliente'])){$cliente=$_GET['cliente'];}
					if(!isset($cliente)){$cliente='';}
					
					$stmt=$dbh->prepare("
					select r.num_doc,c.razaosocial,r.data_vencimento,r.tipo_rec from receber r
					join clientes c on r.idcliente=c.idclientes 
					where (r.num_doc like ?) and (c.razaosocial like ? or c.nomefantasia like ?) and (situacao='aberto')");
					$stmt->execute(array('%'.$num_doc.'%','%'.$cliente.'%','%'.$cliente.'%'));
						
					echo '
					<table>
						<tr>
							<td>Doc.</td>
							<td>Razão Social</td>
							<td>Vencimento</td>
							<td>Tipo</td>
						</tr>';
						$k=0;
						while($ln[]=$stmt->fetch(PDO::FETCH_ASSOC)){
							echo '
							<tr>
							<td><a><span style="cursor:pointer" onclick="AddDoc('."'add','".$ln[$k]['num_doc']."'".')">'.$ln[$k]['num_doc'].'</span></a></td>
							<td>'.$ln[$k]['razaosocial'].'</td>
							<td>'.date('d-m-Y',strtotime($ln[$k]['data_vencimento'])).'</td>
							<td>'.$ln[$k]['tipo_rec'].'</td>
							</tr>
							';
							$k++;
						}	
						
						
					echo '</table>';
				}
				echo '<div id="docspbaixar">';
				require("/_auxiliares/areceberdocsprabaixar.php");	
				echo '</div>';
			}else{
				$i=1;
				$local_pag=$_POST['local_pag'];
				while(isset($_POST['num_doc'.$i])){
					$num_doc=$_POST['num_doc'.$i];
					$stmt=$dbh->prepare("select * from receber where num_doc=?");
					$stmt->execute(array($num_doc));
					$lnRec=$stmt->fetch(PDO::FETCH_ASSOC);
					
					$valor_pag=str_replace(',','.',$_POST['valor_pag'.$i]);
					$data_pag=$_POST['data_pag'.$i];
					
					//baixa no a Receber
					$stmt=$dbh->prepare("update receber set situacao='Pago',data_pag=? where num_doc=?");
					$stmt->execute(array($data_pag,$num_doc));
					//Se existir juros deve entrar no a receber já baixado
					if($valor_pag <> $lnRec['valor']){
						$num_doc_juros='JU'.$num_doc;
						$tipoj='juros';
						$juros_pago=$valor_pag-$lnRec['valor'];
						$stmt=$dbh->prepare("insert into faturamentos (id_fat,num_docs,idcliente,data_fat,valor,vendedor,tipo,dataalter) values (default,?,?,?,?,?,?,default)");
						$stmt->execute(array($num_doc_juros,$lnRec['idcliente'],$data_pag,$juros_pago,$lnRec['vendedor'],$tipoj));
						$parcelaJ='1/1';
						$obsJ='Juros pago junto do pagamento do boleto';
						$situacaoJ='Pago';
						$tipoJ='Juros';
						$stmt=$dbh->prepare("insert into receber (num_doc,tipo_rec,idcliente,data_venda,data_vencimento,valor,vendedor,data_pag,parcela,obs,situacao,tipo,dataalter) values
						(?,?,?,?,?,?,?,?,?,?,?,?,default)");
						$stmt->execute(array($num_doc_juros,$lnRec['tipo_rec'],$lnRec['idcliente'],$data_pag,$data_pag,$juros_pago,$lnRec['vendedor'],$data_pag,$parcelaJ,$obsJ,$situacaoJ,$tipoJ));
					}
					
					//Entrada do pagamento no local de recebimento
					$stmt=$dbh->prepare("select saldo from caixa where num_mov=(select max(num_mov) from caixa where local=?)");
					$stmt->execute(array($local_pag));
					$lnLocal=$stmt->fetch(PDO::FETCH_ASSOC);
					$saldofin=$lnLocal['saldo'];
					$saldo=$saldofin+$lnRec['valor'];
					$mov='E';
					$desc='Pagamento do a receber n '.$num_doc;
					$stmt=$dbh->prepare("insert into caixa (`num_mov`,`local`,`mov`,`desc`,`valor`,`saldo`,`data_mov`,`dataalter`) values (default,?,?,?,?,?,?,default)");
					$stmt->execute(array($local_pag,$mov,$desc,$lnRec['valor'],$saldo,$data_pag));
					if(isset($juros_pago)){
						$stmt=$dbh->prepare("select saldo from caixa where num_mov=(select max(num_mov) from caixa where local=?)");
						$stmt->execute(array($local_pag));
						$lnLocal=$stmt->fetch(PDO::FETCH_ASSOC);
						$saldofin=$lnLocal['saldo'];
						$saldo=$saldofin+$juros_pago;
						$mov='E';
						$desc='Juros pago do a receber n '.$num_doc;
						$stmt=$dbh->prepare("insert into caixa (`num_mov`,`local`,`mov`,`desc`,`valor`,`saldo`,`data_mov`,`dataalter`) values (default,?,?,?,?,?,?,default)");
						$stmt->execute(array($local_pag,$mov,$desc,$juros_pago,$saldo,$data_pag));
					}
					
					
					if( (!is_null($lnRec['vendedor'])) and ($lnRec['vendedor']<>'') ){
						//tem comissão a ser paga..(Gerar gasto e dar baixa em comissoes).
						//selecionar id de fornecedor do representante
						$stmt=$dbh->prepare("select * from forn where forn =?");
						$stmt->execute(array($lnRec['vendedor']));
						$lnIdForn=$stmt->fetch(PDO::FETCH_ASSOC);
						$id_forn=$lnIdForn['id_forn'];
						
						//selecionar modo de pagamento pro representante
						$stmt=$dbh->prepare("select * from dadosgerais where nome_dado=?");
						$stmt->execute(array('Pagamento a Representantes'));
						$lnRecRep=$stmt->fetch(PDO::FETCH_ASSOC);
						foreach($lnRecRep as $lnreprec){
							$pagarep=explode('/',$lnreprec);
							if($lnRec['vendedor']==$pagarep[0]){
								$tipo_pg=$pagarep[1];
							}
						}
						
						$stmt=$dbh->prepare("select * from comissoes where num_doc=? and id_apg is null");
						$stmt->execute(array($num_doc));
						$lnComis=$stmt->fetchAll();
						foreach($lnComis as $lnC){
							$desc='Comissão '.$lnC['tipo_comis'].'-id_comissao:'.$lnC['id_comissao'];
							$parcela='1/1';
							//Gerar gasto
							$stmt=$dbh->prepare("insert into gastos (`id_apg`,`id_forn`,`desc`,`valor`,`tipo_pg`,`data_gasto`,`data_venc`,`parcela`,`dataalter`) values (default,?,?,?,?,?,?,?,default)");
							$stmt->execute(array($id_forn,$desc,$lnC['valor_comis'],$tipo_pg,$data_pag,date('Y-m-d'),$parcela));
							//dar baixa na comissão
							$stmt=$dbh->prepare("select max(id_apg) from gastos");
							$stmt->execute();
							$lnIdApg=$stmt->fetchAll();
							$id_apg=$lnIdApg[0][0];
							$stmt=$dbh->prepare("update comissoes set data_fat_comis=?,id_apg=? where id_comissao=?");
							$stmt->execute(array($data_pag,$id_apg,$lnC['id_comissao']));
						}
					}
					$i++;
				}
				echo 'Boletos baixados';
				unset($_SESSION['baixadocs']);
			}
		}
	}
	?>
	</div>
</body>
</html>
 