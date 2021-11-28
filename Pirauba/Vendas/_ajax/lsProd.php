<?php

$q=$_GET['q'];
if(strlen($q) > 0){
	$exp=explode(' ',$q);
	if(isset($exp[1])){
		$q = $exp[0];
		$w = $exp[1];
	}
	
	require '../../_config/conection.php';
	$stmt=$dbhRPI->prepare(" select m.id_mov,p.ref,m.cor,m.tamanho,m.quantidade,p.descricao from produtos p
							join movimentacao m on m.ref = p.ref
							where ( (p.ref like ?) or (p.descricao like ?) or (m.cor like ?) )
							and ( (p.ref like ?) or (p.descricao like ?) or (m.cor like ?))
							and m.bool_est = 'S'
							order by p.ref,m.cor,m.id_mov");
	if(isset($w)){
		$stmt->execute(array('%'.$q.'%','%'.$q.'%','%'.$q.'%','%'.$w.'%','%'.$w.'%','%'.$w.'%'));
	}else{
		$stmt->execute(array('%'.$q.'%','%'.$q.'%','%'.$q.'%','%'.$q.'%','%'.$q.'%','%'.$q.'%'));
	}
	
	
	while($ln=$stmt->fetch(PDO::FETCH_ASSOC)){
		echo '<span onclick="FormVenda('.$ln['id_mov'].')" style="cursor:pointer">
		Ref.: '.$ln['ref'].' - '.$ln['descricao'].' - Cor: '.$ln['cor'].' - Tamanho: '.$ln['tamanho'].' - Quantidade: '.$ln['quantidade']
		.'</span></br>';
	}
}

?>