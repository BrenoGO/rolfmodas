<?php
echo '<h3>Registrar Gasto</h3>
<form method="post" action="#" >
<div id="selectdoCC")
<label for="CC">Selecione o Centro de Custo:</label>
<select id="CC" name="id_CC" onchange="selectforn(this.value)">
<option value="0">Selecione...</option>';
$qr="select id_CC,CC from CC";
$sql=mysqli_query($con,$qr);
$tableCC=array();
while($row = $sql->fetch_assoc()){
    $tableCC[]=$row;
}
foreach($tableCC as $lnCC){
    echo '<option value="'.$lnCC['id_CC'].'">'.$lnCC['CC'].'</option>';
}
echo '
</select>
<input type="button" value="Cadastrar novo CC" onclick="formcadastCC()"/></br>
</div>
<div id="cadastnovoCC"></div>
<div id="fornselection">
    <label for="forn">Selecione o fornecedor:</forn> 
    <select id="forn" name="id_forn">
    <option>Selecione o CC</option>
    </select><input type="button" value="Novo Fornecedor" onclick="formcadastForn()"/>
</div>
<div id="cadastnovoForn"></div>
<label for="desc">Descrição(opcional):</label><input type="text" id="desc" name="desc" size="30"/></br>
<label for="valor">Valor: R$ </label><input type="text" id="valor" name="valor" size="5"/> 
<label for="data_gasto">Data da Compra:</label> <input type="date" id="data_gasto" name="data_gasto" value="'.date('Y-m-d').'"/></br>
<label for="tipo_pg">Tipo de pagamento:</label>
<select id="tipo_pg" name="tipo_pg">';
$qr="select * from dadosgerais where nome_dado = 'Tipos de pagamento'";
$sql=mysqli_query($con,$qr);
$ln=mysqli_fetch_assoc($sql);
foreach($ln as $dado){
    if($dado <> ''){
        echo '<option ';if($dado=='Dinheiro (gaveta)'){echo 'selected=selected';}echo '>'.$dado.'</option>';
    }	
}
echo '</select></br>
<label for="parcelas">Parcelas:</label>
<input type="number" id="parcelas" name="parcelas" value=1 onclick="parcelagasto(this.value,document.getElementById('."'valor'".').value)" onchange="parcelagasto(this.value,document.getElementById('."'valor'".').value)"/>
<div id="parcelamentogasto"><b>A Vista</b></div>
<label for="obs">Observações:</label><input type="text" name="obs" id="obs" size="50"/></br>
<input type="submit" formaction="?acao=cadastnovogasto" value="Gerar Gasto"/>
</form>';