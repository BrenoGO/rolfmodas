<?php
require '_config/forcehttps.php';
if( is_file('_css/estilo-v-3-5.css') and is_file('_css/responsive-v-3-5.css')){
	echo '<link rel="stylesheet" href="_css/estilo-v-3-5.css"/>';
	echo '<link rel="stylesheet" href="_css/responsive-v-3-5.css"/>';
	echo '<link rel="stylesheet" href="_css/interno.css"/>';
}else{
	echo '<link rel="stylesheet" href="../_css/estilo-v-3-5.css"/>';
	echo '<link rel="stylesheet" href="../_css/responsive-v-3-5.css"/>';
	echo '<link rel="stylesheet" href="../_css/interno.css"/>';
}
echo '
    <meta charset="UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=0.9"/>
	<meta name="description" content="O melhor site de compra atacado de moda feminina."/>
	<link rel="shortcut icon" href="../_imagens/logorolf.ico" type="image/x-icon" />
	<script type="text/javascript" src="../_javascript/jquery-3.3.1.min.js"></script>
	<script type="text/javascript" src="../_javascript/jquery.elevateZoom-3.0.8.min.js"></script>';
	date_default_timezone_set('America/Sao_Paulo');	
	
	echo '<script type="text/javascript"> ';
	echo 'var tlJsHost = ((window.location.protocol == "https:") ? "https://secure.comodo.com/" : "http://www.trustlogo.com/");';
	echo 'document.write(unescape("%3Cscript src='."'".'" + tlJsHost + "trustlogo/javascript/trustlogo.js'."'".' type='."'".'text/javascript'."'".'%3E%3C/script%3E"));';
	echo '</script>';
	
 ?>