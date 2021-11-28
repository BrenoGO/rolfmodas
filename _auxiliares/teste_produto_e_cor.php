<?php
$stmt1=$dbh->prepare("select * from produtos where ref = ?");
$stmt1->execute(array($ref));
$verificaref=$stmt1->rowCount();
		
$stmt2=$dbh->prepare("select * from cores where nomecor = ?");
$stmt2->execute(array($cor));
$verificacor=$stmt2->rowCount();
	
if($verificaref<=0){
	//verifica se referencia existe
	echo"<script language='javascript' type='text/javascript'>alert('Referência incorreta!');</script>";
	die();
}elseif($verificacor<=0){
	//verifica se cor existe
	echo"<script language='javascript' type='text/javascript'>alert('Cor inexistente!');</script>";
	die();
}else{
	$prodROW=$stmt1->fetch(PDO::FETCH_ASSOC);
	$tipoProdArray=explode(';',$prodROW['tipo']);
	if($cor <> 'SORTIDO'){
		
		$corVER=$stmt2->fetch(PDO::FETCH_ASSOC);
		$tipocor=$corVER['tipo'];

		if( (substr($tipoProdArray[0],1)=='E') and ($tipoProdArray[0]<>'AE') ){
			//Produto estampado.
			if($tipocor != 'E'){
				echo"<script language='javascript' type='text/javascript'>alert('Produto estampado e cor não é estampada..');</script>";
				die();
			}
		}else{
			//Produto Liso
			if($tipocor=='E'){
				echo"<script language='javascript' type='text/javascript'>alert('Produto liso e cor estampada..');</script>";
				die();
			}
		}
	}	
}
//Testes do tamanho
$tamMenor=$tipoProdArray[1];
$tamMaior=$tipoProdArray[2];
if($tamMenor=='G'){
	if($t[1]>0 or $t[2]>0){
		echo"<script language='javascript' type='text/javascript'>alert('Tamanho mínimo é G e tem P e(ou) M passando...');</script>";
		die();
	}
}elseif($tamMenor=='GG'){
	if($t[1]>0 or $t[2]>0 or $t[3]>0){
		echo"<script language='javascript' type='text/javascript'>alert('Tamanho mínimo é GG e tem P e(ou) M e(ou) G passando...');</script>";
		die();
	}
}elseif($tamMenor=='EG'){
	if($t[1]>0 or $t[2]>0 or $t[3]>0 or $t[4]>0){
		echo"<script language='javascript' type='text/javascript'>alert('Tamanho mínimo é EG e tem P e(ou) M e(ou) G e(ou) GG passando...');</script>";
		die();
	}
}
if($tamMaior == 'M'){
	if($t[3]>0 or $t[4]>0 or $t[5]>0){
		echo"<script language='javascript' type='text/javascript'>alert('Tamanho máximo é M e tem G e(ou) GG e(ou) EG passando...');</script>";
			die();
	}
}elseif($tamMaior == 'G'){
	if($t[4]>0 or $t[5]>0){
		echo"<script language='javascript' type='text/javascript'>alert('Tamanho máximo é G e tem GG e(ou) EG passando...');</script>";
		die();
	}
}elseif($tamMaior == 'GG'){
	if($t[5]>0){
		echo"<script language='javascript' type='text/javascript'>alert('Tamanho máximo é GG e tem EG passando...');</script>";
		die();
	}
}
//fim do teste do tamanho
