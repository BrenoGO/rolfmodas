<!DOCTYPE html>
<html>
<head>
  <?php
	require '../head.php';
	?>
  <title>Extratos</title>
  
</head>
<body>
	<?php

	require '../config.php';
	require '../header_geral.php';
	require 'menu.php';
	echo '
	<nav id="submenu">';
	$qr = 'select * from dadosgerais where nome_dado = ?';
	$values=['Contas Fluxo de Caixa'];
	$accounts = fetchAssoc($dbh, $qr, $values);
	foreach ($accounts as $key => $account) {
		if(strlen($account) > 0 && $key !== 'nome_dado') {
			echo '<a href="?acao=extrato&local='.$account.'"><button>'.$account.'</button></a>';
		}
	}
	
	echo '
	</nav>
	</header>
	<div class="corpo">
	';
	
	if(!isset($_GET['acao'])){
		foreach($accounts as $key => $account) {
			//echo 'Key: '.$key.' - '.$account;
			// echo '</br>';
			if(strlen($account) > 0 && $key !== 'nome_dado') {
				$qr = 'select saldo from caixa where local=? and num_mov = (select max(num_mov) from caixa where local=?)';
				$values = [$account, $account];
				$stmt = fetchAssoc($dbh, $qr, $values);
				echo '<h3>Saldo '.$account.': R$ '.number_format($stmt['saldo'],2,',','.').'</h3>';
			}
		}	  
	}else{
		switch ($_GET['acao']) {
			case 'extrato':
				require 'extratos/extrato.php';	
				break;
			case 'transfcontas':
				require 'extratos/transfcontas.php';
				break;
			default:
				echo 'caiu no default no switch (avisar Breno)';
				break;
		}
	}
	
	?>
	</div>
</body>
</html>
 