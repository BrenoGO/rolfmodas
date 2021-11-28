<?php

$acao = $_POST['action'];
switch ($acao) {
    case 'removeBannerPic':
        require 'actions/removeBannerPic.php';
        break;
    case 'alterBannerPic':
        require 'actions/alterBannerPic.php';
        break;
    case 'alterProdPic':
        require 'actions/alterProdPic.php';
        break;
    case 'excluirProdPic':
        require 'actions/excluirProdPic.php';
        break;
    case 'alterCorPic':
        require 'actions/alterCorPic.php';
        break;
    default:
        echo 'Erro.. action.php: acao nao existe...';
        break;
}

