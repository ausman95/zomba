<?php

namespace App\Http\Controllers;

use App\Models\Accounts;
use App\Models\ChurchPayment;
use App\Models\MemberPayment;
use App\Models\MinistryPayment;
use App\Models\order;
use App\Models\Payment;
use Illuminate\Http\Request;

include ('libs/fpdf.php');
include ('libs/numberToWords.php');

class DeliveryController extends \FPDF
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
        $this->Image('libs/logo.png', '1', '0', '15');
        $this->SetFont('times', 'B', 12);
        $this->Cell(55, 5, 'RECEIPT', '0', '', 'R');
        $this->SetFont('times', 'B', 10);
        $this->Cell(30, 5, $this->number, '', '', 'C');

        $this->Ln(10);
        $this->Cell(20, 2, '', '', 'L');
        $this->SetFont('times', 'B', 10);
        $this->Cell(55, 5, 'DEVINE OASIS ASSEMBLY', '', '', 'C');
        $this->Ln();
        $this->Cell(20, 2, '', '', 'L');
        $this->Cell(55, 5, 'MALAWI ASSEMBLIES OF GOD', '', '', 'C');

        $this->Ln();
        $this->SetFont('times', '', 9);
        $this->Cell(20, 8, '', '', 'L');
        $this->Cell(55, 5, 'P.O.BOX 208', '', '', 'C');
        $this->Ln();
        $this->SetFont('times', '', 10);
        $this->Cell(20, 8, '', '', 'L');
        $this->Cell(55, 5, 'ZOMBA', '', '', 'C');

        $this->Ln(6);
        $this->Cell(50, 10, '', '', 'L');
        $this->Cell(30, 10, 'Date: ' . $this->date, '', 'R');
        $this->Ln(12);

        $this->SetFont('times', 'B', 10);
        $this->MultiCell(88, 5, 'Received from ' . $this->receivedFrom, '', 'L');
        $this->Ln(5);

        $this->SetFont('times', 'B', 10);
        $this->MultiCell(88, 5, 'The Sum Of : ' . $this->sumOf, '', 'L');
        $this->Ln(5);

        $this->SetFont('times', 'B', 10);
        $this->Cell(4, 5, 'K', '', 'L');
        $this->Cell(22, 5, number_format($this->kwachaFigure, 2), '', 'L');

        if ($this->chequeNo){
            $this->SetFont('times', 'B', 10);
            $this->Cell(6, 5, '');
            $this->Cell(19, 5, 'Cheque No');
            $this->Cell(27, 5, $this->chequeNo, '1');
        }
        $this->Ln(10);

        $this->SetFont('times','B',8);
        $this->Cell(12,5,$this->paymentMode,'1','L');
        $this->Cell(20,5,'','','L');
        $this->Cell(30,5,'Accountant/Treasure','','L');
        $this->SetFont('arial','B',10);
        $this->Cell(28,5,'','1','L');
        $this->Ln(10);

        $this->SetFont('times','B',10);
        $this->MultiCell(88,5,'In Payment of '.$this->paidFor,'','L');
        $this->Ln(5);


    }

    function footer_note(){

        $this->Ln();
        $this->SetFont('times', 'B', 8);
        $this->MultiCell(0, 4, '"Give and it shall be given to you" (Luke 6:38a)', 0, 'C' );
    }

    public function generateDeliveryNote()
    {
        $id = new Payment();
        $payment = MemberPayment::where(['payment_id'=>$_GET['id']])->first();
        $payment_id = Payment::where(['id'=>$_GET['id']])->first();
        $account = Accounts::where(['id'=>$payment_id->account_id])->first();

        $totalInWords = $payment->amount;
        $total = $totalInWords;
        $totalInWords = str_replace('  ',' ',str_replace(
            '.',' Kwacha ',strtolower(numberToWords($totalInWords))));

        $this->monthOf = date('F');
        $this->date = date('d-m-Y');
        $this->kwachaFigure = $total;
        $this->sumOf = ucwords($totalInWords);
        $this->paidFor = $account->name;
        $this->receivedFrom = $id->getMember($_GET['id']);
        $this->SD = 'AOG-'.$payment->id;

        $this->SetMargins(5,5,5);
        $this->AddPage('P','struck');
        $this->AliasNbPages();
        $this->header();
        $this->content();
        //$this->footer_note();
        @$this->Output();
    }
    public function generateReceipt()
    {
        $id = new Payment();
        $payment = MinistryPayment::where(['payment_id'=>$_GET['id']])->first();
        $payment_id = Payment::where(['id'=>$_GET['id']])->first();
        $account = Accounts::where(['id'=>$payment_id->account_id])->first();

        $totalInWords = $payment->amount;
        $total = $totalInWords;
        $totalInWords = str_replace('  ',' ',str_replace(
            '.',' Kwacha ',strtolower(numberToWords($totalInWords))));

        $this->monthOf = date('F');
        $this->date = date('d-m-Y');
        $this->kwachaFigure = $total;
        $this->sumOf = ucwords($totalInWords);
        $this->paidFor = $account->name;
        $this->receivedFrom = $id->getMinistry($_GET['id']).' Ministry';
        $this->SD = 'AOG-'.$payment->id;

        $this->SetMargins(5,5,5);
        $this->AddPage('P','struck');
        $this->AliasNbPages();
        $this->header();
        $this->content();
        //$this->footer_note();
        @$this->Output();
    }
    public function generateChurchReceipt()
    {
        $id = new Payment();
        $payment = ChurchPayment::where(['payment_id'=>$_GET['id']])->first();
        $payment_id = Payment::where(['id'=>$_GET['id']])->first();
        $account = Accounts::where(['id'=>$payment_id->account_id])->first();

        $totalInWords = $payment->amount;
        $total = $totalInWords;
        $totalInWords = str_replace('  ',' ',str_replace(
            '.',' Kwacha ',strtolower(numberToWords($totalInWords))));

        $this->monthOf = date('F');
        $this->date = date('d-m-Y');
        $this->kwachaFigure = $total;
        $this->sumOf = ucwords($totalInWords);
        $this->paidFor = $account->name;
        $this->receivedFrom = $id->getHome($_GET['id']).' Home Cell';
        $this->SD = 'AOG-'.$payment->id;

        $this->SetMargins(5,5,5);
        $this->AddPage('P','struck');
        $this->AliasNbPages();
        $this->header();
        $this->content();
        //$this->footer_note();
        @$this->Output();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
