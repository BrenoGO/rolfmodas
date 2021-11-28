<?php
session_start();

require '../_config/conection.php'; 


date_default_timezone_set('America/Sao_Paulo');

error_reporting(E_ALL);
ini_set('display_errors', 'On');

require_once 'vendor/autoload.php';

use NFePHP\NFe\Make;
use NFePHP\Common\Certificate;
use NFePHP\NFe\Common\Standardize;


require 'config/config.php';
require 'config/configNfeVenda.php';


/* Dentro do config acima temos valores de:
$versao='4.0' ou '3.10';
$tpAmb = '2'(homologacao) ou '1'(producao);//Tipo de ambiente
$ambiente = 'producao' ou 'homologacao';
$cUF = '31'; //codigo numerico do estado
$nNF //numero da NFe
$cNF = ...;//numero aleatório da NF
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

//Dados do Emitente
$razaosocial="Rolf Modas Ltda";
$nomefantasia="Rolf Modas";
$CNPJ="19556708000151";
$IE:"0022932320033";
$IEST="";
$IM="";
$CNAE="1412601";
$CRT="1";

//Node com o endereço do emitente
$xLgr = 'Praça Dr. Último de Carvalho';
$nro = '02';
$xCpl = '';
$xBairro = 'Centro';
$cMun = '3155801';
$xMun = 'Rio Pomba';
$UF = 'MG';
$CEP = '36180000';
$cPais = '1058';
$xPais = 'Brasil';
$fone = '3235711010';
*/


//pegando dados vindo de nfe.php
$pedido=$_POST['pedido'];
$tipoprodsnfe=$_POST['tipoprodsnfe'];
$desconto_pedido=$_POST['desconto_pedido'];
$total_sem_desconto=$_POST['total_sem_desconto'];
$indPag = $_POST['indPag']; //0=Pagamento à vista; 1=Pagamento a prazo; 2=Outros


//pegando dados já cadastrados do cliente
$stmt=$dbh->prepare("
			select p.id_cliente,razaosocial,nomefantasia,cnpj,email,ie,estado,contato,complemento,bairro,cidade,logradouro,num,CEP,tipo_pessoa from usuarios u
			join pedidos p on p.id_cliente = u.id_usuario
			where p.pedido=?
			");
$stmt->execute(array($pedido));
$ln=$stmt->fetch(PDO::FETCH_ASSOC);
$id_cliente=$ln['id_cliente'];
$rsocial=$ln['razaosocial'];
$nfantasia = $ln['nomefantasia'];
$cnpj = $ln['cnpj'];
$email = $ln['email'];
$ie_cliente = $ln['ie'];
$UF = $ln['estado'];
$fone = $ln['contato'];
$xCpl = $ln['complemento'];
$xBairro = $ln['bairro'];
$xMun = $ln['cidade'];
$xLgr = $ln['logradouro'];
$tipo_pessoa = $ln['tipo_pessoa'];

if($ln['num']==''){
	$nro='SN';
}else{
	$nro = $ln['num'];
}
$CEP = str_replace('-','',$ln['CEP']);
$cPais = '1058';
$xPais = 'Brasil';
$fone=str_replace('(','',$fone);
$fone=str_replace(')','',$fone);
$fone=str_replace(' ','',$fone);
$fone=str_replace('-','',$fone);
$fone=str_replace('.','',$fone);


$stmt=$dbh->prepare("select ibge_cidade from cidade c join estado e on e.id_estado = c.id_estado where c.nome_cidade=? and e.uf_estado=?");
$stmt->execute(array($xMun,$UF));
$ln=$stmt->fetch(PDO::FETCH_ASSOC);
$cMun=$ln['ibge_cidade'];



if($UF == 'MG'){
	$idDest = '1';
	$cfoppad = '5101';
}else{
	$idDest = '2';
	$cfoppad = '6101';
}//1=Operação interna; 2=Operação interestadual; 3=Operação com exterior.

$nfe=new Make();

//node principal
$std= new stdClass();
$std->versao = $versao; //versão do layout
$std->Id = null;//se o Id de 44 digitos não for passado será gerado automaticamente
$std->pk_nItem = null; //deixe essa variavel sempre como NULL

$elem = $nfe->taginfNFe($std);

//Node de identificação da NFe
$std = new stdClass();
$std->cUF = $cUF;
$std->cNF = $cNF;
$std->natOp = $natOp;
if($versao=='3.10'){
	$std->indPag = $indPag; //NÃO EXISTE MAIS NA VERSÃO 4.00 
}

$std->mod = $mod;
$std->serie = $serie;
$std->nNF = $nNF;
$std->dhEmi = $dhEmi;
$std->dhSaiEnt = null;
$std->tpNF = $tpNF;
$std->idDest = $idDest;
$std->cMunFG = $cMunFG;
$std->tpImp = $tpImp;
$std->tpEmis = $tpEmis;
$std->cDV = null;
$std->tpAmb = $tpAmb;
$std->finNFe = $finNFe;
if($tipo_pessoa=='F'){
	$indFinal='1';//no config.php ele é deixado como 0..
}
$std->indFinal = $indFinal; 
$std->indPres = $indPres;
$std->procEmi = $procEmi;
$std->verProc = $verProc;
$std->dhCont = $dhCont;
$std->xJust = $xJust;

$elem = $nfe->tagide($std);


//Node com os dados do emitente
$std = new stdClass();
$std->CNPJ=$CNPJRolf;
$std->xNome=$razaosocialRolf;
$std->xFant=$nomefantasiaRolf;
$std->IM=$IM;
//$std->CNAE=$CNAE;
$std->IE=$IERolf;
$std->IEST=$IEST;
$std->CRT=$CRT;

$elem = $nfe->tagemit($std);


//Node com o endereço do emitente
$std = new stdClass();
$std->xLgr=$xLgrRolf;
$std->nro=$nroRolf;
$std->xCpl=$xCplRolf;
$std->xBairro=$xBairroRolf;
$std->cMun=$cMunRolf;
$std->xMun=$xMunRolf;
$std->UF=$UFRolf;
$std->CEP=$CEPRolf;
$std->cPais=$cPaisRolf;
$std->xPais=$xPaisRolf;
$std->fone=$foneRolf;

$elem=$nfe->tagenderEmit($std);


//Node com os dados do destinatário
if($tipo_pessoa=='F'){
	$std = new stdClass();
	$std->xNome=$rsocial;
	$std->indIEDest=9;
	$std->email = $email;
	$std->CPF = $cnpj; 
	$std->ISUF = null;
	$std->IM = null;
	

	$elem = $nfe->tagdest($std);
}else{	
	$std = new stdClass();
	$std->xNome=$rsocial;
	$std->indIEDest= 1;//não sei se é o correto.. talvez varia de acordo c cada cliente...
	$std->IE = $ie_cliente;
	$std->ISUF = null;
	$std->IM = null;
	$std->email = $email;
	$std->CNPJ = $cnpj; 
	$std->idEstrangeiro = null;

	$elem = $nfe->tagdest($std);
}

//Node de endereço do destinatário
$std = new stdClass();

$std->xLgr=$xLgr;
$std->nro=$nro;
$std->xCpl=$xCpl;
$std->xBairro=$xBairro;
$std->cMun=$cMun;
$std->xMun=$xMun;
$std->UF=$UF;
$std->CEP=$CEP;
$std->cPais=$cPais;
$std->xPais=$xPais;
$std->fone=$fone;

$elem = $nfe->tagenderDest($std);


//Nodes de Produtos

$qntTotal = 0;
foreach ($_SESSION['NFe-'.$tipoprodsnfe] as $prodQ) {
	$qntTotal += $prodQ['qtd'];
}

$i = 0;
$TotBasedeCalculo = 0;
$TotDescProds = 0;
$soma_desc_item = 0;
$arrayKeys = array_keys($_SESSION['NFe-'.$tipoprodsnfe]);
$numdeitens=count($arrayKeys);
foreach ($_SESSION['NFe-'.$tipoprodsnfe] as $prod) {
	//Node de dados do produto/serviço
	$std = new stdClass();
	
	$i++;
	$ref=$prod['ref'];
	$stmt=$dbh->prepare("select * from produtos where ref=?");
	$stmt->execute(array($ref));
	$ln=$stmt->fetch(PDO::FETCH_ASSOC);
	
	
    $std->item = $i;
	
	$std->cProd=$ref;
	$std->cEAN=null;
	$std->xProd=$ln['descricao'];
	$std->NCM=$ln['NCM'];
	$std->cBenef=null;
	
	$std->EXTIPI = null;
    $std->CFOP = $cfoppad; 
    $std->uCom = 'Un';
    $std->qCom = $prod['qtd'];
    $std->vUnCom = $prod['preco'];
	
	$vProd=number_format(($prod['subtotal']/(1-$prod['desconto']/100)),2,'.','');
    $std->vProd = $vProd;

	$TotBasedeCalculo += $vProd;
	
    $std->cEANTrib = null;
    $std->uTrib = 'Un';
    $std->qTrib = $prod['qtd'];
    $std->vUnTrib = $prod['preco'];
    $std->vFrete = null;
    $std->vSeg = null;
	
	$desc_item = 0;
	if($desconto_pedido > 0){
		$fator=$vProd/$total_sem_desconto;
		$desc_item = number_format($fator * $desconto_pedido,2,'.','');
		if($numdeitens == $i){
			$desc_item = $desconto_pedido-$soma_desc_item;
		}
		$soma_desc_item += $desc_item;
	}
	
    if(($prod['desconto']/100*$prod['preco']==0)and($desconto_pedido==0)){$vDesc = 0;}else{$vDesc = number_format($prod['desconto']/100*$prod['preco']*$prod['qtd']+$desc_item,2,'.','');}
	$TotDescProds += $vDesc;
	if($vDesc == 0){$vDesc=null;}
	$std->vDesc=$vDesc;
	$std->vOutro = null;
    $std->indTot = '1';
    $std->xPed = $pedido;
    $std->nItemPed = null;
    $std->nFCI = null;
    
	$elem = $nfe->tagprod($std);
	
	
	//Node de informações adicionais do produto
	/*
	$std = new stdClass();
	$std->item = $i; //item da NFe
	$std->infAdProd = '';
	$elem = $nfe->taginfAdProd($std);
	*/
	
	//Node inicial dos Tributos incidentes no Produto ou Serviço do item da NFe
	$std = new stdClass();
	$std->item = $i; //item da NFe
	$std->vTotTrib = null;
	$elem = $nfe->tagimposto($std);
	
	
	
	//Node referente Tributação ICMS pelo Simples Nacional do item da NFe
	$std = new stdClass();
	$std->item = $i; //item da NFe
	$std->orig = '0';
	$std->CSOSN = '102'; 
	$std->pCredSN=null;
	$std->vCredICMSSN =null;
	$std->modBCST = null;
	$std->pMVAST = null;
	$std->pRedBCST = null;
	$std->modBC = '';
	$std->vBCST = null;
	$std->pICMSST = null;
	$std->vICMSST = null;
	$std->vBCFCPST = null; //incluso no layout 4.00
	$std->pFCPST = null; //incluso no layout 4.00
	$std->vFCPST = null; //incluso no layout 4.00
	$std->pCredSN = null;
	$std->vCredICMSSN = null;
	$std->pCredSN = null;
	$std->vCredICMSSN = null;
	$std->vBCSTRet = null;
	$std->pST = null;
	$std->vICMSSTRet = null;
	$std->vBCFCPSTRet = null; //incluso no layout 4.00
	$std->pFCPSTRet = null; //incluso no layout 4.00
	$std->vFCPSTRet = null; //incluso no layout 4.00
	$std->modBC = null;
	$std->vBC = null;
	$std->pRedBC = null;
	$std->pICMS = null;
	$std->vICMS = null;

	$elem = $nfe->tagICMSSN($std);

	
	//Node PIS do item da NFe
	$std = new stdClass();
	$std->item = $i; //item da NFe
	$std->CST = '07';
	$std->vBC = null;
	$std->pPIS = null;
	$std->vPIS = null;
	$std->qBCProd = null;
	$std->vAliqProd = null;

	$elem = $nfe->tagPIS($std);
	
	
	//Node COFINS do item da NFe
	$std = new stdClass();
	$std->item = $i; //item da NFe
	$std->CST = '07';
	$std->vBC = null;
	$std->pCOFINS = null;
	$std->vCOFINS = null;
	$std->qBCProd = null;
	$std->vAliqProd = null;

	$elem = $nfe->tagCOFINS($std);
	
}
//Node dos totais referentes ao ICMS
$std = new stdClass();
$std->vBC = null;
$std->vICMS = null;
$std->vICMSDeson = null;
$std->vFCP = null; //incluso no layout 4.00
$std->vBCST = null;
$std->vST = null;
$std->vFCPST = null; //incluso no layout 4.00
$std->vFCPSTRet = null; //incluso no layout 4.00
$vProd=number_format($TotBasedeCalculo,2,'.','');
$std->vProd = $vProd;
$std->vFrete = null;
$std->vSeg = null;
$vDesc=$TotDescProds == 0 ? null : number_format($TotDescProds,2,'.','');
$std->vDesc = $vDesc;
$std->vII = null;
$std->vIPI = null;
$std->vIPIDevol = null; //incluso no layout 4.00
$std->vPIS = null;
$std->vCOFINS = null;
$std->vOutro = null;
$vNF=number_format($vProd-$vDesc, 2, '.', '');
$std->vNF = $vNF;
$std->vTotTrib = null;

$elem = $nfe->tagICMSTot($std);

//Node indicativo da forma de frete
$std = new stdClass();
$std->modFrete = 9;

$elem = $nfe->tagtransp($std);


//Node com as informações dos volumes transportados
$numdevolumes = $_POST['numdevolumes'];

$std = new stdClass();
$std->item = 1; //indicativo do numero do volume
$std->qVol = $numdevolumes;;
$std->esp = 'Caixa';
$std->marca = null;
$std->nVol = null;
$std->pesoL = null;
$std->pesoB = null;

$elem = $nfe->tagvol($std);

//Node de informações das duplicatas


if(!isset($_POST['ocultdupl'])){
	//////////////Gerar boletos
	/*
	$valor=$TotBasedeCalculo;
	require '../PCP/functions.php';
	require '../_auxiliares/faturamento.php';
	require("../financeiro/_auxiliares/GerarAReceber.php");
	*/
	/////////////fim do gerar boletos
}


$std = new stdClass();
$std->nFat = $nNF;
$std->vOrig = number_format($vProd, 2, '.', '');
$vDesc = number_format($vDesc, 2, '.', '');
$std->vDesc = $vDesc;
$std->vLiq = $vNF;



$nfe->tagfat($std);


$aDup = array();
for($cdrz = 1;$cdrz<=12;$cdrz++){
	if(isset($_POST['dataparc'.$cdrz])){
		$docparc = $_POST['docparc'.$cdrz];
		$dataparc = $_POST['dataparc'.$cdrz];
		$valorparc=str_replace('.','',$_POST['valorparc'.$cdrz]);
		$valorparc= number_format(str_replace(',','.',$valorparc),2,'.','');
		$aDup[] = array($docparc,$dataparc,$valorparc);
	}
}

$counter = 1;
foreach ($aDup as $dup) {
	
	if(strlen($counter) == 2){
		$str_counter = '0'.$counter;
	}elseif(strlen($counter) == 1){
		$str_counter = '00'.$counter;
	}else{
		$str_counter = $counter;
	}

	$std = new stdClass();
	//$std->nDup = $dup[0];//Código da Duplicata
	$std->nDup = $str_counter;//Código da Duplicata
	$std->dVenc = $dup[1];//Vencimento
	$std->vDup = $dup[2];// Valor

	$elem = $nfe->tagdup($std);
	$counter++;
		
}


//Node referente as formas de pagamento OBRIGATÓRIO para NFCe a partir do layout 3.10 e também obrigatório para NFe (modelo 55) a partir do layout 4.00

$std = new stdClass();
$std->vTroco = null; //incluso no layout 4.00, obrigatório informar para NFCe (65)

$elem = $nfe->tagpag($std);

//Node com o detalhamento da forma de pagamento OBRIGATÓRIO para NFCe e NFe layout4.00
/* ATENCAO, TEM QUE VINCULAR COM DADOS QUE VEM PRA DIFERENCIAR SE FOR PAGAMENTO A VISTA, OU DUPLICATA


****************/
$tPag=$_POST['indPag'];

$std = new stdClass();
$std->tPag = $tPag;
$std->vPag = $vNF; //Obs: deve ser informado o valor pago pelo cliente

//$std->CNPJ = '12345678901234';
//$std->tBand = '01';
//$std->cAut = '3333333';
//$std->tpIntegra = 1; //incluso na NT 2015/002

$elem = $nfe->tagdetPag($std);

/*
NOTA: para NFe (modelo 55), temos ...

vPag=0.00 mas pode ter valor se a venda for a vista

tPag é usualmente:
01=Dinheiro
02=Cheque
03=Cartão de Crédito
04=Cartão de Débito
05=Crédito Loja
10=Vale Alimentação
11=Vale Refeição
12=Vale Presente
13=Vale Combustível
14 = Duplicata Mercantil
15 = Boleto Bancário
90 = Sem pagamento
99 = Outros
Porém podem haver casos que os outros nodes e valores tenha de ser usados.
*/


//Node referente as informações adicionais da NFe
$std = new stdClass();
$std->infAdFisco = '';
$std->infCpl = 'Empresa optante pelo simples nacional, não gera direito a crédito de ICMS.';

$elem = $nfe->taginfAdic($std);

$result = $nfe->montaNFe();//Este método executa a montagem do XML
$xml = $nfe->getXML();//Este método retorna o XML em uma string
//header('Content-type: text/xml; charset=UTF-8');
//echo $xml;
$chave = $nfe->getChave();//Este método retorna o numero da chave da NFe
//$modelo = $nfe->getModelo();//Este método retorna o modelo de NFe 55 ou 65


$stmt=$dbh->prepare("insert into nf (num_nf,id_usuario,situacao,chave) values (?,?,?,?)");
$stmt->execute(array($nNF,$id_cliente,'XML Gerado',$chave));


$tools = new NFePHP\NFe\Tools($configJson, Certificate::readPfx($certificadoDigital, $certPassword));
try {
    $xmlAssinado = $tools->signNFe($xml); // O conteúdo do XML assinado fica armazenado na variável $xmlAssinado
	//Salvar o XML assinado
	$path_xml_assinado='xmls/NF-e/'.$ambiente.'/assinadas/'.$chave.'-nfe.xml';
	file_put_contents($path_xml_assinado,$xmlAssinado);
	
	
	$stmt=$dbh->prepare("update nf set situacao=? where num_nf=?");
	$stmt->execute(array('XML assinado',$nNF));
	//header('Content-type: text/xml; charset=UTF-8');
	//echo $xmlAssinado;
} catch (\Exception $e) {
    echo "Erro em NFeVenda.php->linha 601.</br>";//aqui você trata possíveis exceptions da assinatura
    echo 'NF número '.$nNF.' excluída do banco de dados, tente novamente...</br>';
	$stmt=$dbh->prepare("delete from nf where num_nf=?");
	$stmt->execute(array($nNF));
	exit($e->getMessage());
}


try{
	$idLote = str_pad(100, 15, '0', STR_PAD_LEFT); // Identificador do lote
    $resp = $tools->sefazEnviaLote([$xmlAssinado], $idLote);
	
    $st = new Standardize();
    $std = $st->toStd($resp);
	
	//var_dump ($std);   
	
   if ($std->cStat != 103) {
        //erro registrar e voltar
		echo 'Erro NFeVenda.php->linha 620.</br>';
        exit("[$std->cStat] $std->xMotivo");
    }else{
		 $recibo = $std->infRec->nRec; // Vamos usar a variável $recibo para consultar o status da nota
	
		$stmt=$dbh->prepare("update nf set situacao=?,recibo=? where num_nf=?");
		$stmt->execute(array('Lote Enviado',$recibo,$nNF));
	}
   
	
}catch (\Exception $e) {
    //aqui você trata possiveis exceptions do envio
    echo 'Erro NFeVenda.php->linha 632.</br>';
	echo 'NF número '.$nNF.' excluída do banco de dados, tente novamente...</br>';
	$stmt=$dbh->prepare("delete from nf where num_nf=?");
	$stmt->execute(array($nNF));
	exit($e->getMessage());
}
//var_dump($recibo);
//http://localhost/rolfmodas.com.br/PCP/nfe.php?tprods=geral&pedido=1431


require 'loopProt.php';
