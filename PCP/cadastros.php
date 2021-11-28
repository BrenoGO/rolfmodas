<!DOCTYPE html>
<html>
<head>
  <?php
	require '../head.php';
	?>
  <title>Cadastros</title>
  <script src="_javascript/functionscadast.js"></script>
  <script src="../_javascript/functions.js"></script>
  
</head>
<body>
	<?php
	require '../config.php';
	require 'config.php';
	require '../header_geral.php';
	require 'menu.php';
	echo '
	<nav id="submenu">
	<a href="?acao=buscaprod"><button>Produtos</button></a>
	<a href="?acao=buscacor"><button>Cores</button></a>
	<a href="?acao=buscausuario"><button>Usuário</button></a>
	<a href="?acao=lsacessousuario"><button>Dados de Usuário</button></a>
	<a href="?acao=alterarsenha"><button>Alterar Senha</button></a>
	<a href="?acao=fotos"><button>Fotos</button></a>
	</nav>
	</header>
	<div class="corpo">
	';
	
	if(isset($_GET['acao'])){
		
		if($_GET['acao']=='cadastprod'){
			if(isset($_GET['ref'])){
				$boolRef = true;

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
				$boolRef = false;
				$ref = '';
			}
			echo 
			'<form method="post" id="cadastprod" action="?acao=cadastrarproduto';if($boolRef){echo '&ref='.$ref;}echo'">
			<label for="ref">Ref:</label><input type="text" name="ref" id="ref" size="5" maxlength="6"';if($boolRef){echo ' value="'.$ref.'"';}echo'/>
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
				elseif($grupo =='Polo'){echo 'value=Polo>Camisa Pólo';}
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
			<input type="radio" name="material" id="neopreneest" value="NE"';if(isset($material)){if($material=='NE'){echo ' checked';}}echo'/><label for="neopreneest">NeoPrene Est</label></br>';
			//<input type="radio" name="material" id="piquet" value="PL"';if(isset($material)){if($material=='PL'){echo ' checked';}}echo'/><label for="piquet">Piquet</label>
			echo '<input type="radio" name="material" id="algodao" value="AL"';if(isset($material)){if($material=='AL'){echo ' checked';}}echo'/><label for="algodao">100% Algodão</label>
			<input type="radio" name="material" id="algelast" value="AE"';if(isset($material)){if($material=='AE'){echo ' checked';}}echo'/><label for="algelast">Algodão c Elastano</label></br>
			<input type="radio" name="material" id="linho" value="FL"';if(isset($material)){if($material=='FL'){echo ' checked';}}echo'/><label for="linho">Linho</label>
			<input type="radio" name="material" id="renda" value="RE"';if(isset($material)){if($material=='RE'){echo ' checked';}}echo'/><label for="renda">Renda</label>
			<input type="radio" name="material" id="invernoliso" value="IL"';if(isset($material)){if($material=='IL'){echo ' checked';}}echo'/><label for="invernoliso">Outros de Inverno Liso</label></br></br>
			Tamanho de:<input type="text" name="tamini" id="tamini" size="1" maxlength="2"';if(isset($tamini)){echo ' value="'.$tamini.'"';}echo'/> a <input type="text" name="tamfin" id="tamfin" size="1" maxlength="2"';if(isset($tamfin)){echo ' value="'.$tamfin.'"';}echo'/></br>
			<label for="loc_estoque">Local no estoque: </label><input type="text" name="loc_estoque" id="loc_estoque" size="8"';
			if(isset($loc_estoque)){echo ' value="'.$loc_estoque.'"';}echo'/> </br>
			<label for="NCM">NCM:</label><input type="text" name="NCM" id="NCM" size="7"';if(isset($NCM)){echo ' value="'.$NCM.'"';}echo'/></br></br>';
			for($i=1;file_exists('_fotos/'.$ref.'-'.$i.'.jpg');$i++){
				echo '
				<div style="display:flex">
					<div style="display:flex-box">
						<img src="_fotos/'.$ref.'-'.$i.'.jpg" style="height:200px; margin: 10px"/>
					</div>
					<div style="display:flex-box; padding: 10px; margin-top: 20px;">
						<input type="file" id="pic-'.$ref.'-'.$i.'"/></br>
						<input type="button" class="butAlterProdPic" id="butAlter-'.$ref.'-'.$i.'" value="Alterar"/>
						</br></br></br>
						<input type="button" class="butExcluirProdPic" id="butExcluir-'.$ref.'-'.$i.'" value="Excluir"/>
					</div>
				</div>
				';
			}
			if($boolRef){
				echo '
				</br>
				<input type="file" id="pic-'.$ref.'-'.$i.'"/>
				</br>
				<input type="button" class="butAlterProdPic" id="butAlter-'.$ref.'-'.$i.'" value="Adicionar Nova Foto"/>
				</br>';
			}
			echo '
			</br></br>
			<input type="submit" name="submit" value="';
			if($boolRef){echo 'Alterar Cadastro Deste Produto';}
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
			$tempo = $tempo + 0;
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
				$qr = "insert into produtos (ref,descricao,tipo,preco,preco_ant,tempo,secao,Tags,grupo,NCM,loc_estoque) values (?,?,?,?,?,?,?,?,?,?,?)";
				$values = [$ref,$desc,$tipo,$preco,$preco_ant,$tempo,$secao,$Tags,$Grupo,$NCM,$loc_estoque];
				$stmt = executeSQL($dbh, $qr, $values);
				if(!is_string($stmt)){
					echo '</br></br><h2>Cadastro realizado com sucesso!</h2>';
				}else{
					echo $stmt;
				}
			}
		}
		if($_GET['acao']=='cadastcor'){
			echo 
			'<form method="post" id="cadastprod" action="?acao=cadastrarcor">
			<label for="refcor">Ref da cor:</label><input type="text" name="refcor" id="refcor" size="5" maxlength="6"/>
			<label for="nomecor">Nome da cor:</label><input type="text" name="nomecor" id="nomecor" size="20" maxlength="50"/></br>
			<input type="checkbox" name="if_Est" id="if_Est" value="E"/><label for="if_Est">Estampada</label></br></br>
			<input type="file" name="fotoCor"/></br>
			<input type="submit" name="submit" value="Cadastrar Cor" />
			<input type="reset" id="limpar" name="limpar" value="Limpar"/>
			</form>	';
		}
		if($_GET['acao']=='resbuscacor'){
			$busca = $_GET['busca'];
			$cor= mb_strtoupper($busca, 'UTF-8');
			
			$stmt=$dbh->prepare("select * from cores where refcor = ?");
			$stmt->execute(array($cor));
			$linha=$stmt->fetch(PDO::FETCH_ASSOC);
			if($linha['tipo']=='E'){
				$estamp=true;
			}else{
				$estamp=false;
			}
			echo '
			<form enctype="multipart/form-data" method="post" action="?acao=cadastrarcor"> 
			Ref: '.$linha['refcor'].' 
			<input type="hidden" name="boolupdate"/>
			<input type="hidden" name="refcor" value="'.$linha['refcor'].'"/>
			- Nome: <input type="text" name="nomecor" id="nomecor" value="'.$linha['nomecor'].'" size="8"/></br>
			<input type="checkbox" name="if_Est" id="if_Est" value="E"';
			if($estamp){ echo ' checked ';}
			echo '/><label for="if_Est">Estampada</label></br>
			Para alterar a foto:
			<input name="userfile" type="file" /></br></br>
			<input type="submit" name="submit" value="Atualizar Cor" />
			</br>
			
			</form>
			</br>';
			if(file_exists('_cores/'.$linha['nomecor'].'.jpg')){
				echo '<img style="width: 400px;height:600px;" src="_cores/'.$linha['nomecor'].'.jpg"/>';
			}else{
				echo 'Não tem foto desta cor no sistema.';
			}
		}
		if($_GET['acao']=='cadastrarcor'){
			// echo '<pre>';
			// var_dump($_POST);
			// echo '</pre>';
			// echo '<pre>';
			// var_dump($_FILES);
			// echo '</pre>';
			$file = $_FILES['userfile'];	
			
			$refcora = $_POST['refcor'];
			$refcor= mb_strtoupper($refcora, 'UTF-8');
			$nomecora = $_POST['nomecor'];
			$nomecor= mb_strtoupper($nomecora, 'UTF-8');
			if(isset($_POST['if_Est'])){$if_Est='E';}else{$if_Est='';}
			

			$path = '_cores/'.$nomecor.'.jpg';

			$fileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
			if($fileType != "jpg" && $fileType != "png" && $fileType != "jpeg") {
					echo 'Erro ao inserir imagem: Imagem deve ser .jpg ou .jpeg ou .png';
			}else{
					move_uploaded_file($file['tmp_name'], $path);
			}

			if(isset($_POST['boolupdate'])){
				//atualizar cor
				$stmt=$dbh->prepare("update cores set nomecor=?,tipo=? where refcor=?");
				$stmt->execute(array($nomecor,$if_Est,$refcor));
				echo '</br></br><h2>Cor atualizada com sucesso!</h2>';
				
			}else{
				//cadastrar nova cor
				$stmt=$dbh->prepare("select * from cores where refcor = ?");
				$stmt->execute(array($refcor));
				$verificacor=$stmt->rowCount();
				if($verificacor>=1){
					//verifica se cor existe
					echo"<script language='javascript' type='text/javascript'>alert('Referência da Cor já existente!');</script>";
					die();
				}else{
					$qr = "insert into cores (refcor,nomecor,tipo) values ('$refcor','$nomecor','$if_Est')";
					$sql = mysqli_query($con,$qr);
					
					echo '</br></br><h2>Cor cadastrada com sucesso!</h2>';
				}
			}
		}
		if($_GET['acao']=='buscaprod'){
			echo '<form method="post" action="?acao=resbuscaprod">
			<label for="buscaprod">Produto: </label>
			<input type="text" name="buscaprod" id="buscaprod" onkeyup="lsbuscaprod(this.value)" autofocus/></br>
			<div id="livesearch"></div>
			</form>';
		}
		if($_GET['acao']=='buscacor'){
			echo '<form method="post" action="?acao=resbuscacor">
			<label for="buscaprod">Cor: </label>
			<input type="text" name="buscacor" id="buscacor" onkeyup="lsbuscacor(this.value)" autofocus/></br>
			<div id="livesearch"></div>
			</form>';
		}
		
		if($_GET['acao']=='buscausuario'){
			echo '
			<label for="buscausuario">Usuário: </label>
			<input type="text" name="buscausuario" id="buscausuario" onkeyup="lsbuscausuario(this.value,'."'acao=cadastusuario'".')" autofocus/></br>
			<div id="livesearch"></div>
			</form>';
		}
		if($_GET['acao']=='cadastusuario'){
			
			echo '<div class="underline">'; 
			
			if($_GET['id']<>'novo'){
				$boolnovocliente=false;
			}else{
				$boolnovocliente=true;
			}
			$actionFornCadastPessoa='?acao=cadastrodecliente';
			
			if(!$boolnovocliente){
				$actionFornCadastPessoa .= '&id='.$_GET['id'];
				$id_usuario=$_GET['id'];
			}
			$boolemoutroforn=false;
			$boolcadastsenha=false;
			require("../_auxiliares/formcadastpessoa.php");
		}
		if($_GET['acao']=='cadastrodecliente'){
			require("../_auxiliares/cadastpessoa.php");
		}
		if($_GET['acao']=='lsacessousuario'){
			if(isset($_GET['update'])){
				$id_usuario=$_POST['id_usuario'];
				$acesso=$_POST['acesso'];
				$comissao=str_replace(',','.',$_POST['comissao']);
				if($comissao==''){$comissao=0;}
				$comissaofat=str_replace(',','.',$_POST['comissaofat']);
				if($comissaofat==''){$comissaofat=0;}
				$protestos=$_POST['protestos'];
				$obs=$_POST['obs'];
				$stmt=$dbh->prepare("update usuarios set acesso=?,comissao=?,comissaofat=?,protestos=?,Obs=? where id_usuario=?");
				if($stmt->execute(array($acesso,$comissao,$comissaofat,$protestos,$obs,$id_usuario))){
					echo 'Alterações realizadas com sucesso</br></br></br>';
				}else{
					echo 'Erro ao realizar as alterações</br></br></br>';
				}
			}			
			if(!isset($_GET['id'])){
				echo 'Usuário: <input type="text" onkeyup="lsbuscausuario(this.value,'."'acao=lsacessousuario'".')" autofocus></br>
				<div id="livesearch"></div>';
			}else{
				$id_usuario=$_GET['id'];
				$stmt=$dbh->prepare("select razaosocial,protestos,acesso,comissao,comissaofat,obs from usuarios where id_usuario=?");
				$stmt->execute(array($id_usuario));
				$row=$stmt->fetch(PDO::FETCH_ASSOC);
				//var_dump($row);
				echo $row['razaosocial'].'</br>
				<form method="post" id="formacesso" action="?acao=lsacessousuario&update&id='.$id_usuario.'">
			
				<label for="id_usuario">ID: </label><input type="text" name="id_usuario" id="id_usuario" value="'.$id_usuario.'" size="5" readonly/></br>	
				<label for="acesso">Acesso: </label><input type="text" name="acesso" id="acesso" value="'.$row['acesso'].'"/></br>
				<label for="comissao">Comissão: </label><input type="text" name="comissao" id="comissao" value="'.$row['comissao'].'" size="6"/>% 
				&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
				<label for="comissaofat">Comissão no Faturamento: </label><input type="text" name="comissaofat" id="comissaofat" value="'.$row['comissaofat'].'"size="6"/>%</br>
				<label for="protestos">Protestos: </label><input type="text" name="protestos" id="protestos" value="'.$row['protestos'].'" size="3"/></br>
				<label for="obs">Observações: </label><textarea name="obs" form="formacesso" id="obs">'.$row['obs'].'</textarea>
				<input type="submit" value="Alterar"/>
				</form>';
				//LIMITE, acesso, comissao, comissaofat, protestos
			}
		}
		if($_GET['acao']=='alterarsenha'){
			if(!isset($_GET['update'])){	
				if(isset($_GET['id'])){
					$idusuario=$_GET['id'];
					$stmt=$dbh->prepare("select nomefantasia from usuarios where id_usuario=?");
					$stmt->execute(array($idusuario));
					$ln=$stmt->fetchAll();
					$usuario = $ln[0][0];
				}			
				echo '
				<label for="usuario">Usuário:</label><input type="text" name="usuario" onkeyup="lsbuscausuario(this.value,'."'acao=alterarsenha'".')"';
				if(isset($usuario)){echo 'value="'.$usuario.'" readonly';}
				echo ' autofocus/></br>
				<div id="livesearch"></div>';
				if(isset($usuario)){
					echo '
					<form method="post" action="?acao=alterarsenha&update">
					ID: <input type="text" name="idusuario" value="'.$idusuario.'" readonly/></br>
					<label for="newpass">Nova Senha:</label><input type="password" name="newpass"/></br>
					<label for="confnewpass">Confirme Nova Senha:</label><input type="password" name="confnewpass"/></br>
					<input type="submit" value="Modificar senha"/>';
				}
				echo '
				
				</form>';
			}else{
				$idusuario=$_POST['idusuario'];
				$senha = MD5($_POST['newpass']);
				$senha2 = MD5($_POST['confnewpass']);
				if($senha <> $senha2){
					echo "<script language='javascript' type='text/javascript'>alert('Senha deve ser igual à confirma senha');window.location.href='?acao=alterarsenha';</script>";
					die();
				}else{
					$stmt=$dbh->prepare("update usuarios set senha=? where id_usuario=?");
					if($stmt->execute(array($senha,$idusuario))){
						echo 'Senha atualizada com sucesso';
					}else{
						echo 'Erro ao atualizar senha...';	
					}
				}
			}
		}
		if($_GET['acao']=='fotos'){
			$boolPosts = (
				isset($_POST['alterFoto'])
			);
			if(!$boolPosts){
				echo '
				<div id="fotosBanner" style="border-bottom: 1px solid black">
				<h2>Fotos Banner</h2>';
				$max_banner=1; 
				while(file_exists("_banner/$max_banner-banner.png")){
					$max_banner++;
				}
				$max_banner--;

				for($i = 1; $i <= $max_banner; $i++){
					echo '
					<div style="display:flex">
						<div style="display:flex-box">
							<img src="_banner/'.$i.'-banner.png" style="width:300px"/>
						</div>
						<div style="display:flex-box; padding:10px;">
							<input type="file" id="file-'.$i.'"/></br>
							<input type="button" class="butAlterBanner" id="butAlter-'.$i.'" value="Alterar"/>
							</br></br>
							<input type="button" class="butExcluirBanner" id="Excluir-'.$i.'" value="Excluir"/>
						</div>
					</div>
					';
				}
				echo '
					<h3>Adicionar Foto</h3>
					<input type="file" id="file-'.($max_banner+1).'"/></br>
					<input type="button" class="butAlterBanner" id="butAlter-'.($max_banner+1).'" value="Adicionar"/>
					</br></br>
				';
				echo '</div>';
				$namePics = ['Feminino', 'Vestidos', 'Plus', 'Masculino', 'Calcas', 'Infantil'];
				echo '
				<div id="fotosMenuCat">
				<h2>Fotos do Menu abaixo do banner:</h2>';
				foreach($namePics as $namePic){
					echo '<b>'.$namePic.'</b>
					<div style="display:flex">
						<div style="display:flex-box">
							<img src="_banner/'.$namePic.'.jpg" style="height:200px"/>
						</div>
						<div style="display:flex-box; padding:10px;">
							<input type="file" id="file-'.$namePic.'"/></br>
							<input type="button" class="butAlterBanner" id="butAlter-'.$namePic.'" value="Alterar"/>
						</div>
					</div>
					</br>
					';
				}
				echo '</div>';
			}
		}
	}
	?>
	</div>
</body>
</html>
 