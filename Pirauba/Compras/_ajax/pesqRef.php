<?php

require '../../_config/conection.php';
require '../../../functionsPDO.php';

$ref=$_GET['ref'];


if(seExiste($dbhRPI,'produtos','ref',$ref)){
	echo 'true';
}else{
	echo 'false';
}


?>