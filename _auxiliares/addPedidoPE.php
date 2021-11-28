
<?php

//Para utilizar o addPedidoPE:
//$_SESSION['pedidope'];



$refs=array_keys($_SESSION['pedidope']);
foreach($refs as $ref){
	$cores=array_keys($_SESSION['pedidope'][$ref]);
	foreach($cores as $cor){
	if($cor <> 'desconto'){
		$stmt=$dbh->prepare("select t1,t2,t3,t4,t5,tot from pcp where ref=? and cor=? and situacao=? and loc=?");
		$stmt->execute(array($ref,$cor,'S','estoque'));
		$ln=$stmt->fetch(PDO::FETCH_ASSOC);
		
		for($i=1;$i<=5;$i++){
			$t[$i]=$_SESSION['pedidope'][$ref][$cor]['t'.$i];
			$dif[$i]=$ln['t'.$i]-$t[$i]; 
		}
		$tot = array_sum($t);
		$stmt=$dbh->prepare("select preco from produtos where ref=?");
		$stmt->execute(array($ref));
		$ln=$stmt->fetch(PDO::FETCH_ASSOC);
		$preco=round($ln['preco']*$_SESSION['markup'],1);
		if(isset($_SESSION['pedidope'][$ref]['desconto'])){
			$desconto = $_SESSION['pedidope'][$ref]['desconto'];
		}else{
			$desconto = 0;
		}
		
		$sit='S';
		
		$stmt=$dbh->prepare("select * from pcp where ref=? and cor=? and situacao=? and loc=?");
		$stmt->execute(array($ref,$cor,$sit,$loc));
		$ver=$stmt->rowCount();
		$ln=$stmt->fetch(PDO::FETCH_ASSOC);
		if($ver>0){
			//já existe a linha..
			for($i=1;$i<=5;$i++){
				$tnovo[$i]=$t[$i]+$ln['t'.$i];
			}
			$totnovo=array_sum($tnovo);
			$stmt=$dbh->prepare("update pcp set t1=?,t2=?,t3=?,t4=?,t5=?,tot=? where ref=? and cor=? and loc=? and situacao=?");
			$stmt->execute(array($tnovo[1],$tnovo[2],$tnovo[3],$tnovo[4],$tnovo[5],$totnovo,$ref,$cor,$loc,$sit));
			
		}else{
			$stmt=$dbh->prepare("insert into pcp (ref,cor,t1,t2,t3,t4,t5,tot,loc,lote,dataalter,situacao,preco,desconto) 
								values (?,?,?,?,?,?,?,?,?,0,default,?,?,?)");
			$stmt->execute(array($ref,$cor,$t[1],$t[2],$t[3],$t[4],$t[5],$tot,$loc,$sit,$preco,$desconto));						
		}
		
		
		//Ajustar Estoque
		$totdif = array_sum($dif);
		if($totdif == 0){
			$stmt=$dbh->prepare("delete from pcp where ref=? and cor=? and situacao=? and loc=?");
			$stmt->execute(array($ref,$cor,'S','estoque'));
		}else{
			$stmt=$dbh->prepare("update pcp set t1=?,t2=?,t3=?,t4=?,t5=?,tot=? where ref=? and cor=? and situacao=? and loc=?");
			$stmt->execute(array($dif[1],$dif[2],$dif[3],$dif[4],$dif[5],$totdif,$ref,$cor,'S','estoque'));
		}
	}
	}
}
?>
