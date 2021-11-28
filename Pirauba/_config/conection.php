<?php
	
	//localhost de Pirauba
	
	$host = 'localhost';
	$user = 'root';
	$pass = '';
	$db = 'rolf';
	$dbh= new PDO('mysql:host='.$host.';dbname='.$db.';charset=utf8',$user,$pass);
	
	$host = 'localhost';
	$user = 'root';
	$pass = '';
	$db = 'rpi';
	$dbhRPI = new PDO('mysql:host='.$host.';dbname='.$db.';charset=utf8',$user,$pass);
	
	
	
	/*
	//rolfmodas.com.br
	
	$host = 'mysql.hostinger.com.br';
	$user = 'u355881160_rolf';
	$pass = '1q2w3e';
	$db ='u355881160_rolf';
	$dbh= new PDO('mysql:host='.$host.';dbname='.$db.';charset=utf8',$user,$pass);
	
	$host = 'mysql.hostinger.com.br';
	$user = 'u355881160_pirau';
	$pass = '1q2w3e';
	$db = 'u355881160_pirau';
	$dbhRPI = new PDO('mysql:host='.$host.';dbname='.$db.';charset=utf8',$user,$pass);
	
		*/
?>