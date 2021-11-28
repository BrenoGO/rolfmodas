	<!DOCTYPE html>
<html>
<head>
  <?php
	require '../head.php';
	?>
  <title>NFe</title>
  <script src="_javascript/functionsnfe.js"></script>
  <script src="../_javascript/functions.js"></script>
  <script src="../financeiro/_javascript/functionsarec.js"></script>
</head>
<body>
<?php
//se tiver desconto no pedido, como passar pra nf, e tbm pra gerar os a receber e comissoes etc..

	require '../config.php';
	require 'config.php';

	require '../header_geral.php';

	echo '<nav id="submenu"></nav>
	</header>
	<div class="conteudo">';
	
	
	if(isset($_POST['id_cliente'])){
		$id_cliente = $_POST['id_cliente'];
		$rsocial = $_POST['rsocial'];
		$nfantasia = $_POST['fantasia'];
		$data_nascimento = $_POST['data_nascimento'];
		$cidade = $_POST['cidade'];
		$estado = $_POST['estado'];
		$cnpj = $_POST['cnpj'];
		$cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);
		$contato = $_POST['contato'];
		$outroscontatos=$_POST['outroscontatos'];
		$email = $_POST['email'];
		$tipo_pessoa=$_POST['tipo_pessoa'];
		$ie = $_POST['ie'];
		if($ie <> 'ISENTO'){
			$ie = preg_replace('/[^0-9]/', '', (string) $ie);
		}
		$xLgr = $_POST['logradouro'];
		$nro = $_POST['numero'];
		$xCpl = $_POST['complem'];
		$bairro = $_POST['bairro'];
		$CEP=$_POST['CEP'];
		$qr="update usuarios set razaosocial=?,nomefantasia=?,data_nascimento=?,cidade=?,estado=?,cnpj=?,contato=?,outroscontatos=?,email=?,ie=?,logradouro=?,num=?,complemento=?,bairro=?,CEP=?,tipo_pessoa=? where id_usuario=?";
		$stmt=$dbh->prepare($qr);
		if($data_nascimento=='0000-00-00'){
			$data_nascimento=null;
		}
		$array_exec=array($rsocial,$nfantasia,$data_nascimento,$cidade,$estado,$cnpj,$contato,$outroscontatos,$email,$ie,$xLgr,$nro,$xCpl,$bairro,$CEP,$tipo_pessoa,$id_cliente);
		if(!($stmt->execute($array_exec))){
			echo 'erro no sql do nfe.php->linha 57';
		}
		
	}
	$pedido = $_GET['pedido'];
	if(isset($_GET['data'])){$dataentrega = $_GET['data'];}else{$dataentrega="";}
	$qr= "select id_cliente,desconto_pedido from pedidos where pedido='$pedido'";
	$sql=mysqli_query($con,$qr);
	$ln=mysqli_fetch_assoc($sql);
	$desconto_pedido=$ln['desconto_pedido'];
	if($desconto_pedido==''){$desconto_pedido=0;}
	$id_cliente=$ln['id_cliente'];
	
	
	$qr="select * from usuarios where id_usuario ='$id_cliente'";
	$sql=mysqli_query($con,$qr);
	$ln=mysqli_fetch_assoc($sql);
	$rsocial = $ln['razaosocial'];
	$cnpj=$ln['cnpj'];
	$nfantasia=$ln['nomefantasia'];
	$data_nascimento=$ln['data_nascimento'];
	$cidade=$ln['cidade'];
	$estado=$ln['estado'];
	$contato=$ln['contato'];
	$outroscontatos=$ln['outroscontatos'];
	$email=$ln['email'];
	$ie=$ln['ie'];
	$xLgr=$ln['logradouro'];
	$nro=$ln['num'];
	$tipo_pessoa=$ln['tipo_pessoa'];
	$xCpl=$ln['complemento'];
	$xBairro=$ln['bairro'];
	$CEP=$ln['CEP'];
	
	$actionFornCadastPessoa='?tprods=geral&pedido='.$pedido;
	$boolnovocliente=false;
	$boolemoutroforn=false;
	$id_usuario=$id_cliente;
	echo '<div class="underline">
	<h3>Cliente</h3>';
	$boolcadastsenha=false;
	require("../_auxiliares/formcadastpessoa.php");
	echo '</div>';
	
	
	$tipoprodsnfe=$_GET['tprods'];
	$sumdesc=0;
	if(!isset($_SESSION['NFe-'.$tipoprodsnfe])){
		$qr = "select pcp.ref,p.grupo,sum(pcp.tot),pcp.preco,pcp.desconto,pcp.preco*(1-pcp.desconto/100)*sum(pcp.tot) from pcp 
		join produtos p on p.ref=pcp.ref
		where loc='$pedido' and situacao='E' and ";
		if($dataentrega <> ""){$qr .= "data_entrega >='$dataentrega' and data_entrega <='$dataentrega' and ";}
				
		if($tipoprodsnfe == "geral"){$qr .= "tot>0 group by pcp.ref order by pcp.ref";}
		elseif($tipoprodsnfe == "resum"){
			
			$qr .= "tot>0 group by p.grupo order by p.grupo";
			
			
			}
		$sql = mysqli_query($con,$qr);
		$table = array();
		while($row = $sql -> fetch_assoc()){
			$table[] = $row;
		}
		$i=0;
		//	var_dump($qr);
		foreach($table as $linha){
			$ref=$linha['ref'];
			if($tipoprodsnfe=="geral"){
				$qr="select descricao from produtos where ref='$ref'";
				$sql=mysqli_query($con,$qr);
				$ln=mysqli_fetch_assoc($sql);
				$desc = $ln['descricao'];
			}elseif($tipoprodsnfe=="resum"){$desc = $linha['grupo'];}
			
			$_SESSION['NFe-'.$tipoprodsnfe][$i]['ref']=$ref;
			$_SESSION['NFe-'.$tipoprodsnfe][$i]['desc']=$desc;
			$_SESSION['NFe-'.$tipoprodsnfe][$i]['qtd']=$linha['sum(pcp.tot)'];
			$_SESSION['NFe-'.$tipoprodsnfe][$i]['preco']=$linha['preco'];
			$_SESSION['NFe-'.$tipoprodsnfe][$i]['desconto']=$linha['desconto'];
			$_SESSION['NFe-'.$tipoprodsnfe][$i]['subtotal']=number_format
			(round(floatval($linha['pcp.preco*(1-pcp.desconto/100)*sum(pcp.tot)']),2),2,'.','');
			
			$sumdesc += $_SESSION['NFe-'.$tipoprodsnfe][$i]['desconto'];
			$i++;
		}
	}
	if(isset($_POST['desconto1'])){
		$fator=1-$_POST['descontar']/100;
		$array_i=array_keys($_SESSION['NFe-'.$tipoprodsnfe]);
		foreach($array_i as $i){
			$_SESSION['NFe-'.$tipoprodsnfe][$i]['preco']=round($_SESSION['NFe-'.$tipoprodsnfe][$i]['preco']*$fator,2);
			$_SESSION['NFe-'.$tipoprodsnfe][$i]['subtotal'] = $_SESSION['NFe-'.$tipoprodsnfe][$i]['preco']*(1-$_SESSION['NFe-'.$tipoprodsnfe][$i]['desconto']/100)*$_SESSION['NFe-'.$tipoprodsnfe][$i]['qtd'];
			$sumdesc += $_SESSION['NFe-'.$tipoprodsnfe][$i]['desconto'];
		}	
	}
	if(isset($_POST['desconto2'])){
		$desconto=$_POST['adddesconto'];
		$array_i=array_keys($_SESSION['NFe-'.$tipoprodsnfe]);
		foreach($array_i as $i){
			$_SESSION['NFe-'.$tipoprodsnfe][$i]['desconto'] = $desconto;
			$_SESSION['NFe-'.$tipoprodsnfe][$i]['subtotal'] = $_SESSION['NFe-'.$tipoprodsnfe][$i]['preco']*(1-$desconto/100)*$_SESSION['NFe-'.$tipoprodsnfe][$i]['qtd'];
			$sumdesc += $_SESSION['NFe-'.$tipoprodsnfe][$i]['desconto'];
		}	
	}
	
	
	echo '<div class="underline">
	<h3>Produtos</h3>';
	if($tipoprodsnfe == "geral"){echo '<button onclick="window.location.href='."'?pedido=".$pedido."&tprods=resum'".'">Resumir Produtos NFe</button>';}
	if($tipoprodsnfe == "resum"){echo '<button onclick="window.location.href='."'?pedido=".$pedido."&tprods=geral'".'">Detalhar Produtos NFe</button>';}
	echo '<form method="post" action="?pedido='.$pedido.'&tprods='.$tipoprodsnfe.'">
	<input type="hidden" name="pedido" value="'.$pedido.'"/>
	<input type="hidden" name="tprods" value="'.$tipoprodsnfe.'"/>
	<input type="number" name="descontar" id="descontar"/><input type="submit" name="desconto1" value="% -> Descontar no preço"/> 
	 <input type="number" name="adddesconto" id="adddesconto"/><input type="submit" name="desconto2" value="% -> Desconto"/>
	</form>
	';
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
	foreach($_SESSION['NFe-'.$tipoprodsnfe] as $linha){
				
		echo '<tr>
		<td>'.$linha['ref'].'</td>
		<td>'.$linha['desc'].'</td>
		<td>'.$linha['qtd'].'</td>
		<td>'.number_format($linha['preco'],2,',','.').'</td>';
		if($sumdesc > 0){echo '<td>'.number_format($linha['desconto'],1,',','.').'</td>';}
		echo '<td>'.number_format($linha['subtotal'],2,',','.').'</td>
		</tr>';
		$total += $linha['subtotal'];
		$pecas += $linha['qtd'];
	}
	echo '<tr>
	<td></td><td colspan="';
	if($sumdesc>0){echo '3';}else{echo '2';}
	echo '">Peças: '.$pecas.'</td>
	<td colspan="2">Total: R$ '.number_format($total,2,',','.').'</td>
	</tr>';
	$total_d=$total-$desconto_pedido;
	if($desconto_pedido <> 0){
		
		echo '	
		<tr>
		<td></td><td colspan="';
		if($sumdesc>0){echo '3';}else{echo '2';}
		echo '"><b>Desconto: '.number_format($desconto_pedido,2,',','.').'</b></td>
		<td colspan="2"><b>Total: R$ '.number_format($total_d,2,',','.').'</b></td>
		</tr>';
	}
	echo '	</table>
	</br>
	</div>';
	//var_dump($_SESSION['NFe-'.$tipoprodsnfe]);
	$datavencvista = date("Y-m-d",mktime (0, 0, 0, date("m")  , date("d")+5, date("Y")));
	$qr = "select max(num_nf) from nf";
	$sql=mysqli_query($con,$qr);
	$ln=mysqli_fetch_assoc($sql);
	$num_nf = $ln['max(num_nf)']+1;
	
	$qr="select id_vendedor from pedidos where pedido='$pedido'";
	$sql=mysqli_query($con,$qr);
	$ln=mysqli_fetch_assoc($sql);
	$id_vendedor=$ln['id_vendedor'];
	$qr="select comissao,comissaofat from usuarios where id_usuario='$id_vendedor'";
	$sql=mysqli_query($con,$qr);
	$ln=mysqli_fetch_assoc($sql);
	$comissaofat=$ln['comissaofat'];
	if($comissaofat==''){$comissaofat=0;}
	$comissao=$ln['comissao'];
	if($comissao==''){$comissao=0;}
			
	echo '
	<div class="underline">
		<h3>Dados de Pagamento</h3>
		<form method="post" action="../_nfe/NFeVenda.php">
		<input type="hidden" name="tipoprodsnfe" value="'.$tipoprodsnfe.'"/>
		<input type="hidden" name="pedido" value="'.$pedido.'"/>
		<input type="hidden" name="desconto_pedido" value="'.$desconto_pedido.'"/>
		<input type="hidden" name="total_sem_desconto" value="'.$total.'"/>
		<input type="hidden" value="'.$id_vendedor.'" name="id_vendedor"/>
		
		Vendedor: <select name="vendedor" onchange="mudarRepPed(this.value,'."'".$pedido."'".','.$total.','.$id_vendedor.');window.location.reload();">
		<option selected="selected" value=""></option>';
		$qr="select id_usuario,nomefantasia,comissao from usuarios where acesso like '%Representante%'";
		$sql=mysqli_query($con,$qr);
		$usuarios = array();
		while($row = $sql -> fetch_assoc()){
			echo '<option value="'.$row['id'].'" ';
			if($row['id_usuario']==$id_vendedor){echo "selected=selected";}
			echo '>'.$row['nomefantasia'].'</option>';
		}
		echo '</select>
		<label for="data_venda"> &nbsp;&nbsp;&nbsp;&nbsp;
		Data de Faturamento: </label><input type="date" id="data_venda" name="data_venda" value="'.date('Y-m-d').'"/>
		</br>
		Forma de Pagamento:<select name="indPag" onchange="formpagam(this.value,'.$total_d.','.$num_nf.','.$comissao.')">
		<option selected="selected" value="01">0 - A Vista</option>
		<option value="14">1 - A Prazo</option>
		<option value="99">2 - Outros</option>
		</select></br>';
		
		/*
		echo '
		<select id="tipo_rec" name="tipo_rec">';
		$qr="select * from dadosgerais where nome_dado = 'Tipos de recebimento'";
		$sql=mysqli_query($con,$qr);
		$ln=mysqli_fetch_assoc($sql);
		foreach($ln as $dado){
			if($dado <> ''){
				echo '<option ';if($dado=='Boleto CEF'){echo 'selected=selected';}echo '>'.$dado.'</option>';
			}	
		}
		echo '</select>';
		
		*/
		echo '</br></br>';
		if($comissaofat > 0){
			echo '
			- Comissão Fat: R$ <input type="text" size="8" name="comissaofat" id="comissaofat" value="'.number_format($comissaofat/100*$total_d,2,',','.').'" size=5/>';
		}
		$valor_edit=number_format($total_d,2,',','.');
		echo'		
		<div id="duplicatas">
			1 Parcela - R$ '.$valor_edit.' <input type="hidden" name="valorparc1" id="valorparc1" value="'.$valor_edit.'"/>
			- Vencimento: <input type="date" name="dataparc1" value="'.$datavencvista.'" id="dataparc1"/> 
			- Doc N°.:<input type="text" size="8" name="docparc1" id="docparc1" value="'.$num_nf.'"/>
			- Comissão:<input type="text" size="8" name="comiparc1" id="comiparc1" value="'.number_format($comissao/100*$total_d,2,',','.').'" size=5/>
		</div>
		<div id="duplicaparceladas">
		</div>
		<div id="serecebido">
			</br>Recebido em: <input type="radio" name="local" id="checkCEF" value="CEF"/><label for="checkCEF">CEF</label> - 
			<input type="radio" name="local" id="checkGaveta" value="Gaveta"/><label for="checkGaveta">Gaveta</label></br>
		</div>
		<label for="ocultdupl">Não gerar duplicatas:</label><input type="checkbox" name="ocultdupl" id="ocultdupl"/>
		<div class="underline">
		<h3>Dados de Transporte</h3>
		<label for="numdevolumes">Volumes:</label><input type="number" name="numdevolumes" id="numdevolumes"/>
		</div>
	
	<input type="submit" value="Gerar NFe"/>
	</form>
	</div>
	</div>';
	require '../footer.php';
	//var_dump($_SESSION['NFe-'.$tipoprodsnfe]);
?>

</body>
</html>