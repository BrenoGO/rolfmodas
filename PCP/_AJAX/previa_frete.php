<?php
//get the q parameter from URL

require '../../_config/conection.php'; 
$dbh = new PDO('mysql:host='.$host.';dbname='.$db.';charset=utf8',$user,$pass);

$CEP=$_GET["CEP"];
$valor=$_GET['valor'];


if($valor>=500){
	echo '
	Frete Grátis!
	';
}else{
	$boollogisticarolf=false;
	require '../functions.php';
	$buscaCEP=CEP_curl($CEP);

	$cidadeUF=$buscaCEP['cidade'].'/'.$buscaCEP['uf'];
	
	
	$stmt=$dbh->prepare("select dado_1 from dadosgerais where nome_dado=? and dado_1 like ?");
	$stmt->execute(array('Logística Rolf','%'.$cidadeUF.'%'));

	$boollogisticarolf=( (($stmt->rowCount()) > 0) and ($cidadeUF<>'/'));
	echo '</br><b>'.$cidadeUF.'</b></br>';
	if($boollogisticarolf){
		echo 'Logística Rolf: R$ 5,00 - até 10 dias úteis
		';
	}else{
		if($valor <= 100){
			$data['nVlComprimento'] = '28';
			$data['nVlAltura'] = '4';
			$data['nVlLargura'] = '26';
			$data['nVlPeso'] = '1';
		}else{
			$data['nVlComprimento'] = '41';
			$data['nVlAltura'] = '17';
			$data['nVlLargura'] = '26';
			$data['nVlPeso'] = '4';
		}
								
					
		$data['nVlValorDeclarado'] = number_format($valor,0);
		if($data['nVlValorDeclarado'] <= 20){
			$data['nVlValorDeclarado']=20;
		}
		$data['sCepDestino'] = $CEP;
		require("../../_auxiliares/frete.php");
		
		
		foreach($frete as $frete_servico){
			//frete estava dando muito caro, diminui um pouco por conta da rolf..
			$frete_servico['valor']=round((0.7*$frete_servico['valor']),1);
			echo '<option value="'.$frete_servico['valor'].'/'.$frete_servico['nomeServico'].'">'.$frete_servico['nomeServico'].': R$ '.number_format($frete_servico['valor'],2,',','.').' - até '.($frete_servico['PrazoEntrega']+1).' dias úteis</option>';
		}
	}

}



