<?php

namespace App\Http\Controllers;

use App\Http\Requests\Payments\StoreRequest;
use App\Models\Accounts;
use App\Models\Banks;
use App\Models\BankTransaction;
use App\Models\Church;
use App\Models\ChurchPayment;
use App\Models\Department;
use App\Models\Incomes;
use App\Models\Labourer;
use App\Models\LabourerPayments;
use App\Models\Member;
use App\Models\MemberPayment;
use App\Models\Ministry;
use App\Models\MinistryPayment;
use App\Models\Month;
use App\Models\order;
use App\Models\Payment;
use App\Models\Project;
use App\Models\ProjectPayment;
use App\Models\Requisition;
use App\Models\RequisitionItem;
use App\Models\Supplier;
use App\Models\SupplierPayments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function homePayments()
    {

        activity('FINANCES')
            ->log("Accessed Payments")->causer(request()->user());
        $payment = Payment::where(['type'=>6])->orderBy('id','desc')->get();
        return view('payments.church-transactions')->with([
            'cpage' => "finances",
            'payments'=>$payment,
        ]);
    }
    public function ministryPayments()
    {

        activity('FINANCES')
            ->log("Accessed Payments")->causer(request()->user());
        $payment = Payment::where(['type'=>7])->orderBy('id','desc')->get();
        return view('payments.ministry-transactions')->with([
            'cpage' => "finances",
            'payments'=>$payment,
        ]);
    }
    public function memberPayments()
    {

        activity('FINANCES')
            ->log("Accessed Payments")->causer(request()->user());
        $payment = Payment::where(['type'=>5])->orderBy('id','desc')->get();
        return view('payments.member-transactions')->with([
            'cpage' => "finances",
            'payments'=>$payment,
        ]);
    }
    public function index()
    {

         if(Month::getActiveMonth()){
             $month =Month::getActiveMonth();
    }else{
        return redirect()->route('months.index')->with([
            'success-notification'=>"Please Create a new month"
        ]);
    }

        activity('FINANCES')
            ->log("Accessed Payments")->causer(request()->user());
        $payment = Payment::whereBetween('t_date',[$month->start_date,$month->end_date])
            ->orderBy('id','desc')->get();
        return view('payments.index')->with([
            'cpage' => "finances",
            'payments'=>$payment,
            'months'=>Month::orderBY('id','desc')->get()
        ]);
    }
    public function generateReceipt(Request $request)
    {
        $request->validate([
            'month_id' => "required|numeric",
        ]);
        $month =  Month::where(['id'=>$request->post('month_id')])
            ->first();

        activity('FINANCES')
            ->log("Accessed Payments")->causer(request()->user());
        $payment = Payment::whereBetween('t_date',[$month->start_date,$month->end_date])
            ->orderBy('id','desc')->get();
        return view('payments.index')->with([
            'cpage' => "finances",
            'payments'=>$payment,
            'months'=>Month::orderBY('id','desc')->get()
        ]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $suppliers = Supplier::all();
        $banks = Banks::all();
        $labourer = Labourer::all();
        $projects = Department::all();
        $accounts = Accounts::where(['type'=>2])->get();
        return view('payments.create')->with([
            'cpage'=>"finances",
            'suppliers'=>$suppliers,
            'banks'=>$banks,
            'members'=>Member::all(),
            'churches'=>Church::all(),
            'ministries'=>Ministry::all(),
            'projects'=>$projects,
            'labourers'=>$labourer,
            'accounts'=>$accounts
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store (StoreRequest $request)
    {
        $request->validate([
            'type' => "required",
            'cheque_number'=>"required",
        ]);
        $data = $request->all();
        if($data['type']=='1') {
            $request->validate([
                'project_id' => "required",
            ]);
        }
        if($data['type']=='3') {
            $request->validate([
                'supplier_id' => "required",
            ]);
        }
        if($data['type']=='4') {
            $request->validate([
                'labourer_id' => "required",
            ]);
            $labour_project_id = Labourer::where(['id'=>$request->post('labourer_id')])->first();
            $labour_project_id = $labour_project_id->department_id;
        }

        $account = Accounts::where(['id'=>$request->post('account_id')])->first();
        $account_name =$account->name;

        $account_type = $account->type;

        // dd($project_id);
        $bal = BankTransaction::where(['bank_id'=>$request->post('bank_id')])->orderBy('id','desc')->first();
        @$balance = $bal->balance;
        if(!$balance){
            $balance = 0;
        }
        // we are here think of a grant
        $transactions = [
            'description'=>$request->post('name').' - '.$account_name,
            'type'=>$request->post('type'),
            'amount'=>$request->post('amount'),
            'account_id'=>$request->post('account_id'),
            'bank_id'=>$request->post('bank_id'),
            'method'=>$request->post('payment_method'),
            'balance'=>$balance-$request->post('amount')
        ];

        // Supplier Balance
        $bal = SupplierPayments::where(['supplier_id'=>$request->post('supplier_id')])->orderBy('id','desc')->first();
        @$balance = $bal->balance;
        if(!$balance){
            $balance = 0;
        }
        $supplier_balance = $balance-$request->post('amount');
        //
        $data = $request->post();
        Payment::create($data);

        if($request->type==4){
            $bala = LabourerPayments::where(['labourer_id'=>$request->post('labourer_id')])->orderBy('id','desc')->first();
            @$balances = $bala->balance;
            if(!$balances){
                $balances = 0;
            }
            $labourers = [
                'labourer_id'=>$request->post('labourer_id'),
                'amount'=>$request->post('amount'),
                'account_id'=>$request->post('account_id'),
                'description'=>$request->post('name').' - '.$account_name,
                'balance'=>$balances-$request->post('amount'),
                'method'=>$request->post('payment_method'),
                'type'=>2,
            ];

//            ProjectPayment::create($project_payment);
            LabourerPayments::create($labourers);
        }
        if($request->type==3){
            $suppliers = [
                'expenses_id'=>'1111111',
                'supplier_id'=>$request->post('supplier_id'),
                'amount'=>$request->post('amount'),
                'method'=>$request->post('payment_method'),
                'balance'=>$supplier_balance,
                'transaction_type'=>2,
            ];
            SupplierPayments::create($suppliers);
        }

        BankTransaction::create($transactions);
        activity('FINANCES')
            ->log("Created a Payment")->causer(request()->user());
        return redirect()->route('payments.index')->with([
            'success-notification'=>"Payment successfully Created"
        ]);
    }

//    public function store (StoreRequest $request)
//    {
//        $request->validate([
//            'type' => "required",
//            'cheque_number'=>"required",
//        ]);
//        $data = $request->all();
//        switch ($data['type']){
//            case '1':{
//                $request->validate(['project_id' => "required"]);
//                break;
//            }
//            case '2':{
//                $projects = [
//                    'account_id'=>$request->post('account_id'),
//                    'project_id'=>'0',
//                    'amount'=>$request->post('amount'),
//                    'bank_id'=>$request->post('bank_id'),
//                    'cheque_number'=>$request->post('cheque_number'),
//                    'description'=>$request->post('name'),
//                    'method'=>$request->post('payment_method'),
//                    'transaction_type'=>$request->post('type'),
//                ];
//                Incomes::create($projects);
//                break;
//            }
//            case '3':{
//                $request->validate(['supplier_id' => "required"]);
//                break;
//            }
//            case '4':{
//                $request->validate(['labourer_id' => "required"]);
//                $labour_project_id = Labourer::where(['id'=>$request->post('labourer_id')])->first();
//                $labour_project_id = $labour_project_id->department_id;
//                break;
//            }
//            case '5':{
//                $request->validate(['member_id' => "required"]);
//                break;
//            }
//            case '6':{
//                $request->validate(['church_id' => "required"]);
//                break;
//            }
//            case '7':{
//                $request->validate(['ministry_id' => "required"]);
//                break;
//            }
//        }
//
//        $account = Accounts::where(['id'=>$request->post('account_id')])->first();
//        $account_name =$account->name;
//        $account_type = $account->type;
//
//        // dd($project_id);
//        $bal = BankTransaction::where(['bank_id'=>$request->post('bank_id')])->orderBy('id','desc')->first();
//        @$balance = $bal->balance;
//        if(!$balance){
//            $balance = 0;
//        }
//        if($account_type=='2'){
//            if($request->post('amount')>$balance){
//                return back()->with(['error-notification'=>"The bank balance is less than the amount requested"]);
//            }else{
//                $new_balance = $balance-$request->post('amount');
//            }
//        }else{
//            $new_balance = $balance+$request->post('amount');
//        }
//        $transactions = [
//            'description'=>$request->post('name'),
//            'type'=>$account_type,
//            'account_id'=>$request->post('account_id'),
//            'amount'=>$request->post('amount'),
//            'bank_id'=>$request->post('bank_id'),
//            'method'=>$request->post('payment_method'),
//            'balance'=>$new_balance
//        ];
//
//        // Supplier Balance
//        $bal = SupplierPayments::where(['supplier_id'=>$request->post('supplier_id')])->orderBy('id','desc')->first();
//        @$balance = $bal->balance;
//        if(!$balance){
//            $balance = 0;
//        }
//        $supplier_balance = $balance-$request->post('amount');
//        //
//        $data = $request->post();
//
//        $raw_data = [
//            'account_id'=>$data['account_id'],
//            'amount'=>$data['amount'],
//            'name'=>$data['name'],
//            't_date'=>$data['t_date'],
//            'reference'=>$data['reference'],
//        ];
//        if(Payment::where($raw_data)->first()){
//            return redirect(
//                route('payments.create') . "?id=pending")->with(
//                ['error-notification'=>"You have already created this transaction Today. Check the Transaction Properly"]
//            );
//        }
//        $payments = Payment::create($data);
//        $payment = $payments->id;
//        $projects = [
//            'account_id'=>$request->post('account_id'),
//            'project_id'=>$request->post('project_id'),
//            'amount'=>$request->post('amount'),
//            'bank_id'=>$request->post('bank_id'),
//            'cheque_number'=>$request->post('cheque_number'),
//            'description'=>"Payment",
//            'method'=>$request->post('payment_method'),
//            'transaction_type'=>$request->post('type'),
//        ];
//        $bala = ProjectPayment::where(['project_id'=>$request->post('project_id')])->orderBy('id','desc')->first();
//        @$balances = $bala->balance;
//        if(!$balances){
//            $balances = 0;
//        }
//        if($account_type=='2'){
//            $new_balances = $balances+$request->post('amount');
//        }else{
//            $new_balances = $balances-$request->post('amount');
//        }
//
//        if($request->type==4){
//            $bala = LabourerPayments::where(['labourer_id'=>$request->post('labourer_id')])->orderBy('id','desc')->first();
//            @$balances = $bala->balance;
//            if(!$balances){
//                $balances = 0;
//            }
//            $labourers = [
//                'expense_name'=>$request->post('name'),
//                'labourer_id'=>$request->post('labourer_id'),
//                'amount'=>$request->post('amount'),
//                'description'=>$request->post('description'),
//                'project_id'=>$labour_project_id,
//                'balance'=>$balances-$request->post('amount'),
//                'method'=>$request->post('payment_method'),
//                'type'=>2,
//            ];
//            $project_payment = [
//                'project_id'=>$labour_project_id,
//                'amount'=>$request->post('amount'),
//                'balance'=>$new_balances,
//                'payment_name'=>$request->post('name'),
//                'payment_type'=>'2'
//            ];
//            $projects = [
//                'account_id'=>$request->post('account_id'),
//                'project_id'=>$labour_project_id,
//                'amount'=>$request->post('amount'),
//                'bank_id'=>$request->post('bank_id'),
//                'cheque_number'=>$request->post('cheque_number'),
//                'description'=>"Payment",
//                'method'=>$request->post('payment_method'),
//                'transaction_type'=>$request->post('type'),
//            ];
//            ProjectPayment::create($project_payment);
//            Incomes::create($projects);
//            LabourerPayments::create($labourers);
//        }
//        if($request->type==3){
//            $suppliers = [
//                'expenses_id'=>'1111111',
//                'supplier_id'=>$request->post('supplier_id'),
//                'amount'=>$request->post('amount'),
//                'method'=>$request->post('payment_method'),
//                'balance'=>$supplier_balance,
//                'transaction_type'=>2,
//            ];
//            $projects = [
//                'account_id'=>$request->post('account_id'),
//                'project_id'=>'0',
//                'amount'=>$request->post('amount'),
//                'bank_id'=>$request->post('bank_id'),
//                'cheque_number'=>$request->post('cheque_number'),
//                'description'=>$request->post('name'),
//                'method'=>$request->post('payment_method'),
//                'transaction_type'=>$request->post('type'),
//            ];
//            Incomes::create($projects);
//            SupplierPayments::create($suppliers);
//        }
//        if($request->type==1){
//            $project_payment = [
//                'project_id'=>$request->post('project_id'),
//                'amount'=>$request->post('amount'),
//                'balance'=>$new_balances,
//                'payment_name'=>$request->post('name'),
//                'payment_type'=>'1'
//            ];
//            ProjectPayment::create($project_payment);
//            Incomes::create($projects);
//        }
//        if($request->type==5){
//            $bala = MemberPayment::where(['member_id'=>$request->post('member_id')])->orderBy('id','desc')->first();
//            @$balances = $bala->balance;
//            if(!$balances){
//                $balances = 0;
//            }
//            $members = [
//                'name'=>$request->post('name'),
//                'member_id'=>$request->post('member_id'),
//                'amount'=>$request->post('amount'),
//                'account_id'=>$request->post('account_id'),
//                'balance'=>$balances+$request->post('amount'),
//                'payment_id'=>$payment,
//                'transaction_type'=>2,
//            ];
//            $projects = [
//                'account_id'=>$request->post('account_id'),
//                'project_id'=>'0',
//                'amount'=>$request->post('amount'),
//                'bank_id'=>$request->post('bank_id'),
//                'cheque_number'=>$request->post('cheque_number'),
//                'description'=>"Payment",
//                'method'=>$request->post('payment_method'),
//                'transaction_type'=>$request->post('type'),
//            ];
//            Incomes::create($projects);
//            $last_id = MemberPayment::create($members);
//            $order = new DeliveryController();
//            $order->generateMemberReceipt($last_id->id);
//        }
//        if($request->type==6){
//            $bala = ChurchPayment::where(['church_id'=>$request->post('church_id')])->orderBy('id','desc')->first();
//            @$balances = $bala->balance;
//            if(!$balances){
//                $balances = 0;
//            }
//            $churches= [
//                'name'=>$request->post('name'),
//                'church_id'=>$request->post('church_id'),
//                'amount'=>$request->post('amount'),
//                'account_id'=>$request->post('account_id'),
//                'balance'=>$balances+$request->post('amount'),
//                'payment_id'=>$payment,
//                'transaction_type'=>2,
//            ];
//            $projects = [
//                'account_id'=>$request->post('account_id'),
//                'project_id'=>'0',
//                'amount'=>$request->post('amount'),
//                'bank_id'=>$request->post('bank_id'),
//                'cheque_number'=>$request->post('cheque_number'),
//                'description'=>"Payment",
//                'method'=>$request->post('payment_method'),
//                'transaction_type'=>$request->post('type'),
//            ];
//            Incomes::create($projects);
//            $last_id = ChurchPayment::create($churches);
//            $order = new DeliveryController();
//            $order->generateHomeReceipt($last_id->id);
//        }
//        if($request->type==7){
//            $bala = MinistryPayment::where(['ministry_id'=>$request->post('ministry_id')])->orderBy('id','desc')->first();
//            @$balances = $bala->balance;
//            if(!$balances){
//                $balances = 0;
//            }
//            $ministries = [
//                'name'=>$request->post('name'),
//                'ministry_id'=>$request->post('ministry_id'),
//                'amount'=>$request->post('amount'),
//                'account_id'=>$request->post('account_id'),
//                'balance'=>$balances+$request->post('amount'),
//                'payment_id'=>$payment,
//                'transaction_type'=>2,
//            ];
//            $projects = [
//                'account_id'=>$request->post('account_id'),
//                'project_id'=>'0',
//                'amount'=>$request->post('amount'),
//                'bank_id'=>$request->post('bank_id'),
//                'cheque_number'=>$request->post('cheque_number'),
//                'description'=>"Payment",
//                'method'=>$request->post('payment_method'),
//                'transaction_type'=>$request->post('type'),
//            ];
//            Incomes::create($projects);
//            $last_id =MinistryPayment::create($ministries);
//            $order = new DeliveryController();
//            $order->generateMinistryReceipt($last_id->id);
//        }
//
//        BankTransaction::create($transactions);
//        //code to generate an invoice
//        activity('FINANCES')
//            ->log("Created a Payment")->causer(request()->user());
//        return redirect(
//            route('payments.create') . "?id=success")->with(
//            ['success-notification'=>"Successfully Created"]
//        );
//    }

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
