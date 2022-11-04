<?php
include ('libs/fpdf.php');

class order extends FPDF
{
    function my_header($customerArray){

        $from = $customerArray['from'];
        $to   = $customerArray['to'];

        $this->SetFont('Arial','b',18);
        $this->Ln(15);
        $this->Cell(285,10,'P.O.BOX 3206','','','R','');
        $this->Ln();
        $this->Cell(285,10,'Lilongwe','','','R','');

        $this->Ln();
        $this->Cell(285,10,'Cell : +265 999 609 242','','','R','');

        $this->Image('libs/logo.jpg',-10,5,100);
        $this->Line(5,70,284,70);

        $this->Ln(20);

        $this->SetFont('Arial','',19.5);
        $this->MultiCell(284,10,
            'Civil Engineering Constructions | Irrigation,Design & Constructions | Bulding Construction',
            '','C','');

        $this->SetFont('Arial','',15);


        $this->Ln(15);
        $this->Cell(132,14,'TO :','','','L','');
        $this->Cell(20,14,'','','','L','');

        $this->SetFont('Arial','B',20);
        $this->Cell(132,14,'DELIVERY NOTE : ','','','L','');

        $this->SetFont('Arial','',15);
        $this->Ln();
        $this->Cell(132,10,$to['ref'],'1','','L','');
        $this->Cell(20,10, ' ','','','L','');
        $this->Cell(132,10,'Number : '.$from['no'],'1','','L','');

        $this->Ln();
        $this->Cell(132,10,$to['date'],'1','','L','');
        $this->Cell(20,10, ' ','','','L','');
        $this->Cell(132,10,'Date : '.$from['date'],'1','','L','');

        $this->Ln();
        $this->Cell(132,10, $to['no'],'1','','L','');
        $this->Cell(20,10, ' ','','','L','');
        $this->Cell(132,10,'Reference : '.$from['ref'],'1','','L','');
        $this->Ln(10);

    }

    function contents($data,$totalPaid=0){

        $this->Ln(10);
        $this->SetFont('Arial','B',15);
        $this->Cell(55,15,'QUANTITY','1','','C','');
        $this->Cell(230,15,'DESCRIPTION OF GOODS & SERVICES','1','','C','');
        $this->Ln(15);


        foreach ($data as $key=>$values){

            $this->SetFont('Arial','',15);
            $this->Cell(55,12,$key+1,1,'','C','');
            $this->Cell(230,12,$values['description'],1,'','C','');
            $this->Ln(12);
        }


    }

    function footer_note($footerData){
        $this->Ln(10);
        $this->SetFont('Arial','B',15);
        $this->Cell(142,20,'ISSUED BY : '.$footerData['issuesBy'],'','','L','');
        $this->Cell(142,20,'VEHICLE NO : '.$footerData['vehicleNo'],'','','R','');
        $this->Ln(12);

        $this->Cell(142,20,'DELIVERED BY : '.$footerData['deliveredBy'],'','','L','');
        $this->Cell(142,20,'RECEIVED BY : '.$footerData['receivedBy'],'','','R','');
        $this->Ln(12);

        $this->Cell(142,20,'SIGNATURE : ______________________','','','L','');
        $this->Cell(142,20,'SIGNATURE : ______________________','','','R','');
        $this->Ln(10);
    }
}
///////////////////////////SAMPLE DATA///////////////////////////////////////////////////////////////



$contents = [
    array('description'=>'Goods Description 1'),
    array('description'=>'Goods Description 1'),
    array('description'=>'Goods Description 1'),
    array('description'=>'Goods Description 1'),
    array('description'=>'Goods Description 1')
];

$preparedBy = "Clement";
$approvedBy = "Sakala";
$footerData = array(
    'issuesBy'=>'Kondwan Clement',
    'vehicleNo'=>'223',
    'deliveredBy'=>'Ausman Sakala',
    'receivedBy'=>'A Kilementi'
);


///////////////////////////END OF SAMPLE DATA////////////////////////////////////////////////////////


$pdf = new order();
$pdf->SetMargins(5,5,5);
$pdf->AddPage('P','A4');
$pdf->AliasNbPages();
$pdf->my_header($customerInfoData);
$pdf->contents($contents);
$pdf->footer_note($footerData);
@$pdf->Output();




