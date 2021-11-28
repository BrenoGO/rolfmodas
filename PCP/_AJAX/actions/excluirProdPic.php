<?php

$ref = $_POST['ref'];
$numPic = $_POST['numPic'];
$path = '../_fotos/'.$ref.'-'.$numPic.'.jpg';
unlink($path);
$numPic++;
$upPath = '../_fotos/'.$ref.'-'.$numPic.'.jpg';
while(file_exists($upPath)){
    rename($upPath, $path);
    $path = '../_fotos/'.$ref.'-'.$numPic.'.jpg';
    $numPic++;
    $upPath = '../_fotos/'.$ref.'-'.$numPic.'.jpg';
}
echo 'Foto excluída';