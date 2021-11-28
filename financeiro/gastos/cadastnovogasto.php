<?php

if(isset($_POST['id_CC'])){
    $id_CC=$_POST['id_CC'];
}else{
    $CC = $_POST['novoCC'];
    $FixoVar= $_POST['FixoVar'];
    $DirInd= $_POST['DirInd'];
    $qr="insert into CC (id_CC,CC,FixoVar,DirInd) values (default,'$CC','$FixoVar','$DirInd')";
    $sql=mysqli_query($con,$qr);
    $qr="select id_CC from CC where CC = '$CC';";
    $sql=mysqli_query($con,$qr);
    $ln=mysqli_fetch_assoc($sql);
    $id_CC = $ln['id_CC'];
}
if(isset($_POST['id_forn'])){
    $id_forn=$_POST['id_forn'];
    $qr = "select forn from forn where id_forn=$id_forn";
    $sql=mysqli_query($con,$qr);
    $ln=mysqli_fetch_assoc($sql);
    $forn = $ln['forn'];
}else{
    $forn = $_POST['novoForn'];
    $qr="insert into forn (id_forn,id_CC,forn) values (default,$id_CC,'$forn')";
    $sql=mysqli_query($con,$qr);
    $qr="select id_forn from forn where forn = '$forn' and id_CC=$id_CC";
    $sql=mysqli_query($con,$qr);
    $ln=mysqli_fetch_assoc($sql);
    $id_forn = $ln['id_forn'];
}
$desc = $_POST['desc'];
$valor= str_replace(',','.',$_POST['valor']);
$data_gasto=$_POST['data_gasto'];
$parcelas = $_POST['parcelas'];
$tipo_pg = $_POST['tipo_pg'];

$obs=$_POST['obs'];
if(!isset($_POST['dataparc1'])){
    //Gasto a vista..
    $data_venc=date('Y-m-d');
    $qr="insert into gastos (id_apg,id_forn,`desc`,valor,tipo_pg,data_gasto,data_venc,data_pag,parcela,obs,dataalter) values
    (default,$id_forn,'$desc',$valor,'$tipo_pg','$data_gasto','$data_venc','$data_venc','1/1','$obs',default)";
    $sql = mysqli_query($con,$qr);
    if(!$sql){
        echo "erro no sql";
    }
    if( ($tipo_pg == 'Dinheiro (gaveta)')or($tipo_pg == 'Em mãos') ){
        $local='Gaveta';
    }else{
        $local='CEF';
    }	
    $qr="select saldo from caixa where num_mov=(select max(num_mov) from caixa where local='$local')";
    $sql=mysqli_query($con,$qr);
    $ln=mysqli_fetch_assoc($sql);
    $saldoanterior = $ln['saldo'];
    $novosaldo = $saldoanterior - $valor;
    $desccaixa = $forn;
    if($desc <> ''){
        $desccaixa .= ': '.$desc;
    }
    $qr="insert into caixa (num_mov,local,mov,`desc`,valor,saldo,data_mov,dataalter) values (default,'$local','S','$desccaixa',$valor,$novosaldo,$data_venc,default)";
    $sql=mysqli_query($con,$qr);
    
}else{
    //gasto a prazo
    for($i=1;$i<=$parcelas;$i++){
        $data_venc = $_POST['dataparc'.$i];
        $valor = str_replace('.','',$_POST['valorparc'.$i]);
        $valor = str_replace(',','.',$valor);
        $parcela=$i."/".$parcelas;
        $stmt=$dbh->prepare("insert into gastos
        (`id_apg`,`id_forn`,`desc`,`valor`,`tipo_pg`,`data_gasto`,`data_venc`,`parcela`,`obs`,`dataalter`) values
        (default,?,?,?,?,?,?,?,?,default)");
        $stmt->execute(array($id_forn,$desc,$valor,$tipo_pg,$data_gasto,$data_venc,$parcela,$obs));
        if(!$stmt){echo 'erro no sql...';}
    }
}
echo 'Gasto cadastrado';