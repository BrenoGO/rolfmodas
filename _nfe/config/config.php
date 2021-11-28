<?php
//alguns dados principais padrões

//na área abaixo os dados podem modificar..
$versao='4.00';


$tpAmb = 2;//Tipo de ambiente->preencher quando mudar para produção!

if($tpAmb == 1){
	$ambiente = 'producao';
}elseif($tpAmb == 2){
	$ambiente = 'homologacao';
}

//Dados do Emitente
$razaosocialRolf="Rolf Modas Ltda";
$nomefantasiaRolf="Rolf Modas";
$CNPJRolf="19556708000151";
$IERolf="0022932320033";
$IEST='';
$IM='';
$CNAE=1412601;
$CRT=1;

//Endereço do emitente
$xLgrRolf = 'Praça Dr. Último de Carvalho';
$nroRolf = '02';
$xCplRolf = null;
$xBairroRolf = 'Centro';
$cMunRolf = '3155801';
$xMunRolf = 'Rio Pomba';
$UFRolf = 'MG';
$CEPRolf = '36180000';
$cPaisRolf = '1058';
$xPaisRolf = 'Brasil';
$foneRolf = '3235711010';


$PathCertificado="../_nfe/certs/ROLF_MODAS_LTDA19556708000151.pfx";
$certPassword="rolf10";

$config=[
"atualizacao"=>"2018-05-17 18:46:00",
"tpAmb"=>$tpAmb,
"razaosocial" => $razaosocialRolf,
"siglaUF" => $UFRolf,
"cnpj" => $CNPJRolf,
"schemes" => "PL_008i2",
"versao" => $versao
];
//"tokenIBPT" => "AAAAAAA" : estava dentro do array $config
$configJson = json_encode($config);
$certificadoDigital = file_get_contents($PathCertificado);


?>