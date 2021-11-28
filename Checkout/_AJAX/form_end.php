<?php
require '../../_config/conection.php'; 
$dbh = new PDO('mysql:host='.$host.';dbname='.$db.';charset=utf8',$user,$pass);	
echo '
<table class="semborda">
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
		echo '<option>'.$lnuf['uf_estado'].'</option>';
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

<tr class="semborda">
<td class="semborda"><label class="fr" for="logradouro">Logradouro:</label></td><td class="semborda"><input class="fl" type="text" name="logradouro" id="logradouro" size="35"/></td>
</tr>
<tr class="semborda">
<td class="semborda"><label class="fr" for="num">Número:</label></td><td class="semborda"><input class="fl"type="text" name="num" id="num" size="4"/> </td></tr>
<tr class="semborda">
<td class="semborda"><label class="fr" for="complem">Complemento:</label></td><td class="semborda"><input class="fl" type="text" name="complemento" id="complemento" size="4"/></td></tr>
<tr class="semborda">
<td class="semborda"><label class="fr" for="bairro">Bairro:</label></td><td class="semborda"><input class="fl" type="text" name="bairro" id="bairro" size="12"/></td></tr>
<tr class="semborda">
<td class="semborda"><label class="fr" for="CEP">CEP:</label></td><td class="semborda"><input class="fl" type="text" onkeypress="formatar('."'#####-###', this".')" name="CEP" id="CEP" size="12"/></td></tr>
</table>
';