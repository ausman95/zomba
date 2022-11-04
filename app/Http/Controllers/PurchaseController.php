<?php

namespace App\Http\Controllers;

use App\Http\Requests\Purchases\StoreRequest;
use App\Models\Accounts;
use App\Models\Banks;
use App\Models\BankTransaction;
use App\Models\Department;
use App\Models\Expenses;
use App\Models\Incomes;
use App\Models\LabourerPayments;
use App\Models\Material;
use App\Models\Price;
use App\Models\Project;
use App\Models\ProjectPayment;
use App\Models\Purchase;
use App\Models\Selection;
use App\Models\StockFlow;
use App\Models\Supplier;
use App\Models\SupplierPayments;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        activity('FINANCES')
            ->log("Accessed Purchases")->causer(request()->user());
        $purchases = Purchase::orderBy('id','desc')->get();;
        return view('purchases.index')->with([
            'cpage' => "purchases",
            'purchases'=>$purchases,
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
        $material = Material::all();
        $projects = Department::all();
        $accounts = Accounts::where(['type'=>1])->orderBy('name','DESC')->get();
        return view('purchases.create')->with([
            'cpage'=>"purchases",
            'suppliers'=>$suppliers,
            'banks'=>$banks,
            'projects'=>$projects,
            'materials'=>$material,
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
            'payment_type' => "required",
        ]);
        $data = $request->all();
        if($data['payment_type']=='1') {
            $request->validate([
                'supplier_id' => "required",
            ]);
        }
        if($data['payment_type']=='2') {
            $request->validate([
                'supplier_id' => "required",
            ]);
        }
        $data = $request->post();
//        $check_data = [
//            'material_id'=>$data['material_id'],
//            'supplier_id'=>$data['supplier_id']
//        ];


//        if(!Selection::where($check_data)->first()){
//            // labourer is already part of this project
//            return back()->with(['error-notification'=>"The material you trying to purchase is not allocated to the supplier specified!"]);
//        }

        $price = Price::where(['material_id'=>$request->post('material_id')])->first()->price;
        if(!$price){
            return back()->with(['error-notification'=>"The Material you are trying to purchase does not have a price tag"]);
        }
        $amount = $price*$request->post('quantity');

        // validation
        $account = Accounts::where(['id'=>$request->post('account_id')])->first();

        $mat = Material::where(['id'=>$request->post('material_id')])->first();
        $mat_name =$mat->name;
        $account_name =$account->name;
        $account_name = @$mat_name.' - '.$account_name;
        $account_type = $account->type;
        $bal = BankTransaction::where(['bank_id'=>$request->post('bank_id')])->orderBy('id','desc')->first();
        @$balance = $bal->balance;
        if($request->post('payment_type')!=2){
            if($amount>$balance){
                return back()->with(['error-notification'=>"The bank balance is less than the amount requested"]);
            }
        }
        $new_balance = $balance-$amount;

        //validation
        // Supplier Balance
        $bal = SupplierPayments::where(['supplier_id'=>$request->post('supplier_id')])->orderBy('id','desc')->first();
        @$balance = $bal->balance;
        if(!$balance){
            $balance = 0;
        }
        if($request->post('payment_type')==2){
            $supplier_balance = $balance+$amount;
        }else{
            $supplier_balance = $balance;
        }



        $tuple = [
            'expenses_id'=>1111,
            'supplier_id'=>$request->post('supplier_id'),
            'amount'=>$amount,
            'balance'=>$supplier_balance,
            'method'=>$request->post('payment_type'),
            'transaction_type'=>1,
        ];
        // we need to create a record for Project payment
        $bala = ProjectPayment::where(['project_id'=>$request->post('project_id')])->orderBy('id','desc')->first();
        @$balances = $bala->balance;
        if(!$balances){
            $balances = 0;
        }
        $mat_balance = StockFlow::where(['department_id'=>$request->post('project_id'),'material_id'=>$request->post('material_id')])->orderBy('id','desc')->first();
        @$material_balance = $mat_balance->balance;
        if(!$material_balance){
            $material_balance = 0;
        }
        $payment = [
            'project_id'=>$request->post('project_id'),
            'amount'=>$amount,
            'balance'=>$balances-$amount,
            'payment_name'=>"Purchase of Materials",
            'payment_type'=>'2'
        ];
        $purchase_data = [
            'material_id'=>$request->post('material_id'),
            'supplier_id'=>$request->post('supplier_id'),
            'amount'=>$amount,
            'account_id'=>$request->post('account_id'),
            'reference'=>$request->post('reference'),
            'date'=>$request->post('date'),
            'stores'=>1,
            'quantity'=>$request->post('quantity'),
            'bank_id'=>$request->post('bank_id'),
            'payment_type'=>$request->post('payment_type'),
        ];
        Purchase::create($purchase_data);
        SupplierPayments::create($tuple);
        $projects = [
            'account_id'=>$request->post('account_id'),
            'project_id'=>$request->post('project_id'),
            'amount'=>$amount,
            'bank_id'=>$request->post('bank_id'),
            'cheque_number'=>0,
            'description'=>"Purchase of Materials",
            'transaction_type'=>1,
            'method'=>$request->post('payment_type')
        ];
        $stock = [
            'quantity'=>$request->post('quantity'),
            'department_id'=>$request->post('project_id'),
            'amount'=>$amount,
            'flow'=>1,
            'status' => 'PENDING',
            'balance'=>$material_balance+$request->post('quantity'),
            'material_id'=>$request->post('material_id'),
        ];
        StockFlow::create($stock);
        ProjectPayment::create($payment);
        Incomes::create($projects);
        $transactions = [
            'account_name'=>$account_name,
            'type'=>$account_type,
            'amount'=>$amount,
            'bank_id'=>$request->post('bank_id'),
            'method'=>$request->post('payment_type'),
            'balance'=>$new_balance
        ];
        if($request->post('bank_id')){
            BankTransaction::create($transactions);
        }
        activity('FINANCES')
            ->log("Created a Purchase")->causer(request()->user());
        return redirect()->route('purchases.index')->with([
            'success-notification'=>"Purchase successfully Created"
        ]);
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
