<?php

namespace App\Http\Controllers;

use App\Models\DepartmentRequisitionItem;
use App\Models\Incomes;
use App\Models\order;
use App\Models\Requisition;
use App\Models\RequisitionItem;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

include ('libs/fpdf.php');

class OrderController extends \FPDF
{
    function my_header($customerArray=false,$invoiceNo){

        $this->Ln(50);
        $this->SetFont('Times','i',20);
        $this->Ln(10);

        $this->Image('libs/logo.jpg',0,5,50);
        $this->Ln(-20);

        $this->SetFont('Times','B',20);
        $this->MultiCell(284,10,'PAYMENT VOUCHER','','C','');
        $this->SetFont('Times','B',10);
        $this->MultiCell(284,10,'VOUCHER NO : '.@$invoiceNo['number'],'','L','');
        $this->SetFont('Times','',15);
        $this->Ln(6);
        $this->SetFillColor(0,225,225);

        $this->Cell(284,10,@date('d/m/Y'),1,'','L',1);

    }

    function contents($data,$totalPaid=0){

        $this->Ln(10);
        $this->SetFont('Times','B',15);
        $this->Cell(15,12,'REF','1','','C','');
        $this->Cell(40,12,'CASH (MK)','1','','C','');
        $this->Cell(117,12,'ITEM DESCRIPTION','1','','C','');
        $this->Cell(54,12,'REQUISITION NO','1','','C','');
        $this->Cell(58,12,'PAYEE','1','','C','');
        $this->Ln(10);

        $total = 0;

        foreach ($data as $key=>$values){

            $total += $values['cash'];

            $this->SetFont('Times','',11);
            $this->Cell(15,12,$key+1,false,'','C','');
            $this->Cell(40,12,number_format($values['cash']),false,'','C','');
            $this->Cell(117,12,$values['description'],false,'','C','');
            $this->Cell(52,12,$values['voucherNumber'],false,'','C','');
            $this->Cell(60,12,$values['comments'],false,'','C','');
            $this->Ln(10);
        }

        $this->Ln(10);
        $this->SetFont('Times','B',15);
        $this->Cell(15,12,'','1','','C','');
        $this->Cell(40,12,number_format($total),'1','','C','');
        $this->Cell(117,12,'TOTAL PAID ','1','','R','');
        $this->Cell(112,12,'MK'.number_format($total),'1','','C','');
        $this->Ln(10);

    }

    function footer_note($footerData){
        $this->Ln(10);
        $this->SetFont('Times','B',13);
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
    public function generateVoucher(order $order)
    {
        $this->SetMargins(5,5,5);
        $this->AddPage('P','A4');
        $this->AliasNbPages();
        $this->my_header();
        /////////////////////////// DATA///////////////////////////////////////////////////////////////
        $requisition = new Requisition ();
        $materials = RequisitionItem::where(['requisition_id'=>$_GET["id"]])->get();
        $id_now = $_GET["id"];
        $sql_1 =sprintf("SELECT CONCAT(u.first_name,' ',u.last_name) AS name,position
                                from users u join requisition_approvals r
                                on u.id = r.user_id
                                where r.status = 'closed' AND requisition_id = $id_now");
        $querySet = DB::select($sql_1);
        $name = Incomes::hydrate($querySet)->pluck('name')->first();
        $position = Incomes::hydrate($querySet)->pluck('position')->first();

        foreach($materials as $item ){
            $data[] = [
                'description' => $item->material->name.' of '.$item->material->specifications,
                'voucherNumber' =>$_GET["id"] ,
                'comments' => $item->personale,
                'cash' => $item->quantity*$item->getPrice($item->material_id)
            ];
        }


        $footerData = array(
            'preparedBy'=>request()->user()->name,
            'position'=>request()->user()->position,
            'approvedBy'=>$name,
            'position2'=>$position
        );
///////////////////////////END OF DATA////////////////////////////////////////////////////////



        $this->contents($data);
        $this->footer_note($footerData);
        @$this->Output();
        activity('REQUISITIONS')
            ->log("Generated a Voucher")->causer(request()->user());
    }
    public function generateDepartmentVoucher(order $order)
    {
        $this->SetMargins(5,5,5);
        $this->AddPage('P','A4');
        $this->AliasNbPages();

        /////////////////////////// DATA///////////////////////////////////////////////////////////////
        $requisition = new Requisition ();

        $materials = DepartmentRequisitionItem::where(['department_requisition_id'=>$_GET["id"]])->get();
        $id_now = $_GET["id"];
        $sql_1 =sprintf("SELECT CONCAT(u.first_name,' ',u.last_name) AS name,position
                                from users u join requisition_department_approvals r
                                on u.id = r.user_id
                                where r.status = 'closed' AND department_requisition_id = $id_now");
        $querySet = DB::select($sql_1);
        $name = Incomes::hydrate($querySet)->pluck('name')->first();
        $position= Incomes::hydrate($querySet)->pluck('position')->first();

        foreach($materials as $item ){
            $data[] = [
                'description' => $item->description,
                'voucherNumber' =>$_GET["id"] ,
                'comments' => $item->personale,
                'cash' => $item->amount
            ];
        }


        $footerData = array(
            'preparedBy'=>request()->user()->name,
            'position'=>request()->user()->position,
            'approvedBy'=>$name,
            'position2'=>$position,
        );
///////////////////////////END OF DATA////////////////////////////////////////////////////////

        $voucher= Voucher::where(['requisition_id'=>$_GET["id"]])->first();
        if(!$voucher){
            @$number = 'N/A';
        }else{
            @$number = $voucher->id;
        }
        $number = array(
            'number'=>$number,
        );
        $this->my_header(0,$number);

        $this->contents($data);
        $this->footer_note($footerData);
        @$this->Output();
        activity('REQUISITIONS')
            ->log("Generated a Voucher")->causer(request()->user());
    }
    public function generateDeliveryNote(order $order)
    {
        $this->SetMargins(5,5,5);
        $this->AddPage('P','A4');
        $this->AliasNbPages();
        $this->my_header();
        /////////////////////////// DATA///////////////////////////////////////////////////////////////

        $materials = DepartmentRequisitionItem::where(['department_requisition_id'=>$_GET["id"]])->get();
        $id_now = $_GET["id"];
        $sql_1 =sprintf("SELECT CONCAT(u.first_name,' ',u.last_name) AS name,position
                                from users u join requisition_department_approvals r
                                on u.id = r.user_id
                                where r.status = 'closed' AND department_requisition_id = $id_now");
        $querySet = DB::select($sql_1);
        $name = Incomes::hydrate($querySet)->pluck('name')->first();
        $position = Incomes::hydrate($querySet)->pluck('position')->first();

        foreach($materials as $item ){
            $data[] = [
                'description' => $item->material->name.' of '.$item->material->specifications,
                'voucherNumber' =>$_GET["id"] ,
                'comments' => $item->personale,
                'cash' => $item->quantity*$item->getPrice($item->material_id)
            ];
        }


        $footerData = array(
            'preparedBy'=>request()->user()->name,
            'position'=>request()->user()->position,
            'approvedBy'=>$name,
            'position2'=>$position
        );
///////////////////////////END OF DATA////////////////////////////////////////////////////////



        $this->contents($data);
        $this->footer_note($footerData);
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
