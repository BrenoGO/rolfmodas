<!DOCTYPE html>
<?php

if(isset($_POST['id'])){
	$id = $_POST['id'];
	$id = preg_replace('/[^0-9]/', '', (string) $id);

	$senha = MD5($_POST['senha']);
	$entrar = $_POST['entrar'];
	//$contconect=isset($_POST['contconect']) ? TRUE : FALSE ;
	$contconect=TRUE;
}else{
	$id=$_GET['id'];
	$id = preg_replace('/[^0-9]/', '', (string) $id);
	$senha =$_GET['senha'];
	$entrar = true;
}

	
require '_config/conection.php'; 
$dbh = new PDO('mysql:host='.$host.';dbname='.$db.';charset=utf8',$user,$pass);
if(isset($entrar)){

	$stmt = $dbh ->prepare("SELECT * FROM usuarios WHERE senha = ? AND (id_usuario = ? OR cnpj = ?) ");
	$stmt->execute(array($senha,$id,$id));
	$array = $stmt->fetch(PDO::FETCH_ASSOC);
	
	
	if(!$array){
		echo"<script language='javascript' type='text/javascript'>alert('Id ou senha incorretos');window.location.href='index.php';</script>";
		die();
	}else{

		$acesso = $array['acesso'];
		$idusuario=$array['id_usuario'];
		$nomefantasia=$array['nomefantasia'];
		if($contconect){
			setcookie('id',$idusuario,time()+(5*12*30*24*3600));
			$stmt=$dbh->prepare("update usuarios set contconect = 'S' where id = ?");
			$stmt->execute(array($idusuario));
		}else{
			$stmt=$dbh->prepare("update usuarios set contconect = 'N' where id = ?");
			$stmt->execute(array($idusuario));
		}
		
	
		session_start();
		require '_auxiliares/define_session.php';
		
		
		if(strpos($acesso,'adm') !== false){
			header("Location:PCP/pedido.php");
		}
		else{
			header("Location:PCP/catalogo.php?default");
		}
		
	}
}
?>
