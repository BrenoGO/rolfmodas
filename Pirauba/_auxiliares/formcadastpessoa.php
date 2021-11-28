<?php


echo'
<span class="fr" onclick="OcultaForm()" style="cursor: pointer;">X</span>
<select id="tipo_pessoa" name="tipo_pessoa" onchange="up_tipo_pessoa(this.value)">

<option value="J">Pessoa Jurídica</option>
<option value="F">Pessoa Física</option>
</select></br></br>
<input type="hidden" id="tp_js" name="tp_js" value="J"/>
<input type="hidden" name="id_usuario" id="id_usuario"/> 
<table class="semborda">
<tbody class="semborda">
'; 

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
	
/*}*/
echo '


<tr class="semborda">
<td class="semborda"><label class="fr" for="num">Número:</label></td><td class="semborda"><input class="fl"type="text" name="numero" id="numero" size="4"'; 
/*if(!$boolnovocliente){ echo 'value ="'.$nro;}*/
echo '"/></td></tr>
<tr class="semborda">
<td class="semborda"><label class="fr" for="complem">Complemento:</label></td><td class="semborda"><input class="fl" type="text" name="complem" id="complem" size="4"'; 
/*if(!$boolnovocliente){ echo 'value ="'.$xCpl;}*/
echo '"/></td></tr>



<tr class="semborda">
<td class="semborda"><label class="fr" for="contato">Telefone (com DDD)*: </label></td>
<td class="semborda"><input class="fl" type="text" onkeypress="formatar('."'##-#########', this".')" name="contato" id="contato" size="14"'; 
/*f(!$boolnovocliente){ echo 'value ="'.$contato;}*/
echo '" required/></td></tr>
<tr class="semborda">
<td class="semborda"><label class="fr" for="email">E-mail*:</label></td><td class="semborda"><input class="fl" type="email" name="email" id="email" size="20"'; 
/*if(!$boolnovocliente){ echo 'value ="'.$email;}*/
echo '" required/></td></tr>
<tr class="semborda">
<td class="semborda"><label class="fr" for="outroscontatos">Outros Contatos:</label></td><td class="semborda"><input class="fl" type="text" name="outroscontatos" id="outroscontatos" size="24"'; 
/*if(!$boolnovocliente){ echo 'value ="'.$outroscontatos;}*/
echo '"/></td></tr>
</tbody>
</table>
<span style="font-size:8pt;">*Preenchimento obrigatório</span></br>';
/*if($boolcadastsenha){
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
	else{echo '<input type="submit" value="Cadastrar" action="?acao=cadastforn"/>';/*}	
	echo '</form>';
}else{
	echo '<input type="hidden" name="boolemoutroforn" value="true"/>';
}*/


