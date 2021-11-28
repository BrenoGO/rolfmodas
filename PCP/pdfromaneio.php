<?php
require '../_config/conection.php'; 
$con = conection();
mysqli_set_charset($con,"utf8");
$dbh = new PDO('mysql:host='.$host.';dbname='.$db.';charset=utf8',$user,$pass);

require 'functions.php';
require '../fpdf.php';
$pedido=$_GET['pedido'];
if(isset($_GET['data'])){$data = $_GET['data'];}
else{$data='';}
	//if($data == ''){
	$qr="select distinct(data_entrega) from pcp where loc='$pedido' order by data_entrega";
	$sql=mysqli_query($con,$qr);
	//if(mysqli_num_rows($sql)>1){
		$table=array();
		while($row = $sql->fetch_assoc()){
			$table[] = $row;
		}
	//	var_dump($table);
		foreach($table as $dia){
	//		var_dump($dia);
			$d=date('d-m-Y',strtotime($dia['data_entrega']));
	//		var_dump($d);
			$dlink=date('Y-m-d',strtotime($dia['data_entrega']));
		}
		$d=str_replace('-','/',$d);
		if($d === '01/01/1970') {
			$d = date('d/m/Y');
		}
	//	var_dump($d);
	//}
	

			
$z=0;
if(isset($_GET['sep'])){$sep = $_GET['sep'];$z++;}
if(isset($_GET['can'])){$can = $_GET['can'];$z++;}
if(isset($_GET['pro'])){$pro = $_GET['pro'];$z++;}			
if(isset($_GET['agu'])){$agu = $_GET['agu'];$z++;}
if(isset($_GET['ent'])){$ent = $_GET['ent'];$z++;}
if(isset($_GET['cns'])){$cns = $_GET['cns'];$z++;}			
if($z == 0){$ent = 'E';$z++;}

$qr= "select u.id_usuario,u.razaosocial,u.cidade,u.estado,p.dataentrega,p.dataped,p.prazopag,p.obs,p.desconto_pedido from pedidos p
join usuarios u on p.id_cliente=u.id_usuario
where pedido='$pedido'";
$sql= mysqli_query($con,$qr);
$ln = mysqli_fetch_assoc($sql);
if($ln['desconto_pedido']>0){
	$desconto_pedido=$ln['desconto_pedido'];
}else{
	$desconto_pedido=0;
}	
$cliente = $ln['razaosocial'];

$id_cliente=$ln['id_usuario'];	
$estado=$ln['estado'];		
$cidade = $ln['cidade'];
$dataentrega = $ln['dataentrega'];
$dataped = $ln['dataped'];
$prazopag=$ln['prazopag'];
$obs=$ln['obs'];

$qr = "select pcp.ref,sum(pcp.tot),pcp.preco,pcp.desconto,pcp.preco*(1-pcp.desconto/100)*sum(pcp.tot) from pcp 
where loc='$pedido' and ";
if($data <> ""){$qr .= "data_entrega >='$data' and data_entrega <='$data 23:59:59' and ";}
if($z == 1){
	if(isset($sep)){$qr .="situacao='S' and ";}
	if(isset($can)){$qr .="situacao='C' and ";}
	if(isset($pro)){$qr .="situacao='P' and ";}
	if(isset($agu)){$qr .="situacao='A' and ";}
	if(isset($ent)){$qr .="situacao='E' and ";}
	if(isset($cns)){$qr .="situacao='O' and ";}
}
if($z >= 2){
	$w = 0;
	if(isset($sep)){$qr .="(situacao='S'";$w++;}
	if(isset($can)){
		if($w == 0){$qr .="(situacao='C'";$w++;
		}else{$qr .=" or situacao='C'";}
	}
	if(isset($pro)){
		if($w == 0){$qr .="(situacao='P'";$w++;
		}else{$qr .=" or situacao='P'";}
	}
	if(isset($agu)){
		if($w == 0){$qr .="(situacao='A'";$w++;
		}else{$qr .=" or situacao='A'";}
	}
	if(isset($cns)){
		if($w == 0){$qr .="(situacao='O'";$w++;
		}else{$qr .=" or situacao='O'";}
	}
	if(isset($ent)){
		$qr .=" or situacao='E'";
	}
	$qr .=") and ";
}
$qr .= "tot>0 group by ref order by ref";
$sql = mysqli_query($con,$qr);
$table = array();
while($row = $sql -> fetch_assoc()){
	$table[] = $row;
}
$qr="select sum(desconto) from pcp where loc='$pedido'";
$sql=mysqli_query($con,$qr);
$ln=mysqli_fetch_assoc($sql);
$sumdesc=$ln['sum(desconto)'];

$total = 0;
$pecas = 0;
$dd=0;
foreach($table as $linha){
	$ref=$linha['ref'];
	$dados[$dd][]=$ref;
	$stmt=$dbh->prepare("select descricao from produtos where ref=?");
	$stmt->execute(array($ref));
	$ln=$stmt->fetch(PDO::FETCH_ASSOC);
	$desc=$ln['descricao'];
	$dados[$dd][]=$desc;
	$subtotal = $linha['pcp.preco*(1-pcp.desconto/100)*sum(pcp.tot)'];
	
	$dados[$dd][]=$linha['sum(pcp.tot)'];
	$dados[$dd][]=$linha['preco'];
	if($sumdesc > 0){$dados[$dd][]=$linha['desconto'];}
	$dados[$dd][]=$subtotal;
	$total += $subtotal;
	$pecas += $linha['sum(pcp.tot)'];
	$dd++;
}


if($sumdesc>0){
	$header=array('Ref.','Descrição','Qtd.','Valor Unit.','Desconto (%)','Subtotal');	
	$sedesconto=true;
}else{
	$header=array('Ref.','Descrição','Qtd.','Valor Unit.','Subtotal');
	$sedesconto=false;
}
if($sedesconto){
	$espaco=12.5;
}else{
	$espaco=25;
}
class PDF extends FPDF
{
	///	
	// Page header
	function Header()
	{
		// Logo
		$this->Image('../_imagens/logo.png',10,6,30);
		// Arial bold 15
		$this->SetFont('Arial','B',15);
		// Move to the right
		$this->Cell(80);
		// Title
		$this->Cell(50,10,'Romaneio Rolf',1,0,'C');
		// Line break
		$this->Ln(20);
	}
	// Page footer
	function Footer()
	{
		// Position at 1.5 cm from bottom
		$this->SetY(-15);
		// Arial italic 8
		$this->SetFont('Arial','I',8);
		// Page number
		$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
	//$header=array('Ref.','Descrição','Qtd.','Valor Unit.','Desconto','Subtotal');	
	function TableRomaneio($header,$dados,$sedesconto,$espaco,$pdf,$desconto_pedido)
	{
		$espvalores=25;
		// Column widths
		if($sedesconto){
			$w = array(11, 60, 15, $espvalores, $espvalores, $espvalores);
			$celantesdotot=$w[0]+$w[1]+$w[2]+$w[3]+$w[4];
		}else{
			$w = array(11, 60, 15, $espvalores, $espvalores);
			$celantesdotot=$w[0]+$w[1]+$w[2]+$w[3];
		}
		$this->Cell($espaco,6,'');
		// Header
		$pdf->SetFont('Times','B',12);
		for($i=0;$i<count($header);$i++){
			$str=$header[$i];
			$this->Cell($w[$i],7,$str,1,0,'C');
		}	
		$this->Ln();
		$pdf->SetFont('Times','',12);
		// Data
		$total=0;
		$numpecas=0;
		foreach($dados as $row)
		{
			
			$this->Cell($espaco,6,'');
			
			$desc=$row[1];
			$this->Cell($w[0],6,$row[0],1,0,'C');
			$this->Cell($w[1],6,$desc,1,0,'L');
			$this->Cell($w[2],6,$row[2],1,0,'C');
			$this->Cell($w[3],6,number_format($row[3],2,',','.'),1,0,'C');
			if($sedesconto){
				if(number_format($row[4])==0){
					$this->Cell($w[4],6,'-',1,0,'C');
				}else{
					$this->Cell($w[4],6,number_format($row[4]),1,0,'C');
				}
				$this->Cell($w[5],6,number_format($row[5],2,',','.'),1,0,'C');
			}else{
				$this->Cell($w[4],6,number_format($row[4],2,',','.'),1,0,'C');
			}
			$numpecas += $row[2];
			$this->Ln();
			if($sedesconto){
				$total+=$row[5];
			}else{
				$total+=$row[4];
			}
		}
		// Total
		if($desconto_pedido==0){
			$pdf->SetFont('Times','B',12);
		}
		//$this->Cell($celantesdotot+$espaco-$espvalores,6,'');
		//$this->Cell($espvalores,6,'Total:',1,0,'R');
		//$this->Cell($w[4],6,number_format($total,2,',','.'),1,0,'R');
		$this->Cell($celantesdotot+$espaco-$espvalores-$w[2]-$w[3],6,'');
		$pecas='Peças:';
		$this->Cell($espvalores,6,$pecas,1,0,'R');
		$this->Cell($w[2],6,number_format($numpecas,0),1,0,'C');
		$this->Cell($espvalores,6,'Total:',1,0,'R');
		$this->Cell($w[4],6,number_format($total,2,',','.'),1,0,'R');
		$this->Ln();
		if($desconto_pedido>0){
			$this->Cell($celantesdotot+$espaco-$espvalores,6,'');
			$this->Cell($espvalores,6,'Desconto:',1,0,'R');
			$this->Cell($w[4],6,number_format(-$desconto_pedido,2,',','.'),1,0,'R');
			$this->Ln();
			$pdf->SetFont('Times','B',12);
			$this->Cell($celantesdotot+$espaco,6,'');
			$this->Cell($w[4],6,number_format($total-$desconto_pedido,2,',','.'),1,0,'R');
		}
	}
	
}
	// Instanciation of inherited class

$pedido_echo=pedido_echo($pedido);

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);
$str='Romaneio N° ';
//$str = utf8_decode($str);
$pdf->Cell(25,8,$str,0,0);
$pdf->SetFont('Times','B',12);
$pdf->Cell(20,8,$pedido_echo,0,0);
$pdf->SetFont('Times','',12);
$pdf->Cell(40,8,'Data de Faturamento: ',0,0);
$pdf->Cell(25,8,$d,0,0);
$pdf->Cell(80,8,'Cidade: '.$cidade.' / '.$estado,0,1);
//$cliente=utf8_decode($cliente);
$pdf->Cell(80,8,'Cliente: '.$cliente,0,1);

$pdf->TableRomaneio($header,$dados,$sedesconto,$espaco,$pdf,$desconto_pedido);
$pdf->Output();

////
///
///
//gerar PDF do Romaneio!
?>	