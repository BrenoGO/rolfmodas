
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
	
	echo'
	<nav id="submenu">
	<a href="?acao=venda"><button>Relatar Venda</button></a>
	</nav>';
	if(!isset($_GET['acao'])){
		echo '';
	}else{
		if($_GET['acao']=='venda'){
			echo '<input type="text" id="digprod" name="digprod" autofocus onkeyup="lsProd(this.value)"/>
			<div id="lsProd"></div>
			<div id="formVenda" style="display:none">
			<form method="post" action="?acao=confirmVenda">
			<input type="hidden" name="id_mov" id="id_mov"/>
			<label for="ref">Ref.:</label><input type="text" name="ref" id="ref" size="5" readonly/>
			<label for="desc">Descrição: </label><input type="text" name="desc" id="desc" size="12" readonly/>
			<label for="custo">Custo: </label><input type="text" name="custo" id="custo" size="7" readonly/></br>
			<label for="cor">Cor:</label><input type="text" name="cor" id="cor" size="8" readonly/>
			<label for="tam">Tamanho:</label><input type="text" name="tam" id="tam" size="3" readonly/>
			<label for="qnt_est">Quant. em estoque:</label><input type="number" name="qnt_est" id="qnt_est" size="3" readonly/></br>
			<label for="valor"><b>Valor de Venda:</b></label><input type="text" name="valor" id="valor" size="5"/></br>
			<label for="qnt"><b>Quant. VENDIDA</b>:</label><input type="number" name="qnt" id="qnt" value="1" size="3"/>
			<label for="forma_pag">Forma de Pagamento:</label>
			<select name="forma_pag" id="forma_pag">
			<option selected="selected">Dinheiro</option>
			<option>Cartão</option>
			</select></br>
			<input type="submit" value="Confirmar"/>
			</form>
			</div>
			';
		}
		if($_GET['acao']=='confirmVenda'){
			$ref=$_POST['ref'];
			$cor=$_POST['cor'];
			$tam=$_POST['tam'];
			$qnt=$_POST['qnt'];
			$valor=$_POST['valor'];
			if($valor ==''){
				echo 'Você esqueceu de passar o Valor da Venda!';
				die();
			}
			$valor=str_replace(',','.',$valor);
			
			$forma_pag=$_POST['forma_pag'];
			if($forma_pag == 'Cartão'){
				$Obs='Cartão';
			}else{
				$Obs='';
			}
			$stmt=$dbhRPI->prepare("select * from movimentacao where ref=? and cor=? and tamanho=? and bool_est='S' order by id_mov");
			$stmt->execute(array($ref,$cor,$tam));
			
			$w=$qnt;
			while ($w>0){
				$ln=$stmt->fetch(PDO::FETCH_ASSOC);
				if($w < $ln['quantidade']){
					$dif=$ln['quantidade']-$w;
					
					$stmt2=$dbhRPI->prepare("update movimentacao set tipo_mov='#FIM#Compra',bool_est='N' where id_mov=?");
					$stmt2->execute(array($ln['id_mov']));
					
					$stmt2=$dbhRPI->prepare("insert into movimentacao (id_mov,tipo_mov,bool_est,id_forn,ref,cor,tamanho,quantidade,data_op,preco_ent,preco_sai,id_ant,Obs) 
					values (default,'Venda','N',?,?,?,?,?,default,?,?,?,?)");
					$stmt2->execute(array($ln['id_forn'],$ref,$cor,$tam,$w,$ln['preco_ent'],$valor,$ln['id_mov'],$Obs));
					
					$stmt2=$dbhRPI->prepare("insert into movimentacao (id_mov,tipo_mov,bool_est,id_forn,ref,cor,tamanho,quantidade,data_op,preco_ent,id_ant) 
					values (default,'Estoque','S',?,?,?,?,?,default,?,?)");
					$stmt2->execute(array($ln['id_forn'],$ref,$cor,$tam,$dif,$ln['preco_ent'],$ln['id_mov']));
					
					$w=0;
				}else{//$w é maior ou = que a linha..
					$stmt2=$dbhRPI->prepare("update movimentacao set tipo_mov='#FIM#Compra',bool_est='N',data_op=default where id_mov=?");
					$stmt2->execute(array($ln['id_mov']));
					
					$stmt2=$dbhRPI->prepare("insert into movimentacao (id_mov,tipo_mov,bool_est,id_forn,ref,cor,tamanho,quantidade,data_op,preco_ent,preco_sai,id_ant,Obs) 
					values (default,'Venda','N',?,?,?,?,?,default,?,?,?,?)");
					$stmt2->execute(array($ln['id_forn'],$ref,$cor,$tam,$ln['quantidade'],$ln['preco_ent'],$valor,$ln['id_mov'],$Obs));
					
					$w= $w-$ln['quantidade'];
				}
			}
			echo 'Venda realizada..
			<script>window.location.href="?acao=venda"</script>';
		}	
	}
	
	
	require '../footer.php';
?>
</body>
</html>
