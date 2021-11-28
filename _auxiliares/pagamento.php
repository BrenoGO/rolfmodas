<?php

//requesitos pra utilizar php auxiliar:
//$pedido se for pedido já no sistema, $totalgeral com pedido novo..
//$boolemoutroforn, e $action se emoutroforn for false
if(isset($pedido)){
	$stmt=$dbh->prepare("select situacao,sum( (100-desconto)/100*preco*tot )as valor from pcp where loc=? and situacao='S' group by situacao;");
	$stmt->execute(array($pedido));
	$ln=$stmt->fetch(PDO::FETCH_ASSOC);	
	$totalgeral=$ln['valor'];
}
if(!$boolemoutroforn){
	echo '<form method="post" action="'.$action.'">';
}
echo '
<label for="valor_pagamento">Valor do Pagamento: </label> R$ <input type="text" name="valor_pagamento" id="valor_pagamento" onchange="update_valor(this.value)" value="'.number_format($totalgeral,2,',','.').'" size="8" style="border:0;" readonly/></br>';
$stmt=$dbh->prepare("select bonus from usuarios where id_usuario=?");
$stmt->execute(array($_SESSION['id_usuario']));
$ln=$stmt->fetch(PDO::FETCH_ASSOC);
$bonus=$ln['bonus'];
$resto_a_pg=$totalgeral;
if($bonus > 0){
	if($bonus>$totalgeral){
		$thisvalue=$totalgeral;
	}else{
		$thisvalue=$bonus;
	}
	$resto_a_pg=$totalgeral-$thisvalue;
	echo '<label for="uso_bonus">Utilizar Bônus: </label>R$<input type="text" size="8" name="uso_bonus" id="uso_bonus" value="'.number_format($thisvalue,2,',','.').'"/>
	&nbsp&nbsp Total disponível de Bônus: R$ '.number_format($bonus,2,',','.').'</br>
	</br><label for="valor_pagamento_final">A pagar: </label> R$ <input type="text" name="valor_pagamento_final" id="valor_pagamento_final" value="'.number_format($resto_a_pg,2,',','.').'" size="8" style="border:0;" readonly/></br>';
}

if($resto_a_pg > 0){
	echo '
	<input type="radio" id="tipo_pag_pagseguro" name="tipo_pagamento" value="pagseguro" onclick="check_tipo_pag(1,0,0)" checked><label for="tipo_pag_pagseguro">Pag Seguro</label>
	<input type="radio" id="tipo_pag_deposito" name="tipo_pagamento" onclick="check_tipo_pag(0,1,0)"><label for="tipo_pag_deposito">Depósito/Transferência</label>
	<input type="radio" id="tipo_pag_dinheiro" name="tipo_pagamento" onclick="check_tipo_pag(0,0,1)"><label for="tipo_pag_dinheiro">Dinheiro (válido apenas para retiradas na fábrica)</label>

	</br></br>
	<div id="pagamento_pagseguro">
	Após finalizar você será redirecionado para realizar seu pagamento no PagSeguro.';


	echo '
	</div>
	<div id="pagamento_deposito" style="display:none">
	<h3>Pagamento por depósito ou transferência em conta:</h3>

	Rolf Modas Ltda</br>
	Caixa Econômica Federal</br>
	Agência: 1123</br>
	Conta: 1501-2</br>
	Operação: 003
	</div>

	<div id="pagamento_dinheiro" style="display:none">
	<h3>Pagamento em mãos na fábrica com dinheiro</h3>
	Praça Dr. Último de Carvalho, 02</br>
	Centro</br>
	Rio Pomba / MG</br>
	Telefone: (32)3571-1010
	</div>
	</br>';
}

if(!$boolemoutroforn){
	echo '
	<input type="submit" onclick="return confirm('."'Tem certeza que deseja efetuar o pagamento?'".')" value="Efetuar Pagamento"/>
	</form>';
}