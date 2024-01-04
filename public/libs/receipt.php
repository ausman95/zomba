<?php
include ('fpdf.php');
include ('numberToWords.php');
class receipt extends FPDF

{

    var $paidFor,
        $sumOf,
        $kwachaFigure,
        $monthOf,
        $receivedFrom;


    function SetDash($black=null, $white=null)

    {

        if($black!==null)

            $s=sprintf('[%.3F %.3F] 0 d',$black*$this->k,$white*$this->k);

        else

            $s='[] 0 d';

        $this->_out($s);

    }

    function content($ID){
        $this->Image('logo.jpg','3','5','20');
        $this->SetFont('Courier','B',12);
        $this->Cell(55,5,'RECEIPT','0','','R');
        $this->SetFont('Courier','B',7);
        $this->Cell(30,5,'NO '.$ID,'','','C');


        $this->Ln();
        $this->Cell(20,8,'','','L');
        $this->SetFont('Arial','B',8);
        $this->Cell(95,5,'BLANTYRE C.C.A.P SYNOD','','','C');
        $this->Ln(10);


        $this->SetFont('Arial','B',9);
        $this->Cell(50,10,'','','L');
        $this->Cell(30,10,'Date: '.@date('D d, M Y'),'','R');
        $this->Ln(5);

        $this->SetFont('Arial','B',9);
        $this->MultiCell(88,5,'Received from '.$this->receivedFrom,'','L');
        $this->Ln(5);


        $this->SetFont('Arial','B',9);
        $this->MultiCell(88,5,'The Sum Of : '.$this->sumOf.' Kwacha','','L');
        $this->Ln(5);

        $this->SetFont('Arial','B',7);
        $this->MultiCell(88,5,'Being Paid for '.$this->paidFor.' for the month of '.$this->monthOf,'','L');
        $this->Ln(5);


        $this->SetFont('Arial','B',8);
        $this->Cell(55,5,'Thank You','0','','L');
        $this->SetFont('Arial','B',7);
        $this->Cell(5,5,'K','0','','L');
        $this->Cell(22,5,number_format($this->kwachaFigure,2),'','L');
        $this->Ln(5);

        $this->SetDash(1, 1);
        $this->Line(5, 28, 95, 28);
        $this->Line(5, 70, 95, 70);
        $this->Line(5, 100, 95, 100);
    }

    function footer_note(){

        $this->Ln(5);
        $this->SetFont('Arial', 'B', 8);
        $this->cell(0, 10, 'Treasurer/Cashier/Secretary', 0, 0, 'C','');
        $this->Ln();
        $this->MultiCell(0, 4, '"Bring all the tithe into the storehouse, that there maybe meat in the mine house (Malachi 3:10)"', 0, 'C' );
    }
}







