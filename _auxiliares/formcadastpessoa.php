<?php

//requesitos pra utilizar php auxiliar:
//$boolemoutroforn e $boolnovocliente e $actionFornCadastPessoa e $id_usuario
//$boolcadastsenha
//id do cliente deve entrar como $id_usuario, se ele já existe..

if(!$boolemoutroforn){
	echo '<form method="post" action="'.$actionFornCadastPessoa.'">';
}
if(!$boolnovocliente){
	echo 'ID: '.$id_usuario.'<input type="hidden" name="id" value="'.$id_usuario.'"/>';
	$stmt=$dbh->prepare("select * from usuarios where id_usuario =?");
	$stmt->execute(array($id_usuario));
	$ln=$stmt->fetch(PDO::FETCH_ASSOC);
	$rsocial=$ln['razaosocial'];
	$cnpj=$ln['cnpj'];
	$nfantasia=$ln['nomefantasia'];
	$data_nascimento=$ln['data_nascimento'];
	$cidade=$ln['cidade'];
	$estado=$ln['estado'];
	$contato=$ln['contato'];
	$outroscontatos=$ln['outroscontatos'];
	$email=$ln['email'];
	$ie=$ln['ie'];
	$xLgr=$ln['logradouro'];
	$nro=$ln['num'];
	$xCpl=$ln['complemento'];
	$xBairro=$ln['bairro'];
	$CEP=$ln['CEP'];
	$tipo_pessoa=$ln['tipo_pessoa'];
}else{
	$tipo_pessoa='';
}
echo '
<select id="tipo_pessoa" name="tipo_pessoa" onchange="up_tipo_pessoa(this.value)">

<option value="J"';if($tipo_pessoa=='J'){echo 'selected="selected"';}
echo '>Pessoa Jurídica</option>
<option value="F"';if($tipo_pessoa=='F'){echo 'selected="selected"';}
echo '>Pessoa Física</option>
</select></br></br>
<input type="hidden" id="tp_js" name="tp_js" value="J"/>';
if(!$boolnovocliente){ 
	echo '<input type="hidden" name="id_cliente" id="id_cliente" value="'.$id_usuario.'"/> ';
}
echo '<table class="semborda">
<tbody class="semborda">
'; 
if(!$boolnovocliente){
	echo '<tr class="semborda"><td class="semborda"><label for="rsocial"><span class="fr" id="span_rsocial">';
	if($tipo_pessoa=='J'){echo 'Razão Social*: ';}
	else{echo 'Nome Completo*: ';}
	echo '</span></label></td><td class="semborda">
	<input class="fl" type="text" name="rsocial" id="rsocial" size="30" value ="'.$rsocial.'" required/>
	</td></tr>
	<tr class="semborda"><td class="semborda"><label for="fantasia"><span class="fr" id="span_fantasia">';
	if($tipo_pessoa=='J'){echo 'Nome Fantasia: ';}
	else{echo 'Como deseja ser chamado(a): ';}
	echo '</span></label></td><td class="semborda">
	<input class="fl" type="text" name="fantasia" id="fantasia" size="20" value ="'.$nfantasia.'"/>
	</td></tr>
	<tr class="semborda"><td class="semborda">
	<label for="data_nascimento"><span class="fr" id="span_data_nascimento">';
	if($tipo_pessoa=='J'){echo 'Data do CNPJ: ';}
	else{echo 'Data de Nascimento: ';}
	echo '</span></label></td><td class="semborda">
	<input class="fl" type="date" name="data_nascimento" id="data_nascimento" value="'.$data_nascimento.'"/></br>
	</td></tr>
	<tr class="semborda"><td class="semborda">
	<label for="cnpj"><span class="fr" id="span_cpf_cnpj">';
	if($tipo_pessoa=='J'){echo 'CNPJ*: ';}
	else{echo 'CPF*: ';}
	echo '</span></label></td><td class="semborda">
	<input class="fl" type="text" name="cnpj" id="cnpj" value ="'.$cnpj.'"';
	if($tipo_pessoa=='J'){echo ' size="18" onkeypress="formatar(document.getElementById('."'tp_js'".').value,this)" ';}
	else{echo ' size="16" onkeypress="formatar('."'###.###.###-##'".',this.value)" ';}
	echo 'required/>
	</td></td>
	<tr class="semborda"><td class="semborda">
	<label for="ie"><span class="fr" id="span_IE_RG">';
	if($tipo_pessoa=='J'){echo 'Inscrição Estadual: ';}
	else{echo 'RG: ';}
	echo '</span></label></td><td class="semborda">
	<input class="fl" type="text" name="ie" id="ie" size="12" value ="'.$ie.'"/></td></tr>
	
	<tr class="semborda">
	<td class="semborda"><label class="fr" for="CEP">CEP:</label></td>
	<td class="semborda"><input class="fl" type="text" onkeypress="formatar('."'#####-###', this".')" onchange="verif_CEP(this.value)" name="CEP" id="CEP" size="12" value ="'.$CEP.'"/></td>
	</tr>
	
	
	<tr class="semborda">
	<td class="semborda"><span class="fr"><label for="estado">Estado:</label></span></td>
	<td class="semborda">
	<select class="fl" id="estado" name="estado" onchange="selectcidade(this.value)" onclick="selectcidade(this.value)" required>
	<option>Selecione...</option>';
	$stmt=$dbh->prepare("select uf_estado from estado order by uf_estado");
	$stmt->execute();
		
	$tablesqluf = array();
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		$tablesqluf[] = $row;
	}
	foreach($tablesqluf as $lnuf){
		echo '<option value="'.$lnuf['uf_estado'].'" '; 
		if($estado == $lnuf['uf_estado']){echo 'selected="selected"';}
		echo '>'.$lnuf['uf_estado'].'</option>';
	}
	echo '</select></td></tr>
	<tr class="semborda">
	<td class="semborda"><span class="fr">Cidade:</span></td>
	<td class="semborda">
	<div style="display: inline" id="ajaxcidade">
	<select class="fl" id="cidade" name="cidade" required>';
	
	if(strlen($cidade) >1){
		echo '<option>'.$cidade.'</option>';
	}else{
		echo '<option>Confira seu CEP</option>';
	}
	echo '</select></td>
		</div>
	<tr class="semborda">
	<td class="semborda"><label class="fr" for="bairro">Bairro:</label></td><td class="semborda"><input class="fl" type="text" name="bairro" id="bairro" size="12" value ="'.$xBairro.'"/></td></tr>
	
	<tr class="semborda">
	<td class="semborda"><label class="fr" for="logradouro">Logradouro:</label></td><td class="semborda"><input class="fl" type="text" name="logradouro" id="logradouro" size="35" value ="'.$xLgr.'"/></td>
	</tr>
	';
}else{
	//nao é cliente cadastrado
	echo '<tr class="semborda">
	<td class="semborda"><label for="rsocial"><span class="fr" id="span_rsocial">Razão Social: </span></label></td>
	<td class="semborda"><input class="fl" type="text" name="rsocial" id="rsocial" size="30" required/></td>
	</tr>
	<tr class="semborda">
	<td class="semborda"><label for="fantasia"><span class="fr"  id="span_fantasia">Nome Fantasia: </span></label></td>
	<td class="semborda"><input class="fl" type="text" name="fantasia" id="fantasia" size="20"/></td>
	</tr>
	<tr class="semborda">
	<td class="semborda"><label for="data_nascimento"><span class="fr" id="span_data_nascimento">Data do CNPJ: </span></label></td>
	<td class="semborda"><input class="fl" type="date" name="data_nascimento" id="data_nascimento"/></td>
	</tr>
	<tr class="semborda">
	<td class="semborda"><label for="cnpj"><span class="fr" id="span_cpf_cnpj">CNPJ*: </span></label></td>
	<td class="semborda"><input class="fl" type="text" name="cnpj" id="cnpj" size="16" onkeypress="formatar(document.getElementById('."'tp_js'".').value,this)" required/></td>
	</tr>
	<tr class="semborda">
	<td class="semborda"><label for="ie"><span class="fr" id="span_IE_RG">Inscrição Estadual: </span></label></td>
	<td class="semborda"><input class="fl" type="text" name="ie" id="ie" size="12"/></td>
	</tr>
	<tr class="semborda">
	<td class="semborda"><label class="fr" for="CEP">CEP*:</label></td>
	<td class="semborda"><input class="fl" type="text" onkeypress="formatar('."'#####-###', this".')" onchange="verif_CEP(this.value)" name="CEP" id="CEP" size="12"/></td>
	</tr>

	
	
	
	<tr class="semborda">
	<td class="semborda"><span class="fr"><label for="estado">Estado*:</label></span></td>
	<td class="semborda">
	<div id="div_estado">
	<select class="fl" id="estado" name="estado" onchange="selectcidade(this.value)" onclick="selectcidade(this.value)" required disabled>
	<option id="optuf1">Digite seu CEP</option>';
	$stmt=$dbh->prepare("select uf_estado from estado order by uf_estado");
	$stmt->execute();
		
	$tablesqluf = array();
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		$tablesqluf[] = $row;
	}
	foreach($tablesqluf as $lnuf){
		echo '<option value="'.$lnuf['uf_estado'].'">'.$lnuf['uf_estado'].'</option>';
	}
	echo '</select>
	</div>
		
	</td></tr>
	<tr class="semborda">
	<td class="semborda"><span class="fr">Cidade*:</span></td>
	<td class="semborda">
	
	<div style="display: inline" id="ajaxcidade">';
	
	echo '
	<select class="fl" id="cidade" name="cidade" required disabled>
	<option id="optcid1">Digite seu CEP</option>
	</select>
	';
	
	echo '</div>
	</td>
		';
	
	
	
	/*
	<tr class="semborda">
		<td class="semborda"><span class="fr"><label for="estado">Estado:</label></span></td>
		<input class="fl" type="text" name="estado" id="estado" placeholder="Digite seu CEP" readonly/>
		<td class="semborda"></td>
	</tr>
	<tr class="semborda">
		<td class="semborda"><span class="fr">Cidade:</span></td>
		<td class="semborda"><input class="fl" type="text" name="cidade" id="cidade" placeholder="Digite seu CEP" readonly/></td>
	</tr>
	*/
	
	
	
	
	echo '
	
	<tr class="semborda">
		<td class="semborda"><label class="fr" for="bairro">Bairro:</label></td>
		<td class="semborda"><input class="fl" type="text" name="bairro" id="bairro" size="12"/></td>
	</tr>
	<tr class="semborda">
		<td class="semborda"><label class="fr" for="logradouro">Logradouro:</label></td>
		<td class="semborda"><input class="fl" type="text" name="logradouro" id="logradouro" size="35"/></td>
	</tr>
	<div id="ajax_CEP"></div>
	';
	
}
echo '


<tr class="semborda">
<td class="semborda"><label class="fr" for="num">Número:</label></td><td class="semborda"><input class="fl"type="text" name="numero" id="numero" size="4"'; 
if(!$boolnovocliente){ echo 'value ="'.$nro;}
echo '"/></td></tr>
<tr class="semborda">
<td class="semborda"><label class="fr" for="complem">Complemento:</label></td><td class="semborda"><input class="fl" type="text" name="complem" id="complem" size="4"'; 
if(!$boolnovocliente){ echo 'value ="'.$xCpl;}
echo '"/></td></tr>



<tr class="semborda">
<td class="semborda"><label class="fr" for="contato">Telefone (com DDD)*: </label></td>
<td class="semborda"><input class="fl" type="text" onkeypress="formatar('."'##-#########', this".')" name="contato" id="contato" size="14"'; 
if(!$boolnovocliente){ echo 'value ="'.$contato;}
echo '" required/></td></tr>
<tr class="semborda">
<td class="semborda"><label class="fr" for="email">E-mail*:</label></td><td class="semborda"><input class="fl" type="email" name="email" id="email" size="20"'; 
if(!$boolnovocliente){ echo 'value ="'.$email;}
echo '" required/></td></tr>
<tr class="semborda">
<td class="semborda"><label class="fr" for="outroscontatos">Outros Contatos:</label></td><td class="semborda"><input class="fl" type="text" name="outroscontatos" id="outroscontatos" size="24"'; 
if(!$boolnovocliente){ echo 'value ="'.$outroscontatos;}
echo '"/></td></tr>
</tbody>
</table>
<span style="font-size:8pt;">*Preenchimento obrigatório</span></br>';
if($boolcadastsenha){
	echo'
	</br><label for="nsenha">Senha:</label><input type="password" id="nsenha" name="nsenha" size=5/>
	<label for="confnsenha">Confirme a Senha:</label><input type="password" id="confnsenha" name="confnsenha" size=5/></br></br>
	</br>
	<label for="id_patrocinador">ID de quem te indicou: </label><input type="text" name="id_patrocinador" id="id_patrocinador" value="';
	if(isset($_SESSION['id_usuario'])){echo $_SESSION['id_usuario'];}else{echo '" placeholder="se foi indicado';}
	echo '" size="12"/></br>
	';
}
if(!$boolemoutroforn){
	if(!$boolnovocliente){echo '<input type="submit" value="Alterar dados do cliente"/>';}
	else{ echo '<input type="submit" value="Cadastrar"/>';}	
	echo '</form>';
}else{
	echo '<input type="hidden" name="boolemoutroforn" value="true"/>';
}

