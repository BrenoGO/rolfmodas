<?php

//$minminifranqueado, $minconsultor, $markconsumidor, $markconsultor, $markminifranqueado
if( ($_SESSION['valor_total_pedido']>$minminifranqueado) and ($_SESSION['markup'] == $markconsultor) ){
	if( (!isset($_SESSION['aviso_mudanca'])) or ($_SESSION['aviso_mudanca']<>'sim') ){
		$pend=$minminifranqueado/($markminifranqueado/$markconsumidor);
		$desconto=100-($markminifranqueado/$markconsumidor*100);
		echo '<script>alert("Seu pedido está acima de R$ '. number_format($minminifranqueado,2,',','.') .' como consultor, mas ainda não está com preço de Mini-Franqueado.. Compre até chegar em R$'.number_format($pend,2,',','.').' a preço de tabela para garantir desconto '.$desconto.'% de Mini Franqueado!!")</script>';
		$_SESSION['aviso_mudanca']='sim';
	}
}

if( ($_SESSION['valor_total_pedido']>$minconsultor) and ($_SESSION['markup'] == $markconsumidor) ){
	if( (!isset($_SESSION['aviso_mudanca'])) or ($_SESSION['aviso_mudanca']<>'sim') ){
		$pend=$minconsultor/ ($markconsultor/$markconsumidor);
		$desconto=100-($markconsultor/$markconsumidor*100);
		echo '<script>alert("Seu pedido está acima de R$ '. number_format($minconsultor,2,',','.') .' como consumidor, mas ainda não está com preço de Consultor.. Compre até chegar em R$'.number_format($pend,2,',','.').' para garantir desconto de '.$desconto.'% de Consultor!!")</script>';
		$_SESSION['aviso_mudanca']='sim';
	}
}
if( ($_SESSION['valor_total_pedido']<$minminifranqueado) and ($_SESSION['markup'] == $markconsultor) ){
	$_SESSION['aviso_mudanca']='nao';
}
if( ($_SESSION['valor_total_pedido']<$minconsultor) and ($_SESSION['markup'] == $markconsumidor) ){
	$_SESSION['aviso_mudanca']='nao';
}