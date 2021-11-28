<?php

if(!isset($_GET['transferencia'])){
    $stmt=$dbh->prepare("select * from dadosgerais where nome_dado=?");
    $stmt->execute(array('Contas Fluxo de Caixa'));
    $ln=$stmt->fetch(PDO::FETCH_ASSOC);
    echo '
    <form method="post" action="?acao=transfcontas&transferencia">
    <label for="valor">Valor: </label>
    <input type="text" name="valor" id="valor" size="5"/>
    </br>
    <label for="conta_origem">Da conta: </label>
    <select name="conta_origem" id="conta_origem">
    ';
    foreach($ln as $conta){
        if( ($conta <> 'Contas Fluxo de Caixa') and (!is_null($conta)) ){
            echo '<option ';
            if($conta == 'CEF'){echo 'selected=selected';}
            echo '>'.$conta.'</option>';
        }
    }
    echo '</select></br>
    <label for="conta_destino">Para Conta: </label>
    <select name="conta_destino" id="conta_destino">';
    foreach($ln as $conta){
        if( ($conta <> 'Contas Fluxo de Caixa') and (!is_null($conta)) ){
            echo '<option ';
            if($conta == 'Gaveta'){echo 'selected=selected';}
            echo '>'.$conta.'</option>';
        }
    }
    echo '</select></br>
    <label for="data_mov">Data: </label><input type="date" name="data_mov" id="data_mov" value="'.date('Y-m-d').'"/></br>
    <input type="submit" value="Transferir"/>
    </form>';
}else{
    $valor=$_POST['valor'];
    $conta_origem=$_POST['conta_origem'];
    $conta_destino=$_POST['conta_destino'];
    $data_mov=
    $desc='Transferência de '.$conta_origem.' para '.$conta_destino;
    $qr1="select saldo from caixa where num_mov=(select max(num_mov) from caixa where local=?)";
    $qr2="insert into caixa (`num_mov`,`local`,`mov`,`desc`,`valor`,`saldo`,`data_mov`,`dataalter`) values (default,?,?,?,?,?,?,default)";
    
    //retira da origem
    $stmt=$dbh->prepare($qr1);
    $stmt->execute(array($conta_origem));
    $ln=$stmt->fetch(PDO::FETCH_ASSOC);
    $saldoini=$ln['saldo'];
    $saldo=$saldoini-$valor;
    $mov='S';
    $stmt=$dbh->prepare($qr2);
    $stmt->execute(array($conta_origem,$mov,$desc,$valor,$saldo,$data_mov));
    
    //deposita no destino
    $stmt=$dbh->prepare($qr1);
    $stmt->execute(array($conta_destino));
    $ln=$stmt->fetch(PDO::FETCH_ASSOC);
    $saldoini=$ln['saldo'];
    $saldo=$saldoini+$valor;
    $mov='E';
    $stmt=$dbh->prepare($qr2);
    $stmt->execute(array($conta_destino,$mov,$desc,$valor,$saldo,$data_mov));
}