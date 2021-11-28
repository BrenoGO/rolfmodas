'<?php
	require '../fpdf.php';

	class makeCatalogoPDF extends FPDF {
		function Footer(){
				// Position at 1.5 cm from bottom
				$this->SetXY(-10,-10);
				// Arial italic 8
				$this->SetFont('Arial','I',8);
				// Page number
				$this->AliasNbPages();
				$this->Cell(0,10,'Página '.$this->PageNo().'/{nb}',0,0,'C');
		}
		function ImgsPages($arrayRefs,$dbh){
			/*$imgCapa='';
			$this->AddPage();
			$this->Image($imgCapa,0,0,100,150);*/
			foreach($arrayRefs as $ref){
				/*$qr = "select descricao, Tags from produtos where ref=?";
				$values = array($ref);
				$fetch = $this->fetchAssoc($dbh,$qr,$values);
				$desc = $fetch['descricao'];
				$tag = $fetch['Tags'];*/

				$imgPath= '../PCP/_fotos/'.$ref.'-1.jpg';
				if(file_exists($imgPath)){  
					
					$this->AddPage();
					$this->Image($imgPath,2,2,96,146);
					$this->SetFillColor(255);
					$this->RoundedRect(5, 124, 20, 10, 5, '1234', 'DF');
					$this->SetFillColor(255);
					$this->RoundedRect(65, 124, 50, 500, 5, '1234', 'F');
					$this->SetXY(5,129);
					$this->SetTextColor(0);
					$this->SetFont('Arial','',10);
					$this->Cell(0,0,'Ref.: '.$ref,0,0,'',false);
					$this->SetAutoPageBreak(false);
					$this->SetFont('Arial','',15);
					$this->SetXY(67,126);   
					//$this->MultiCell(30,4.5, $desc,0,'L',false);
					//$this->Cell(0,0,$desc,0,0,'',false);
				}
			}
			$this->Output();
		}
	
	}
	
?>

