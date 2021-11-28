<?php
require '../../_config/forcehttps.php';
require '../_config/conection.php';
require '../functions.php';
require '../../functionsPDO.php';

if( is_file('_css/estilo-rpi-1.0.css') ){
	echo '<link rel="stylesheet" href="_css/estilo-rpi-1.0.css"/>';
}else{
	echo '<link rel="stylesheet" href="../_css/estilo-rpi-1.0.css"/>';
}
echo '
	<title>Rolf Piraúba</title>
    <meta charset="UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"/>
	<meta name="description" content="O melhor site de compra atacado de moda feminina."/>
	<link rel="shortcut icon" href="../../_imagens/logorolf.ico" type="image/x-icon" />
	<script type="text/javascript" src="../_javascript/functions.js"></script>
	<script type="text/javascript" src="../../_javascript/jquery-3.3.1.min.js"></script>
	<script type="text/javascript" src="_javascript/functions.js"></script>';
	date_default_timezone_set('America/Sao_Paulo');	
	
	echo '<script type="text/javascript"> ';
	echo 'var tlJsHost = ((window.location.protocol == "https:") ? "https://secure.comodo.com/" : "http://www.trustlogo.com/");';
	echo 'document.write(unescape("%3Cscript src='."'".'" + tlJsHost + "trustlogo/javascript/trustlogo.js'."'".' type='."'".'text/javascript'."'".'%3E%3C/script%3E"));';
	echo '</script>';
	
 ?>