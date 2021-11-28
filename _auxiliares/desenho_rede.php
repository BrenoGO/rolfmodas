<?php

//pra utilizar: $data_fat1,$data_fat2

$stmt=$dbh->prepare("select rede,nomefantasia from usuarios where id_usuario=?");
$stmt->execute(array($_SESSION['id_usuario']));
$ln=$stmt->fetch(PDO::FETCH_ASSOC);
$rede=$ln['rede'];
$exprede=explode('/',$rede);
$num_diretos=count($exprede)-2;
$nome=$ln['nomefantasia'];
//calcular VP e VE
$n_var=0;
$id=$_SESSION['id_usuario'];
$MMN[$n_var][$id]['VP']=0;


$boolproximonivel=true;

$stmt=$dbh->prepare("select sum(valor) from faturamentos where id_usuario=? and data_fat >=? and data_fat <=?");
$stmt->execute(array($id,$data_fat1,$data_fat2));
$ln=$stmt->fetch(PDO::FETCH_ASSOC);
$MMN[$n_var][$id]['VP']=$ln['sum(valor)'];
$MMN[$n_var][$id]['VE']=$ln['sum(valor)'];
$MMN[$n_var][$id]['num_cons_tot']=1;
$stmt=$dbh->prepare("select rede from usuarios where id_usuario=?");
$stmt->execute(array($id));
$ln=$stmt->fetch(PDO::FETCH_ASSOC);
$ln=$ln['rede'];
$expln=explode('/',$ln);
$MMN[$n_var][$id]['num_diretos']=count($expln)-2;
$MMN[$n_var][$id]['num_cons_tot']+=$MMN[$n_var][$id]['num_diretos'];
//$MMN[$n_var][$id]['diretos']=$expln;

if($MMN[$n_var][$id]['num_diretos']>0){
	foreach($expln as $id_down){
	if($id_down<>''){	
		$MMN[($n_var+1)][$id_down]['id_upline']=$id;
	}
	}	
}
$n_var++;
while(isset($MMN[$n_var])){
	$ids=array_keys($MMN[$n_var]);
	foreach($ids as $id){
		$stmt=$dbh->prepare("select sum(valor) from faturamentos where id_usuario=? and data_fat >=? and data_fat <=?");
		$stmt->execute(array($id,$data_fat1,$data_fat2));
		$ln=$stmt->fetch(PDO::FETCH_ASSOC);
		
		$MMN[$n_var][$id]['VP']=$ln['sum(valor)'];
		$MMN[$n_var][$id]['VE']=$ln['sum(valor)'];
		$MMN[$n_var][$id]['num_cons_tot']=1;
			
		$stmt=$dbh->prepare("select rede from usuarios where id_usuario=?");
		$stmt->execute(array($id));
		$ln=$stmt->fetch(PDO::FETCH_ASSOC);
		$ln=$ln['rede'];
		$expln=explode('/',$ln);
		$MMN[$n_var][$id]['num_diretos']=count($expln)-2;
		$MMN[$n_var][$id]['num_cons_tot']+=$MMN[$n_var][$id]['num_diretos'];
		//$MMN[$n_var][$id]['diretos']=$expln;
		
		$stmt=$dbh->prepare("select nomefantasia,qualificacao,acesso from usuarios where id_usuario= ?");
		$stmt->execute(array($id));
		$ln=$stmt->fetch(PDO::FETCH_ASSOC);
		$MMN[$n_var][$id]['nomefantasia']=$ln['nomefantasia'];
		if($ln['qualificacao']<>null){
			$MMN[$n_var][$id]['qualificacao']=$ln['qualificacao'];
		}else{
			$MMN[$n_var][$id]['qualificacao']=$ln['acesso'];
		}
		
		if($MMN[$n_var][$id]['num_diretos']>0){
			foreach($expln as $id_down){
			if($id_down<>''){
				$MMN[($n_var+1)][$id_down]['id_upline']=$id;
			}
			}
		}
		$id_upline=$MMN[($n_var)][$id]['id_upline'];
		$n_up=$n_var-1;
		while(isset($MMN[$n_up])){
			$MMN[$n_up][$id_upline]['VE'] += $MMN[$n_var][$id]['VP'];
			$MMN[$n_up][$id_upline]['num_cons_tot'] += $MMN[$n_var][$id]['num_diretos'];
			if($n_up > 0){$id_upline = $MMN[($n_up)][$id_upline]['id_upline'];}
			$n_up--;
		}
					
	}	
	$n_var++;
}