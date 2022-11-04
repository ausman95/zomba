<?php
include ('fpdf2.php');
include ('numberToWords.php');
class receipt extends FPDF

{

    var $paidFor,
        $paymentMode,
        $chequeNo,
        $sumOf,
        $kwachaFigure,
        $accountant,
        $receivedFrom,
        $date,
        $number;


    function SetDash($black=null, $white=null)

    {

        if($black!==null)

            $s=sprintf('[%.3F %.3F] 0 d',$black*$this->k,$white*$this->k);

        else

            $s='[] 0 d';

        $this->_out($s);

    }

    function content()
    {
        $this->Image('logo.png', '3', '0', '18');
        $this->SetFont('Courier', 'B', 12);
        $this->Cell(55, 5, 'RECEIPT', '0', '', 'R');
        $this->SetFont('Courier', 'B', 10);
        $this->Cell(30, 5, $this->number, '', '', 'C');

        $this->Ln(10);
        $this->Cell(20, 8, '', '', 'L');
        $this->SetFont('arial', 'B', 10);
        $this->Cell(55, 5, 'DEVINE OASIS ASSEMBLY', '', '', 'C');
        $this->Ln();
        $this->Cell(20, 8, '', '', 'L');
        $this->Cell(55, 5, 'MALAWI ASSEMBLIES OF GOD', '', '', 'C');

        $this->Ln();
        $this->SetFont('arial', '', 9);
        $this->Cell(20, 8, '', '', 'L');
        $this->Cell(55, 5, 'P.O.BOX 208', '', '', 'C');
        $this->Ln();
        $this->SetFont('arial', '', 10);
        $this->Cell(20, 8, '', '', 'L');
        $this->Cell(55, 5, 'ZOMBA', '', '', 'C');

        $this->Ln(6);
        $this->Cell(50, 10, '', '', 'L');
        $this->Cell(30, 10, 'Date: ' . $this->date, '', 'R');
        $this->Ln(12);

        $this->SetFont('arial', 'B', 10);
        $this->MultiCell(88, 5, 'Received from ' . $this->receivedFrom, '', 'L');
        $this->Ln(5);

        $this->SetFont('arial', 'B', 10);
        $this->MultiCell(88, 5, 'The Sum Of : ' . $this->sumOf, '', 'L');
        $this->Ln(5);

        $this->SetFont('arial', 'B', 10);
        $this->Cell(4, 5, 'K', '', 'L');
        $this->Cell(22, 5, number_format($this->kwachaFigure, 2), '', 'L');

        if ($this->chequeNo){
            $this->SetFont('arial', 'B', 10);
        $this->Cell(6, 5, '');
        $this->Cell(19, 5, 'Cheque No');
        $this->Cell(27, 5, $this->chequeNo, '1');
    }
        $this->Ln(10);

        $this->SetFont('arial','B',8);
        $this->Cell(12,5,$this->paymentMode,'1','L');
        $this->Cell(20,5,'','','L');
        $this->Cell(30,5,'Accountant/Treasure','','L');
        $this->SetFont('arial','B',10);
        $this->Cell(28,5,'','1','L');
        $this->Ln(10);

        $this->SetFont('arial','B',10);
        $this->MultiCell(88,5,'In Payment of '.$this->paidFor,'','L');
        $this->Ln(5);

        $this->SetDash(1, 1);
        $this->Line(5, 45, 95, 45);
        //$this->Line(5, 80, 95, 80);
        $this->Line(5, 120, 95, 120);
    }

    function footer_note(){

        $this->Ln();
        $this->SetFont('arial', 'B', 8);
        $this->MultiCell(0, 4, '"Give and it shall be given to you" (Luke 6:38a)', 0, 'C' );
    }
}





