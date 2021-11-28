<?php

$stmt=$dbh->prepare("select * from dadosgerais where nome_dado='Multa Renegociacao'");
$stmt->execute();
$ln=$stmt->fetch(PDO::FETCH_ASSOC);
$multa=$ln['dado_1'];
		
$stmt=$dbh->prepare("select * from dadosgerais where nome_dado='Juros Mensal Renegociacao'");
$stmt->execute();
$ln=$stmt->fetch(PDO::FETCH_ASSOC);
$juros=$ln['dado_1'];

echo '<form method="post" action="?acao=baixadereceber&baixa">';
if(isset($_SESSION['baixadocs'])){
	echo '</br>Documentos selecionados para dar baixa:
	<table>
		<tr>
		<td>Doc.</td>
		<td>Cliente</td>
		<td>Vencimento</td>
		<td>Valor</td>
		<td>Valor Pago</td>
		<td>Data Pag.</td>
		<td>Repres.</td>
		</tr>
	';
	$i=1;
	foreach($_SESSION['baixadocs'] as $num_doc){
		$stmt=$dbh->prepare("select * from receber where num_doc=?");
		$stmt->execute(array($num_doc));
		$ln=$stmt->fetch(PDO::FETCH_ASSOC);
		
		$stmt=$dbh->prepare("select razaosocial from clientes where idclientes=?");
		$stmt->execute(array($ln['idcliente']));
		$cliente=$stmt->fetch(PDO::FETCH_ASSOC);
		
		$hoje= new DateTime(date('Y-m-d'));
		$vencimento = new DateTime($ln['data_vencimento']);
		$intervalo= $hoje->diff($vencimento);
		$atraso=$intervalo->days;
		echo '<input type="hidden" name="num_doc'.$i.'" value="'.$num_doc.'"/>
		<tr>
		<td>'.$num_doc.'</td>
		<td>'.$cliente['razaosocial'].'</td>
		<td>'.date('d-m-y',strtotime($ln['data_vencimento'])).'</td>
		<td>'.number_format($ln['valor'],2,',','.').'</td>
		<td>';
		if($ln['data_vencimento']<date('Y-m-d')){
			$valor_c_juros= $ln['valor']+$ln['valor']*$multa+$ln['valor']*$juros/30*$atraso;
			echo '<input style="color:red;font-weight:bold" type="text" name="valor_pag'.$i.'" value="'.number_format($valor_c_juros,2,',','.').'" size="5"/>';
		}else{
			echo '<input type="text" name="valor_pag'.$i.'" value="'.number_format($ln['valor'],2,',','.').'" size="5"/>';
		}
		echo '</td>
		<td><input type="date" name="data_pag'.$i.'" value="'.date('Y-m-d').'"/></td>
		<td>'.$ln['vendedor'].'</td>
		<td><a><span style="cursor:pointer" onclick="AddDoc('."'del','".$num_doc."'".')">Remover<span></a></td>
		</tr>';
		$i++;
	}
	echo '</table>
	<label for="local_pag">Local de Pagamento: </label>
	<select name="local_pag" id="local_pag">';
	$stmt=$dbh->prepare("select * from dadosgerais where nome_dado=?");
	$stmt->execute(array('Contas Fluxo de Caixa'));
	$ln=$stmt->fetch(PDO::FETCH_ASSOC);
	foreach($ln as $opcao){
		if( (!is_null($opcao)) and ($opcao <> 'Contas Fluxo de Caixa') ) {
			echo '<option>'.$opcao.'</option>';
		}
	}
	echo '</select></br>
	<button type="button" onclick="AddDoc('."'unset','doc'".')">Limpar</button>
	<input type="submit" value="Baixar Boletos!"/>
	</form>';	
}



?>