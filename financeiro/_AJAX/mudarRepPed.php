<?php

$id_usuario=$_GET["idusuario"];
$pedido=$_GET["pedido"];
/*$total=$_GET["total"];
$idcliente=$_GET["idcliente"];
*/

require '../../_config/conection.php';
$con = conection();
mysqli_set_charset($con,"utf8");
$dbh = new PDO('mysql:host='.$host.';dbname='.$db.';charset=utf8',$user,$pass);


if($id_usuario === 0){
	/*echo $id_usuario;*/
	$qr="update pedidos set id_vendedor= null where pedido = ?";
	$array=array($pedido);
}else{
	$qr="update pedidos set id_vendedor= ? where pedido = ?";	
	$array=array($id_usuario,$pedido);
}
$stmt=$dbh->prepare($qr);
$stmt->execute($array);

echo '<script>window.location.reload()</script>';


?>