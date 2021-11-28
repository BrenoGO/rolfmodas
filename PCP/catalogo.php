
<!DOCTYPE html>
<html>
<head>

    <?php
	require '../head.php';
	?>
    <title>Rolf Modas</title>
	<script src="_javascript/functionscatalog.js"></script>
	<script src="../_javascript/functions.js"></script>

<?php
$max_banner=1; 
while(file_exists("_banner/$max_banner-banner.png")){
	$max_banner++;
}
$max_banner--;

echo '';
echo'

</head>

<body id="catalogo" onLoad="slideshow_banner(1,'.$max_banner.')" onresize="ChangeCatSize()">';

	header("access-control-allow-origin: https://pagseguro.uol.com.br");
	
	require '../config.php';
	require 'config.php';
	
	require '../header_geral.php';
	echo '</header>';
	
	//var_dump($_SESSION);
    //var_dump($_COOKIE);
	//var_dump($_SESSION['pedido_ped']);
	//var_dump($_SESSION['pedidope']);
	
	
	/*if(isset($_SESSION['valor_total_pedido'])){
		require '../_auxiliares/markup.php';
		$_SESSION['valor_total_pedido']= total_pedidope($dbh);
		require '../_auxiliares/alertasmarkup.php';
	}*/

	
	$stmt=$dbh->prepare("select * from dadosgerais where nome_dado=?");
	$stmt->execute(array('Grupos'));
	$ln=$stmt->fetch(PDO::FETCH_ASSOC);
	$nome_grupos=explode(';',$ln['dado_1']);
	
	//var_dump($nome_grupos);
	
	//
	echo '
	<div class="menu-overlay"></div>
	<div id="menu_lateral">
		<div id="imageX">
			<h1 style="color: white; margin: 0; padding: 5px; font-size: 120%; left:0; float: left;">CATEGORIAS</h1>
			<img id="x" src="../_imagens/x2.png"/></div>
		<div id="fundo">';
			$inim='lat_';
			require '../_auxiliares/menus_cat.php';
			echo '
		</div>
	</div>
	<div id="fundo_opaco" onclick="fecharX()"></div>';
	echo '<nav id="cat_pc">';
	$inim='';
	require '../_auxiliares/menus_cat.php';
	echo '</nav>';
	echo '
	<div>
		<!--<div id="div_busca">';
			if( ($_SERVER['PHP_SELF']=='/PCP/catalogo.php')or($_SERVER['PHP_SELF']=='/rolfmodas.com.br/PCP/catalogo.php') ){
				
				/*if(!isset($_COOKIE['tipo_ped'])){
					setcookie('tipo_ped','PE',time()+(5*12*30*24*3600));
					$tipo_ped='PE';
				}else{
					$tipo_ped=$_COOKIE['tipo_ped'];
				}*/
				
				echo '

				<div id="espacobuscar" class="fl espacobuscar inputbusca">
					<input type="text" id="buscaprod" onkeyup="proc_produto_catalog(this.value)" placeholder="Buscar"/>
					<div id="ls_produto_catalogo"></div>
				</div>
				<img id="lupa" class="lupa" src="../_imagens/lupa.png"/>';
				echo '
				<div id="tipo_ped_pc">
					<span class="space">
						<input name="tipo_ped" id="tipo_ped_PE" onclick="set_tipo_ped_PE()" type="radio"';if($tipo_ped=='PE'){echo ' checked';}echo '/>
						<label for="tipo_ped_PE">Pronta Entrega</label>
					</span>
					<span class="space">
						<input name="tipo_ped" id="tipo_ped_Pedido" onclick="set_tipo_ped_Pedido()" type="radio"';
						if($tipo_ped=='Pedido'){echo ' checked';}
						echo '/>
						<label for="tipo_ped_Pedido">Pedido</label>
					</span>
					<span class="space">
						<input name="tipo_ped" id="tipo_ped_Ambos" onclick="set_tipo_ped_Ambos()" type="radio"';
						if($tipo_ped=='Ambos'){echo ' checked';}
						echo '/>
						<label for="tipo_ped_Ambos">Ambos</label>
					</span>
				</div>
				<div class="fr">
					<label id="label_mobile" class="fl label_mobile" for="span_ped">Tipo de Pedido: </label>
					<div id="tipo_ped">
						<span id="span_ped">';
						if($_COOKIE['tipo_ped'] == 'PE'){
							echo 'Pronta Entrega';
						}else if($_COOKIE['tipo_ped'] == 'Pedido'){
							echo 'Pedido';
						}else{
							echo 'Ambos';
						}
						echo '</span>
					</div>
					<div>
						<div id="tipo_ped_mobile">
							<span class="space space_mobile">
								<input name="tipo_ped" id="tipo_ped_PE" onclick="set_tipo_ped_PE()" type="radio"';if($tipo_ped=='PE'){echo ' checked';}echo '/>
								<label for="tipo_ped_PE">Pronta Entrega</label>
							</span>
							<span class="space space_mobile">
								<input name="tipo_ped" id="tipo_ped_Pedido" onclick="set_tipo_ped_Pedido()" type="radio"';
								if($tipo_ped=='Pedido'){echo ' checked';}
								echo '/>
								<label for="tipo_ped_Pedido">Pedido</label>
							</span>
							<span class="space space_mobile">
								<input name="tipo_ped" id="tipo_ped_Ambos" onclick="set_tipo_ped_Ambos()" type="radio"';
								if($tipo_ped=='Ambos'){echo ' checked';}
								echo '/>
								<label for="tipo_ped_Ambos">Ambos</label>
							</span>
						</div>
					</div>
				</div>';
			}
	echo '</div>-->';
	echo '</div></div>';
	/*echo '<div id="nav_catalogo">';
	echo '<nav id="submenu">
	</nav>';
	
	$inim='';
	require '../_auxiliares/menus_cat.php';
	echo '<nav id="submenu2">
	</nav></div></div>';*/

	
		if( (!isset($_GET['acao']))) {
			$width_div=6+($max_banner-1)*6+$max_banner*12;
			echo '<div id="banner">
				<a id="link_img_banner" href="?secao=Feminino#anchor"><img id="img_banner" src="_banner/1-banner.png"/></a>
				<div id="bolas_banner">
				<script>document.getElementById("bolas_banner").style.width="'.$width_div.'px"</script>';
				
				
				for($k=1;$k <= $max_banner;$k++){
					if($k==1){
						echo '<img class="img_bolas_banner" id="bola-'.$k.'" src="../_imagens/bola_cinza_escuro.png" onclick="clear_slideshow();slideshow_banner('.$k.','.$max_banner.')"/>';
					}else{
						echo '<img class="img_bolas_banner" id="bola-'.$k.'" src="../_imagens/bola_cinza_claro.png" onclick="clear_slideshow();slideshow_banner('.$k.','.$max_banner.')"/>';
					}
					
				}
			echo '</div></div>';
			echo '<div id="ContCentral">
				<section class="hover i1">
					<a href="../PCP/catalogo.php?secao=Feminino&grupo=Vestidos#anchor">
						<img src="_banner/Vestidos.jpg"/>
						<div id="hover1" class="hiddenDiv">
							<span class="hiddenName">
								Vestidos
							</span>
						</div>
					</a>
				</section>
				<section class="hover i2">
					<a href="../PCP/catalogo.php?secao=Feminino&grupo=Fitness#anchor">
						<img src="_banner/Plus.jpg"/>
						<div id="hover2" class="hiddenDiv">
							<center><span class="hiddenName">
								Plus
							</span></center>
						</div>
					</a>
				</section>
				<section class="hover i3">
					<a href="../PCP/catalogo.php?secao=Infantil#anchor">
						<img src="_banner/Infantil.jpg"/>
						<div id="hover3" class="hiddenDiv">
							<center><span class="hiddenName">
								Infantil
							</span></center>
						</div>
					</a>
				</section>
				<section class="hover i4">
					<a href="../PCP/catalogo.php?secao=Feminino&grupo=CalcaBermuda#anchor">
						<img src="_banner/Calcas.jpg"/>
						<div id="hover4" class="hiddenDiv">
							<center><span class="hiddenName">
								Calças
							</span></center>
						</div>
					</a>
				</section>
				<section class="hover i5">
					<a href="../PCP/catalogo.php?secao=Feminino#anchor">
						<img src="_banner/Feminino.jpg"/>
						<div id="hover5" class="hiddenDiv">
							<center><span class="hiddenName">
								Feminino
							</span></center>
						</div>
					</a>
				</section>
				<section class="hover i6">
					<a href="../PCP/catalogo.php?secao=Masculino#anchor">
						<img src="_banner/Masculino.jpg"/> 
						<div id="hover6" class="hiddenDiv">
							<center><span class="hiddenName">
								Masculino
							</span></center>
						</div>
					</a>
				</section>
			</div>';
		}
	
	
	echo '<div id="conteudo"><a name="anchor"></a>';
	if(isset($_GET['default'])){
		/*
		
		*/
	}
	if(isset($_GET['promocao'])){
		$_SESSION['grupo'] = 'promocao';
		if($_COOKIE['tipo_ped']=='PE'){
			$qr="select p.ref,p.descricao,p.preco from produtos p 
			join pcp on p.ref = pcp.ref 
			where p.Tags like '%promo%' and pcp.loc='estoque' and pcp.situacao='S' group by ref";
		}elseif($_COOKIE['tipo_ped']=='Pedido'){
			$qr="select p.ref,p.descricao,p.preco from produtos p 
			join tabela_pedidos t on p.ref=t.ref
			where p.Tags like '%promo%'";
		}elseif($_COOKIE['tipo_ped']=='Ambos'){
			$qr="select p.ref,p.descricao,p.preco from produtos p 
			join tabela_pedidos t on p.ref=t.ref
			where p.Tags like '%promo%' 
			union
			select p.ref,p.descricao,p.preco from produtos p 
			join pcp on p.ref = pcp.ref 
			where pcp.loc='estoque' and pcp.situacao='S' and p.Tags like '%promo%' 
			group by ref order by ref";
		}
		
		$sql=mysqli_query($con,$qr);
		$tablesql = array();
		while($row = $sql->fetch_assoc()){
			$tablesql[] = $row;
		}
		echo '<div class="containerprods">';
		foreach($tablesql as $ln){
				
			$ref=$ln['ref'];
			$pco=$dbh->prepare("select * from produtos where ref=?");
			$pco->execute(array($ref));
			$preco=$pco->fetch(PDO::FETCH_ASSOC);
			$preco_ant=round($preco['preco_ant']*$_SESSION['markup'],1);
			$desc=$ln['descricao'];
			$preco=round($ln['preco']*$_SESSION['markup'],1);
			$stmt=$dbh->prepare("select Tags from produtos where ref=?");
			$stmt->execute(array($ref));
			$ln=$stmt->fetch(PDO::FETCH_ASSOC);
			if( (strpos($ln['Tags'],'oculto') === false) and (file_exists('_fotos/'.$ref.'-1.jpg' )) ){
				echo '<div class="quadro_produto">';
				echo '<span class="ref"><b>Ref: '.$ref.'</b></span></br>';
				echo '<a href=?ref='.$ref.'#anchor>';
				if($preco_ant > $preco) {
							$x = (($preco / $preco_ant) * 100);
							$desct = 100 - $x;
							echo '<div class="off">-'.round($desct).'% OFF</div>';
				}
				
				echo '<img class="foto_prod_grupo" src="_fotos/'.$ref.'-1.jpg"/></br>';
				if(strpos($ln['Tags'],'promo') !== false){
					echo '<img class="icon_promocao" src="../_imagens/PROMOCAO.png"/>';
				}
				echo '<div class="desc">'.$desc.'</div>';
				if(($preco_ant <= $preco) or (is_null($preco_ant))){
							echo '<span class="preco">R$ '.number_format($preco,2,',','.').'</span>';
				}elseif($preco_ant > $preco) {
							echo '<del class="promox">R$ '.number_format($preco_ant,2,',','.').'</del>';
							echo '<span class="promoy"><b> R$ '.number_format($preco,2,',','.').'</b></span>';
				}
				
				echo '</a></div>';
			}
		}	
		echo '</div>';
	}	
	if(isset($_GET['grupo'])){
		$secao=$_GET['secao'];
		$grupo = $_GET['grupo'];
		
		$_SESSION['grupo'] = 'grupo='.$grupo.'&secao='.$secao;
		
		if($_COOKIE['tipo_ped']=='PE'){
			$qr="select p.ref,p.descricao,p.preco from produtos p 
			join pcp on p.ref = pcp.ref 
			where (p.Tags like '%$grupo%') and p.secao='$secao' and pcp.loc='estoque' and pcp.situacao='S' group by ref";
		}elseif($_COOKIE['tipo_ped']=='Pedido'){
			$qr="select p.ref,p.descricao,p.preco from produtos p 
			join tabela_pedidos t on p.ref=t.ref
			where (p.Tags like '%$grupo%') and p.secao='$secao'";
		}elseif($_COOKIE['tipo_ped']=='Ambos'){
			$qr="select p.ref,p.descricao,p.preco from produtos p 
			join tabela_pedidos t on p.ref=t.ref
			where (p.Tags like '%$grupo%') and p.secao='$secao' 
			union
			select p.ref,p.descricao,p.preco from produtos p 
			join pcp on p.ref = pcp.ref 
			where pcp.loc='estoque' and pcp.situacao='S' and (p.Tags like '%$grupo%') and p.secao='$secao' 
			group by ref order by ref";
		}
		
		$stmt=$dbh->prepare($qr);
		$stmt->execute();
		$tablesql = array();
		while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
			$tablesql[] = $row;
		}
		
		echo '<div class="containerprods">';
		foreach($tablesql as $ln){
			$ref=$ln['ref'];
			$pco=$dbh->prepare("select * from produtos where ref=?");
			$pco->execute(array($ref));
			$preco=$pco->fetch(PDO::FETCH_ASSOC);
			$preco_ant=round($preco['preco_ant']*$_SESSION['markup'],1);
			if(file_exists('_fotos/'.$ref.'-1.jpg')){
				$desc=$ln['descricao'];
				$preco=round($ln['preco']*$_SESSION['markup'],1);
				$stmt=$dbh->prepare("select Tags from produtos where ref=?");
				$stmt->execute(array($ref));
				$ln=$stmt->fetch(PDO::FETCH_ASSOC);
				
				
				if(strpos($ln['Tags'],'oculto') === false){
					echo '<div class="quadro_produto"><a href=?ref='.$ref.'#anchor>';
					echo '<span class="ref"><b>Ref: '.$ref.'</b></span></br>'; 
					if($preco_ant > $preco) {
							$x = (($preco / $preco_ant) * 100);
							$desct = 100 - $x;
							echo '<div class="off">-'.round($desct).'% OFF</div>';
					}
					echo '<img class="foto_prod_grupo" src="_fotos/'.$ref.'-1.jpg"/></br>';
					if(strpos($ln['Tags'],'promo') !== false){
						echo '<img class="icon_promocao" src="../_imagens/PROMOCAO.png"/>';
					}
					echo '<div class="desc">'.$desc.'</div>';
					if(($preco_ant <= $preco) or (is_null($preco_ant))){
							echo '<span class="preco">R$ '.number_format($preco,2,',','.').'</span>';
					}elseif($preco_ant > $preco) {
							echo '<del class="promox">R$ '.number_format($preco_ant,2,',','.').'</del>';
							echo '<span class="promoy"><b> R$ '.number_format($preco,2,',','.').'</b></span>';
					}
					
					echo '</a></div>';
				}
			}
		}
		echo '</div></a>';
	}elseif(isset($_GET['secao'])){
		$secao=$_GET['secao'];
		$_SESSION['grupo'] = 'secao='.$secao;
		//var_dump($_SESSION['grupo']);
		if(isset($_COOKIE['tipo_ped'])){
			if($_COOKIE['tipo_ped']=='PE'){
				$qr="select p.ref,p.descricao,p.preco from produtos p 
				join pcp on p.ref = pcp.ref 
				where p.secao='$secao' and pcp.loc='estoque' and pcp.situacao='S' group by ref";
			}elseif($_COOKIE['tipo_ped']=='Pedido'){
				$qr="select p.ref,p.descricao,p.preco from produtos p 
				join tabela_pedidos t on p.ref=t.ref
				where p.secao='$secao'";
			}elseif($_COOKIE['tipo_ped']=='Ambos'){
				$qr="select p.ref,p.descricao,p.preco from produtos p 
				join tabela_pedidos t on p.ref=t.ref
				where p.secao='$secao'
				union
				select p.ref,p.descricao,p.preco from produtos p 
				join pcp on p.ref = pcp.ref 
				where pcp.loc='estoque' and pcp.situacao='S' and p.secao='$secao' 
				group by ref order by ref";
			}
			
			$stmt=$dbh->prepare($qr);
			$stmt->execute();
			$tablesql = array();
			while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
				$tablesql[] = $row;
			}
		
		echo '<div class="containerprods">
		<a name="anchor" style="display: none;"></a>';
		foreach($tablesql as $ln){
			$ref=$ln['ref'];
			$pco=$dbh->prepare("select * from produtos where ref=?");
			$pco->execute(array($ref));
			$preco=$pco->fetch(PDO::FETCH_ASSOC);
			$preco_ant=round($preco['preco_ant']*$_SESSION['markup'],1);
			if(file_exists('_fotos/'.$ref.'-1.jpg')){
				
				$desc=$ln['descricao'];
				$preco=round($ln['preco']*$_SESSION['markup'],1);
				$stmt=$dbh->prepare("select Tags from produtos where ref=?");
				$stmt->execute(array($ref));
				$ln=$stmt->fetch(PDO::FETCH_ASSOC);
				
				if(strpos($ln['Tags'],'oculto') === false){
					echo '<div class="quadro_produto" ">';
					echo '<span class="ref"><b>Ref: '.$ref.'</b></span>';
					echo '<a href=?ref='.$ref.'#anchor>';
					if($preco_ant > $preco) {
							$x = (($preco / $preco_ant) * 100);
							$desct = 100 - $x;
							echo '<div class="off">-'.round($desct).'% OFF</div>';
					}
					echo '<img class="foto_prod_grupo" src="_fotos/'.$ref.'-1.jpg"></img></br>';
					if(strpos($ln['Tags'],'promo') !== false){
						echo '<img class="icon_promocao" src="../_imagens/PROMOCAO.png"/>';
					}
					echo '<div class="desc">'.$desc.'</div>';
					if(($preco_ant <= $preco) or (is_null($preco_ant))){
							echo '<span class="preco">R$ '.number_format($preco,2,',','.').'</span>';
					}elseif($preco_ant > $preco) {
								echo '<del class="promox">R$ '.number_format($preco_ant,2,',','.').'</del>';
								echo '<span class="promoy"><b> R$ '.number_format($preco,2,',','.').'</b></span>';
							}
				
					
					echo '</a></div>';
				}
			}
		}
		echo '</div></a>';
		}
	}
	if(isset($_GET['ref'])){
		$ref = $_GET['ref'];
		$qrprod="select * from produtos where ref='$ref'";
		$sql=mysqli_query($con,$qrprod);
		$ln=mysqli_fetch_assoc($sql);
		$desc=$ln['descricao'];
		$preco=round($ln['preco']*$_SESSION['markup'],1);
		if(file_exists('_fotos/'.$ref.'-1.jpg')){
			$maxfoto=1;
			$k=2;
			$u = 1;
			echo '<div class="quadro_foto_grande">
					<a name="anchor"></a>
					<div id="inf_do_produto">
					<span id="span_ref">Ref.: '.$ref.'</span>
					<span id="span_preco">R$ '.number_format($preco,2,',','.').'</span>
					</br>
					<span id="span_desc">'.$desc.'</span>
					
					</div> ';//fecha div id inf_do_produto
				
				echo '<div id="foto_do_produto">
				<img id="image" src="_fotos/'.$ref.'-1.jpg" class="fotoprod zoom"/>
				<script type="text/javascript">
					let largura = window.innerWidth;					
					if(largura < 760){
						let element = document.getElementById("image");
						element.classList.remove("zoom");
					}
					
				</script>';
				echo '<script type="text/javascript" src="../_javascript/javazoom.js"></script>';
			
				//para usar require o arquivo tem que ser php, entao repare dentro do arquivo javazoom.php como deveria ser..
				//require '../_javascript/javazoom.php';
				
				while(file_exists('_fotos/'.$ref.'-'.$k.'.jpg')){
					$maxfoto++;
					$k++;
				}
				
				if(file_exists('_fotos/'.$ref.'-2.jpg')){
					echo '
					<img src="../_imagens/Back-Music.png" id="button_back" onclick="ant('.$maxfoto.','."'".$ref."'".')"/>
					<img src="../_imagens/forward-Music.png" id="button_forward" onclick="prox('.$maxfoto.','."'".$ref."'".')"/>
					';
				}else{
					echo '</div>';
				}
			
			echo '</div>';//fecha div id=foto_do_produto
			
			echo '</div>';//fecha div class quadro_foto_grande
		}else{
			echo "</br></br>";
		}
		$stmt_PE = $dbh->prepare("select pcp.cor,pcp.t1,pcp.t2,pcp.t3,pcp.t4,pcp.t5 from pcp where ref=? and loc='estoque' and situacao='S' order by pcp.cor");
		$stmt_PE->execute(array($ref));
		if($stmt_PE->rowCount() > 0){
			$bool_tem_PE=true;
		}else{
			$bool_tem_PE=false;
		}
		$stmt_ped=$dbh->prepare("select * from tabela_pedidos where ref=?");
		$stmt_ped->execute(array($ref));
		if($stmt_ped->rowCount() > 0){
			$bool_tem_ped=true;
		}else{
			$bool_tem_ped=false;
		}
		
		if($_COOKIE['tipo_ped']=='Ambos' or $_COOKIE['tipo_ped']=='PE'){
			$bool_quadro_PE=true;
		}else{
			$bool_quadro_PE=false;
		}
		if($_COOKIE['tipo_ped']=='Ambos' or $_COOKIE['tipo_ped']=='Pedido'){
			$bool_quadro_ped=true;
		}else{
			$bool_quadro_ped=false;
		}
		echo '<div id="form_pedidos">';
		if($bool_quadro_PE){
			echo '<div id="form_add_pedido">';//inicio do quadro de PE
			//echo '<div id="tipo_PE_no_add_pedido">Pronta Entrega</div>';
			
			if(!$bool_tem_PE){
				echo '<img src="../_imagens/PE_n_tem.png"/>';
				/*echo '<div id="cores_no_add_pedido" style="display:block">';//div com o quadro que seria de cores, mas não tem cor disponivel no estoque...
				echo '<b>Não possuímos estoque para Pronta Entrega deste produto...</b>';
				echo '</div>';//fim do div que seria de cores..*/
			}else{
				if($bool_tem_ped and $bool_quadro_ped){
					/*echo '<div id="se_escolher_PE"><a style="color:black" onclick="escolherPEemref()">Clique <b>aqui</b> para escolher produtos de Pronta Entrega!</a></div>';//Quadro pra se escolher PE
					*/
					echo '<img id="voltar" onclick="history.go(-1)" style="cursor: pointer; width: 30px; height:30px" src="../_imagens/voltar2.png"/><br/>';
					echo '<img id="se_escolher_PE" style="cursor:pointer" src="../_imagens/PE.png" onclick="escolherPEemref()">';
					echo '<div id="cores_no_add_pedido" style="display:none">';//Quadro com cores. Fica display none até ele escolher pedir PE..(se existir ped)
					echo '<img style="cursor: pointer; width: 30px; height:30px" src="../_imagens/voltar_p.png" onclick="BotVoltarPE()" /><br/>';//onClick="history.go(0)
				}else{
					echo '<img id="voltar" onclick="history.go(-1)" style="cursor: pointer; width: 30px; height:30px" src="../_imagens/voltar2.png"/><br/>';
					echo '<div id="cores_no_add_pedido">';//Inicio do quadro c cores. Se não tiver ped ele já aparece
				}
				echo '<form method="post" action="?acao=add&refpe='.$ref.'">';
				$tablepcp=array();
				while($row=$stmt_PE->fetch(PDO::FETCH_ASSOC)){
					$tablepcp[]=$row;
				}
				$cont = 1;
				foreach($tablepcp as $lnpcp){
					$cor = $lnpcp['cor'];
					
					echo '<div class="corespec">';//Div pra cada cor
						if(strpos($cor,'/')!==false){
							$exp_cor=explode('/',$cor);
							$arquivo_cor=$exp_cor[0].'-'.$exp_cor[1];
						}else{
							$arquivo_cor=$cor;
						}
						echo '<div class="div_nome_cor">'.$cor.'</div>';
						echo '<div class="div_img_cor">
								<img';
						if($arquivo_cor == 'BRANCO'){echo ' style="border: 1px solid black;"';}
						echo ' onmouseover="imgCor('.$cont.')" onmouseout="imgCor2('.$cont.')" src="_cores/'.$arquivo_cor.'.jpg" class="cor"/>
								<img id="cor'.$cont.'" src="_cores/'.$arquivo_cor.'.jpg" class="corM"/>
						</div>';
					echo '<div class="div_tams">';//div com todos tamanhos
					echo '<div class="PaG">';	
					for($i=1;$i<=5;$i++){
						//if($i == 4){echo '</div><div class="GGeEG">';}//fecha div dos tamanhos P a G e abre do GG e EG.
						echo '<td><span ';
						if($lnpcp['t'.$i] == 0){
							echo ' style="color:grey" ';
						}
						echo 'class="cada_tam">';//span para cada tamanho
						if($lnpcp['t'.$i] > 0){
							echo '<span class="tam">';
						}
						$maxt[$i] = $lnpcp['t'.$i];
						$testinf = substr($ref,-1);
						if(isset($_SESSION['pedidope'][$ref][$cor]['t'.$i])){$atual = $_SESSION['pedidope'][$ref][$cor]['t'.$i];}
						else{$atual=0;}
						if($testinf == "I"){
							if($i == 1){echo ' 4<br>';} 
							elseif($i == 2){echo ' 6<br> ';}
							elseif($i == 3){echo ' 8<br> ';}
							elseif($i == 4){echo ' 10<br> ';}
							elseif($i == 5){echo ' 12<br> ';}
						}else{
							if($i == 1){echo ' P<br>';} 
							elseif($i == 2){echo ' M<br>';}
							elseif($i == 3){echo ' G<br>';}
							elseif($i == 4){echo ' GG<br>';}
							elseif($i == 5){echo ' EG<br>';}
						}
						if($lnpcp['t'.$i] > 0){	
							echo '</span>';
						}
						if($maxt[$i] == 0){
							echo '<select class="NoSelect" disabled="disabled"><option selected="select" readonly="readonly">0</option></select>';
						}else{
							echo '<select name="'.$cor.'-t'.$i.'">';
							for($j=0;$j<=$maxt[$i];$j++){
								echo '<option '; if($atual == $j){echo 'selected="select"';}
								echo'>'.$j.'</option>';
							}
							echo '</select> ';
						}	
						echo '</span>';//fecha span para cada tamanho
						if($i == 5){echo '</div>';}//fecha div do tamanho GG e EG..
						
					}
					echo '</div>';//fecha div de todos os tamanhos
					echo '</div>';//fecha div que inclui tudo sobre a cor (corespec)..
					$cont++;
				}
				echo '</div>';//fim do quadro com todas as cores de PE..
			}
			
			echo '
				<div id="submit_no_add_pedido"';
				if($bool_tem_ped and $bool_quadro_ped){echo ' style="display:none"';}
				echo '>
					<div id="botao_submit">
						<input type="submit" value="Adicionar na Pronta Entrega"/>
					</div>	
				</div>	
				</form>
				';
			
			echo '</div>';//fim do quadro de PE..
		}
		if($bool_quadro_ped){
			$ln=$stmt_ped->fetch(PDO::FETCH_ASSOC);
			$cores=explode(',',$ln['refs_cores']);
			$tamanhos=$ln['tamanhos'];
			
			echo '<div id="form_add_pedido_ped">';//inicio do quadro de ped
			$stmt=$dbh->prepare("select dado_1 from dadosgerais where nome_dado='Tempo pra faturar pedido'");
			$stmt->execute();
			$tempo_p_faturar=$stmt->fetch(PDO::FETCH_ASSOC);
			$tempo_p_faturar=$tempo_p_faturar['dado_1'];
		//	echo '<div id="tipo_ped_no_add_pedido">Pedido com Prazo (máximo '.$tempo_p_faturar.'dias para faturar.)</div>';
			
			if(!$bool_tem_ped){
				//echo '<div id="cores_no_add_pedido" style="display:block">';//div com o quadro que seria de cores, mas não tem cor disponivel no estoque...
				echo '<img src="../_imagens/PP_n_tem.png">';
			}else{
				if($bool_tem_PE and $bool_quadro_PE){
					/*echo '<div id="se_escolher_ped"><a style="color:black" onclick="escolherPedemref()">Clique <b>aqui</b> para fazer pedido com prazo!</a></div>';//Quadro pra se escolher ped
					*/
					echo '<img id="se_escolher_ped" style="cursor:pointer" src="../_imagens/PP.png" onclick="escolherPedemref()">';
					echo '<div id="cores_no_add_pedido_ped" style="display:none">';//Quadro com cores. Fica display none até ele escolher pedido..(se existir PE)
					echo '<img style="cursor: pointer; width: 30px; height:30px" src="../_imagens/voltar_p.png" onclick="BotVoltarPed()"/><br/>';
				}else{
					echo '<a href="catalogo.php?secao=Feminino"><img style="width: 30px; height:30px" src="../_imagens/voltar_p.png" /><br/></a>';
					echo '<div id="cores_no_add_pedido_ped">';//Inicio do quadro c cores. Se não tiver PE ele já aparece
				}
				
				echo '<form method="post" action="?acao=add_ped&refpe='.$ref.'">';
				$tablepcp=array();
				while($row=$stmt_PE->fetch(PDO::FETCH_ASSOC)){
					$tablepcp[]=$row;
				}
				$cont = 1;
				foreach($tablepcp as $lnpcp){
					$cor = $lnpcp['cor'];
					
					echo '<div class="corespec">';//Div pra cada cor
						if(strpos($cor,'/')!==false){
							$exp_cor=explode('/',$cor);
							$arquivo_cor=$exp_cor[0].'-'.$exp_cor[1];
						}else{
							$arquivo_cor=$cor;
						}
						echo '<div class="div_nome_cor">'.$cor.'</div>';
						echo '<div class="div_img_cor">
								<img';
						if($arquivo_cor == 'BRANCO'){echo ' style="border: 1px solid black;"';}
						echo ' onmouseover="imgCor('.$cont.')" onmouseout="imgCor2('.$cont.')" src="_cores/'.$arquivo_cor.'.jpg" class="cor"/>
								<img id="cor'.$cont.'" src="_cores/'.$arquivo_cor.'.jpg" class="corM"/>
						</div>';
					echo '<div class="div_tams">';//div com todos tamanhos
					echo '<div class="PaG">';	
					for($i=1;$i<=5;$i++){
						//if($i == 4){echo '</div><div class="GGeEG">';}//fecha div dos tamanhos P a G e abre do GG e EG.
						echo '<td><span ';
						if($lnpcp['t'.$i] == 0){
							echo ' style="color:grey" ';
						}
						echo 'class="cada_tam">';//span para cada tamanho
						if($lnpcp['t'.$i] > 0){
							echo '<span class="tam">';
						}
						$maxt[$i] = $lnpcp['t'.$i];
						$testinf = substr($ref,-1);
						if(isset($_SESSION['pedido_ped'][$ref][$cor]['t'.$i])){$atual = $_SESSION['pedido_ped'][$ref][$cor]['t'.$i];}
						else{$atual=0;}
						if($testinf == "I"){
							if($i == 1){echo ' 4<br>';} 
							elseif($i == 2){echo ' 6<br> ';}
							elseif($i == 3){echo ' 8<br> ';}
							elseif($i == 4){echo ' 10<br> ';}
							elseif($i == 5){echo ' 12<br> ';}
						}else{
							if($i == 1){echo ' P<br>';} 
							elseif($i == 2){echo ' M<br>';}
							elseif($i == 3){echo ' G<br>';}
							elseif($i == 4){echo ' GG<br>';}
							elseif($i == 5){echo ' EG<br>';}
						}
						if($lnpcp['t'.$i] > 0){	
							echo '</span>';
						}
						if($maxt[$i] == 0){
							echo '<select class="NoSelect" disabled="disabled"><option selected="select" readonly="readonly">0</option></select>';
						}else{
							echo '<select name="'.$cor.'-t'.$i.'">';
							for($j=0;$j<=$maxt[$i];$j++){
								echo '<option '; if($atual == $j){echo 'selected="select"';}
								echo'>'.$j.'</option>';
							}
							echo '</select> ';
						}	
						echo '</span>';//fecha span para cada tamanho
						if($i == 5){echo '</div>';}//fecha div do tamanho GG e EG..
						
					}
					echo '</div>';//fecha div de todos os tamanhos
					echo '</div>';//fecha div que inclui tudo sobre a cor (corespec)..
					$cont++;
				}
				echo '</div>';//fim do quadro com todas as cores de PE..

				echo '</div>';//fim do quadro com todas as cores.
			}
			echo '<div id="submit_no_add_pedido_ped"';
			if($bool_tem_PE and $bool_quadro_PE){echo ' style="display:none"';}
				   echo '><div id="botao_submit_ped">
							<input id="input_submit_ped" type="submit" value="Adicionar no Pedido"/>
						</div>	
				</div>
			</form>';
			echo '</div>';//fim do quadro de ped..
		}
	}//fecha "ref"
		
	//adicionar produto
	
	if( (isset($_GET['acao'])) and (isset($_SESSION['id_usuario'])) ){
		
		if($_GET['acao']=='add'){
			if(!isset($_SESSION['pedidope'])){
				$_SESSION['pedidope'] = array();
			}
			//ADICIONAR no pedido de PE
			$ref= $_GET['refpe'];
			$qr="select preco from produtos where ref='$ref'";
			$sql=mysqli_query($con,$qr);
			$ln=mysqli_fetch_assoc($sql);
			$preco=round($ln['preco']*$_SESSION['markup'],1);
			
			//verificar se excede limite
			$total=total_pedidope($dbh);
			
			$sumadd=0;
			
			$qrpcp = "select pcp.cor from pcp where ref='$ref' and loc='estoque' and situacao='S'";
			$sql=mysqli_query($con,$qrpcp);
			$tablepcp = array();
			while($row = $sql->fetch_assoc()){
				$tablepcp[] = $row;
			}
			foreach($tablepcp as $lnpcp){
				$cor=$lnpcp['cor'];
				for($i=1;$i<=5;$i++){
					if(isset($_POST[$cor.'-t'.$i])){
						$sumadd += $_POST[$cor.'-t'.$i];;
					}
				}
			}
			
			$subtotadd = $sumadd*$preco;
			if(($total + $subtotadd) > $_SESSION['limitevenda']){
				echo '<script>alert("Limite excedido! Seu limite de compras é de '.$_SESSION['limitevenda'].'.");window.location.href="?acao=conferirped";</script>';
				die();
			}
			//fim do verificar se excede limite
			
			$qrpcp = "select pcp.cor,pcp.t1,pcp.t2,pcp.t3,pcp.t4,pcp.t5 from pcp where ref='$ref' and loc='estoque' and situacao='S'";
			$sql=mysqli_query($con,$qrpcp);
			$tablepcp = array();
			while($row = $sql->fetch_assoc()){
				$tablepcp[] = $row;
			}
			foreach($tablepcp as $lnpcp){
				$cor=$lnpcp['cor'];
				for($i=1;$i<=5;$i++){
					if(isset($_POST[$cor.'-t'.$i])){
						$_SESSION['pedidope'][$ref][$cor]['t'.$i] = $_POST[$cor.'-t'.$i];
					}else{$_SESSION['pedidope'][$ref][$cor]['t'.$i] = 0;}	
				}
				if(array_sum($_SESSION['pedidope'][$ref][$cor])==0){
					unset($_SESSION['pedidope'][$ref][$cor]);
				}
			}
			
			$_SESSION['valor_total_pedido']=$total+$subtotadd;
			if(!isset($_SESSION['grupo'])){
				$_SESSION['grupo'] ='';
			}
			if($_SESSION['grupo'] ==''){
				echo '<script>window.location.href="?default#anchor"</script>';
			}else{
				echo '<script>window.location.href="?'.$_SESSION['grupo'].'#anchor"</script>';
			}
			
		}
		if($_GET['acao']=='add_ped'){
			if(!isset($_SESSION['pedido_ped'])){
				$_SESSION['pedido_ped'] = array();
			}
			//ADICIONAR no pedido ped
			$ref= $_GET['refpe'];
			$qr="select preco from produtos where ref='$ref'";
			$sql=mysqli_query($con,$qr);
			$ln=mysqli_fetch_assoc($sql);
			$preco=round($ln['preco']*$_SESSION['markup'],1);
			
			$total=total_pedido_ped($dbh);
			$sumadd=0;
			
			
			$stmt=$dbh->prepare("select refs_cores from tabela_pedidos where ref=?");
			$stmt->execute(array($ref));
			$ln=$stmt->fetch(PDO::FETCH_ASSOC);
			$cores=explode(',',$ln['refs_cores']);
			
			foreach($cores as $cor){
				
				for($i=1;$i<=5;$i++){
					if(isset($_POST[$cor.'-t'.$i])){
						$_SESSION['pedido_ped'][$ref][$cor]['t'.$i] = $_POST[$cor.'-t'.$i];
						$sumadd += $_POST[$cor.'-t'.$i];
					}else{$_SESSION['pedido_ped'][$ref][$cor]['t'.$i] = 0;}	
				}
				if(array_sum($_SESSION['pedido_ped'][$ref][$cor])==0){
					unset($_SESSION['pedido_ped'][$ref][$cor]);
				}
			}
			$subtotadd = $sumadd*$preco;
			$_SESSION['valor_total_pedido_ped']=$total+$subtotadd;
			
			if(!isset($_SESSION['grupo'])){
				$_SESSION['grupo'] ='';
			}
			if($_SESSION['grupo'] ==''){
				echo '<script>window.location.href="?default#anchor"</script>';
			}else{
				echo '<script>window.location.href="?'.$_SESSION['grupo'].'#anchor"</script>';
			}
			
		}
		if($_GET['acao']=='conferirped'){
			if(isset($_SESSION['pedidope'])){
				$totalgeral=0;
				$totalpecas=0;
				echo '
				<form class="table" method="post" action="?acao=alteracao">
				<table>
				<tr><td class="table_title" id="table_title_PE" colspan="11">Pedido de Pronta Entrega</td></tr>
				<tr>
				<td>Ref</td>
				<td>Descrição</td>
				<td>Cor</td>
				<td>P <span style="font-size:6pt">ou 4</span></td>
				<td>M <span style="font-size:6pt">ou 6</span></td>
				<td>G <span style="font-size:6pt">ou 8</span></td>
				<td>GG <span style="font-size:6pt">ou 10</span></td>
				<td>EG <span style="font-size:6pt">ou 12</span></td>
				<td>Total</td>';
				
				/*if( ($_SESSION['markup']==$markconsultor) or ($_SESSION['markup']==$markminifranqueado) ){
					$booldesc=true;
					echo '<td>Preço Tabela</td>
					<td>Desconto</td>';
				}else{$booldesc=false;}*/
				echo '<td>Preço</td>
				<td>Subtotal</td>
				</tr>';
				
				
			
				$refs=array_keys($_SESSION['pedidope']);
				foreach($refs as $ref){
					$cores=array_keys($_SESSION['pedidope'][$ref]);
					foreach($cores as $cor){
					if($cor <> 'desconto'){
						$qr="select descricao,preco from produtos where ref='$ref'";
						$sql=mysqli_query($con,$qr);
						$ln=mysqli_fetch_assoc($sql);
						$desc = $ln['descricao'];
						$preco = round($ln['preco']*$_SESSION['markup'],1);
						$tot = array_sum($_SESSION['pedidope'][$ref][$cor]);
						if(isset($_SESSION['pedidope'][$ref]['desconto'])){
							$desconto = $_SESSION['pedidope'][$ref]['desconto'];			
							$fator= (1-$desconto/100);
							$preco *= $fator;
						}
						$subtot = $tot * $preco;
						$totalgeral += $subtot; 
						$totalpecas += $tot;
						$qr = "select * from pcp where ref='$ref' and cor='$cor' and situacao='S' and loc='estoque'";
						$sql=mysqli_query($con,$qr);
						$ln = mysqli_fetch_assoc($sql);		
						
						echo '<tr>
						<td>'.$ref.'</td>
						<td>'.$desc.'</td>
						<td>'.$cor.'</td>';
						for($i=1;$i<=5;$i++){
							$maxt[$i]=$ln['t'.$i];
							$atual = $_SESSION['pedidope'][$ref][$cor]['t'.$i];
							echo '<td>';
							if($maxt[$i] == 0){
								echo '0';
							}else{	
								echo '<select name="'.$ref.'-'.$cor.'-t'.$i.'">';
								for($j=0;$j<=$maxt[$i];$j++){
									echo '<option '; if($atual == $j){echo 'selected="select"';}
									echo'>'.$j.'</option>';
								}
								echo '</select>';
							}	
							echo '</td>';
						}
						echo '<td>'.$tot.'</td>';
						/*if($booldesc){
							if(!isset($totalgeral_tab)){$totalgeral_tab=0;}
							$preco_tab=$preco/$_SESSION['markup']*$markconsumidor;
							$preco_tab=round($preco_tab,1);
							$desconto_tab=(1-$_SESSION['markup']/$markconsumidor)*100;
							$subtot_tab=$preco_tab*$tot;
							$totalgeral_tab += $subtot_tab;
							echo '
							<td>'.number_format($preco_tab,2,',','.').'</td>
							<td>'.number_format($desconto_tab,0).'%</td>
							';
						}*/
						echo '<td>'.number_format($preco,2,',','.').'</td>
						<td>'.number_format($subtot,2,',','.').'</td>
						</tr>
						';
					}
					}
				}
				echo '<tr><td colspan=';
				/*if($booldesc){echo '6';}else{*/echo '7';/*}*/
				echo ' style="border-bottom:none; border-left:none;"></td>
				<td>Total:</td>
				<td>'.$totalpecas.'</td>
				<td></td>
				';	
				/*if($booldesc){echo '<td>'.number_format($totalgeral_tab,2,',','.').'</td><td>'.$desconto_tab.'%</td><td></td>';}*/
				echo '<td>'.number_format($totalgeral,2,',','.').'</td></tr></table>
				
				<input type="submit" value="Alterar Pedido"/>
				
				<input type="button" value="ZERAR pedido" style="position:absolute; left: 300px;" onclick="if(confirm('."'Tem certeza que deseja ZERAR o pedido?'".')) window.location.href='."'?acao=zerarped'".';"/>
				</form></br></br>
				';
			}	
			if(isset($_SESSION['pedido_ped'])){
				$totalgeral_ped = 0; 
				$totalpecas_ped = 0;
				$stmt=$dbh->prepare("select dado_1 from dadosgerais where nome_dado='Tempo pra faturar pedido'");
				$stmt->execute();
				$tempo_p_faturar=$stmt->fetch(PDO::FETCH_ASSOC);
				$tempo_p_faturar=$tempo_p_faturar['dado_1'];
				echo '
				<form class="table" method="post" action="?acao=alteracao_ped">
				<table>
				<tr><td class="table_title" id="table_title_Pedido" colspan="11">Pedido com prazo máximo de faturamento em '.$tempo_p_faturar.' dias</td></tr>
				<tr>
				<td>Ref</td>
				<td>Descrição</td>
				<td>Cor</td>
				<td>P <span style="font-size:6pt">ou 4</span></td>
				<td>M <span style="font-size:6pt">ou 6</span></td>
				<td>G <span style="font-size:6pt">ou 8</span></td>
				<td>GG <span style="font-size:6pt">ou 10</span></td>
				<td>EG <span style="font-size:6pt">ou 12</span></td>
				<td>Total</td>';
				
				/*if( ($_SESSION['markup']==$markconsultor) or ($_SESSION['markup']==$markminifranqueado) ){
					$booldesc=true;
					echo '<td>Preço Tabela</td>
					<td>Desconto</td>';
				}else{$booldesc=false;}*/
				echo '<td>Preço</td>
				<td>Subtotal</td>
				</tr>';
				

				$refs=array_keys($_SESSION['pedido_ped']);
				foreach($refs as $ref){
					$cores=array_keys($_SESSION['pedido_ped'][$ref]);
					foreach($cores as $cor){
					if($cor <> 'desconto'){
						$qr="select descricao,preco from produtos where ref='$ref'";
						$sql=mysqli_query($con,$qr);
						$ln=mysqli_fetch_assoc($sql);
						$desc = $ln['descricao'];
						$preco = round($ln['preco']*$_SESSION['markup'],1);
						$tot = array_sum($_SESSION['pedido_ped'][$ref][$cor]);
						if(isset($_SESSION['pedido_ped'][$ref]['desconto'])){
							$desconto = $_SESSION['pedido_ped'][$ref]['desconto'];			
							$fator= (1-$desconto/100);
							$preco *= $fator;
						}
						$subtot = $tot * $preco;
						$totalgeral_ped += $subtot; 
						$totalpecas_ped += $tot;
						echo '<tr>
						<td>'.$ref.'</td>
						<td>'.$desc.'</td>
						<td>'.$cor.'</td>';
						$stmt=$dbh->prepare("select tamanhos from tabela_pedidos where ref=?");
						$stmt->execute(array($ref));
						$ln=$stmt->fetch(PDO::FETCH_ASSOC);
						$tamanhos=$ln['tamanhos'];
						for($i=1;$i<=5;$i++){
							if(
								( ($i==1) and ((strpos($tamanhos,'/P/')!==false)or(strpos($tamanhos,'/4/')!==false)) ) or
								( ($i==2) and ((strpos($tamanhos,'/M/')!==false)or(strpos($tamanhos,'/6/')!==false)) ) or
								( ($i==3) and ((strpos($tamanhos,'/G/')!==false)or(strpos($tamanhos,'/8/')!==false)) ) or
								( ($i==4) and ((strpos($tamanhos,'/GG/')!==false)or(strpos($tamanhos,'/10/')!==false)) ) or
								( ($i==5) and ((strpos($tamanhos,'/EG/')!==false)or(strpos($tamanhos,'/12/')!==false)) )
							)
							{
								$booltamanho=true;
								$maxt[$i] = 10;
							}else{
								$booltamanho=false;
								$maxt[$i] = 0;
							}
							$atual = $_SESSION['pedido_ped'][$ref][$cor]['t'.$i];
							echo '<td>';
							if($maxt[$i] == 0){
								echo '0';
							}else{	
								echo '<select name="'.$ref.'-'.$cor.'-t'.$i.'">';
								for($j=0;$j<=$maxt[$i];$j++){
									echo '<option '; if($atual == $j){echo 'selected="select"';}
									echo'>'.$j.'</option>';
								}
								echo '</select>';
							}	
							echo '</td>';
						}
						echo '<td>'.$tot.'</td>';
						/*if($booldesc){
							if(!isset($totalgeral_tab)){$totalgeral_tab=0;}
							$preco_tab=$preco/$_SESSION['markup']*$markconsumidor;
							$preco_tab=round($preco_tab,1);
							$desconto_tab=(1-$_SESSION['markup']/$markconsumidor)*100;
							$subtot_tab=$preco_tab*$tot;
							$totalgeral_tab += $subtot_tab;
							echo '
							<td>'.number_format($preco_tab,2,',','.').'</td>
							<td>'.number_format($desconto_tab,0).'%</td>
							';
						}*/
						echo '<td>'.number_format($preco,2,',','.').'</td>
						<td>'.number_format($subtot,2,',','.').'</td>
						</tr>
						';
					}
					}
				}
				echo '<tr><td colspan=';
				/*if($booldesc){echo '6';}else{*/echo '7';/*}*/
				echo ' style="border-bottom:none; border-left:none;"></td>
				<td>Total:</td>
				<td>'.$totalpecas_ped.'</td>
				<td></td>
				';	
				/*if($booldesc){echo '<td>'.number_format($totalgeral_tab,2,',','.').'</td><td>'.$desconto_tab.'%</td><td></td>';}*/
				echo '<td>'.number_format($totalgeral_ped,2,',','.').'</td></tr></table>
				<input type="submit" value="Alterar Pedido"/>
				<input type="button" value="ZERAR pedido" style="position:absolute; left: 300px;" onclick="if(confirm('."'Tem certeza que deseja ZERAR o pedido?'".')) window.location.href='."'?acao=zerarped_ped'".';"/>
				</form></br></br>
				';
			}
			if($_SESSION['acesso'] <> ('Representante') and ($_SESSION['acesso'] <> ('adm')) ){
				$id_usuario = $_SESSION['id_usuario'];
				//criar form, action="?acao=finalizarpedidope"
				echo '<a href="?acao=finalizarpedidope&id='.$id_usuario.'"><button onclick="return confirm('."'Tem certeza que deseja finalizar?'".')">FINALIZAR PEDIDO</button></a></br></br>';
				
				//echo '<a href="../Checkout/index.php?etapa=1';
				/*if(isset($_SESSION['pedidope'])){
					echo '&total_carrinho='.$totalgeral;
				}
				if(isset($_SESSION['pedido_ped'])){
					echo '&total_carrinho_ped='.$totalgeral_ped;
				}
				*/
			}else{
				echo '<a href="?acao=dadosdopedido"><button>FINALIZAR PEDIDO</button></a></br></br>';
			}
			
			
			if( (strpos($_SESSION['acesso'],'Representante') !== false) or (strpos($_SESSION['acesso'],'adm') !== false)  ){
				echo '<a href="?acao=formdesc"><span style="font-size: 80%;">Descontos</span></a>';
			}
		}
		if($_GET['acao']=='alteracao'){
			$refs=array_keys($_SESSION['pedidope']);
			
			//Verificar se excede limite
			$totalver=0;
			
			foreach($refs as $ref){
				$qr="select preco from produtos where ref='$ref'";
				$sql=mysqli_query($con,$qr);
				$ln=mysqli_fetch_assoc($sql);
				$precoat = round($ln['preco']*$_SESSION['markup'],1);
				$cores=array_keys($_SESSION['pedidope'][$ref]);
				foreach($cores as $cor){
				if($cor<>'desconto'){	
					for($i=1;$i<=5;$i++){
						if(isset($_POST[$ref.'-'.$cor.'-t'.$i])){
							$totalver += $_POST[$ref.'-'.$cor.'-t'.$i]*$precoat;
						}
					}
				}
				}
			}	
			
			if($totalver > $_SESSION['limitevenda']){
				echo '<script>alert("Limite excedido! Seu limite de compras é de '.$_SESSION['limitevenda'].'.");window.location.href="?acao=conferirped";</script>';
				die();
			}
			//fim do verifica se excede limite
			foreach($refs as $ref){
				$cores=array_keys($_SESSION['pedidope'][$ref]);
				foreach($cores as $cor){
				if($cor<>'desconto'){	
					for($i=1;$i<=5;$i++){
						if(isset($_POST[$ref.'-'.$cor.'-t'.$i])){
							if($_POST[$ref.'-'.$cor.'-t'.$i] <> $_SESSION['pedidope'][$ref][$cor]['t'.$i]){
								$_SESSION['pedidope'][$ref][$cor]['t'.$i]=$_POST[$ref.'-'.$cor.'-t'.$i];
							}
						}
					}
					if(array_sum($_SESSION['pedidope'][$ref][$cor])==0){
						unset($_SESSION['pedidope'][$ref][$cor]);
					}
				}
				}
			}
			$_SESSION['valor_total_pedido']=$totalver;
			echo '<script>window.location.href="?acao=conferirped"</script>';
		}
		if($_GET['acao']=='alteracao_ped'){
			$refs=array_keys($_SESSION['pedido_ped']);
			
			
			$totalver=0;	
			
			foreach($refs as $ref){
				$qr="select preco from produtos where ref='$ref'";
				$sql=mysqli_query($con,$qr);
				$ln=mysqli_fetch_assoc($sql);
				$precoat = round($ln['preco']*$_SESSION['markup'],1);
				
				$cores=array_keys($_SESSION['pedido_ped'][$ref]);
				foreach($cores as $cor){
				if($cor<>'desconto'){	
					for($i=1;$i<=5;$i++){
						if(isset($_POST[$ref.'-'.$cor.'-t'.$i])){
							if($_POST[$ref.'-'.$cor.'-t'.$i] <> $_SESSION['pedido_ped'][$ref][$cor]['t'.$i]){
								$_SESSION['pedido_ped'][$ref][$cor]['t'.$i]=$_POST[$ref.'-'.$cor.'-t'.$i];
								$totalver += $_POST[$ref.'-'.$cor.'-t'.$i]*$precoat;
							}
						}
					}
					if(array_sum($_SESSION['pedido_ped'][$ref][$cor])==0){
						unset($_SESSION['pedido_ped'][$ref][$cor]);
					}
				}
				}
			}
			$_SESSION['valor_total_pedido_ped']=$totalver;
			echo '<script>window.location.href="?acao=conferirped"</script>';
		}
		if($_GET['acao'] == 'zerarped'){
			unset($_SESSION['pedidope']);
			$_SESSION['valor_total_pedido']=0;
			echo '<script>window.location.href="?acao=conferirped"</script>';	
		}
		if($_GET['acao'] == 'zerarped_ped'){
			unset($_SESSION['pedido_ped']);
			$_SESSION['valor_total_pedido_ped']=0;
			echo '<script>window.location.href="?acao=conferirped"</script>';
		}
		if($_GET['acao']=='dadosdopedido'){
			if(isset($_SESSION['valor_total_pedido'])){$totalgeral=$_SESSION['valor_total_pedido'];}
			if(isset($_SESSION['valor_total_pedido_ped'])){$totalgeral_ped=$_SESSION['valor_total_pedido_ped'];}
			echo '<div class="underline">';
					
				echo '<b>Dados do cliente:</b></br>
				<form method="post" action="?acao=finalizarpedidope';
				if(isset($_GET['id'])){
					echo '&id='.$_GET['id'];
				}	
				echo '">';
				if(!isset($_GET['id'])){
					echo '<input type="text" name="cliente" id="cliente" size="10" onkeyup="lsbuscausuario(this.value,'."'acao=dadosdopedido'".')"/>
					<div id="livesearch"></div>
					';
				}else{	
					if($_GET['id']<>'novo'){
						$boolnovocliente=false;
						$id_usuario=$_GET['id'];
					}else{
						$boolnovocliente=true;
					}
					$boolemoutroforn=true;
					$boolcadastsenha=false;
					require("../_auxiliares/formcadastpessoa.php");
					if(isset($id_usuario)){
						$stmt=$dbh->prepare("select distinct(p.pedido) from pcp 
						join pedidos p on p.pedido=pcp.loc 
						where p.id_cliente=? and situacao == 'S'");
						$stmt->execute(array($id_usuario));
						$i=0;
						while($ln[$i]=$stmt->fetch(PDO::FETCH_ASSOC)){
							if($i==0){echo 'Juntar com o pedido ';}
							else{echo ', ou ';}
							echo '<a href="?acao=juntarpedido&pedido='.$ln[$i]['pedido'].'&id_usuario='.$id_usuario.'">'.$ln[$i]['pedido'].'</a>';
							$i++;
						}
					}
					
					
				}
				echo '</div>';
				if( (isset($_SESSION['pedidope'])) and (isset($_SESSION['pedido_ped'])) ){
				echo '</br><input type="radio" name="junto_separado" id="separado" value="separado" checked/><label for="separado">Enviar Pedido de Pronta Entrega no próximo dia útil e o Pedido com prazo quando ficar pronto.</label></br>
				<input type="radio" name="junto_separado" id="junto" value="junto"/><label for="junto">Enviar ambos os pedidos juntos quando ficarem completos.</label></br></br>';
				
			}
				$id_usuario = $_SESSION['id_usuario'];
				$qr="select email from usuarios where id_usuario = '$id_usuario'";
				$sql=mysqli_query($con,$qr);
				$ln=mysqli_fetch_assoc($sql);
				$emailusuario = $ln['email'];
				echo '<div class="underline">
				<b>Dados do pedido:</b>
				</br><label for="prazopag">Prazo de Pagamento: </label>
				<input type="text" name="prazopag" id="prazopag" size="12"/>
				<select name="tiporemessa">
				<option>Venda</option>
				<option>Consignado</option>
				</select>';
				//<input type="hidden" name="valor_pagamento" id="valor_pagamento" value="'.number_format($totalgeral,2,',','.').'"/>
				//<input type="hidden" name="tipo_pagamento" id="tipo_pagamento" value="NULL"/>';
								
				echo '</br>
				<label for="obs">Observações</label></br><textarea name="obs" id="obs" cols="60" rows="2"></textarea></br>
				</div>
				<div class="underline">
				<b>Emails</b></br>';
				if(isset($email)){
					if($email<>''){echo '<input type="checkbox" name="emailcliente" id="emailcliente" value ="'.$email.'" checked/><label for="emailcliente">'.$email.'</label>';}
					if($emailusuario<>$email){
						echo '<input type="checkbox" name="emailusuario" id="emailusuario" value="'.$emailusuario.'" checked/>
						<label for="emailusuario">'.$emailusuario.'</label>';
					}
				}else{
					echo '<input type="checkbox" name="emailusuario" id="emailusuario" value="'.$emailusuario.'" checked/>
						<label for="emailusuario">'.$emailusuario.'</label>';
				}
				
				$stmt=$dbh->prepare("select dado_1 from dadosgerais where nome_dado='E-mail pedido'");
				$stmt->execute();
				$ln=$stmt->fetch(PDO::FETCH_ASSOC);
				
				echo'
				<input type="hidden" name="emailrolf" id="emailrolf" value="'.$ln['dado_1'].'" />';
				
				
				echo '</br>
				<div id="outrosemails"></div>
				Outro E-mail:<input type="email" name="emailadd" id="emailadd"/><input type="button" onclick="addEmail(document.getElementById('."'emailadd'".').value)" value="Adicionar E-mail"/>
				</div>
				<input onclick="return confirm('."'Tem certeza que deseja finalizar?'".')" type="submit" name="submit" value="Finalizar"/>
				</form>';
		}
		if($_GET['acao']=='finalizarpedidope'){
			if(!isset($_GET['id'])) {
				require("../_auxiliares/cadastpessoa.php");
				$stmt=$dbh->prepare("select id_usuario from usuarios where cnpj =?");
				$stmt->execute(array($cnpj));
				$ln=$stmt->fetch(PDO::FETCH_ASSOC);
				$id_cliente=$ln['id_usuario'];
			}else{$id_cliente = $_GET['id'];}

			$dataped=date('Y-m-d');
			
			//$valor_pagamento=$_POST['valor_pagamento'];
			if(isset($_POST['junto_separado'])){$junto_separado=$_POST['junto_separado'];}
			else{$junto_separado='separado';}
			
			if( $junto_separado=='junto'){
				//Passar pedido PE para pedido PD
				$refs=array_keys($_SESSION['pedidope']);
				foreach($refs as $ref){
					if(!isset($_SESSION['pedido_ped'][$ref])){
						$cores=array_keys($_SESSION['pedidope'][$ref]);
						foreach($cores as $cor){
							for($i=1;$i<=5;$i++){
								$_SESSION['pedido_ped'][$ref][$cor]['t'.$i] = $_SESSION['pedidope'][$ref][$cor]['t'.$i];
							}
						}
					}else{
						$cores=array_keys($_SESSION['pedidope'][$ref]);
						foreach($cores as $cor){
							if(!isset($_SESSION['pedido_ped'][$ref][$cor])){
								for($i=1;$i<=5;$i++){
									$_SESSION['pedido_ped'][$ref][$cor]['t'.$i] = $_SESSION['pedidope'][$ref][$cor]['t'.$i];
								}
							}else{
								for($i=1;$i<=5;$i++){
									$_SESSION['pedido_ped'][$ref][$cor]['t'.$i] += $_SESSION['pedidope'][$ref][$cor]['t'.$i];
								}
							}
						}
					}
				}
				unset($_SESSION['pedidope']);
			}
			
			//se for separado tem que ser gerado 2 pedidos. Juntos pode juntar os pedidos em 1 apenas...
				
			if(strpos($_SESSION['acesso'],'Representante') !== false){
				$id_vendedor=$_SESSION['id_usuario'];
			}else{
				$id_vendedor=null;
			}
			
		
			$prazopag = isset($_POST['prazopag']) ? $_POST['prazopag'] : '';
			$obs = isset($_POST['obs']) ? $_POST['obs'] : '';
		
			if(isset($_POST['tiporemessa'])){
				if($_POST['tiporemessa']=='Consignado'){
					if($obs = ''){
						$obs.="Pedido Consignado";
					}else{
						$obs.=" / Pedido Consignado";
					}	
				}
			}
				
			$status_pagamento=0;
			
			if( isset($_SESSION['pedidope']) ){
				//
				$qr="select max(loc) from pcp where loc like 'PE%'";
				$sql=mysqli_query($con,$qr);
				$ln=mysqli_fetch_assoc($sql);
				$numpe=intval(substr($ln['max(loc)'],2,5));
				$numpenovo=$numpe+1;
				if($numpenovo<10){
					$loc_pe='PE000'.$numpenovo;
				}elseif($numpenovo<100){
					$loc_pe='PE00'.$numpenovo;
				}elseif($numpenovo<1000){
					$loc_pe='PE0'.$numpenovo;
				}elseif($numpenovo<10000){
					$loc_pe='PE'.$numpenovo;
				}
				
				$dataentrega=$dataped;
				
				$stmt=$dbh->prepare("insert into pedidos (pedido,id_cliente,id_vendedor,dataped,dataentrega,prazopag,status_pagamento,obs) values(?,?,?,?,?,?,?,?)");
				if($stmt->execute(array($loc_pe,$id_cliente,$id_vendedor,$dataped,$dataentrega,$prazopag,$status_pagamento,$obs))){
					echo 'Pedido de Pronta Entrega cadastrado com sucesso!</br></br>';
				}else{
					$arr=$stmt->errorInfo();
					print_r($arr);
					echo 'Erro ao cadastrar pedido. Favor entrar em contato com suporte.';
				}
			}
			if(isset($_SESSION['pedido_ped'])){
				$stmt=$dbh->prepare("select max(pedido) from pedidos where pedido like 'PD%'");
				$stmt->execute();
				$ln=$stmt->fetch(PDO::FETCH_ASSOC);
				$PD=$ln['max(pedido)'];
				$expPD=explode('-',$PD);
				$numPD=$expPD[1];
				$num_novo_PD=$numPD+1;
				if($num_novo_PD<10){
					$loc_pd='PD-00000000'.$num_novo_PD;
				}elseif($num_novo_PD<100){
					$loc_pd='PD-0000000'.$num_novo_PD;
				}elseif($num_novo_PD<1000){
					$loc_pd='PD-000000'.$num_novo_PD;
				}elseif($num_novo_PD<10000){
					$loc_pd='PD-00000'.$num_novo_PD;
				}elseif($num_novo_PD<100000){
					$loc_pd='PD-0000'.$num_novo_PD;
				}elseif($num_novo_PD<1000000){
					$loc_pd='PD-000'.$num_novo_PD;
				}elseif($num_novo_PD<10000000){
					$loc_pd='PD-00'.$num_novo_PD;
				}elseif($num_novo_PD<100000000){
					$loc_pd='PD-0'.$num_novo_PD;
				}elseif($num_novo_PD<1000000000){
					$loc_pd='PD-'.$num_novo_PD;
				}
				$stmt=$dbh->prepare("select dado_1 from dadosgerais where nome_dado='Tempo pra faturar pedido'");
				$stmt->execute();
				$tempo_p_faturar=$stmt->fetch(PDO::FETCH_ASSOC);
				$tempo_p_faturar='+'.($tempo_p_faturar['dado_1']-3).' days';
				$dataentr=date('Y-m-d',strtotime($tempo_p_faturar,strtotime($dataped)));
				$stmt=$dbh->prepare("insert into pedidos (pedido,id_cliente,id_vendedor,dataped,dataentrega,prazopag,status_pagamento,obs) values(?,?,?,?,?,?,?,?)");
				if($stmt->execute(array($loc_pd,$id_cliente,$id_vendedor,$dataped,$dataentr,$prazopag,$status_pagamento,$obs))){
					echo 'Pedido com prazo cadastrado com sucesso!</br></br>';
				}else{
					$arr=$stmt->errorInfo();
					print_r($arr);
					echo 'Erro ao cadastrar pedido. Favor entrar em contato com suporte.';
				}
			}
				
			
			if(isset($_SESSION['pedidope'])){
				$tipodopedido='pedidope';
				$loc=$loc_pe;
				require("../_auxiliares/emailAddPedidoPE.php");
				require("../_auxiliares/addPedidoPE.php");
				unset($_SESSION['pedidope']);
			}
			if(isset($_SESSION['pedido_ped'])){
				$loc=$loc_pd;
				$tipodopedido='pedido_ped';
				$origem='catalogo';
				$pedido=$loc_pd;
				
				require("../_auxiliares/emailAddPedidoPE.php");	
				require("../_auxiliares/addPedido_ped.php");
				//$_SESSION['pedido_ped'] é 'unset'ada se isset no addPedido_ped.php 
			}
			if(isset($_GET['id']) ){echo '<script>alert("Seu pedido foi cadastrado com sucesso!")</script>';}
			echo '<script>window.location.href="../index.php"</script>';
			
		}if($_GET['acao'] == 'juntarpedido'){
			$loc=$_GET['pedido'];
			$id_usuario=$_GET['id_usuario'];
			$stmt=$dbh->prepare("select dado_1 from dadosgerais where nome_dado='E-mail pedido'");
			$stmt->execute();
			$ln=$stmt->fetch(PDO::FETCH_ASSOC);
			$emailrolf=$ln['dado_1'];
			$stmt=$dbh->prepare("select email from usuarios where id_usuario=?");
			$stmt->execute(array($id_usuario));
			$ln=$stmt->fetch(PDO::FETCH_ASSOC);
			$emailcliente=$ln['email'];
			$stmt=$dbh->prepare("select email from usuarios where id_usuario=?");
			$stmt->execute(array($_SESSION['id_usuario']));
			$ln=$stmt->fetch(PDO::FETCH_ASSOC);
			$emailusuario=$ln['email'];
			$booljuntar=true;
			require("../_auxiliares/emailAddPedidoPE.php");
			require("../_auxiliares/addPedidoPE.php");
			unset($_SESSION['pedidope']);
			echo 'Novo pedido adicionado ao pedido '.$loc;
		}	
		
		if($_GET['acao']=='formdesc'){
			echo '
			<div id="desapbutton"> 
			<form method="post" action="?acao=descontos">
				<label for="desc">Desconto (%):</label><input type="number" name="desc" id="desc"/>
			 	<input type="submit" value="Inserir desconto"/></br>
			</form>
			 <button onclick="showlistprod()">Desconto por produto</button></br>
			</div>
			 <div id="listprod" style="display:none">
			 <form method="post" action="?acao=descontos">
			 <table>
			 <tr>
			 <td>Ref</td>
			 <td>Descrição</td>
			 <td>Preço</td>
			 <td>Desconto(%)</td>
			 <td>Novo Preço</td>
			 </tr>';
			 $refs=array_keys($_SESSION['pedidope']);
			 foreach($refs as $ref){
				$qr="select descricao,preco from produtos where ref='$ref'";
				$sql=mysqli_query($con,$qr);
				$ln=mysqli_fetch_assoc($sql);
				$desc=$ln['descricao'];
				$precotab=round($ln['preco']*$_SESSION['markup'],1);
				$fator=1;
				if(isset($_SESSION['pedidope'][$ref]['desconto'])){
					$desconto = $_SESSION['pedidope'][$ref]['desconto'];			
					$fator= (1-$desconto/100);
				}
				$preco = $precotab * $fator;
				$preco = number_format($preco,2);
				echo"<tr>
				<td>$ref</td>
				<td>$desc</td>
				<td>$precotab</td>
				<td><input type='text' size='2' name='desconto-$ref' onkeyup='calc_desc(this.value,".'"'.$ref.'"'.",$precotab)' ";
				if(isset($_SESSION['pedidope'][$ref]['desconto'])){
					echo 'value= "'.$desconto.'"';
				}
				echo "/></td>
				<td><input type='text' size='6' id='preconovo-$ref' value='$preco' readonly/></td>
				</tr>";
			}
			 echo'</table>
			 <input type="submit" value="Inserir desconto"/></br>
			 </form></div>';
			/*$pedido=$_GET['pedido'];
			if(isset($_GET['data'])){$data=$_GET['data'];}else{$data='';}
			$maisdeum=false;
			if($data == ''){
				$qr="select distinct(data_entrega) from pcp where loc='$pedido' and situacao='E' order by data_entrega";
				$sql=mysqli_query($con,$qr);
				if(mysqli_num_rows($sql)>1){
					echo 'Temos mais que uma entrega para o mesmo pedido:</br>';
					$maisdeum=true;
					$table=array();
					while($row = $sql->fetch_assoc()){ 
						$table[] = $row;
					}
					foreach($table as $dia){
						$d=date('d-m-Y',strtotime($dia['data_entrega']));
						$dlink=date('Y-m-d',strtotime($dia['data_entrega']));
						echo '<a href="?acao=romaneio&pedido='.$pedido.'&data='.$dlink.'">Dia: '.$d.'</a></br>'; 
					}
					echo 'Selecione uma data ou dará desconto em porcentagem para todas as entregas abaixo:</br>';
				}
			}
			$qr="select pcp.ref,produtos.descricao,sum(pcp.tot),pcp.preco,pcp.desconto,pcp.preco*(1-pcp.desconto/100)*sum(pcp.tot) from pcp
			join produtos on pcp.ref=produtos.ref
			where loc='$pedido' and ";
			if($data <> ""){$qr .= "data_entrega >='$data' and data_entrega <='$data 23:59:59' and ";}
			$qr .= "tot>0 group by ref order by ref";
			$sql=mysqli_query($con,$qr);
			$table=array();
			while($row = $sql -> fetch_assoc()){
				$table[] = $row;
			}
			$qr="select sum(desconto) from pcp where loc='$pedido'";
			$sql=mysqli_query($con,$qr);
			$ln=mysqli_fetch_assoc($sql);
			$total = 0;
			$totaldescontos = 0;
			$totalsemdescontos = 0;
			$sumdesc=$ln['sum(desconto)'];
			foreach($table as $linha){
				$subtotal = $linha['pcp.preco*(1-pcp.desconto/100)*sum(pcp.tot)'];
				$total += $subtotal;
				$totaldescontos += $linha['preco']*$linha['desconto']/100*$linha['sum(pcp.tot)'];
				$totalsemdescontos +=  $linha['preco']*$linha['sum(pcp.tot)'];
			}
			
			echo '<h3>Total do Pedido';
			if($total == $totalsemdescontos){echo ' : R$ '.number_format($total,2,',','.');}
			else{echo ' (sem desconto): R$ '.number_format($totalsemdescontos,2,',','.');}
			if($maisdeum){
				echo ' - Data Entrega: '.date('d-m-Y',strtotime($data));
			}
			echo '</h3>';
			if($sumdesc > 0){
				echo 'Pedido possui desconto total de R$ '.number_format($totaldescontos,2,',','.').' em porcentagens nos produto</br></br>';
			}
			$qr="select desconto_pedido from pedidos where pedido='$pedido'";
			$sql=mysqli_query($con,$qr);
			$ln=mysqli_fetch_assoc($sql);
			$desconto_pedido=$ln['desconto_pedido'];
			if($desconto_pedido > 0){
				echo 'Pedido possui desconto em valor de R$ '.number_format($desconto_pedido,2,',','.').'</br></br>';
			}
			
			if(!$maisdeum){
				echo '<form method="post" action="?acao=dardesconto&pedido='.$pedido.'&tipo=porvalorpedido"><label for="descvalor">';
				if($desconto_pedido>0){echo 'Substituir d';}else{echo 'D';}
				echo 'esconto por valor: R$ </label><input type="text" name="descvalor" id="descvalor" size=5/>
				<input type="submit" value="Descontar no orçamento"/></br></br>
				</form>';
			}
			echo '<div id="desapbutton">';
			echo '<form method="post" action="?acao=dardesconto&pedido='.$pedido;
			if($maisdeum){echo '&data='.$data;} 
			echo'&tipo=porctodosprods"><label for="descporcprods">';
			if($totaldescontos>0){echo 'Substituir d';}else{echo 'D';}
			echo 'esconto porcentagem em todos produtos: </label><input type="text" name="descporcprods" id="descporcprods" size=5/>% 
			<input type="submit" value="Descontar em todos produtos"/>
			</form></br>
			</div>
			<button onclick="showlistprod()">Desconto por produto</button>
			<div id="listprod" style="display:none">
			<form method="post" action="?acao=dardesconto&pedido='.$pedido;
			if($maisdeum){echo '&data='.$data;}
			echo'&tipo=cadaprod">
			<table>
			<tr>
			<td>Ref</td>
			<td>Descrição</td>
			<td>Preço</td>
			<td>Desconto(%)</td>
			<td>Novo Preço</td>
			</tr>';
			var_dump($desconto_pedido);
			foreach($table as $linha){
				
				echo '<tr>
				<td>'.$linha['ref'].'</td>
				<td>'.$linha['descricao'].'</td>
				<td>'.number_format($linha['preco'],2,',','.').'</td>
				<td><input type="text" name="desconto-'.$linha['ref'].'" id="desconto-'.$linha['ref'].'" onkeyup="calc_desc(this.value,'."'".$linha['ref']."'".','.$linha['preco'].')" size=5 value="'.number_format($linha['desconto'],2,',','.').'"/></td>
				<td><input type="text" size=6 id="preconovo-'.$linha['ref'].'" name="preconovo-'.$linha['ref'].'" value="'.number_format($linha['preco']-$linha['preco']*$linha['desconto']/100,2,',','.').'" onkeyup="calc_porcent_mud_valor(this.value,'."'".$linha['ref']."'".','.$linha['preco'].')"/></td>
				</tr>';
			}
			echo '</table>
			<input type="submit" value="Descontos em produtos"/>';
			echo'</form></div>';*/
			
		}
		if($_GET['acao']=='descontos'){
			if(isset($_POST['desc'])){
				$desconto = $_POST['desc'];
			}
			$refs=array_keys($_SESSION['pedidope']);
			foreach($refs as $ref){
				if(isset($_POST['desc'])){
					$desconto = $_POST['desc'];
				}elseif(isset($_POST['desconto-'.$ref])){
					$desconto=$_POST['desconto-'.$ref];
				}
				$desconto=str_replace(',','.',$desconto);
				if($desconto == ""){$desconto = 0;}
				$_SESSION['pedidope'][$ref]['desconto']=$desconto;	
				
			}
			echo '<script>window.location.href="?acao=conferirped"</script>';
		}
			
	}else{
		if(isset($_GET['acao'])){
			if(!isset($_SESSION['id_usuario'])){
				echo '<script>alert("É necessário estar logado para realizar pedido...");window.location.href="catalogo.php"</script>';
			}
		}
				
	}
	echo '</div>';
	require '../footer.php';
?>	 
</body>
</html>
 