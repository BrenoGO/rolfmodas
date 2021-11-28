<?php

$ref = $_POST['ref'];
$numPic = $_POST['numPic'];
$file = $_FILES['data_pic'];

$path = '../_fotos/'.$ref.'-'.$numPic.'.jpg';

$fileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
if($fileType != "jpg" && $fileType != "png" && $fileType != "jpeg") {
    echo 'Erro: Imagem deve ser .jpg ou .jpeg ou .png';
}else{
    move_uploaded_file($file['tmp_name'], $path);
    echo 'Foto inserida com sucesso';
}