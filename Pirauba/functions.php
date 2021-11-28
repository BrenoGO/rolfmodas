<?php
function testInf($ref){
	$testinf = substr($ref,-1);
	if($testinf == "I"){
		return true;
	}else{
		return false;
	}	
}

function TamanhoRolf($ref,$i){
	if(testInf($ref)){
		//infantil
		if($i==1){
			return '4';
		}elseif($i==2){
			return '6';
		}elseif($i==3){
			return '8';
		}elseif($i==4){
			return '10';
		}elseif($i==5){
			return '12';
		}	
	}else{
		//adulto
		if($i==1){
			return 'P';
		}elseif($i==2){
			return 'M';
		}elseif($i==3){
			return 'G';
		}elseif($i==4){
			return 'GG';
		}elseif($i==5){
			return 'EG';
		}	
	}
}

function ConvertTams($ini,$fin){
	$tamIni=$ini.';'.$fin;
	switch($tamIni){
		case 'P;G':
			return('/P/M/G/');
			BREAK;
		case 'P;GG':
			return('/P/M/G/GG/');
			BREAK;
		case 'P;M':
			return('/P/M/');
			BREAK;
		case 'M;G':
			return('/M/G/');
			BREAK;
		case 'G;EG':
			return('/G/GG/EG/');
			BREAK;
		case 'GG;EG':
			return('/GG/EG/');
			BREAK;
		case 'EG;EG':
			return('/EG/');
			BREAK;
		case '4;12':
			return('/4/6/8/10/12/');
			BREAK;
		default:
			return('ERROR Tam');
	}
}
function CadastProdRolfToRpi($ref,$dbh,$dbhRPI){
	$stmt=$dbh->prepare("select * from produtos where ref=?");
	$stmt->execute(array($ref));
	$ln=$stmt->fetch(PDO::FETCH_ASSOC);
	$aux=explode(';',$ln['tipo']);
	$tamanhos= ConvertTams($aux[1],$aux[2]);
	$id_forn=1;
	$stmt=$dbhRPI->prepare("insert into produtos (ref,descricao,tamanhos,preco,secao,Tags,grupo,NCM,id_forn) values (?,?,?,?,?,?,?,?,?)");
	$stmt->execute(array($ln['ref'],$ln['descricao'],$tamanhos,$ln['preco'],$ln['secao'],$ln['Tags'],$ln['grupo'],$ln['NCM'],$id_forn));
}
function CEP_curl($cep) {
	$cep=preg_replace('/[^0-9]/', '', (string) $cep);
	$url = "http://viacep.com.br/ws/".$cep."/json/";
            // CURL
    $ch = curl_init();
            // Disable SSL verification
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
          // Will return the response, if false it print the response
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            // Set the url
    curl_setopt($ch, CURLOPT_URL, $url);
            // Execute
    $result = curl_exec($ch);
            // Closing
    curl_close($ch);
            
    $json=json_decode($result);
	//var_dump($json);
	if(!isset($json->erro)){
		$array['uf']=$json->uf;
		$array['cidade']=$json->localidade;
		$array['bairro']=$json->bairro;
		$array['logradouro']=$json->logradouro;
	}else{
		$array='Erro';
	}
	
	return $array;
}
?>