<?php
$stmt=$dbh->prepare("select distinct(secao) from pcp
	join produtos on pcp.ref=produtos.ref
	where pcp.situacao='S' and pcp.loc='estoque' and produtos.Tags not like '%oculto%'
    order by secao");
	$stmt->execute();
echo '<nav id="'.$inim.'menu_catalog">';
	$w=0;
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		$w++;
		//var_dump($row);
		$num_grupos=0;
		foreach($nome_grupos as $grupo){
			$stmt2=$dbh->prepare("select distinct(p.Tags) from pcp
								join produtos p on pcp.ref=p.ref
								where Tags like ? and p.secao='".$row['secao']."' and pcp.situacao='S' and pcp.loc='estoque' and Tags not like '%oculto%'");
			$stmt2->execute(array("%".$grupo."%"));
			$num_rows=$stmt2->rowCount();
			if($num_rows>=1){
				$num_grupos++;
			}
		}
		if($num_grupos > 1){
			echo '<div class="'.$inim.'hoverDrop"';
			if($inim==''){echo 'onmouseover="hoverDrop('."'".$w."','".$inim."'".')" onmouseout="hDout('."'".$w."','".$inim."'".')">';}
			else{echo 'onclick="lat_hoverDrop('."'".$w."','".$inim."'".')">';}
			
			if($inim==''){echo '<a class="a-black" href="catalogo.php?secao='.$row['secao'].'#anchor">';}
			else{echo '<span class="span-black">';}

			

			echo strtoupper($row['secao']);

			if($inim==''){echo '</a>';}
			else{echo '</span>';}			

			echo '<img id="'.$inim.'down" src="../_imagens/down.png"/>';
			echo '<div id="'.$inim.'GH'.$w.'" class="'.$inim.'grupoHidden">';
			foreach($nome_grupos as $grupo){
				$stmt2=$dbh->prepare("select distinct(p.ref) from pcp
								join produtos p on pcp.ref=p.ref
								where Tags like ? and p.secao='".$row['secao']."' and pcp.situacao='S' and pcp.loc='estoque' and Tags not like '%oculto%'");
				$stmt2->execute(array("%".$grupo."%"));
				$num_rows=$stmt2->rowCount();
				if($num_rows>=1){
					echo '<a class="'.$inim.'a_gH" href="catalogo.php?secao='.$row['secao'].'&grupo='.$grupo.'#anchor" >';
					if($grupo == 'Macacoes'){echo 'Macacões';}
					elseif($grupo == 'CalcaBermuda'){echo 'Calças e Bermudas';}
					elseif($grupo == 'Plus'){echo 'Plus Size';}
					elseif($grupo =='Silkadas'){echo 'Camisas Estampadas';}
					elseif($grupo =='CamisaLisa'){echo 'Camisa Lisa';}
					elseif($grupo =='Polo'){echo 'Camisa Polo';}
					else{echo $grupo;}
					echo '
					</a>';
				}
			}
			echo '</div></div>';
		}else{
			echo '<a class="a-black" href="catalogo.php?secao='.$row['secao'].'#anchor">'.strtoupper($row['secao']).'</a>';
		}
	}
	echo '<div class="'.$inim.'hoverDrop"><a class="a-black" href="?promocao#anchor">
		PROMOÇÃO
		</a></div>
	</nav>';
?>