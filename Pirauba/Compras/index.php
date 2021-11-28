
<!DOCTYPE html>
<html>
<head>
  <?php 
	session_start();
	require('../head.php') 
	
  ?>

</head>
<body>

<?php
	require '../header_geral.php';
/*
	$stmt=$dbh->prepare("select * from produtos where ref=?");
	$stmt->execute(array("015"));
	$ln=$stmt->fetch(PDO::FETCH_ASSOC);
	
	$stmt=$dbhRPI->prepare("insert into produtos (ref,descricao,tipo) values (?,?,?)");
	$stmt->execute(array($ln['ref'],$ln['descricao'],$ln['tipo']));
*/
	
	echo'
	<nav id="submenu">
	<a href="?acao=importrolf"><button>Importar da Rolf</button></a>
	<a href="?acao=compra"><button>Nova Compra</button></a>
	</nav>';
	if(!isset($_GET['acao'])){
		echo '';
	}else{
		if($_GET['acao'] == 'importrolf'){
			if(!isset($_GET['pedido'])){
				$stmt = $dbh -> prepare("select distinct(p.pedido) from pcp
				 join pedidos p on pcp.loc = p.pedido
				 where situacao='S' and p.id_cliente =447");
				$stmt->execute();
				if($stmt->rowCount() > 0){
					if($stmt->rowCount() > 1){
						echo 'Os pedidos separados são: </br>';
						while($ln=$stmt->fetch(PDO::FETCH_ASSOC)){
							echo $ln['pedido'].'<a href="?acao=importrolf&pedido='.$ln['pedido'].'"><button>Finalizar Pedido</button></a></br></br>';
						}
					}else{
						$ln=$stmt->fetch(PDO::FETCH_ASSOC);
						echo 'O pedido separado na Rolf é o '.$ln['pedido'].'<a href="?acao=importrolf&pedido='.$ln['pedido'].'"><button>Finalizar pedido</button></a></br>';
					}
					
				}else{
					echo 'Não existe pedido separado da Rolf Piraúba na Rolf.';
				}
			}else{
				$ped = $_GET['pedido'];
				$stmt=$dbh->prepare("select * from pcp where loc=? and situacao='S'");
				$stmt->execute(array($ped));
				$stmt2=$dbh->prepare("update pcp set situacao='E',data_entrega=?,dataalter=default where situacao='S' and loc=?");
				$stmt2->execute(array(date('Y-m-d'),$ped));
				
				while($ln=$stmt->fetch(PDO::FETCH_ASSOC)){
					$ref=$ln['ref'];
					$stmt2=$dbhRPI->prepare("select * from produtos where ref=?");
					$stmt2->execute(array($ln['ref']));
					if($stmt2->rowCount() <= 0){//teste se este produto já está cadastrado em Produtos no RPI
						//cadastrar nos produtos
						CadastProdRolfToRpi($ref,$dbh,$dbhRPI);
					}
					for($i=1;$i<=5;$i++){
						if($ln['t'.$i] > 0){
							$Tam=TamanhoRolf($ref,$i);
							$Obs="Pedido ".$ped;
							$preco_ent=$ln['preco']*(1-$ln['desconto']/100);
							$stmt2=$dbhRPI ->prepare("insert into movimentacao (id_mov,tipo_mov,bool_est,id_forn,ref,cor,tamanho,quantidade,data_op,preco_ent,Obs) 
							values (default,'Compra','S',1,?,?,?,?,default,?,?)");
							$stmt2->execute(array($ref,$ln['cor'],$Tam,$ln['t'.$i],$preco_ent,$Obs));
						}						
					}
				}
				echo 'Pedido importado para Rolf Piraúba';
			}
		}
		if($_GET['acao']=='compra'){
			//onkeyup="lsforn(this.value)"
			echo '
			<div>
				<div id="Fornecedor">
					Fornecedor: <input type="text" onkeyup="lsforn(this.value)" id="inputTextLs" />
					<div id="lsforn"></div>
					<div id="cadastForn" style="border: 1px solid black; display: none;">';
							require '../_auxiliares/formcadastpessoa.php';
							echo'
							<input type="button" onclick="AltForn()" id="butAltCad" name="Alterar" value="Alterar ou Cadastrar"/>
							<input type="button" onclick="ConfForn()" id="butConfirmar" name="Confirmar" value="Dados estão corretos, confirmar fornecedor"/>
					</div></br>
				</div>
				<div id="Produtos">
					
				</div>
				<input type="button" onclick="adicProd()" value="Adicionar Produto"/>
				<input type="submit" value="Submit"/>
				</form>	
			</div>';
			echo '<div></div>';
		}
		if($_GET['acao']=='confirmCompra'){
			
		}
	}
	/*if($_GET['acao'] == 'cadastforn'){
		require '../_ajax/fillform2.php';
		$stmt=$dbhRPI->prepare("select * from usuarios where id_usuario=?");
		$stmt->execute(array($id_usuario));
		$ln=$stmt->fetch(PDO::FETCH_ASSOC);
		$rsocial = $ln['razaosocial'];
		$nomefantasia = $ln['nomefantasia'];
		$datanascimento = $ln['data_nascimento'];
		$cnpj = $ln['cnpj'];
		$ie = $ln['ie'];
		$CEP = $ln['CEP'];
		$estado = $ln['estado'];
		$cidade = $ln['cidade'];
		$bairro = $ln['bairro'];
		$logradouro = $ln['logradouro'];
		$num = $ln['num'];
		$complemento = $ln['complemento'];
		$contato = $ln['contato'];
		$email = $ln['email'];
		$outroscontatos = $ln['outroscontatos'];
		
		if($id_usuario <> 'id_novo'){
			$stmt=$dbhRPI->prepare("update usuarios set razaosocial=?, nomefantasia=?, data_nascimento=?, cnpj=?, ie=?, CEP=?, estado=?, cidade=?, bairro=?, logradouro=?, num=?, complemento=?, contato=?, email=?, outroscontatos=? where id_usuario=?");
			$stmt->execute(array($rsocial,$nomefantasia, $datanascimento, $cnpj, $ie, $CEP, $estado, $cidade, $bairro, $logradouro, $num, $complemento, $contato, $email, $outroscontatos, $id_usuario));
			$ln=$stmt->fetch(PDO::FETCH_ASSOC);
		}else{
			$stmt=$dbhRPI->prepare("insert into usuarios (razaosocial, nomefantasia, data_nascimento, cnpj, ie, CEP, estado, cidade, bairro, logradouro, num, complemento, contato, email, outroscontatos) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
			$stmt->execute(array($rsocial,$nomefantasia, $datanascimento, $cnpj, $ie, $CEP, $estado, $cidade, $bairro, $logradouro, $num, $complemento, $contato, $email, $outroscontatos, $id_usuario));
			$ln=$stmt->fetch(PDO::FETCH_ASSOC);
		}
		require 'cadastpessoa.php';
	}*/
	require '../footer.php';
?>
</body>
</html>
