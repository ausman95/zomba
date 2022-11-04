<?php


class order extends FPDF
{
    function my_header($customerArray=false,$invoiceNo=false){

        $this->Ln(50);
        $this->SetFont('Arial','i',20);
        $this->Ln(10);

        $this->Image('libs/logo.jpg',85,5,125);
        $this->Ln(5);

        $this->SetFont('Arial','',12);
        $this->Cell(54,7,'',false,'','L',false);

        $this->Cell(40,7,'+265 999 609 242',false,'','L',false);
        $this->Cell(40,7,'| info@sico.com',false,'','L',false);
        $this->Cell(94,7,'| Area 47 Sector 2 Near Mlambe lodge, LL MW',false,'','L',false);

        $this->Cell(54,7,'',false,'','L',false);
        $this->Ln();

        $this->Cell(54,7,'',false,'','L',false);

        $this->Cell(40,7,'',false,'','L',false);
        $this->Cell(40,7,'| www.sico.mw',false,'','L',false);
        $this->Cell(94,7,'| P.O.Box 3206',false,'','L',false);

        $this->Cell(54,7,'',false,'','L',false);

        $this->Line(5,90,284,90);
        $this->Ln(30);

        $this->SetFont('Arial','B',20);
        $this->MultiCell(284,10,'PAYMENT ORDER','','C','');

        $this->SetFont('Arial','',15);
        $this->Ln();
        $this->SetFillColor(0,225,225);

        $this->Cell(284,10,@date('d/m/Y'),1,'','L',1);

    }

    function contents($data,$totalPaid=0){

        $this->Ln(10);
        $this->SetFont('Arial','B',15);
        $this->Cell(15,12,'REF','1','','C','');
        $this->Cell(40,12,'CASH','1','','C','');
        $this->Cell(117,12,'ITEM DESCRIPTION','1','','C','');
        $this->Cell(54,12,'VOUCHER NUMBER','1','','C','');
        $this->Cell(58,12,'COMMENTS','1','','C','');
        $this->Ln(10);

        $total = 0;

        foreach ($data as $key=>$values){

            $total += $values['cash'];

            $this->SetFont('Arial','',15);
            $this->Cell(15,12,$key+1,false,'','C','');
            $this->Cell(40,12,'MK'.number_format($values['cash']),false,'','C','');
            $this->Cell(117,12,$values['description'],false,'','C','');
            $this->Cell(52,12,$values['voucherNumber'],false,'','C','');
            $this->Cell(60,12,$values['comments'],false,'','C','');
            $this->Ln(10);
        }

        $this->Ln(10);
        $this->SetFont('Arial','B',15);
        $this->Cell(15,12,'','1','','C','');
        $this->Cell(40,12,number_format($total),'1','','C','');
        $this->Cell(117,12,'TOTAL PAID ','1','','R','');
        $this->Cell(112,12,number_format($totalPaid),'1','','C','');
        $this->Ln(10);

    }

    function footer_note($footerData){
        $this->Ln(10);
        $this->SetFont('Arial','B',15);
        $this->Cell(142,20,'PREPARED BY : '.$footerData['preparedBy'],'','','L','');
        $this->Cell(142,20,'APPROVED BY : '.$footerData['approvedBy'],'','','R','');
        $this->Ln(12);

        $this->Cell(142,20,'POSITION : '.$footerData['position'],'','','L','');
        $this->Cell(142,20,'POSITION : '.$footerData['position2'],'','','R','');
        $this->Ln(12);

        $this->Cell(142,20,'SIGNATURE : ______________________','','','L','');
        $this->Cell(142,20,'SIGNATURE : ______________________','','','R','');
        $this->Ln(10);
    }
}




