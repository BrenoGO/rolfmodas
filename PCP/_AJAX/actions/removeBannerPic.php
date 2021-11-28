<?php

$pic = $_POST['pic'];
$path = '../_banner/'.$pic.'-banner.png';
if(file_exists($path)){
    unlink($path);//excluiu a foto que queria
    $picUP = $pic + 1;
    $upPath = '../_banner/'.$picUP.'-banner.png';
    while(file_exists($upPath)){
        $newPath = '../_banner/'.($picUP-1).'-banner.png'; 
        rename($upPath, $newPath);
        $picUP++;
        $upPath = '../_banner/'.$picUP.'-banner.png';
    }
    echo 'Removido com sucesso..';
}else{
    echo 'erro, caminho inválido:'.$path;
}