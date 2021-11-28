<?php
	session_start();
	require '../_config/conection.php'; 
	mysqli_set_charset($con,"utf8");
	require 'barcode.php';
	require '../fpdf.php';
	$orientation="horizontal";
	$code_type="code128";
	$print=true;
	$sizefactor="1";
	$size = 20;
	$barcodepath = "barcode";
	$barpath = "bar.png";
	$tot_bars = 0;
	foreach($_SESSION['etiqueta'] as $prod){
		$tot_bars += $prod['qnt'];
	}
	$bar_widht = 148;
	$bar_height = 60 + 7.7;
	//$bar_height_space=$bar_height+40.2;
	$bar_height_space=$bar_height+33;
	$width = $bar_widht*4 + 80;
	if(($tot_bars%4)==0){$rows = $tot_bars/4;}else{$rows = intval($tot_bars/4)+1;}
	$height = 12*$bar_height_space;
	
	$num_bar=0;
	$num_bar_col=0;
	//impressão na Rolf
	$num_bar_row=0;
	//
	//Para imprimir na tecnopel (máximo 16 etiquetas, ou 4 linhas..):
	// $num_bar_row=0.3;
	//
	
	//$sizepage[0]=203;
	//$sizepage[1]=307;
	$sizepage[0]=206;
	$sizepage[1]=311;
	$pdf = new FPDF(); 
	$pagnum = 0;
	$image= imagecreate($width, $height);
	foreach($_SESSION['etiqueta'] as $prod){
		for($i=1;$i<=$prod['qnt'];$i++){
			$num_bar += 1;
			$image1=barcode( $barpath, $prod['ref'], $prod['desc'], $size, $orientation, $code_type, $print, $sizefactor,$prod['cor'],$prod['tam'] );
			if($num_bar_col == 0){
				imagecopyresampled($image,$image1,$num_bar_col*$bar_widht,$num_bar_row*$bar_height_space,0,0,$bar_widht,$bar_height,$bar_widht,$bar_height);
			}elseif($num_bar_col == 1){
				imagecopyresampled($image,$image1,$num_bar_col*$bar_widht+16,$num_bar_row*$bar_height_space,0,0,$bar_widht,$bar_height,$bar_widht,$bar_height);
			}elseif($num_bar_col == 2){
				imagecopyresampled($image,$image1,$num_bar_col*$bar_widht+16+48,$num_bar_row*$bar_height_space,0,0,$bar_widht,$bar_height,$bar_widht,$bar_height);
			}else{
				imagecopyresampled($image,$image1,$num_bar_col*$bar_widht+16+48+16,$num_bar_row*$bar_height_space,0,0,$bar_widht,$bar_height,$bar_widht,$bar_height);
			}
			
			if($num_bar % 48 == 0){
				$pagnum += 1;
				imagepng($image,$barcodepath.$pagnum.".png");
				imagedestroy($image);	
				$pdf->AddPage('P',$sizepage,0);
				$pdf->Image($barcodepath.$pagnum.".png",12,0);
				$num_bar_row = -1;
				$image= imagecreate($width, $height);
			}
			
			if($num_bar_col<>3){
				$num_bar_col += 1;
			}else{
				$num_bar_col=0;
				$num_bar_row += 1;
			}	
		}
	}
	if($num_bar % 48 > 0){
		$pagnum += 1;
		imagepng($image,$barcodepath.$pagnum.".png");
		imagedestroy($image);	
		$pdf->AddPage('P',$sizepage,0);
		$pdf->Image($barcodepath.$pagnum.".png",12,0);
		
	}
	$pdf->Output();
	unset($_SESSION['etiqueta']);
?>