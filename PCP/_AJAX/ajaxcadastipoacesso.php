<?php
$acesso = $_GET['acesso'];
require '../../_config/conection.php';
$con = conection();
if($acesso == 'cliente'){
	echo '<label for="rsocial">Razão Social:</label><input type="text" name="rsocial" id="rsocial" required/>';
	echo '<label for="limite">Limite:</label><input type="text" name="limite" id="limite"/>';
}
if($acesso == 'revendedor'){
	echo '<label for="limite">Limite:</label><input type="text" name="limite" id="limite"/></br>
	<input type="checkbox" name="revendcliente" id="revendcliente" onclick="uptipoacesso('."'revendcliente'".')"/>
	<label for="revendcliente">Revendedor já cadastrado como cliente</label>
	</br>
	<b>Dados Pessoais</b></br>
	<label for="rsocial">Nome Completo:</label><input type="text" name="rsocial" id="rsocial" required/>
	<label for="cpf">CPF:</label><input type="text" name="cpf" id="cpf" required/></br>
	<label for="estado">Estado:</label>
	<select id="estado" name="estado" onclick="selectcidade(this.value)" required>
		<option>Selecione...</option>';
		$qr="select uf_estado from estado order by uf_estado";
		$sql=mysqli_query($con,$qr);
		$tablesqluf = array();
		while($row = $sql->fetch_assoc()){
			$tablesqluf[] = $row;
		}
		foreach($tablesqluf as $lnuf){
			echo '<option value="'.$lnuf['uf_estado'].'">'.$lnuf['uf_estado'].'</option>';
		}
		echo '</select>
		Cidade:
	<div style="display: inline" id="ajaxcidade">
		<select id="cidade" name="cidade" required>
		<option>Selecione seu estado</option>';
		echo '</select>
	</div></br>
	<label for="logradouro">Logradouro:</label><input type="text" name="logradouro" id="logradouro" size="25"/>
	<label for="num">Número:</label><input type="text" name="numero" id="numero" size="4"/>
	<label for="complem">Complemento:</label><input type="text" name="complem" id="complem" size="4"/></br>
	<label for="bairro">Bairro:</label><input type="text" name="bairro" id="bairro" size="12"/>
	<label for="CEP">CEP:</label><input type="text" name="CEP" id="CEP" size="12"/></br>
	<label for="contato">Contato:</label><input type="text" name="contato" id="contato" size="12" required/>
	<label for="outroscontatos">Outros Contatos:</label><input type="text" name="outroscontatos" id="outroscontatos" size="24"/></br></br>
	';
}
if($acesso == 'revendcliente'){
	echo '<label for="limite">Limite:</label><input type="text" name="limite" id="limite"/></br>
	<input type="checkbox" name="revendcliente" id="revendcliente" onclick="uptipoacesso('."'revendedor'".')" checked/>
	<label for="revendcliente">Revendedor já cadastrado como cliente</label>
	</br>
	
	<label for="rsocial">Cliente:</label><input type="text" name="rsocial" id="rsocial" onkeyup="LScliente(this.value,'."'revcliente','acao=formcadastrousuario','revcliente'".')" required/>
	<div id="LScliente"></div>
	
	';
}
if($acesso == 'representante'){
	echo '<label for="comissao">Comissão:</label><input type="text" name="comissao" id="comissao"/>
	<label for="comissaofat">Comissão no Faturamento:</label><input type="text" name="comissaofat" id="comissaofat"/>';
}

?>