<?php




//requesitos pra utilizar auxiliar Frete:
//$data['nVlComprimento'] = '16';
//$data['nVlAltura'] = '5';
//$data['nVlLargura'] = '15';
//$data['nVlValorDeclarado'] = '100';
//$data['sCepDestino'] = '35180010';
//$data['nVlPeso'] = '1';

/************************DIMENSOES DAS CAIXAS:
//$data['nVlComprimento'] = '41';
//$data['nVlAltura'] = '17';
//$data['nVlLargura'] = '26';

//$data['nVlComprimento'] = '41';
//$data['nVlAltura'] = '32';
//$data['nVlLargura'] = '26';

//$data['nVlComprimento'] = '61';
//$data['nVlAltura'] = '36';
//$data['nVlLargura'] = '31';

****************************************/

/*
SEM CONTRATO:
04014 SEDEX à vista
04065 SEDEX à vista pagamento na entrega
04510 PAC à vista
04707 PAC à vista pagamento na entrega
40169 SEDEX 12 ( à vista e a faturar)*
40215 SEDEX 10 (à vista e a faturar)*
40290 SEDEX Hoje Varejo*


*/


$data['nCdEmpresa'] = '14354578';
$data['sDsSenha'] = 'p1X45';	
$data['nCdServico'] = '41068,40096';
//$data['nCdServico'] = '04510,04014';//PAC e Sedex novos->varejo, sem contrato..
$data['sCepOrigem'] = '36180000';
$data['nCdFormato'] = '1';
$data['nVlDiametro'] = '0';
$data['sCdMaoPropria'] = 'n';
$data['sCdAvisoRecebimento'] = 's';
$data['StrRetorno'] = 'xml';

$data = http_build_query($data);
//var_dump($data);
$url = 'http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx'; 

$curl = curl_init($url . '?' . $data);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 0);  -> nao adiantou nada...tentativa pra resolver erro que da no checkout/index.php no frete. as vezes não dá resposta no curl entao $result -> cServico não existe...
$result = curl_exec($curl);
$result = simplexml_load_string($result);
curl_close($curl); 

//print_r($result);



$w=0;
foreach($result -> cServico as $row) {
	//Os dados de cada serviço estará aqui
	if($row -> Erro == 0) {
		$frete[$w]['codServico']=$row->Codigo;
		if($frete[$w]['codServico']=='40096'){
			$frete[$w]['nomeServico']='SEDEX';
		}elseif($frete[$w]['codServico']=='41068'){
			$frete[$w]['nomeServico']='PAC';
		}
		$frete[$w]['valor']=str_replace(',','.',($row -> Valor)) ;
		$frete[$w]['PrazoEntrega']=$row -> PrazoEntrega ;
		$frete[$w]['ValorAvisoRecebimento']=str_replace(',','.',($row -> ValorAvisoRecebimento)) ;
		$frete[$w]['ValorValorDeclarado']=str_replace(',','.',($row -> ValorValorDeclarado)) ;
		$frete[$w]['EntregaDomiciliar']=$row -> EntregaDomiciliar ;
		$frete[$w]['PrazoEntrega']=$row -> PrazoEntrega ;
		
		/*
		echo $row -> Codigo . '<br>';
		echo $row -> Valor . '<br>';
		echo $row -> PrazoEntrega . '<br>';
		echo $row -> ValorMaoPropria . '<br>';
		echo $row -> ValorAvisoRecebimento . '<br>';
		echo $row -> ValorValorDeclarado . '<br>';
		echo $row -> EntregaDomiciliar . '<br>';
		echo $row -> EntregaSabado;
		*/
	} else {
		echo $row -> MsgErro;
	}
	$w++;
}


