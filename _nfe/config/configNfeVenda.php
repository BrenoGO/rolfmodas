<?php 



$stmt=$dbh->prepare("select max(num_nf) from nf");
$stmt->execute();
$ln=$stmt->fetch(PDO::FETCH_ASSOC);
$nNF = $ln['max(num_nf)']+1; // numero da NFe
$cUF = '31'; //codigo numerico do estado
$cNF = strval($nNF);//numero aleatório da NF
while(strlen($cNF)<8){
	$cNF ='0'.$cNF; 
}


$natOp = 'Venda de Produto'; //natureza da operação

$mod = '55'; //modelo da NFe 55 ou 65 essa última NFCe
$serie = '1'; //serie da NFe
$dhEmi = date("Y-m-d\TH:i:sP");//Formato: “AAAA-MM-DDThh:mm:ssTZD” (UTC - Universal Coordinated Time).
$dhSaiEnt = date("Y-m-d\TH:i:sP");//Não informar este campo para a NFC-e.
$tpNF = '1';


$cMunFG = '3155801';

$tpImp = '1'; //0=Sem geração de DANFE; 1=DANFE normal, Retrato; 2=DANFE normal, Paisagem;
              //3=DANFE Simplificado; 4=DANFE NFC-e; 5=DANFE NFC-e em mensagem eletrônica
              //(o envio de mensagem eletrônica pode ser feita de forma simultânea com a impressão do DANFE;
              //usar o tpImp=5 quando esta for a única forma de disponibilização do DANFE).
$tpEmis = '1'; //1=Emissão normal (não em contingência);
               //2=Contingência FS-IA, com impressão do DANFE em formulário de segurança;
               //3=Contingência SCAN (Sistema de Contingência do Ambiente Nacional);
               //4=Contingência DPEC (Declaração Prévia da Emissão em Contingência);
               //5=Contingência FS-DA, com impressão do DANFE em formulário de segurança;
               //6=Contingência SVC-AN (SEFAZ Virtual de Contingência do AN);
               //7=Contingência SVC-RS (SEFAZ Virtual de Contingência do RS);
               //9=Contingência off-line da NFC-e (as demais opções de contingência são válidas também para a NFC-e);
               //Nota: Para a NFC-e somente estão disponíveis e são válidas as opções de contingência 5 e 9.

$finNFe = '1'; //1=NF-e normal; 2=NF-e complementar; 3=NF-e de ajuste; 4=Devolução/Retorno.
$indFinal = '0'; //0=Normal; 1=Consumidor final;
$indPres = '9'; //0=Não se aplica (por exemplo, Nota Fiscal complementar ou de ajuste);
               //1=Operação presencial;
               //2=Operação não presencial, pela Internet;
               //3=Operação não presencial, Teleatendimento;
               //4=NFC-e em operação com entrega a domicílio;
               //9=Operação não presencial, outros.
$procEmi = '0'; //0=Emissão de NF-e com aplicativo do contribuinte;
                //1=Emissão de NF-e avulsa pelo Fisco;
                //2=Emissão de NF-e avulsa, pelo contribuinte com seu certificado digital, através do site do Fisco;
                //3=Emissão NF-e pelo contribuinte com aplicativo fornecido pelo Fisco.
$verProc = '5.0'; //versão do aplicativo emissor
$dhCont = ''; //entrada em contingência AAAA-MM-DDThh:mm:ssTZD
$xJust = ''; //Justificativa da entrada em contingência



