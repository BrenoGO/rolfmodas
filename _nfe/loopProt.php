<?php 

//utiliza: $nNF,$recibo,$chave,_nfe/config/config.php,$tools

use NFePHP\NFe\Common\Standardize;
use NFePHP\DA\NFe\Danfe;
use NFePHP\DA\Legacy\FilesFolders;

if(!isset($iniPath)){$iniPath='';}

try {
    $protocolo = $tools->sefazConsultaRecibo($recibo);
} catch (\Exception $e) {
    echo "Erro em loopProt.php->linha 14.</br>";//aqui você trata possíveis exceptions da consulta
    exit($e->getMessage());
}
$st= new Standardize();
$stdProt = $st->toStd($protocolo);

//var_dump($stdProt);


if($stdProt->cStat == '105'){
	echo 'Lote em processamento
		<form method="post" action="ConsultaRecibo.php">
		<input type="hidden" value="'.$chave.'" name="chave"/>
		<input type="hidden" value="'.$recibo.'" name="recibo"/>
		<input type="hidden" value="'."'".$configJson."'".' name="configJson"/>
		<input type="hidden" value="'.$nNF.'" name="nNF"/>
		<input type="submit" value="Tentar novamente"/>
		</form>';
		
	$stmt=$dbh->prepare("update nf set situacao=? where num_nf=?");
	$stmt->execute(array('Lote em Processamento',$nNF));
	
}elseif($stdProt->cStat == '104'){
	if($stdProt->protNFe->infProt->cStat=='100'){
		$nProt=$stdProt->protNFe->infProt->nProt;
		$stmt=$dbh->prepare("update nf set nprot=? where num_nf=?");
		$stmt->execute(array($nProt,$nNF));
		try {
			$protocol = new NFePHP\NFe\Factories\Protocol();
			
			if(!isset($xmlassinado)){
				$pathXmlAss=$iniPath.'xmls/NF-e/'.$ambiente.'/assinadas/'.$chave.'-nfe.xml';
				$xmlAssinado=file_get_contents($pathXmlAss);
			}
			$xmlProtocolado = $protocol->add($xmlAssinado,$protocolo);
			$stmt=$dbh->prepare("update nf set situacao=? where num_nf=?");
			$stmt->execute(array('Autorizado o uso da NF-e',$nNF));
			
			$pathXmlProtocolado=$iniPath.'xmls/NF-e/'.$ambiente.'/enviadas/aprovadas/'.date("Y").date("m").'/'.$chave.'-protNFe.xml';
			
			$pastatest=$iniPath.'xmls/NF-e/'.$ambiente.'/enviadas/aprovadas/'.date("Y").date("m");
			if(!file_exists($pastatest)){
				mkdir($pastatest,0777);
			}
			
			file_put_contents($pathXmlProtocolado,$xmlProtocolado);
			
			try{
				$danfe = new Danfe($xmlProtocolado, 'P', 'A4', 'images/logo.jpg', 'I', '');
				$id = $danfe->montaDANFE();
				$pdf = $danfe->render();
				$pathPDFDanfe=$iniPath.'xmls/NF-e/'.$ambiente.'/pdf/'.date("Y").date("m").'/'.$chave.'-danfe.pdf';
				
				$pastatest=$iniPath.'xmls/NF-e/'.$ambiente.'/pdf/'.date("Y").date("m");
				if(!file_exists($pastatest)){
					mkdir($pastatest,0777);
				}
				
				file_put_contents($pathPDFDanfe, $pdf);
				$stmt=$dbh->prepare("update nf set situacao=?,data_emissao=? where num_nf=?");
				$stmt->execute(array('Nota Gerada',date('Y-m-d'),$nNF));
			
				header('Content-Type: application/pdf');
				echo $pdf;
				
			}catch(\Exception $e){
				echo "Erro em loopProt.php->linha 80.</br>
				Ocorreu um erro durante o processamento :" . $e->getMessage();
			}
					
			/*header('Content-type: text/xml; charset=UTF-8');
			echo $xmlProtocolado;
			*/
			
		
		} catch (\Exception $e) {
			//aqui você trata possíveis exceptions ao adicionar protocolo
			echo "Erro em loopProt.php->linha 91.</br>";
			exit($e->getMessage());
		}
	}else{
		echo 'Lote inválido.. (loopProt.php->linha 95)</br>cStat: '.
		$stdProt->protNFe->infProt->cStat
		.'</br>Motivo: '.$stdProt->protNFe->infProt->xMotivo
		.'</br>Corrija e tente novamente.
		Nf Número '.$nNF.' excluída do banco de dados..';
		$stmt=$dbh->prepare("delete from nf where num_nf=?");
		$stmt->execute(array($nNF));
	}
}else{
	var_dump($stdProt);
}
