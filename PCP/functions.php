
<?php
function gerar_prod($dbh,$ref,$cor,$t,$boolprod){
	$tot= array_sum($t);
	//Verificações
	require '../_auxiliares/teste_produto_e_cor.php';
	
	if($tot<=0){
		echo"<script language='javascript' type='text/javascript'>alert('Não foi informado quantidade!');</script>";
		die();
	}

	$tem_sort=false;//variavel que verifica se tem pedido da ref sortido.
	
	//Pegar local no estoque
	$stmt=$dbh->prepare("select loc_estoque from produtos where ref=?");
	$stmt->execute(array($ref));
	$ln=$stmt->fetch(PDO::FETCH_ASSOC);
	$localEstoque=$ln['loc_estoque'];
	
	if($boolprod == 'Gerar produção'){
		//registra produção
		$stmt=$dbh->prepare("select tempo from produtos where ref=?");
		$stmt->execute(array($ref));
		$ln=$stmt->fetchAll();
		$tempotot = $ln[0]['tempo']*$tot;
		$stmt=$dbh->prepare("insert into medprod values (?,?,?,default)");
		$stmt->execute(array($ref,$tot,$tempotot));
	}
	
	if($cor == 'SORTIDO'){
		echo '</br>Cor "Sortido" no '.$localEstoque.': não foi pra nenhum pedido. Verificar pedidos com opções sortidas posteriormente.'; 
		//verificar se existe linha de estoque.
		$stmt=$dbh->prepare("select * from pcp where ref=? and cor=? and loc='estoque' and situacao='S'");
		$stmt->execute(array($ref,$cor));
		$verexist=$stmt->rowCount();
		
		if($verexist <= 0){
			$stmt=$dbh->prepare("select preco from produtos where ref=?");
			$stmt->execute(array($ref));
			$ln=$stmt->fetch(PDO::FETCH_ASSOC);
			$precolnest=$ln['preco'];	
			$descontolnest=0;
			//Não tem linha de estoque-> insere linha de estoque
			$stmt=$dbh->prepare("insert into pcp (ref,cor,t1,t2,t3,t4,t5,tot,loc,lote,dataalter,situacao,preco,desconto) values (?,?,0,0,0,0,0,0,'estoque',0,default,'S',?,?)");
			$stmt->execute(array($ref,$cor,$precolnest,$descontolnest));
		}
		//adiciona a linha de estoque
		$stmt=$dbh->prepare("select * from pcp where ref=? and cor=? and loc='estoque' and situacao='S'");
		$stmt->execute(array($ref,$cor));
		$ln=$stmt->fetch(PDO::FETCH_ASSOC);
		$lt1novo = $ln['t1'] + $t[1];
		$lt2novo = $ln['t2'] + $t[2];
		$lt3novo = $ln['t3'] + $t[3];
		$lt4novo = $ln['t4'] + $t[4];
		$lt5novo = $ln['t5'] + $t[5];
		$totn = $lt1novo + $lt2novo  + $lt3novo  + $lt4novo  + $lt5novo;
		$stmt=$dbh->prepare("update pcp set t1=?,t2=?,t3=?,t4=?,t5=?,tot=? where ref=? and cor=? and loc='estoque' and situacao='S'");
		$stmt->execute(array($lt1novo,$lt2novo,$lt3novo,$lt4novo,$lt5novo,$totn,$ref,$cor));		
	}else{
		echo "</br>Separe as peças de acordo com as instruções abaixo:</br>$ref - $cor</br></br>";
		//for para t[i]
		for($i=1;$i<=5;$i++){
			//verifica se t[i] é maior que zero
			while ($t[$i] >0){
				//verifica se tam[i] tem pedido ou lote (P no estoque)
				$stmt=$dbh->prepare("select loc,t$i from pcp where ref=? and cor=? and (situacao = 'A' or situacao='P') and t$i > 0");
				$stmt->execute(array($ref,$cor));
				$ver=$stmt->rowCount();
				if($ver <= 0){
					//T[i] não tem pedido nem programação; verificar se existe linha de estoque separado.
					$stmt=$dbh->prepare("select * from pcp where ref=? and cor=? and loc='estoque' and situacao='S'");
					$stmt->execute(array($ref,$cor));
					$verexist=$stmt->rowCount();
					
					if($verexist <= 0){
						$stmt=$dbh->prepare("select preco from produtos where ref=?");
						$stmt->execute(array($ref));
						$ln=$stmt->fetch(PDO::FETCH_ASSOC);
						$precolnest=$ln['preco'];	
						$descontolnest=0;
						//Não tem linha de estoque-> insere linha de estoque
						$stmt=$dbh->prepare("insert into pcp (ref,cor,t1,t2,t3,t4,t5,tot,loc,lote,dataalter,situacao,preco,desconto) values (?,?,0,0,0,0,0,0,'estoque',0,default,'S',?,?)");
						$stmt->execute(array($ref,$cor,$precolnest,$descontolnest));
					}
					//adiciona a linha de estoque
					$stmt=$dbh->prepare("select * from pcp where ref=? and cor=? and loc='estoque' and situacao='S'");
					$stmt->execute(array($ref,$cor));
					$ln=$stmt->fetch(PDO::FETCH_ASSOC);
					$ltinovo = $ln['t'.$i] + $t[$i];
					$totn = $ln['t1'] + $ln['t2'] +$ln['t3'] +$ln['t4'] +$ln['t5'] + $t[$i];
					$stmt=$dbh->prepare("update pcp set t$i=?,tot=? where ref=? and cor=? and loc='estoque' and situacao='S'");
					$stmt->execute(array($ltinovo,$totn,$ref,$cor));
					
					//echo $t[$i];
					$tamanho=Tamanho($ref,$i);
					/*echo ' Tam:'.$tamanho; 
					if($i == 1){echo ' Tam P <span style="font-size:10pt">ou 4</span>';} 
					elseif($i == 2){echo ' M <span style="font-size:10pt">ou 6</span>';}
					elseif($i == 3){echo ' G <span style="font-size:10pt">ou 8</span>';}
					elseif($i == 4){echo ' GG <span style="font-size:10pt">ou 10</span>';}
					elseif($i == 5){echo ' EG <span style="font-size:10pt">ou 12</span>';}
					echo ' - > ESTOQUE: '.$localEstoque.'</br>';*/
					
					//Verificar se o que foi para o estoque poderia ter ido para um sortido..
					$qr= 'select * from pcp where ref=? and cor=? and loc <> ? and (situacao=? or situacao=?)';
					$values=array($ref,'sortido','estoque','P','A');
					if(seQrExiste($dbh,$qr,$values)){
						$tem_sort = true;
					}
					
					if(isset($array['Estoque'][$i])){$array['Estoque'][$i] += $t[$i];}else{$array['Estoque'][$i] = $t[$i];}
					$array['Estoque'][6] = '2099-12-31';
					$array['Estoque'][7] = 'Estoque';
					$t[$i] = 0;
					
				}else{
					//Tem pedido!	
					//pegar pedido com menor data de entrega
					$stmt=$dbh->prepare("select p.pedido,p.dataentrega 
					from pcp join pedidos p
					on p.pedido = pcp.loc 
					where ref=? and cor=? and (situacao = 'A' or situacao='P') and t$i>0 
					order by p.dataentrega,p.pedido");
					$stmt->execute(array($ref,$cor));
					$ln=$stmt->fetch(PDO::FETCH_ASSOC);
					$pedido = $ln['pedido'];
					$stmt=$dbh->prepare("select preco,desconto from pcp where loc=? and ref=?");
					$stmt->execute(array($pedido,$ref));
					$ln=$stmt->fetch(PDO::FETCH_ASSOC);
					$precoped=$ln['preco'];	
					$descontoped=$ln['desconto'];
					/*if(!isset($precoped)){
						$stmt=$dbh->prepare("select preco from produtos where ref=?");
						$stmt->execute(array($ref));
						$ln=$stmt->fetch(PDO::FETCH_ASSOC);
						$precoped=$ln['preco'];	
						$descontoped=0;
					}*/
					//Verifica primeiro se tem situacao = P, se não tiver será situacao = A..
					$stmt=$dbh->prepare("select * from pcp where ref=? and cor=? and loc=? and situacao='P' and t$i>0");
					$stmt->execute(array($ref,$cor,$pedido));
					$ver=$stmt->rowCount();
					if($ver > 0){
						//tem linha com P..
						$sitpramod = 'P';
					}else{
						//não tem linha com P, portanto linha é com A..(visto que existe necessidade neste pedido..)
						$sitpramod = 'A';								
					}
					$stmt=$dbh->prepare("select * from pcp where ref=? and cor=? and loc=? and situacao=? and t$i>0");
					$stmt->execute(array($ref,$cor,$pedido,$sitpramod));
					$ln=$stmt->fetch(PDO::FETCH_ASSOC);
					if($t[$i] <= $ln['t'.$i]){
						//produção menor ou igual que linha A ou S do pedido
						/*echo $t[$i];
						if($i == 1){echo ' P <span style="font-size:10pt">ou 4</span>';} 
						elseif($i == 2){echo ' M <span style="font-size:10pt">ou 6</span>';}
						elseif($i == 3){echo ' G <span style="font-size:10pt">ou 8</span>';}
						elseif($i == 4){echo ' GG <span style="font-size:10pt">ou 10</span>';}
						elseif($i == 5){echo ' EG <span style="font-size:10pt">ou 12</span>';}*/
						if($pedido <> 'estoque'){
							//echo ' - > Pedido '.$pedido.'</br>';
							if(isset($array[$pedido][$i])){$array[$pedido][$i] += $t[$i];}else{$array[$pedido][$i] = $t[$i];}
							$stmt=$dbh->prepare("select dataentrega from pedidos where pedido=?");
							$stmt->execute(array($pedido));
							$lndatentr=$stmt->fetch(PDO::FETCH_ASSOC);
							$array[$pedido][6] = $lndatentr['dataentrega'];
							$array[$pedido][7] = $pedido;
						}else{ 
							//echo ' - > ESTOQUE: '.$localEstoque.'</br>';
							if(isset($array['Estoque'][$i])){$array['Estoque'][$i] += $t[$i];}else{$array['Estoque'][$i] = $t[$i];}
							$array['Estoque'][6] = '2099-12-31';
							$array['Estoque'][7] = 'Estoque';

							//Verificar se o que foi para o estoque poderia ter ido para um sortido..
							$qr= 'select * from pcp where ref=? and cor=? and loc <> ? and (situacao=? or situacao=?)';
							$values=array($ref,'sortido','estoque','P','A');
							if(seQrExiste($dbh,$qr,$values)){
								$tem_sort = true;
							}
						}
						//atualizar linha de A ou P do pedido
						$dif= $ln['t'.$i] - $t[$i];
						$lote=$ln['lote'];
						$stmt=$dbh->prepare("update pcp set t$i=? where ref=? and cor=? and loc= ? and situacao = ? and t$i>0 and lote=?");
						$stmt->execute(array($dif,$ref,$cor,$pedido,$sitpramod,$lote));
						//atualizar tot da situacao A ou P do pedido
						$stmt=$dbh->prepare("select * from pcp where loc= ? and ref=? and cor=? and situacao = ? and lote=?");
						$stmt->execute(array($pedido,$ref,$cor,$sitpramod,$lote));
						$lna=$stmt->fetch(PDO::FETCH_ASSOC);
						$totna = $lna['t1']+$lna['t2']+$lna['t3']+$lna['t4']+$lna['t5'];
						if($totna == 0){
							$stmt=$dbh->prepare("delete from pcp where loc= ? and ref=? and cor=? and situacao = ? and lote=?");
							$stmt->execute(array($pedido,$ref,$cor,$sitpramod,$lote));
						}else{
							$stmt=$dbh->prepare("update pcp set tot=? where ref=? and cor=? and loc=? and situacao = ? and lote=?");
							$stmt->execute(array($totna,$ref,$cor,$pedido,$sitpramod,$lote));
						}
						//verificar se existe linha S do pedido
						$stmt=$dbh->prepare("select * from pcp where loc= ? and ref=? and cor=? and situacao='S'");
						$stmt->execute(array($pedido,$ref,$cor));
						$ver=$stmt->rowCount();
						if($ver <= 0){
							//se linha não existir, criar linha
							$stmt=$dbh->prepare("insert into pcp (ref,cor,t1,t2,t3,t4,t5,tot,loc,lote,dataalter,situacao,preco,desconto) values (?,?,0,0,0,0,0,0,?,0,default,'S',?,?)");
							$stmt->execute(array($ref,$cor,$pedido,$precoped,$descontoped));
						}
						//atualizar t$i da linha S
						$stmt=$dbh->prepare("select * from pcp where loc=? and ref=? and cor=? and situacao='S'");
						$stmt->execute(array($pedido,$ref,$cor));
						$ver=$stmt->rowCount();
						$lnver =$stmt->fetch(PDO::FETCH_ASSOC);
						$tatualsep = $lnver['t'.$i];
						$tnovo = $tatualsep + $t[$i];
						$stmt=$dbh->prepare("update pcp set t$i=? where loc=? and ref=? and cor=? and situacao='S'");
						$stmt->execute(array($tnovo,$pedido,$ref,$cor));
						//atualizar tot da linha S
						$stmt=$dbh->prepare("select * from pcp where loc=? and ref=? and cor=? and situacao='S'");
						$stmt->execute(array($pedido,$ref,$cor));
						$lns = $stmt->fetch(PDO::FETCH_ASSOC);
						$totns = $lns['t1']+$lns['t2']+$lns['t3']+$lns['t4']+$lns['t5'];
						$stmt=$dbh->prepare("update pcp set tot=? where ref=? and cor=? and loc=? and situacao='S'");
						$stmt->execute(array($totns,$ref,$cor,$pedido));
						//Verificar se pedido ficou completo
						$stmt=$dbh->prepare("select situacao from pcp where loc=? and (situacao  ='A' or situacao='P')");
						$stmt->execute(array($pedido));
						$ver=$stmt->rowCount();
						if(($ver<=0)and($pedido <> 'estoque')){
							echo '</br>Pedido '.$pedido.' completo!!! Favor conferir!</br></br>'; 
						}
						//Finalizar while
						break;
					}else{
						//Produção maior que pedido
					/*	echo  $ln['t'.$i];
						if($i == 1){echo ' P <span style="font-size:10pt">ou 4</span>';} 
						elseif($i == 2){echo ' M <span style="font-size:10pt">ou 6</span>';}
						elseif($i == 3){echo ' G <span style="font-size:10pt">ou 8</span>';}
						elseif($i == 4){echo ' GG <span style="font-size:10pt">ou 10</span>';}
						elseif($i == 5){echo ' EG <span style="font-size:10pt">ou 12</span>';}*/
						if($pedido <> 'estoque'){
							//echo ' - > Pedido '.$pedido.'</br>';
							if(isset($array[$pedido][$i])){$array[$pedido][$i] += $ln['t'.$i];}else{$array[$pedido][$i] = $ln['t'.$i];}
							$stmt=$dbh->prepare("select dataentrega from pedidos where pedido=?");
							$stmt->execute(array($pedido));
							$lndatentr=$stmt->fetch(PDO::FETCH_ASSOC);
							$array[$pedido][6] = $lndatentr['dataentrega'];
							$array[$pedido][7] = $pedido;
						}
						else{
							//echo ' - > ESTOQUE: '.$localEstoque.'</br>';
							if(isset($array['Estoque'][$i])){$array['Estoque'][$i] += $ln['t'.$i];}else{$array['Estoque'][$i] = $ln['t'.$i];}	
							$array['Estoque'][6] = '2099-12-31';
							$array['Estoque'][7] = 'Estoque';

							//Verificar se o que foi para o estoque poderia ter ido para um sortido..
							$qr= 'select * from pcp where ref=? and cor=? and loc <> ? and (situacao=? or situacao=?)';
							$values=array($ref,'sortido','estoque','P','A');
							if(seQrExiste($dbh,$qr,$values)){
								$tem_sort = true;
							}
						}
						$tatuala = $ln['t'.$i];
						//pegar lote pra trabalhar com apenas 1 lote
						$lote = $ln['lote'];
						//atualizar linha de A ou P do pedido
						$stmt=$dbh->prepare("update pcp set t$i=0 where ref=? and cor=? and loc = ? and situacao =? and t$i>0 and lote=?");
						$stmt->execute(array($ref,$cor,$pedido,$sitpramod,$lote));
						//atualizar tot da situacao A ou P do pedido
						$stmt=$dbh->prepare("select * from pcp where loc=? and ref=? and cor=? and situacao = ? and lote=?");
						$stmt->execute(array($pedido,$ref,$cor,$sitpramod,$lote));
						$lna = $stmt->fetch(PDO::FETCH_ASSOC);
						$totna = $lna['t1']+$lna['t2']+$lna['t3']+$lna['t4']+$lna['t5'];
						//verificar se situacao A do pedido ficou vazia
						if($totna == 0){
							$stmt=$dbh->prepare("delete from pcp where loc=? and ref=? and cor=? and situacao = ?  and lote=?");
							$stmt->execute(array($pedido,$ref,$cor,$sitpramod,$lote));
						}
						else{ 
							$stmt=$dbh->prepare("update pcp set tot=? where ref=? and cor=? and loc=? and situacao = ?  and lote=?");
							$stmt->execute(array($totna,$ref,$cor,$pedido,$sitpramod,$lote));
						}
						//verificar se existe linha S do pedido
						$stmt=$dbh->prepare("select * from pcp where loc=? and ref=? and cor=? and situacao='S'");
						$stmt->execute(array($pedido,$ref,$cor));
						$ver=$stmt->rowCount();
						if($ver <= 0){
							//se linha não existir, criar linha
							$stmt=$dbh->prepare("insert into pcp (ref,cor,t1,t2,t3,t4,t5,tot,loc,lote,dataalter,situacao,preco,desconto) values (?,?,0,0,0,0,0,0,?,0,default,'S',?,?)");
							$stmt->execute(array($ref,$cor,$pedido,$precoped,$descontoped));
						}
						//atualizar t$i da linha S
						$stmt=$dbh->prepare("select * from pcp where loc=? and ref=? and cor=? and situacao='S'");
						$stmt->execute(array($pedido,$ref,$cor));
						$lnver =$stmt->fetch(PDO::FETCH_ASSOC);
						$tatualsep = $lnver['t'.$i];
						$tnovo = $tatualsep + $tatuala;
						$stmt=$dbh->prepare("update pcp set t$i=? where loc= ? and ref=? and cor=? and situacao='S'");
						$stmt->execute(array($tnovo,$pedido,$ref,$cor));
						//atualizar tot da linha S
						$stmt=$dbh->prepare("select * from pcp where loc=? and ref=? and cor=? and situacao='S'");
						$stmt->execute(array($pedido,$ref,$cor));
						$lns = $stmt->fetch(PDO::FETCH_ASSOC);
						$totns = $lns['t1']+$lns['t2']+$lns['t3']+$lns['t4']+$lns['t5'];
						$stmt=$dbh->prepare("update pcp set tot=? where ref=? and cor=? and loc=? and situacao='S'");
						$stmt->execute(array($totns,$ref,$cor,$pedido));
						//Verificar se pedido ficou completo
						$stmt=$dbh->prepare("select situacao from pcp where loc=? and (situacao  ='A' or situacao='P')");
						$stmt->execute(array($pedido));
						$ver=$stmt->rowCount();
						if(($ver<=0)and($pedido <> 'estoque')){
							echo '</br>Pedido '.$pedido.' completo!!! Favor conferir!</br></br>'; 
						}
						//modificar ti para talvez finalizar while
						$t[$i] = $t[$i] - $ln['t'.$i];
					}		
				}
			}	
		}
		ksort($array);
		/*echo '</br>';
		foreach($array as $ln){
		if($ln[7] <> 'Estoque'){ echo 'Pedido ';}
			echo $ln[7].': P <span style="font-size:10pt">ou 4</span>: ';
			if(isset($ln[1])){echo $ln[1].' / ';}else{echo '0 / ';}
			echo 'M <span style="font-size:10pt">ou 6</span>: ';
			if(isset($ln[2])){echo $ln[2].' / ';}else{echo '0 / ';}
			echo 'G <span style="font-size:10pt">ou 8</span>: ';
			if(isset($ln[3])){echo $ln[3].' / ';}else{echo '0 / ';}
			echo 'GG <span style="font-size:10pt">ou 10</span>: ';
			if(isset($ln[4])){echo $ln[4].' / ';}else{echo '0 / ';}
			echo 'EG <span style="font-size:10pt">ou 12</span>: ';
			if(isset($ln[5])){echo $ln[5];}else{echo '0';}
		
			echo '</br></br>';
		}*/
		echo'<table>
				<tr>
					<td>Local</td>
					<td>P <span style="font-size:10pt">ou 4</span></td>
					<td>M <span style="font-size:10pt">ou 6</span></td>
					<td>G <span style="font-size:10pt">ou 8</span></td>
					<td>GG <span style="font-size:10pt">ou 10</span></td>
					<td>EG <span style="font-size:10pt">ou 12</span></td>
				</tr>';
		foreach($array as $ln){
		echo '<tr>';
		if($ln[7] <> 'Estoque'){ echo '<td>Pedido:'.$ln[7].'</td> ';}else{echo '<td>Estoque:'.$localEstoque.'</td>';}
		
		echo '<td>'; if(isset($ln[1])){echo $ln[1];}else{echo '0';} echo '</td>';
		
		echo '<td>'; if(isset($ln[2])){echo $ln[2];}else{echo '0';} echo '</td>';
		
		echo '<td>'; if(isset($ln[3])){echo $ln[3];}else{echo '0';} echo '</td>';
		
		echo '<td>'; if(isset($ln[4])){echo $ln[4];}else{echo '0';} echo '</td>';
		
		echo '<td>'; if(isset($ln[5])){echo $ln[5];}else{echo '0';} echo '</td>';
		/*if(isset($ln[6])){echo $ln[6];}*/
		echo '</tr>';
		}
		echo '</table>';
		if($tem_sort){echo '</br><span style="color:red"><b>Essa Referência tem pedido de sortido!</b></span>';}


		/*echo '<table>
				<tr>
					<td>Local</td>
					<td>P <span style="font-size:10pt">ou 4</span></td>
					<td>M <span style="font-size:10pt">ou 6</span></td>
					<td>G <span style="font-size:10pt">ou 8</span></td>
					<td>GG <span style="font-size:10pt">ou 10</span></td>
					<td>EG <span style="font-size:10pt">ou 12</span></td>
				</tr>
				<tr>';
				if ($sit == 'E'){
				  echo '<td>Estoque</td>
						<td>'.$t1.'</td>
						<td>'.$t2.'</td>
						<td>'.$t3.'</td>
						<td>'.$t4.'</td>
						<td>'.$t5.'</td>
				';}
		   echo'</tr>	
				<tr>
					<td>'.$ref.'</td>
					<td>'.$t1.'</td>
					<td>'.$t2.'</td>
					<td>'.$t3.'</td>
					<td>'.$t4.'</td>
					<td>'.$t5.'</td>
				</tr>
			</table>';
			*/
	}
}
function total_pedidope($dbh){
	$total=0;
	if(isset($_SESSION['pedidope'])){
		$refs=array_keys($_SESSION['pedidope']);
		$fator=1;
		foreach($refs as $ref){
		
			$stmt=$dbh->prepare("select preco from produtos where ref=?");
			$stmt->execute(array($ref));
			$ln=$stmt->fetch(PDO::FETCH_ASSOC);
			
			if(isset($_SESSION['pedidope'][$ref]['desconto'])){
				$desconto = $_SESSION['pedidope'][$ref]['desconto'];			
				$fator= (1-$desconto/100);
			}
			$precoat = round($ln['preco']*$_SESSION['markup']*$fator,1);
			$cores=array_keys($_SESSION['pedidope'][$ref]);
			foreach($cores as $cor){
				if($cor<>'desconto'){
					$somacorref=array_sum($_SESSION['pedidope'][$ref][$cor])*$precoat;
					$total += $somacorref;
				}
			}
		}	
	}
	return $total;
}
function total_pedido_ped($dbh){
	$total=0;
	if(isset($_SESSION['pedido_ped'])){
		$refs=array_keys($_SESSION['pedido_ped']);
		$fator=1;
		foreach($refs as $ref){
		
			$stmt=$dbh->prepare("select preco from produtos where ref=?");
			$stmt->execute(array($ref));
			$ln=$stmt->fetch(PDO::FETCH_ASSOC);
			
			if(isset($_SESSION['pedido_ped'][$ref]['desconto'])){
				$desconto = $_SESSION['pedido_ped'][$ref]['desconto'];			
				$fator= (1-$desconto/100);
			}
			$precoat = round($ln['preco']*$_SESSION['markup']*$fator,1);
			$cores=array_keys($_SESSION['pedido_ped'][$ref]);
			foreach($cores as $cor){
				if($cor<>'desconto'){
					$somacorref=array_sum($_SESSION['pedido_ped'][$ref][$cor])*$precoat;
					$total += $somacorref;
				}
			}
		}	
	}
	return $total;
}
function nome_situacao($sit){
	if($sit=='P'){$situacao='Programado';}
	if($sit=='S'){$situacao='Separado';}
	if($sit=='A'){$situacao='Aguard. Programa';}
	if($sit=='E'){$situacao='Faturado';}
	if($sit=='C'){$situacao='Cancelado';}
	if($sit=='O'){$situacao='Consignado';}
	if($sit=='D'){$situacao='Devolvido';}
	return $situacao;
}
function valor_pedido($pedido,$dbh,$sit){
	$stmt=$dbh->prepare("select sum(tot*(preco*(1-desconto/100))) as soma_pcp from pcp where loc=? and situacao=?;");
	$stmt->execute(array($pedido,$sit));
	$ln=$stmt->fetch(PDO::FETCH_ASSOC);
	$soma_pcp=$ln['soma_pcp'];
	$stmt=$dbh->prepare("select desconto_pedido,bonus_utilizado,frete from pedidos where pedido=?");
	$stmt->execute(array($pedido));
	$ln=$stmt->fetch(PDO::FETCH_ASSOC);
	$desconto_pedido=$ln['desconto_pedido']+$ln['bonus_utilizado'];
	$valor_tot=$soma_pcp-$desconto_pedido+$ln['frete'];
	return($valor_tot);
}
function valor_produtos($pedido,$dbh,$sit){
	$stmt=$dbh->prepare("select sum(tot*(preco*(1-desconto/100))) as soma_pcp from pcp where loc=? and situacao=?;");
	$stmt->execute(array($pedido,$sit));
	$ln=$stmt->fetch(PDO::FETCH_ASSOC);
	$valor_tot=$ln['soma_pcp'];
	return($valor_tot);
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
function pedido_echo($pedido){
	if(strpos($pedido,'-')!==false){
		$pedido_echo=explode('-',$pedido);
		$aux=$pedido_echo[1]+0;
		$pedido_echo=$pedido_echo[0].'-'.$aux;
		return ($pedido_echo);
		die;
	}else{
		return($pedido);
	}
}
function check_zeros_pedido($pedido){
	if(strpos($pedido,'-') !== false){
		$arr_exp=explode('-',$pedido);
		$pedido_comp=$arr_exp[1];
		while(strlen($pedido_comp)<9){
			$pedido_comp= '0'.$pedido_comp;
		}
		$pedido=$arr_exp[0].'-'.$pedido_comp;
		return($pedido);
	}else{
		return($pedido);
	}
}
function testInf($ref){
	$testinf = substr($ref,-1);
	if($testinf == "I"){
		return true;
	}else{
		return false;
	}	
}
function Tamanho($ref,$i){
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