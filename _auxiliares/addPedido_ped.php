
<?php

//Para utilizar o addPedido_ped:
//$qrped ou $_SESSION['pedido_ped'];
//$pedido
//$origem
//$dataentr; 
//ter feito anteriormente o require _config/conection.php
if($origem == 'catalogo'){
	$message = '
		<html>
		<head>
		<title>Itens separados no pedido<b> '.$pedido.'</b></title>
		<style>
			table, tr, td, th {
			border: 1px solid #606060;
			border-spacing: 0px;
		}
		</style>
		
		</head>
		<body>
		<table>
			<thead>
				<tr>
					<th>Ref.</th>
					<th>Cor</th>
					<th>P (ou 4)</th>
					<th>M (ou 6)</th>
					<th>G (ou 8)</th>
					<th>GG (ou 10)</th>
					<th>EG (ou 12)</th>
				</tr>
			</thead>
			<tbody>';
}

if(!isset($qrped)){
	$array_session=array_keys($_SESSION['pedido_ped']);
	foreach($array_session as $ref_session){
		
		$stmt=$dbh->prepare("select preco from produtos where ref=?");
		$stmt->execute(array($ref_session));
		$ln=$stmt->fetch(PDO::FETCH_ASSOC);
		$preco=round($ln['preco']*$_SESSION['markup'],1);
		$cores_session=array_keys($_SESSION['pedido_ped'][$ref_session]);
		foreach($cores_session as $cor_session){
			$qrped[]=$ref_session.','.$cor_session.','.$_SESSION['pedido_ped'][$ref_session][$cor_session]['t1'].','.$_SESSION['pedido_ped'][$ref_session][$cor_session]['t2'].','.$_SESSION['pedido_ped'][$ref_session][$cor_session]['t3'].','.$_SESSION['pedido_ped'][$ref_session][$cor_session]['t4'].','.$_SESSION['pedido_ped'][$ref_session][$cor_session]['t5'].','.$preco.',0';
		}
	}	
}

foreach($qrped as $qrarray){
	
	if( ($qrarray <>"") and ($qrarray <> "Desconto") and (strlen($qrarray)>4) ){
		$txt=explode(",",$qrarray);
		$ref= mb_strtoupper($txt[0],'UTF-8');
		$cor= mb_strtoupper($txt[1],'UTF-8');
		$t[1]= intval($txt[2]);
		$t[2]= intval($txt[3]);
		$t[3]= intval($txt[4]);
		$t[4]= intval($txt[5]);
		$t[5]= intval($txt[6]);
		$tot= array_sum($t);
		$preco=$txt[7];
		$desconto=$txt[8];
					
					
		if($cor == "SORTIDO"){
			//Criar linha de pedido A.
			$qr= "insert into pcp (ref,cor,t1,t2,t3,t4,t5,tot,loc,lote,dataalter,situacao,preco,desconto) values ('$ref','$cor',$t[1],$t[2],$t[3],$t[4],$t[5],$tot,'$pedido',0,default,'A',$preco,$desconto)";
			$sql= mysqli_query($con,$qr);
			
			if($origem=='pcp'){
				for($i=1;$i<=5;$i++){
					if($t[$i]>0){
						echo '<span style="font-size:10pt">  Ref '.$ref.', '.$cor.': ';
						if($i == 1){echo ' P(ou 4)';} 
						elseif($i == 2){echo ' M(ou 6)';}
						elseif($i == 3){echo ' G(ou 8)';}
						elseif($i == 4){echo ' GG(ou 10)';}
						elseif($i == 5){echo ' EG(ou 12)';}
						echo ' ? cor "sortida", verifique no estoque </br></span>';
					}
				}
			}elseif($origem=='catalogo'){
				$message .= '<tr>
				<td>'.$ref.'</td>
				<td>SORTIDO!!!</td>
				<td>'.$t[1].'</td>
				<td>'.$t[2].'</td>
				<td>'.$t[3].'</td>
				<td>'.$t[4].'</td>
				<td>'.$t[5].'</td>
				</tr>';
			}
		}else{
			if($origem=='catalogo'){
				$bool_existe_linha=false;
				for($i=1;$i<=5;$i++){
					if($t[$i]>0){
						$qr="select t".$i." from pcp where ref=? and cor=? and loc='estoque' and t".$i.">0"; 
						$stmt=$dbh->prepare($qr);
						$stmt->execute(array($ref,$cor));
						if($stmt->rowCount()>0){
							$bool_existe_linha=true;
						}
					}
				}
				if($bool_existe_linha){
					$message .= '<tr>
					<td>'.$ref.'</td>
					<td>'.$cor.'</td>';
				}
			}
		for($i=1;$i<=5;$i++){
			if($t[$i]>0){
				//verificar se tem t[i] no estoque
				$qrest= "select t$i from pcp where ref='$ref' and cor='$cor' and loc='estoque' and situacao='S' and t$i>0";
				$sqlest = mysqli_query($con,$qrest);
				if(mysqli_num_rows($sqlest) > 0){//tem no estoque
					$lnti= mysqli_fetch_assoc($sqlest);
					if($t[$i]<=$lnti['t'.$i]){
					//tem todas as unidades no estoque
						//tirar qnt do estoque
						$difestped = $lnti['t'.$i]-$t[$i];
						$qr= "update pcp set t$i = $difestped where ref='$ref' and cor='$cor' and loc='estoque' and situacao='S'";
						$sql= mysqli_query($con,$qr);
						//atualizar tot do estoque
						$qr = "select * from pcp where loc= 'estoque' and ref='$ref' and cor='$cor' and situacao='S'";
						$sql = mysqli_query($con,$qr);
						$ln = mysqli_fetch_assoc($sql);
						$totne = $ln['t1']+$ln['t2']+$ln['t3']+$ln['t4']+$ln['t5'];
						//verificar linha de estoque ficou vazia
						if($totne == 0){
							//se ficou vazio deletar linha do estoque
							$qr = "delete from pcp where loc='estoque' and ref='$ref' and cor='$cor' and situacao='S'";
							$sql = mysqli_query($con,$qr);
						}else{
							$qr= "update pcp set tot=$totne where ref='$ref' and cor='$cor' and loc='estoque' and situacao='S'";
							$sql = mysqli_query($con,$qr);
						}
						//verificar se existe linha de pedido com S
						$qr = "select * from pcp where loc= '$pedido' and ref='$ref' and cor='$cor' and situacao='S'";
						$ver = mysqli_query($con,$qr);
						if(mysqli_num_rows($ver)<=0){
							//criar linha S do pedido
							$qr= "insert into pcp (ref,cor,t1,t2,t3,t4,t5,tot,loc,lote,dataalter,situacao,preco,desconto) values ('$ref','$cor',0,0,0,0,0,0,'$pedido',0,default,'S',$preco,$desconto)";
							$sql= mysqli_query($con,$qr);
						}
						//add unidade que estava no estoque na linha S do pedido
						$qr = "select * from pcp where loc= '$pedido' and ref='$ref' and cor='$cor' and situacao='S'";
						$sql= mysqli_query($con,$qr);
						$ln= mysqli_fetch_assoc($sql);
						$tinovo = $t[$i] + $ln['t'.$i];
						$qr= "update pcp set t$i=$tinovo where ref='$ref' and cor='$cor' and loc='$pedido' and situacao='S'";
						$sql = mysqli_query($con,$qr);
						//atualizar tot do estoque
						$qr = "select * from pcp where loc= '$pedido' and ref='$ref' and cor='$cor' and situacao='S'";
						$sql = mysqli_query($con,$qr);
						$ln = mysqli_fetch_assoc($sql);
						$tot = $ln['t1']+$ln['t2']+$ln['t3']+$ln['t4']+$ln['t5'];
						$qr= "update pcp set tot=$tot where ref='$ref' and cor='$cor' and loc='$pedido' and situacao='S'";
						$sql = mysqli_query($con,$qr);
						// dar echo na transa??o de estoque para pedido
						if($origem =='pcp'){
							echo 'Ref '.$ref.', '.$cor.': '.$t[$i];
							if($i == 1){echo ' P(ou 4)';} 
							elseif($i == 2){echo ' M(ou 6)';}
							elseif($i == 3){echo ' G(ou 8)';}
							elseif($i == 4){echo ' GG(ou 10)';}
							elseif($i == 5){echo ' EG(ou 12)';}
							echo ' do estoque - > Pedido '.$pedido.' </br>';
						}elseif($origem=='catalogo'){
							if($i <> 5){
								$message .= '<td>'.$t[$i].'</td>';
							}elseif($i==5){
								$message .= '<td>'.$t[5].'</td>
								</tr>';
							}		
						}	
					}else{
					//n?o tem o suficiente no estoque
						//tirar qnt do estoque
						$qr= "update pcp set t$i = 0 where ref='$ref' and cor='$cor' and loc='estoque' and situacao='S'";
						$sql= mysqli_query($con,$qr);
						//atualizar tot do estoque
						$qr = "select * from pcp where loc= 'estoque' and ref='$ref' and cor='$cor' and situacao='S'";
						$sql = mysqli_query($con,$qr);
						$ln = mysqli_fetch_assoc($sql);
						$totne = $ln['t1']+$ln['t2']+$ln['t3']+$ln['t4']+$ln['t5'];
						//verificar linha de estoque ficou vazia
						if($totne == 0){
							//se ficou vazio deletar linha do estoque
							$qr = "delete from pcp where loc='estoque' and ref='$ref' and cor='$cor' and situacao='S'";
							$sql = mysqli_query($con,$qr);
						}else{
							$qr= "update pcp set tot=$totne where ref='$ref' and cor='$cor' and loc='estoque' and situacao='S'";
							$sql = mysqli_query($con,$qr);
						}
						//verificar se existe linha de pedido com S
						$qr = "select * from pcp where loc= '$pedido' and ref='$ref' and cor='$cor' and situacao='S'";
						$ver = mysqli_query($con,$qr);
						if(mysqli_num_rows($ver)<=0){
							//criar linha S do pedido
							$qr= "insert into pcp (ref,cor,t1,t2,t3,t4,t5,tot,loc,lote,dataalter,situacao,preco,desconto) values ('$ref','$cor',0,0,0,0,0,0,'$pedido',0,default,'S',$preco,$desconto)";
							$sql= mysqli_query($con,$qr);
						}
						//add unidade que estava no estoque na linha S do pedido
						$qrselect = "select * from pcp where loc= '$pedido' and ref='$ref' and cor='$cor' and situacao='S'";
						$sql= mysqli_query($con,$qrselect);
						$ln= mysqli_fetch_assoc($sql);
						$tinovo = $lnti['t'.$i] + $ln['t'.$i];
						$qr= "update pcp set t$i=$tinovo where ref='$ref' and cor='$cor' and loc='$pedido' and situacao='S'";
						$sql = mysqli_query($con,$qr);
						//acertar tot na linha S do pedido
						$sql= mysqli_query($con,$qrselect);
						$ln = mysqli_fetch_assoc($sql);
						$totnovo = $ln['t1']+$ln['t2']+$ln['t3']+$ln['t4']+$ln['t5'];
						$qr= "update pcp set tot=$totnovo where ref='$ref' and cor='$cor' and loc='$pedido' and situacao='S'";
						$sql = mysqli_query($con,$qr);
						//verificar qnt que ainda falta programa??o
						$difaprog = $t[$i] - $lnti['t'.$i];
						// dar echo na transa??o de estoque para pedido
						if($origem=='pcp'){
							echo 'Ref '.$ref.', '.$cor.': '.$lnti['t'.$i];
							if($i == 1){echo ' P(ou 4)';} 
							elseif($i == 2){echo ' M(ou 6)';}
							elseif($i == 3){echo ' G(ou 8)';}
							elseif($i == 4){echo ' GG(ou 10)';}
							elseif($i == 5){echo ' EG(ou 12)';}
							echo ' do estoque - > Pedido '.$pedido.'; <span style="font-size:10pt"> Faltaram '.$difaprog.' pe?as.. </br></span>';
						}elseif($origem=='catalogo'){
							if($i <> 5){
								$message .= '<td>'.$lnti['t'.$i].'</td>';
							}elseif($i==5){
								$message .= '<td>'.$lnti['t'.$i].'</td>
								</tr>';
							}
						}
						
						//Verificar se existe linha linha A do pedido
						$qr = "select * from pcp where loc= '$pedido' and ref='$ref' and cor='$cor' and situacao='A'";
						$ver = mysqli_query($con,$qr);
						if(mysqli_num_rows($ver)<=0){
						//criar linha A do pedido
							$qr= "insert into pcp (ref,cor,t1,t2,t3,t4,t5,tot,loc,lote,dataalter,situacao,preco,desconto) values ('$ref','$cor',0,0,0,0,0,0,'$pedido',0,default,'A',$preco,$desconto)";
							$sql= mysqli_query($con,$qr);
						}
									
						//add unidade que faltou em A.
						$qr= "update pcp set t$i=$difaprog where ref='$ref' and cor='$cor' and loc='$pedido' and situacao='A'";
						$sql = mysqli_query($con,$qr);
						//atualizar tot da linha A
						$qr = "select * from pcp where loc= '$pedido' and ref='$ref' and cor='$cor' and situacao='A'";
						$sql = mysqli_query($con,$qr);
						$ln = mysqli_fetch_assoc($sql);
						$tot = $ln['t1']+$ln['t2']+$ln['t3']+$ln['t4']+$ln['t5'];
						$qr= "update pcp set tot=$tot where ref='$ref' and cor='$cor' and loc='$pedido' and situacao='A'";
						$sql = mysqli_query($con,$qr);										
					}	
				}else{//NÃO TEM NO ESTOQUE
					if($origem=='catalogo'){
						if($bool_existe_linha){
							$message .= '<td>0</td>';
							if($i==5){
								$message .= '</tr>';
							}
						}
					}
					//N?o tem nada no estoque -> 
					/*
					echo '<span style="font-size:10pt">  Ref '.$ref.', '.$cor.': ';
					if($i == 1){echo ' P(ou 4)';} 
					elseif($i == 2){echo ' M(ou 6)';}
					elseif($i == 3){echo ' G(ou 8)';}
					elseif($i == 4){echo ' GG(ou 10)';}
					elseif($i == 5){echo ' EG(ou 12)';}
					echo ' n?o possui no estoque </br></span>';
					*/
					//Verificar se tem linha de programa??o 
					while ($t[$i] > 0){
						$qr = "select pcp.ref,pcp.cor,pcp.t$i,pcp.loc,pcp.lote,pcp.situacao,p.dataentrega from pcp join pedidos p on pcp.loc = p.pedido
								where ref='$ref' and cor='$cor' and situacao='P' and p.dataentrega > '$dataentr' and t$i>0
								order by p.dataentrega desc,pcp.loc desc";
						$result = mysqli_query($con,$qr);
						if(mysqli_num_rows($result)>0){
							//existe programa??o com dataentrega maior que do pedido novo
							$lnprog=mysqli_fetch_assoc($result);
							$loteprog = $lnprog['lote'];
							$locprog = $lnprog['loc'];
							if($t[$i] <= $lnprog['t'.$i]){
								$qrselect = "select * from pcp where ref='$ref' and cor='$cor' and situacao='P' and lote=$loteprog and loc='$pedido'";
								$ver=mysqli_query($con,$qrselect);
								if(mysqli_num_rows($ver) <= 0){
									//n?o existe linha de programacao pra esta ref,cor,pedido ->inserindo
									$qr="insert into pcp (ref,cor,t1,t2,t3,t4,t5,tot,loc,lote,dataalter,situacao,preco,desconto) values ('$ref','$cor',0,0,0,0,0,0,'$pedido','$loteprog',default,'P',$preco,$desconto)";
									$sql=mysqli_query($con,$qr);
								}
								//atualizando linha nova de programa??o
								$sql=mysqli_query($con,$qrselect);
								$lnnovo = mysqli_fetch_assoc($sql);
								$tinovo = $lnnovo['t'.$i] + $t[$i];
								$qr = "update pcp set t$i = $tinovo where ref='$ref' and cor='$cor' and situacao='P' and lote=$loteprog and loc='$pedido'";
								$sql = mysqli_query($con,$qr);
								$sql=mysqli_query($con,$qrselect);
								$lnnovo = mysqli_fetch_assoc($sql);
								$totnovo = $lnnovo['t1']+$lnnovo['t2']+$lnnovo['t3']+$lnnovo['t4']+$lnnovo['t5'];
								$qr = "update pcp set tot = $totnovo where ref='$ref' and cor='$cor' and situacao='P' and lote=$loteprog and loc='$pedido'";
								$sql = mysqli_query($con,$qr);
								//Atualizando linha antiga de programacao
								$dif = $lnprog['t'.$i] - $t[$i];
								$qr = "update pcp set t$i = $dif where ref='$ref' and cor='$cor' and situacao='P' and lote=$loteprog and loc='$locprog'";
								$sql = mysqli_query($con,$qr);
								//atualizar tot
								$qr= "select * from pcp where ref='$ref' and cor='$cor' and situacao='P' and lote=$loteprog and loc='$locprog'";
								$sql = mysqli_query($con,$qr);
								$lnn = mysqli_fetch_assoc($sql);
								$totn = $lnn['t1']+$lnn['t2']+$lnn['t3']+$lnn['t4']+$lnn['t5'];
								if($totn == 0){
									$qr="delete from pcp where ref='$ref' and cor='$cor' and situacao='P' and lote=$loteprog and loc='$locprog'";
									$sql=mysqli_query($con,$qr);
								}else{
									$qr="update pcp set tot=$totn where ref='$ref' and cor='$cor' and situacao='P' and lote=$loteprog and loc='$locprog'";
									$sql=mysqli_query($con,$qr);
								}
								//atualizar linha A de prog anterior, se loc <> de 'estoque'
								if($locprog <> 'estoque'){
									$qr_locprog="select preco,desconto from pcp where ref='$ref' and loc='$locprog'";
									$sql_locprog=mysqli_query($con,$qr_locprog);
									if(mysqli_num_rows($sql_locprog)>0){
										$ln_locprog=mysqli_fetch_assoc($sql_locprog);
										$preco_locprog = $ln_locprog['preco'];
										$desconto_locprog = $ln_locprog['desconto'];
									}else{
										$preco_locprog=null;
										$desconto_locprog=null;
									}
												
									$qrselect = "select * from pcp where ref='$ref' and cor='$cor' and situacao='A' and loc='$locprog'";
									$ver=mysqli_query($con,$qrselect);
									if(mysqli_num_rows($ver) <= 0){
										$qr="insert into pcp (ref,cor,t1,t2,t3,t4,t5,tot,loc,lote,dataalter,situacao,preco,desconto) values ('$ref','$cor',0,0,0,0,0,0,'$locprog',0,default,'A',$preco_locprog,$desconto_locprog)";
										$sql=mysqli_query($con,$qr);
									}
									$sql=mysqli_query($con,$qrselect);
									$lnnovo = mysqli_fetch_assoc($sql);
									$tinovo = $lnnovo['t'.$i] + $t[$i];
									$qr = "update pcp set t$i = $tinovo where ref='$ref' and cor='$cor' and situacao='A' and loc='$locprog'";
									$sql = mysqli_query($con,$qr);
									$sql=mysqli_query($con,$qrselect);
									$lnnovo = mysqli_fetch_assoc($sql);
									$totnovo = $lnnovo['t1']+$lnnovo['t2']+$lnnovo['t3']+$lnnovo['t4']+$lnnovo['t5'];
									$qr = "update pcp set tot = $totnovo where ref='$ref' and cor='$cor' and situacao='A' and loc='$locprog'";
									$sql = mysqli_query($con,$qr);
								}
								$t[$i] = 0;
									
							}else{
								//$t[$i] > $lnprog
								$qrselect = "select * from pcp where ref='$ref' and cor='$cor' and situacao='P' and lote=$loteprog and loc='$pedido'";
								$ver=mysqli_query($con,$qrselect);
								if(mysqli_num_rows($ver) <= 0){
									//n?o existe linha de programacao pra esta ref,cor,pedido ->inserindo
									$qr="insert into pcp (ref,cor,t1,t2,t3,t4,t5,tot,loc,lote,dataalter,situacao,preco,desconto) values ('$ref','$cor',0,0,0,0,0,0,'$pedido',$loteprog,default,'P',$preco,$desconto)";
									$sql=mysqli_query($con,$qr);
								}
								//atualizando linha nova de programa??o
								$sql=mysqli_query($con,$qrselect);
								$lnnovo = mysqli_fetch_assoc($sql);
								$tinovo = $lnnovo['t'.$i] + $lnprog['t'.$i];
								$qr = "update pcp set t$i = $tinovo where ref='$ref' and cor='$cor' and situacao='P' and lote=$loteprog and loc='$pedido'";
								$sql = mysqli_query($con,$qr);
								$sql=mysqli_query($con,$qrselect);
								$lnnovo = mysqli_fetch_assoc($sql);
								$totnovo = $lnnovo['t1']+$lnnovo['t2']+$lnnovo['t3']+$lnnovo['t4']+$lnnovo['t5'];
								$qr = "update pcp set tot = $totnovo where ref='$ref' and cor='$cor' and situacao='P' and lote=$loteprog and loc='$pedido'";
								$sql = mysqli_query($con,$qr);
								//Atualizando linha antiga de programacao
								$qr = "update pcp set t$i = 0 where ref='$ref' and cor='$cor' and situacao='P' and lote=$loteprog and loc='$locprog'";
								$sql = mysqli_query($con,$qr);
								//atualizar tot
								$qr= "select * from pcp where ref='$ref' and cor='$cor' and situacao='P' and lote=$loteprog and loc='$locprog'";
								$sql = mysqli_query($con,$qr);
								$lnn = mysqli_fetch_assoc($sql);
								$totn = $lnn['t1']+$lnn['t2']+$lnn['t3']+$lnn['t4']+$lnn['t5'];
								if($totn == 0){
									$qr="delete from pcp where ref='$ref' and cor='$cor' and situacao='P' and lote=$loteprog and loc='$locprog'";
									$sql=mysqli_query($con,$qr);
								}else{
									$qr="update pcp set tot=$totn where ref='$ref' and cor='$cor' and situacao='P' and lote=$loteprog and loc='$locprog'";
									$sql=mysqli_query($con,$qr);
								}
								//atualizar linha A de prog anterior, se loc <> 'estoque'
								if($locprog <> 'estoque'){
									$qr_locprog="select preco,desconto from pcp where ref='$ref' and cor='$cor' and loc='$locprog'";
									$sql_locprog=mysqli_query($con,$qr_locprog);
									if(mysqli_num_rows($sql_locprog)>0){
										$ln_locprog=mysqli_fetch_assoc($sql_locprog);
										$preco_locprog = $ln_locprog['preco'];
										$desconto_locprog = $ln_locprog['desconto'];
									}else{
										$preco_locprog = null;
										$desconto_locprog = null;
									}
									
									$qrselect = "select * from pcp where ref='$ref' and cor='$cor' and situacao='A' and loc='$locprog'";
									$ver=mysqli_query($con,$qrselect);
									if(mysqli_num_rows($ver) <= 0){
										$qr="insert into pcp (ref,cor,t1,t2,t3,t4,t5,tot,loc,lote,dataalter,situacao,preco,desconto) values ('$ref','$cor',0,0,0,0,0,0,'$locprog',0,default,'A',$preco_locprog,$desconto_locprog)";
										$sql=mysqli_query($con,$qr);
									}
									$sql=mysqli_query($con,$qrselect);
									$lnnovo = mysqli_fetch_assoc($sql);
									$tinovo = $lnnovo['t'.$i] + $lnprog['t'.$i];
									$qr = "update pcp set t$i = $tinovo where ref='$ref' and cor='$cor' and situacao='A' and loc='$locprog'";
									$sql = mysqli_query($con,$qr);
									$sql=mysqli_query($con,$qrselect);
									$lnnovo = mysqli_fetch_assoc($sql);
									$totnovo = $lnnovo['t1']+$lnnovo['t2']+$lnnovo['t3']+$lnnovo['t4']+$lnnovo['t5'];
									$qr = "update pcp set tot = $totnovo where ref='$ref' and cor='$cor' and situacao='A' and loc='$locprog'";
									$sql = mysqli_query($con,$qr);
								}
								$t[$i] -= $lnprog['t'.$i];
							}	
						}else{
							//N?o existe programa??o pra pedido com data maior que pedido novo
							$qrselect = "select * from pcp where loc= '$pedido' and ref='$ref' and cor='$cor' and situacao='A'";
							$ver = mysqli_query($con,$qrselect);
							if(mysqli_num_rows($ver)<=0){
								//criar linha A do pedido
								$qr= "insert into pcp (ref,cor,t1,t2,t3,t4,t5,tot,loc,lote,dataalter,situacao,preco,desconto) values ('$ref','$cor',0,0,0,0,0,0,'$pedido',0,default,'A',$preco,$desconto)";
								$sql= mysqli_query($con,$qr);
							}
							//add unidade que faltou em A.
							$qr= "update pcp set t$i=$t[$i] where ref='$ref' and cor='$cor' and loc='$pedido' and situacao='A'";
							$sql = mysqli_query($con,$qr);
							//atualizar tot da linha A
							$sql = mysqli_query($con,$qrselect);
							$ln = mysqli_fetch_assoc($sql);
							$tot = $ln['t1']+$ln['t2']+$ln['t3']+$ln['t4']+$ln['t5'];
							$qr= "update pcp set tot=$tot where ref='$ref' and cor='$cor' and loc='$pedido' and situacao='A'";
							$sql = mysqli_query($con,$qr);		
							$t[$i] = 0;
						}
					}
				}
			}else{
				if($origem=='catalogo'){
					$message .= '<td>0</td>';
					if($i==5){
						$message .= '</tr>';
					}
						
				}
			}
		}
		}
	}				
}
if($origem=='catalogo'){
	$message .='	
		</tbody>
		</table>
		</body>
		</html>
	';
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "From: Rolf Modas <rolf@rolfmodas.com.br>" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	//$to = 'breno.oliveira.ufv@gmail.com';
	$to = 'rolf@rolfmodas.com.br';
	$subject = 'Itens separados no pedido <b>'.$pedido.'</b>';
	
	//var_dump($message);
	mail($to,$subject,$message,$headers);

	//fim do mandar email
}
if(isset($_SESSION['pedido_ped'])){unset($_SESSION['pedido_ped']);}

