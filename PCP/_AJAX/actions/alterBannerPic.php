<?php

$pic = $_POST['pic'];
$bool = preg_match('/\d+/',$pic);
if($bool){
    $path = '../_banner/'.$pic.'-banner.png';
}else{
    $path = '../_banner/'.$pic.'.jpg';
}
        
$boolUpload = true;

$file = $_FILES['file'];
//check if is an image:
$fileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
if($fileType != "jpg" && $fileType != "png" && $fileType != "jpeg") {
    echo 'Imagem deve ser .jpg ou .jpeg ou .png';
    $boolUpload = false;
}

if($boolUpload){
    move_uploaded_file($file["tmp_name"], $path);
    echo 'Foto inserida com sucesso';
}