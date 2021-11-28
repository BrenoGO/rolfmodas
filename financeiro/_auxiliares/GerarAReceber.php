<?php
$tipo_rec = $_POST['tipo_rec'];
$num_doc = $_POST['docparc1'];


if($num_doc == ''){
	echo "<script language='javascript' type='text/javascript'>alert('Faltou Número do Documento!');window.location.href='?acao=gerarduplsemdados";
	if(isset($_GET['id'])){echo '&id='.$_GET['id'];}
	echo "'</script>";
	die();
}else{
	//verifica se existe o mesmo num_doc em Receber (nos casos de parciais...)
	$qr="select num_doc from receber where num_doc='$num_doc'";
	$sql=mysqli_query($con,$qr);
	$k=2;
	while(mysqli_num_rows($sql)>0){
		$array_num_doc=explode('-',$num_doc);
		if(count($array_num_doc)==1){
			$num_doc='2-'.$num_doc;
		}else{
			$num_doc=$k.'-'.$array_num_doc[1];
		}
		$k++;
		$qr="select num_doc from receber where num_doc='$num_doc'";
		$sql=mysqli_query($con,$qr);
	}
}
//pegar ou cadastrar cliente
if(isset($_GET['id'])){
	$rsocial = $_POST['rsocial'];
	$nomefantasia=$_POST['fantasia'];
	$cidade=$_POST['cidade'];
	$estado=$_POST['estado'];
	$cnpj=$_POST['cnpj'];
	$cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);
	$contato=$_POST['contato'];
	$outroscontatos=$_POST['outroscontatos'];
	$email=$_POST['email'];
	$ie = $_POST['ie'];
	$ie = preg_replace('/[^0-9]/', '', (string) $ie);
	$xLgr = $_POST['logradouro'];
	$nro = $_POST['numero'];
	$xCpl = $_POST['complem'];
	$bairro = $_POST['bairro'];
	$CEP=$_POST['CEP'];
	
	if($_GET['id']<>'novo'){
		$idcliente = $_GET['id'];
		$qr="update usuarios set razaosocial='$rsocial',cnpj='$cnpj',nomefantasia='$nomefantasia',cidade='$cidade',estado='$estado',contato='$contato',outroscontatos='$outroscontatos',email='$email',ie='$ie',logradouro='$xLgr',num='$nro',complemento='$xCpl',bairro='$bairro',CEP='$CEP' where id_usuario='$idcliente'";
		$sql=mysqli_query($con,$qr);
		if(!$sql){echo 'Erro ao atualizar dados do cliente.</br>';}
		
	}else{
		$qr="insert into usuarios (id_usuario,razaosocial,cnpj,nomefantasia,cidade,estado,contato,outroscontatos,email,ie,logradouro,num,complemento,bairro,CEP) values (default,'$rsocial','$cnpj','$nfantasia','$cidade','$estado','$contato','$outroscontatos','$email','$ie','$xLgr','$nro','$xCpl','$bairro','$CEP')";
		$sql= mysqli_query($con,$qr);
		if(!$sql){echo 'Erro ao inserir dados do cliente novo....</br>';}
	}
}
//Gerar boletos

if(isset($_POST['idcliente'])){$id_cliente=$_POST['idcliente'];}
$data_venda=$_POST['data_venda'];
$id_vendedor=$_POST['vendedor'];
if($id_vendedor <> 0){
	$qr="select nomefantasia from usuarios where id_usuario=$id_vendedor";
	$sql=mysqli_query($con,$qr);
	$ln=mysqli_fetch_assoc($sql);
	$vendedor=$ln['nomefantasia'];
	$qr="select id_forn from forn where id_usuario=$id_vendedor";
	$sql=mysqli_query($con,$qr);
	if(mysqli_num_rows($sql)>0){
		$ln=mysqli_fetch_assoc($sql);
		$id_forn = $ln['id_forn'];
	}else{
		$id_forn = 0;
	}
}else{
	$id_forn = 0;
	$vendedor='';
}	
if(isset($_POST['obs'])){$obs=$_POST['obs'];}else{$obs='';}
if(isset($_POST['comissaofat'])){$comissaofat=str_replace(',','.',$_POST['comissaofat']);}else{$comissaofat=0;}
if($comissaofat > 0){
	//TEM COMISSAO GERADA EM FATURAMENTO
	$desc = 'Comissão Fat '.$vendedor.'-'.$num_doc;
	$data_venc_comiss = date('Y-m-d',strtotime('+10 days',strtotime($data_venda)));
	$qr="insert into gastos (id_apg,id_forn,`desc`,valor,tipo_pg,data_gasto,data_venc,parcela,dataalter) values
	(default,$id_forn,'$desc',$comissaofat,'Transferência CEF','$data_venda','$data_venc_comiss','1/1',default)";
	$sql=mysqli_query($con,$qr);
	$qr="select max(id_apg) as id_apg from gastos";
	$sql=mysqli_query($con,$qr);
	$ln=mysqli_fetch_assoc($sql);
	$id_apg = $ln['id_apg'];
	$qr="insert into comissoes (id_comissao,num_doc,valor_comis,data_prev,data_fat_comis,id_apg,tipo_comis) values
	(default,'$num_doc',$comissaofat,'$data_venda','$data_venda',$id_apg,'Fat')";
	$sql=mysqli_query($con,$qr);
	if(!$sql){echo 'Erro ao contabilizar comissão de faturamento.....Avisar o responsável por TI!!!</br>';}
}


if(!isset($_POST['nparcelas'])){
	//a vista 
	
	$data_vencimento=$_POST['dataparc1'];
	$valor=str_replace(',','.',$_POST['valorparc1']);
	$parcela='1/1';
	//a receber
	$qr="insert into receber (num_doc,tipo_rec,id_usuario,data_venda,data_vencimento,valor,id_vendedor,parcela,obs,situacao,tipo,dataalter)
	values ('$num_doc','$tipo_rec',$id_cliente,'$data_venda','$data_vencimento',$valor,'$id_vendedor','$parcela','$obs','aberto','venda',default)";
	$sql=mysqli_query($con,$qr);
	if(!$sql){echo 'Erro ao inserir no a receber parcela única.....Avisar o responsável por TI!!!</br>';}
		//comissao
	$comiparc1 = str_replace(',','.',$_POST['comiparc1']);
	if($comiparc1>0 and $comiparc1<>''){
		$qr="insert into comissoes (id_comissao,num_doc,valor_comis,data_prev,tipo_comis) values
		(default,'$num_doc',$comiparc1,'$data_vencimento','Parc:1/1')";
		$sql=mysqli_query($con,$qr);
		if(!$sql){echo 'Erro ao contabilizar comissão de parcela única.....Avisar o responsável por TI!!!</br>';}
	}
	
	if(isset($_POST['local'])){
	if($_POST['local']=='Gaveta' or $_POST['local']=='CEF'){
		//a vista, dinheiro ja entrou
		//baixa na tabela "receber"
		
		//Algum erro aqui em baixo.. não tem where?!?!
		$qr="update receber set data_pag='$data_venda',situacao='pago' where ";
		$sql=mysqli_query($con,$qr);
		if(!$sql){echo 'Erro ao baixar o a receber gerado.....Avisar o responsável por TI!!!</br>';}
		
		//movimentar caixa
		$local = $_POST['local'];
		$qr="select saldo from caixa where num_mov=(select max(num_mov) from caixa where local='$local')";
		$sql=mysqli_query($con,$qr);
		$ln=mysqli_fetch_assoc($sql);
		$saldoanterior = $ln['saldo'];
		$novosaldo = $saldoanterior + $valor;
		$desc='Venda a Vista: '.$num_doc;
		$qr="insert into caixa (num_mov,local,mov,`desc`,valor,saldo,dataalter) values
		(default,'$local','E','$desc',$valor,$novosaldo,default)";
		$sql=mysqli_query($con,$qr);
		if(!$sql){echo 'Erro ao realizada movimentação no Caixa.....Avisar o responsável por TI!!!</br>';}					
		
		//comiparc1 tbm faturou
		if($comiparc1>0){
			echo 'Entrou no comiparc1</br>';
			$desc = 'Comissão Parc 1/1: '.$vendedor.'-'.$num_doc;
			$data_venc_comiss = date('Y-m-d',strtotime('+10 days',strtotime($data_venda)));
			$qr="insert into gastos (id_apg,id_forn,`desc`,valor,tipo_pg,data_gasto,data_venc,parcela,dataalter) values
			(default,$id_forn,'$desc',$comiparc1,'Transferência CEF','$data_venda','$data_venc_comiss','1/1',default)";
			$sql=mysqli_query($con,$qr);
			if(!$sql){echo 'Erro ao Gerar gasto com comissão.....Avisar o responsável por TI!!!</br>';}
			
			$qr="select max(id_apg) as id_apg from gastos";
			$sql=mysqli_query($con,$qr);
			$ln=mysqli_fetch_assoc($sql);
			$id_apg = $ln['id_apg'];
			$qr="update comissoes set id_apg=$id_apg,data_fat_comis='$data_venda' 
			where num_doc='$num_doc' and valor_comis=$comiparc1 and tipo_comis='Parc:1/1'";
			$sql=mysqli_query($con,$qr);
			if(!$sql){echo 'Erro ao Baixar na previsão de comissão.....Avisar o responsável por TI!!!</br>';}
		}
	}
	}
	
}else{
	$parcelas=$_POST['nparcelas'];
	//a prazo
	for($i=1;$i<=$parcelas;$i++){
		$num_doc=$_POST['docparc'.$i];
		$data_vencimento=$_POST['dataparc'.$i];
		$valor=str_replace(',','.',$_POST['valorparc'.$i]);
		$parcela=$i.'/'.$parcelas;
		//a receber
		$qr="insert into receber (num_doc,tipo_rec,id_usuario,data_venda,data_vencimento,valor,id_vendedor,parcela,obs,situacao,tipo,dataalter)
		values (?,?,?,?,?,?,?,?,?,?,?,default)";
			
		$array=array($num_doc,$tipo_rec,$id_cliente,$data_venda,$data_vencimento,$valor,$id_vendedor,$parcela,$obs,'aberto','venda');
		$stmt=$dbh->prepare($qr);
		$stmt->execute($array);
		$arr=$stmt->errorInfo();
		if($arr[1]<>null){var_dump($arr);}
		
		//comissao
		if(isset($_POST['comiparc'.$i])){
			$comiparc = str_replace(',','.',$_POST['comiparc'.$i]);
			if($comiparc>0 and $comiparc<>''){
				$qr="insert into comissoes (id_comissao,num_doc,valor_comis,data_prev,tipo_comis) values
				(default,'$num_doc',$comiparc,'$data_vencimento','Parc:$i/$parcelas')";
				$sql=mysqli_query($con,$qr);
				if(!$sql){echo 'Erro ao gerar previsão da comissão da parcela '.$i.'.....Avisar o responsável por TI!!!</br>';}
			}
		}
	}
}
?>