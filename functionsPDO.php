<?php
function executeSQL($dbh,$qr,$values){//$values = array();
	$stmt=$dbh->prepare($qr);
	if(!$stmt->execute($values)){
		$error=$stmt->errorInfo();
		return('Erro no MySQL: '.$error[2]);
	}else{
		return($stmt);
	}	
	/*Pode usar na forma:
	$stmt=executeSQL($dbh,$qr,$values);
	if(is_string($stmt)){//deu erro no mysql..
		echo $stmt;
	}else{
		echo 'Executado com sucesso..';
	}
	*/
	
}
function selectPDO($dbh,$fields,$table){
	$qr='select '.$fields.' from '.$table.' where '.$where;
	$stmt=executeSQL($dbh,$qr,array());
	return($stmt);
}
function insertRow($dbh,$table,$cols,$qrValues,$values){
	$qr="insert into ".$table." ".$cols." values ".$qrValues;
	executeSQL($dbh,$qr,$values);
}
function seExiste($dbh,$table,$ind,$varVal){
	$qr="select * from $table where $ind = ?";
	$values=array($varVal);
	$stmt=executeSQL($dbh,$qr,$values);
	if($stmt->rowCount()<=0){
		return false;
	}else{
		return true;
	}
}
function seQrExiste($dbh,$qr,$values){
	$stmt=executeSQL($dbh,$qr,$values);
	if($stmt->rowCount()<=0){
		return false;
	}else{
		return true;
	}
}
function fetchAssoc($dbh,$qr,$values){
	$stmt=$dbh->prepare($qr);
	if($stmt->execute($values)){
		$row=$stmt->fetch(PDO::FETCH_ASSOC);
		return($row);
	}else{
		return($stmt->errorInfo());
	}	
}
function fetchToArray($dbh,$qr,$values){
	$stmt=$dbh->prepare($qr);
	if($stmt->execute($values)){
		$array=array();
		while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
			$array[]=$row;
		}
		return($array);
	}else{
		return($stmt->errorInfo());
	}
}


?>