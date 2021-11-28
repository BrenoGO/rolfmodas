<?php

if(isset($_GET['local'])){$local=$_GET['local'];}
if(isset($_POST['local'])){$local = $_POST['local'];}
$dias = isset($_POST['dias']) ? $_POST['dias'] : 2;
if(isset($_POST['dataini'])){
    if($_POST['dataini'] <> ''){$dataini = $_POST['dataini'];}
    else{
        $dataini= date('Y-m-d H:i:s', mktime(0,0,0,date('m'),date('d')-$dias,date('Y')));
    }
}
else{$dataini= date('Y-m-d H:i:s', mktime(0,0,0,date('m'),date('d')-$dias,date('Y')));}
if(isset($_POST['datafin'])){$datafin = $_POST['datafin'];}else{$datafin='';}
echo '<h2>'.$local.'</h2>';

echo '<form method="post" action="?acao=extrato">
<label for="dataini">De:</label><input type="date" id="dataini" name="dataini"/>
<label for="datafin">a:</label><input type="date" id="datafin" name="datafin"/>
ou 
<label for="dias">Últimos </label><input type="number" name="dias" id="dias"/> <label for="dias">dias</label>
<input type="hidden" name="local" value="'.$local.'"/>
<input type="submit" value="Extrato específico"/></form></br>';
    
$qr = 'select * from caixa where local=? and data_mov >= ?';
if($datafin<>''){
    $qr .= "and dataalter <= ?";
    $datafin = $datafin.'23:59:59';
    $values=[$local, $dataini, $datafin];
} else{
    $values=[$local, $dataini];
}
$qr .= " order by data_mov";
$table = fetchToArray($dbh, $qr, $values);

echo '<table>
<tr>
<td>Data</td>
<td>Entradas</td>
<td>Saídas</td>
<td>Descrição</td>
<td>Saldo</td>
</tr>';
foreach($table as $ln){
    echo'<tr>
    <td>'.date_format(date_create($ln['dataalter']),'d-m-Y').'</td>
    <td>';if($ln['mov']=='E'){echo number_format($ln['valor'],2,',','.');}
    echo '</td>
    <td><span style="color:red">';if($ln['mov']=='S'){echo number_format($ln['valor'],2,',','.');}
    echo '</span></td>
    <td>'.$ln['desc'].'</td>
    <td>'.number_format($ln['saldo'],2,',','.').'</td>
    </tr>';
}
echo '</table>';	