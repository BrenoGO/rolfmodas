<?php 

//$actionformprecadast e $outroforn

echo '

<form method="post" action="'.$actionformprecadast.'"';
if($outroforn != false){
	if($outroforn == 'concurso'){
			echo ' enctype="multipart/form-data" ';
	}
}
echo '>
<table>
<tr>
<td><label for="CPF">CPF: </label></td>
<td><input style="float:left" type="text" name="cnpj" id="cpf" onkeyup="formatar('."'###.###.###-##'".',this)" onchange="checkdocuser(this.value)" required/></td>
</tr>
<tr>
<td><label for="nomecompleto">Nome Completo: </label></td>
<td><input style="float:left" type="text" name="nomecompleto" id="nomecompleto" required/></td>
</tr>
<tr>
<td><label for="contato">Celular (com ddd): </label></td>
<td><input style="float:left" type="text" name="contato" id="contato" onkeyup="formatar('."'##-#####.####'".',this.value) required"/></td>
</tr>
<tr>
<td><label for="data_nascimento">Data de Nascimento: </label></td>
<td><input  style="float:left" type="date" name="data_nascimento" id="data_nascimento" required/></td>
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
		echo '>'.$lnuf['uf_estado'].'</option>';
	}
	echo '</select></td></tr>
	<tr class="semborda">
	<td class="semborda"><span class="fr">Cidade:</span></td>
	<td class="semborda">
<div style="display: inline" id="ajaxcidade">
<select class="fl" id="cidade" name="cidade" required>
<option>Selecione seu estado</option>	
</select></td>
</div>
<tr>
<td><label for="sexo">Sexo: </label></td>
<td><select name="sexo" style="float: left"><option></option><option>Masculino</option><option>Feminino</option></select></td>
</tr>
<tr>
<td><label for="email">E-mail: </label></td>
<td><input style="float:left" type="email" name="email" id="email" required/></td>
</tr>
<tr>
<td><label for="senha">Senha: </label></td>
<td><input style="float: left;" type="password" name="senha" id="senha" size="5" required/></td>
</tr>
<tr>
<td><label for="confsenha">Confirma Senha: </label></td>
<td><input style="float: left" type="password" name="confsenha" id="confsenha" size="5" required/></td>
</tr>';
if($outroforn != false){
	if($outroforn == 'concurso'){
		echo '
			<tr>
			<td>Upload Arquivo (.zip ou .rar): </td>
			<td><input type="file" name="arquivo" id="arquivo"/></td>
			</tr>
		';
	}
}
echo '
</table></br>
<input type="submit" value="Efetuar Cadastro"/>
</form>
<div style="display:inline" id="testedoc"></div>';
	