<?php

namespace App\Http\Controllers;

use App\Models\Accounts;
use App\Models\Church;
use App\Models\ChurchPayment;
use App\Models\Debtor;
use App\Models\DebtorStatement;
use App\Models\Member;
use App\Models\MemberPayment;
use App\Models\Ministry;
use App\Models\MinistryPayment;
use App\Models\Month;
use App\Models\order;
use App\Models\Payment;
use Illuminate\Http\Request;

include ('libs/fpdf.php');
include ('libs/numberToWords.php');

class DeliveryController extends \FPDF
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

    function content(){
        $this->Image('logo.png','3','5','20');
        $this->SetFont('Courier','B',12);
        $this->Cell(55,5,'RECEIPT','0','','R');
        $this->SetFont('Courier','B',7);
        $this->Cell(30,5,'NO '.$this->SD,'','','C');

        $this->Ln();
        $this->Cell(20,8,'','','L');
        $this->SetFont('Courier','B',8);
        $this->Cell(85,5,'MALAWI ASSEMBLIES OF GOD','','','C');
        $this->Ln(10);

        $this->SetFont('Courier','B',9);
        $this->Cell(50,10,'','','L');
        $this->Cell(30,2,'Date: '.@date('D d, M Y'),'','R');
        $this->Ln(10);

        $this->SetFont('Courier','B',9);
        $this->MultiCell(88,1,'Received from '.$this->receivedFrom,'','L');
        $this->Ln(5);

        $this->SetFont('Courier','B',9);
        $this->MultiCell(88,5,'The Sum Of : '.$this->sumOf,'','L');
        $this->Ln(5);

        $this->SetFont('Courier','B',9);
        $this->MultiCell(88,5,'Being Paid for '.$this->paidFor.' for the month of '.$this->monthOf,'','L');
        $this->Ln(5);

        $this->SetFont('Courier','B',9);
        $this->Cell(22,7,'K '.number_format($this->kwachaFigure,2),'','L');
        $this->SetFont('Courier','B',9);
        $this->Cell(1,5,'Thank You','','L');
        $this->Ln(5);

        $this->SetDash(1, 1);
        $this->Line(5, 28, 95, 28);
        $this->Line(5, 70, 95, 70);
        $this->Line(5, 100, 95, 100);
    }

    function footer_note(){

        $this->Ln(10);
        $this->SetFont('Courier', '', 8);

        $this->cell(0, 0, 'Designed By Marc Systems Africa (0882 230 137)', 0, 0, 'C','');
        $this->Ln(4);
    }

    public function generateDeliveryNote()
    {
        $id = new Payment();
        $payment = MemberPayment::where(['payment_id'=>@$_GET['id']])->first();
        $payment_id = Payment::where(['id'=>@$_GET['id']])->first();
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
    public function generateMemberReceipt($id,$monthId)
    {
        $payment = MemberPayment::where(['id'=>$id])->first();
        $account = Accounts::where(['id'=>$payment->account_id])->first();
        $totalInWords = $payment->amount;
        $total = $totalInWords;
        $totalInWords = str_replace('  ',' ',str_replace(
            '.',' Kwacha ',strtolower(numberToWords($totalInWords))));

        $this->monthOf = $monthId;
        $this->date = date('d-m-Y');
        $this->kwachaFigure = $total;
        $this->sumOf = ucwords($totalInWords);
        $this->paidFor = $account->name;
        $this->receivedFrom = Member::where(['id'=>$payment->member_id])->first()->name;
        $this->SD = 'AOG-'.$payment->id;

        $this->SetMargins(5,5,5);
        $this->AddPage('P','struck');
        $this->AliasNbPages();
        $this->header();
        $this->content();
        $this->footer_note();
        $this->Output('libs/receipt.pdf', 'F');

    }
    public function generateHomeReceipt($id,$monthId)
    {
        $payment = ChurchPayment::where(['id'=>$id])->first();
        $account = Accounts::where(['id'=>$payment->account_id])->first();
        $totalInWords = $payment->amount;
        $total = $totalInWords;
        $totalInWords = str_replace('  ',' ',str_replace(
            '.',' Kwacha ',strtolower(numberToWords($totalInWords))));

        $this->monthOf = $monthId;
        $this->date = date('d-m-Y');
        $this->kwachaFigure = $total;
        $this->sumOf = ucwords($totalInWords);
        $this->paidFor = $account->name;
        $this->receivedFrom = Church::where(['id'=>$payment->church_id])->first()->name.' Home Church';
        $this->SD = 'AOG-'.$payment->id;

        $this->SetMargins(5,5,5);
        $this->AddPage('P','struck');
        $this->AliasNbPages();
        $this->header();
        $this->content();
        $this->footer_note();
        $this->Output('libs/receipt.pdf', 'F');

    }
    public function generateDebtorReceipt($id,$monthId)
    {
        $payment = DebtorStatement::where(['id'=>$id])->first();
        $account = Accounts::where(['id'=>$payment->account_id])->first();
        $totalInWords = $payment->amount;
        $total = $totalInWords;
        $totalInWords = str_replace('  ',' ',str_replace(
            '.',' Kwacha ',strtolower(numberToWords($totalInWords))));

        $this->monthOf = $monthId;
        $this->date = date('d-m-Y');
        $this->kwachaFigure = $total;
        $this->sumOf = ucwords($totalInWords);
        $this->paidFor = $account->name;
        $this->receivedFrom = Debtor::where(['id'=>$payment->debtor_id])->first()->name.' Debtor';
        $this->SD = 'AOG-'.$payment->id;

        $this->SetMargins(5,5,5);
        $this->AddPage('P','struck');
        $this->AliasNbPages();
        $this->header();
        $this->content();
        $this->footer_note();
        $this->Output('libs/receipt.pdf', 'F');

    }
    public function generateMinistryReceipt($id,$monthId)
    {
        $payment = MinistryPayment::where(['id'=>$id])->first();
        $account = Accounts::where(['id'=>$payment->account_id])->first();
        $totalInWords = $payment->amount;
        $total = $totalInWords;
        $totalInWords = str_replace('  ',' ',str_replace(
            '.',' Kwacha ',strtolower(numberToWords($totalInWords))));

        $this->monthOf = $monthId;
        $this->date = date('d-m-Y');
        $this->kwachaFigure = $total;
        $this->sumOf = ucwords($totalInWords);
        $this->paidFor = $account->name;
        $this->receivedFrom = Ministry::where(['id'=>$payment->ministry_id])->first()->name.' Ministry';
        $this->SD = 'AOG-'.$payment->id;

        $this->SetMargins(5,5,5);
        $this->AddPage('P','struck');
        $this->AliasNbPages();
        $this->header();
        $this->content();
        $this->footer_note();
        $this->Output('libs/receipt.pdf', 'F');

    }
    public function generateAdminReceipt($id,$monthId)
    {
        $payment = Payment::where(['id'=>$id])->first();
        $account = Accounts::where(['id'=>$payment->account_id])->first();
        $totalInWords = $payment->amount;
        $total = $totalInWords;
        $totalInWords = str_replace('  ',' ',str_replace(
            '.',' Kwacha ',strtolower(numberToWords($totalInWords))));

        $this->monthOf = $monthId;
        $this->date = date('d-m-Y');
        $this->kwachaFigure = $total;
        $this->sumOf = ucwords($totalInWords);
        $this->paidFor = $account->name;
        $this->receivedFrom = 'Main Church';
        $this->SD = 'AOG-'.$id;

        $this->SetMargins(5,5,5);
        $this->AddPage('P','struck');
        $this->AliasNbPages();
        $this->header();
        $this->content();
        $this->footer_note();
        $this->Output('libs/receipt.pdf', 'F');

    }
    public function generateReceipt()
    {
        $id = new Payment();
        $payment = MinistryPayment::where(['payment_id'=>$_GET['id']])->first();
        $payment_id = Payment::where(['id'=>$_GET['id']])->first();
        $account = Accounts::where(['id'=>$payment_id->account_id])->first();
        $month = Month::where(['id'=>$payment_id->month_id])->first();

        $totalInWords = $payment->amount;
        $total = $totalInWords;
        $totalInWords = str_replace('  ',' ',str_replace(
            '.',' Kwacha ',strtolower(numberToWords($totalInWords))));

        $this->monthOf = $month->name;
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
        $month = Month::where(['id'=>$payment_id->month_id])->first();

        $totalInWords = $payment->amount;
        $total = $totalInWords;
        $totalInWords = str_replace('  ',' ',str_replace(
            '.',' Kwacha ',strtolower(numberToWords($totalInWords))));

        $this->monthOf = $month->name;
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
    public function generateMemberReceiptById()
    {
        $id = new Payment();
        $payment = MemberPayment::where(['payment_id'=>$_GET['id']])->first();
        $payment_id = Payment::where(['id'=>$_GET['id']])->first();
        $account = Accounts::where(['id'=>$payment_id->account_id])->first();
        $month = Month::where(['id'=>$payment_id->month_id])->first();

        $totalInWords = $payment->amount;
        $total = $totalInWords;
        $totalInWords = str_replace('  ',' ',str_replace(
            '.',' Kwacha ',strtolower(numberToWords($totalInWords))));

        $this->monthOf = $month->name;
        $this->date = date('d-m-Y');
        $this->kwachaFigure = $total;
        $this->sumOf = ucwords($totalInWords);
        $this->paidFor = $account->name;
        $this->receivedFrom = Member::where(['id'=>$payment->member_id])->first()->name.' Member';
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
