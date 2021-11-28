<?php

include_once 'config/config.php';
include_once '../functionsPDO.php';
//require '../_config/conection.php'; -> pra usar esse php o conection já deve ter sido chamado!!!

require_once 'vendor/autoload.php';
use NFePHP\Common\Certificate;
use NFePHP\NFe\Common\Standardize;
use NFePHP\NFe\Complements;

$tools = new NFePHP\NFe\Tools($configJson, Certificate::readPfx($certificadoDigital, $certPassword));

try{
	echo 'Realizando inutilização...</br></br>';
	$nSerie='1';
	$nIni=$_POST['nIni'];
	$nFin=$_POST['nFin'];
	$xJust=$_POST['xJust'];
	$response = $tools->sefazInutiliza($nSerie, $nIni, $nFin, $xJust);

	$stdCl= new Standardize($response);
	$std=$stdCl->toArray();
	
	if($std['infInut']['cStat'] === '102'){
		echo 'cStat: 102 -> ok!.</br>
		Motivo: '.$std['infInut']['xMotivo'].'</br>
		Salvo no banco de dados: ';
		for($i = $nIni; $i <= $nFin; $i++ ){
			$table='nf';
			$ind='num_nf';
			$varVal=$i;
			if(seExiste($dbh,$table,$ind,$varVal)){
				$qr='update nf set situacao=?, nprot=?, data_emissao=? where num_nf=?';
				$values=array(
					'Número inutilizado',
					$std['infInut']['nProt'],
					date('Y-m-d'),
					$i
				);
				$stmt=executeSQL($dbh,$qr,$values);
				if(is_string($stmt)){//deu erro no mysql..
					echo 'erro ao atualizar tabela: '.$stmt;
				}else{
					echo 'Atualizado numeração '.$i.'</br>';
				}
			} else{
				$qr= 'insert into nf (num_nf,situacao,nprot,data_emissao) values (?,?,?,?);';
				$values=array(
					$i,
					'Número inutilizado',
					$std['infInut']['nProt'],
					date('Y-m-d')
				);
				$stmt=executeSQL($dbh,$qr,$values);
				if(is_string($stmt)){//deu erro no mysql..
					echo 'erro ao inserir linha na tabela nf: '.$stmt;
				}else{
					echo 'inserido numeracao '.$i.' na tabela de nf</br>';
				}
			}
		}
	} else{
		echo 'erro ao inutilizar...</br>
		cStat:'.$std['infInut']['cStat'].'</br>
		Motivo: '.$std['infInut']['xMotivo'].'</br>
		Número inicial passado: '.$nIni.'; Final: '.$nFin;
	}

} catch( \Exception $e ){
	echo $e->getMessage();
}

?>