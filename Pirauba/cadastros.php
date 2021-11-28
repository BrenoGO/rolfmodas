<!DOCTYPE html>
<html>
<head>
  <?php
	require '../head.php';
	?>
  <title>Cadastros</title>
  <script src="../_javascript/functionspirauba.js"></script>
  
</head>
<body>
	<?php
	session_start();
	require '../_config/conection.php'; 
	$con = conection();
	mysqli_set_charset($con,"utf8");
	$dbh = new PDO('mysql:host='.$host.';dbname='.$db.';charset=utf8',$user,$pass);
	require '../header_geral.php';
	require 'menu.php';
	echo '
	</header>
	<div class="corpo">';
	
	if(isset($_GET['acao'])){
		
			if(isset($_GET['ref'])){
				$ref=$_GET['ref'];
				$qr="select * from produtos where ref='$ref'";
				$sql=mysqli_query($con,$qr);
				$ln=mysqli_fetch_assoc($sql);
				$desc = $ln['descricao'];
				$tipo=$ln['tipo'];
				$arraytipo=explode(';',$tipo);
				$material=$arraytipo[0];
				$tamini=$arraytipo[1];
				$tamfin=$arraytipo[2];
				$preco=str_replace('.',',',$ln['preco']);
				if($ln['preco_ant']>0){
					$preco_ant=str_replace('.',',',$ln['preco_ant']);
				}else{
					$preco_ant=0;
				}
				
				$tempo=$ln['tempo'];
				$secao=$ln['secao'];
				$tags=$ln['Tags'];
				$NCM=$ln['NCM'];
				$loc_estoque=$ln['loc_estoque'];
			}else{
				if(!isset($tags)){$tags='';}
			}
			echo 
			'<form method="post" id="cadastprod" action="?acao=cadastrarproduto';if(isset($ref)){echo '&ref='.$ref;}echo'">
			<label for="ref">Ref:</label><input type="text" name="ref" id="ref" size="5" maxlength="6"';if(isset($ref)){echo ' value="'.$ref.'"';}echo'/>
			<label for="desc">Descrição:</label><input type="text" name="desc" id="desc" size="20" maxlength="50"';if(isset($desc)){echo ' value="'.$desc.'"';}echo'/>
			<label for="preco">Preço:</label><input type="text" name="preco" id="preco" size="6"';if(isset($preco)){echo ' value="'.$preco.'"';}echo'/>
			<label for="tempo">Tempo de Costura:</label><input type="number" name="tempo" id="tempo" size="6"';if(isset($tempo)){echo ' value="'.$tempo.'"';}echo'/></br>
			<label for="secao">Seção: </label>
			<select id="secao" name="secao"> 
			<option';if(isset($secao)){if($secao=='Feminino'){echo ' selected=selected';}}echo '>Feminino</option>
			<option';if(isset($secao)){if($secao=='Infantil'){echo ' selected=selected';}}echo '>Infantil</option>
			<option';if(isset($secao)){if($secao=='Masculino'){echo ' selected=selected';}}echo '>Masculino</option>
			</select></br>
			<label for="grupo">Grupo:</label><select name="grupo" id="grupo">
			<option></option>';
			$stmt=$dbh->prepare("select dado_1 from dadosgerais where nome_dado=?");
			$stmt->execute(array('Grupos'));
			$ln=$stmt->fetch(PDO::FETCH_ASSOC);
			$nome_grupos=explode(';',$ln['dado_1']);
			
			foreach($nome_grupos as $grupo){
			if($grupo <> 'Plus' and $grupo <>'Fitness'){
				echo '<option ';
				if(strpos($tags,$grupo) !== false){
					echo 'selected=selected ';
				}
				if($grupo == 'Macacoes'){echo 'value=Macacoes>Macacões';}
				elseif($grupo == 'CalcaBermuda'){echo 'value=CalcaBermuda>Calças e Bermudas';}
				elseif($grupo == 'Plus'){echo 'value=Plus>Plus Size';}
				elseif($grupo =='Silkadas'){echo 'value=Silkadas>Camisas Estampadas';}
				elseif($grupo =='CamisaLisa'){echo 'value=CamisaLisa>Camisa Lisa';}
				elseif($grupo =='Polo'){echo 'value=Polo>Camisa Polo';}
				else{echo 'value='.$grupo.'>'.$grupo;}
				echo '</option>';
			}	
			}
			
			echo '
			</select>
			</br>
			TAGs:
			<input type="checkbox" name="Plus" id="Plus" value="Plus"';if(strpos($tags,'Plus') !== false){echo ' checked';}echo'/><label for="Plus">Plus Size</label>
			<input type="checkbox" name="Fitness" id="Fitness" value="Fitness"';if(strpos($tags,'Fitness') !== false){echo ' checked';}echo'/><label for="Fitness">Fitness</label>
			<input type="checkbox" name="promo" id="promo" onclick="se_promo()" value="promo"';if(strpos($tags,'promo') !== false){echo ' checked';}echo'/><label for="promo">Promoção</label>
			
			<input type="checkbox" name="oculto" id="oculto" value="oculto"';if(strpos($tags,'oculto') !== false){echo ' checked';}echo'/><label for="oculto">Ocultar</label>
			<input type="checkbox" name="Novidade" id="Novidade" value="Novidade"';if(strpos($tags,'Novidade') !== false){echo ' checked';}echo'/><label for="Novidade">Novidade!</label>
			</br>
			<div id="se_promocao" style="display:';
			if(strpos($tags,'promo') !== false){echo 'block';}else{echo 'none';}
			echo '">
			<label for="preco_ant">Preço Cheio (sem promoção): </label><input size="7" type="text" name="preco_ant" id="preco_ant"';
			if(isset($preco_ant)){if($preco_ant>$preco){echo 'value="'.$preco_ant.'"';}}
			echo '/>
			</div>
			
			</br>
			
			<input type="radio" name="material" id="viscolycralisa" value="VL"';if(isset($material)){if($material=='VL'){echo ' checked';}}echo'/><label for="viscolycralisa">Viscolycra Lisa</label>
			<input type="radio" name="material" id="viscolycraestamp" value="VE"';if(isset($material)){if($material=='VE'){echo ' checked';}}echo'/><label for="viscolycraestamp">Viscolycra estampada</label></br>
			<input type="radio" name="material" id="viscoselisa" value="CL"';if(isset($material)){if($material=='CL'){echo ' checked';}}echo'/><label for="viscoselisa">Viscose Lisa</label>
			<input type="radio" name="material" id="viscoseestamp" value="CE"';if(isset($material)){if($material=='CE'){echo ' checked';}}echo'/><label for="viscoseestamp">Viscose estampada</label></br>
			<input type="radio" name="material" id="suplexbodyliso" value="BL"';if(isset($material)){if($material=='BL'){echo ' checked';}}echo'/><label for="suplexbodyliso">Suplex Body Liso</label>
			<input type="radio" name="material" id="suplexbodyestamp" value="BE"';if(isset($material)){if($material=='BE'){echo ' checked';}}echo'/><label for="suplexbodyestamp">Suplex Body Estampado</label></br>
			<input type="radio" name="material" id="suplexcalcaliso" value="SL"';if(isset($material)){if($material=='SL'){echo ' checked';}}echo'/><label for="suplexcalcaliso">Suplex Calça Liso</label></br>
			<input type="radio" name="material" id="neopreneliso" value="NL"';if(isset($material)){if($material=='NL'){echo ' checked';}}echo'/><label for="neopreneliso">NeoPrene</label>
			<input type="radio" name="material" id="neopreneest" value="NE"';if(isset($material)){if($material=='NE'){echo ' checked';}}echo'/><label for="neopreneest">NeoPrene Est</label></br>
			<input type="radio" name="material" id="piquet" value="PL"';if(isset($material)){if($material=='PL'){echo ' checked';}}echo'/><label for="piquet">Piquet</label>
			<input type="radio" name="material" id="algodao" value="AL"';if(isset($material)){if($material=='AL'){echo ' checked';}}echo'/><label for="algodao">100% Algodão</label>
			<input type="radio" name="material" id="algelast" value="AE"';if(isset($material)){if($material=='AE'){echo ' checked';}}echo'/><label for="algelast">Algodão c Elastano</label></br>
			<input type="radio" name="material" id="poliamida" value="FL"';if(isset($material)){if($material=='FL'){echo ' checked';}}echo'/><label for="poliamida">Poliamida Fitness</label>
			<input type="radio" name="material" id="renda" value="RE"';if(isset($material)){if($material=='RE'){echo ' checked';}}echo'/><label for="renda">Renda</label>
			<input type="radio" name="material" id="invernoliso" value="IL"';if(isset($material)){if($material=='IL'){echo ' checked';}}echo'/><label for="invernoliso">Outros de Inverno Liso</label></br></br>
			Tamanho de:<input type="text" name="tamini" id="tamini" size="1" maxlength="2"';if(isset($tamini)){echo ' value="'.$tamini.'"';}echo'/> a <input type="text" name="tamfin" id="tamfin" size="1" maxlength="2"';if(isset($tamfin)){echo ' value="'.$tamfin.'"';}echo'/></br>
			<label for="loc_estoque">Local no estoque: </label><input type="text" name="loc_estoque" id="loc_estoque" size="8"';
			if(isset($loc_estoque)){echo ' value="'.$loc_estoque.'"';}echo'/> </br>
			<label for="NCM">NCM:</label><input type="text" name="NCM" id="NCM" size="7"';if(isset($NCM)){echo ' value="'.$NCM.'"';}echo'/></br></br>
			</br></br>
			<input type="submit" name="submit" value="';
			if(isset($ref)){echo 'Alterar Cadastro Deste Produto';}
			else{echo 'Cadastrar Produto';}
			echo '" />
			<input type="reset" id="limpar" name="limpar" value="Limpar"/>
			</form>	';
		}
		if($_GET['acao']=='cadastrarproduto'){
			$refa = $_POST['ref'];
			$ref=mb_strtoupper($refa, 'UTF-8');
			$desca = $_POST['desc'];
			$desc= ucwords(strtolower($desca));
			$precostr = $_POST['preco'];
			$preco= floatval(str_replace(',','.',$precostr));
			$preco_ant_str = $_POST['preco_ant'];
			$preco_ant= floatval(str_replace(',','.',$preco_ant_str));
			$tempo = $_POST['tempo'];
			$secao= $_POST['secao'];
			$material = $_POST['material'];
			$taminia = $_POST['tamini'];
			$tamini= mb_strtoupper($taminia, 'UTF-8');
			$tamfina = $_POST['tamfin'];
			$tamfin= mb_strtoupper($tamfina, 'UTF-8');
			$tipo = $material.';'.$tamini.';'.$tamfin;
			$NCM=$_POST['NCM'];
			$loc_estoque=$_POST['loc_estoque'];
			$Grupo=$_POST['grupo'];
			$Tags='Grupo:'.$_POST['grupo'].';';
			if(isset($_POST['Plus'])){$Tags .= 'Grupo:Plus;';}
			if(isset($_POST['Fitness'])){$Tags .= 'Grupo:Fitness;';}
			if(isset($_POST['promo'])){$Tags .= 'promo;';}
			if(isset($_POST['oculto'])){$Tags .= 'oculto;';}
			if(isset($_POST['Novidade'])){$Tags .= 'Novidade;';}
			//var_dump($_POST);
			$Tags=substr($Tags,0,-1);
			if(isset($_GET['ref'])){
				$refant=$_GET['ref'];
				$stmt=$dbh->prepare("update produtos set ref=?,descricao=?,tipo=?,preco=?,preco_ant=?,tempo=?,secao=?,Tags=?,grupo=?,NCM=?,loc_estoque=? where ref=?");
				if($stmt->execute(array($ref,$desc,$tipo,$preco,$preco_ant,$tempo,$secao,$Tags,$Grupo,$NCM,$loc_estoque,$refant))){
					echo '</br></br><h2>Alterações realizadas</h2>';
				}
			}else{
				$stmt=$dbh->prepare("insert into produtos (ref,descricao,tipo,preco,preco_ant,tempo,secao,Tags,grupo,NCM,loc_estoque) values (?,?,?,?,?,?,?,?,?,?,?)");
				if($stmt->execute(array($ref,$desc,$tipo,$preco,$preco_ant,$tempo,$secao,$Tags,$Grupo,$NCM,$loc_estoque))){
					echo '</br></br><h2>Cadastro realizado com sucesso!</h2>';
				}
			}
		}
	}
	?>
	</div>
</body>
</html>
 