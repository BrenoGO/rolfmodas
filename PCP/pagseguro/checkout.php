<?php

//para utilizar o pagseguro/checkout.php:    (como feito em ../Checkout/index.php)
/*$valor_pagamento, $pedido
$rsocial
$email
$contato
$xLgr
$nro
$bairro
$cidade
$estado
$CEP
$codigo_pagseguro
*/				
				
header("access-control-allow-origin: https://pagseguro.uol.com.br");
header("Content-Type: text/html; charset=UTF-8",true);

//require_once("PagSeguro.class.php");

require_once("../pagseguro/PagSeguro.class.php");
$PagSeguro = new PagSeguro();

//Tratar Telefone
$contato=preg_replace('/[^0-9]/', '', (string) $contato);
$arraytel=str_split($contato);

if($arraytel[0]==0){//TESTA SE NUMERO ESTA COM 0 NA FRETE TIPO 03235711010
	$contato=substr($contato,1,strlen($contato));
	$arraytel=str_split($contato);
}
if(strlen($contato)==11){
	$contato = $arraytel[0].$arraytel[1].'-'.$arraytel[2].$arraytel[3].$arraytel[4].$arraytel[5].$arraytel[6].$arraytel[7].$arraytel[8].$arraytel[9].$arraytel[10];
}elseif(strlen($contato)==10){
	$contato=$arraytel[0].$arraytel[1].'-'.$arraytel[2].$arraytel[3].$arraytel[4].$arraytel[5].$arraytel[6].$arraytel[7].$arraytel[8].$arraytel[9];
}else{
	var_dump($contato);
	echo '<script>alert("Erro com seu número de telefone. Confira em Meus Dados se ele está correto, incluindo o DDD.");window.location.href="../Usuario/meuspedidos.php?acao=consultped"</script>';
	die;
}

//TRATAR CEP
$arrayCEP=str_split($CEP);
$CEP=$arrayCEP[0].$arrayCEP[1].'.'.$arrayCEP[2].$arrayCEP[3].$arrayCEP[4].'-'.$arrayCEP[5].$arrayCEP[6].$arrayCEP[7];
	
//EFETUAR PAGAMENTO	


//echo $contato.'</br>';
$venda=array("codigo" =>$pedido,
	"valor" => $valor_pagamento,
	"descricao" => 'Pedido '.$pedido,
	"nome" => $rsocial,
	"email"=>$email,
	"telefone"=>$contato,
	"rua"=>$xLgr,
	"numero"=>$nro,
	"bairro"=>$bairro,
	"cidade"=>$cidade,
	"estado"=>$estado,
	"cep"=>$CEP,
	"codigo_pagseguro"=>$codigo_pagseguro
	);
//var_dump($venda);
//var_dump($PagSeguro);
/*
echo '<script type="text/javascript"
src="https://stc.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.lightbox.js">
</script>';
*/
$PagSeguro->executeCheckout($dbh,$venda,'http://www.rolfmodas.com.br/Usuario/meuspedidos.php?pedido='.$pedido.'');	


/*
$venda = array("codigo"=>"1",
			   "valor"=>100.00,
			   "descricao"=>"VENDA DE NONONONONONO",
			   "nome"=>"",
			   "email"=>"",
			   "telefone"=>"(XX) XXXX-XXXX",
			   "rua"=>"",
			   "numero"=>"",
			   "bairro"=>"",
			   "cidade"=>"",
			   "estado"=>"XX", //2 LETRAS MAIÚSCULAS
			   "cep"=>"XX.XXX-XXX",
			   "codigo_pagseguro"=>"");
			   
$PagSeguro->executeCheckout($venda,"http://SEUSITE/pedido/".$_GET['codigo']);
*/
//----------------------------------------------------------------------------


//RECEBER RETORNO
if( isset($_GET['transaction_id']) ){
	$pagamento = $PagSeguro->getStatusByReference($_GET['codigo']);
	
	$pagamento->codigo_pagseguro = $_GET['transaction_id'];
	if($pagamento->status==3 || $pagamento->status==4){
		//ATUALIZAR DADOS DA VENDA, COMO DATA DO PAGAMENTO E STATUS DO PAGAMENTO
		//atualizar banco de dados que recebeu notificacao do pagseguro...TEMPORARIO, PRA TESTAR
		$nota='Notificação recebida pelo pagseguro-foi na pagina checkout.php';
		$codigo_pagseguro=$response->code;
		$pedido=$response->reference;
		$valor=$response->netamount;
		$stmt=$dbh->prepare("insert into dadosgerais (nome_dado,dado_1,dado_2) values (?,?,?)");
		$stmt->execute(array($nota,$codigo_pagseguro,$pedido));
		
	}else{
		//ATUALIZAR NA BASE DE DADOS
	}
}

?>