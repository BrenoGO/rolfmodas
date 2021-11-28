<?php

$doc = $_GET["doc"];
$doc = preg_replace('/[^0-9]/', '', (string) $doc);
require '../_config/conection.php';
$dbh = new PDO('mysql:host='.$host.';dbname='.$db.';charset=utf8',$user,$pass);

$stmt=$dbh->prepare("select * from usuarios where cnpj = ?");
$stmt->execute(array($doc));
$ver=$stmt->rowCount();
if($ver>0){
	$ln=$stmt->fetch(PDO::FETCH_ASSOC);
	
	echo '<form method="post" action="?acao=cadast" enctype="multipart/form-data">
			<table>
			<tr>
			<td><label for="CPF">CPF: </label></td>
			<td><input style="float:left" type="text" name="cnpj" value="'.$ln['cnpj'].'" readonly/></td>
			</tr>
			<tr>
			<td><label for="nomecompleto">Nome Completo: </label></td>
			<td><input style="float:left" type="text" name="nomecompleto" value="'.$ln['razaosocial'].'" readonly/></td>
			</tr>
			<tr>
			<td><label for="contato">Celular: </label></td>
			<td><input style="float:left" type="text" name="contato" id="contato" value="'.$ln['contato'].'" readonly/></td>
			</tr>
			<tr>
			<td><label for="data_nascimento">Data de Nascimento: </label></td>
			<td><input  style="float:left" type="date" name="data_nascimento" id="data_nascimento" value="'.$ln['data_nascimento'].'" readonly/></td>
			</tr>
			<tr>
			<td><label for="email">E-mail: </label></td>
			<td><input style="float:left" type="email" name="email" id="email" value="'.$ln['email'].'" readonly/></td>
			</tr>
			<tr>
			<td>Upload Arquivo (.zip ou .rar): </td>
			<td><input type="file" name="arquivo" id="arquivo"/></td>
			</tr>
			</table></br>
			<input type="hidden" name="userant" value="true"/>
			<input type="submit" value="Efetuar Cadastro"/>
			</form>
			';
	
}else{
	
}

?>