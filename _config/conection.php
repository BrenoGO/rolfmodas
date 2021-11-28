<?php
	
	//localhost
	
	$host = 'localhost';
	$user = 'root';
	$pass = 'root';
	$db = 'rolf';
	$dbh = new PDO('mysql:host='.$host.';dbname='.$db.';charset=utf8',$user,$pass);
	function conection(){
		$host = 'localhost';
		$user = 'root';
		$pass = 'root';
		$db = 'rolf';
		return mysqli_connect($host,$user,$pass,$db);
	}
	$con = conection();
	
	
	
	//rolfmodas.com.br
	/*
	$host = 'mysql.hostinger.com.br';
	$user = 'u355881160_rolf';
	$pass = '1q2w3e';
	$db ='u355881160_rolf';
	$dbh = new PDO('mysql:host='.$host.';dbname='.$db.';charset=utf8',$user,$pass);
	function conection(){
		$host = 'mysql.hostinger.com.br';
		$user = 'u355881160_rolf';
		$pass = '1q2w3e';
		$db ='u355881160_rolf';
		return mysqli_connect($host,$user,$pass,$db);
	}
	$con = conection();
	*/
	
	
	/*
	//gaudereto.xyz
	$host = 'mysql.hostinger.com.br';
	$user = 'u386457832_gau';
	$pass = '1q2w3e';
	$db ='u386457832_gau';
	function conection(){
		$host = 'mysql.hostinger.com.br';
		$user = 'u386457832_gau';
		$pass = '1q2w3e';
		$db ='u386457832_gau';
		return mysqli_connect($host,$user,$pass,$db);
	}
	*/
	
	
?>