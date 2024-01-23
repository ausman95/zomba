<?php

namespace App\Http\Controllers;

use App\Http\Requests\Supplier\StoreRequest;
use App\Http\Requests\Supplier\UpdateRequest;
use App\Models\Accounts;
use App\Models\Incomes;
use App\Models\Material;
use App\Models\Payment;
use App\Models\Supplier;
use App\Models\SupplierPayments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    public function supplierStore (Request $request)
    {
        $request->validate([
            'name'=>"required",
            'account_id' => "required",
            'payment_method'=>"required",
            'amount' => "required",
            'reference'=>"required",
            'supplier_id' => "required",
        ]);

        $data = [
            'account_id'=>$request->post('account_id'),
            'name'=>$request->post('name'),
            'amount'=>$request->post('amount'),
            'payment_method'=>$request->post('payment_method'),
            'reference'=>$request->post('reference'),
            'bank_id'=>0,
        ];
        // Supplier Balance
        $bal = SupplierPayments::where(['supplier_id'=>$request->post('supplier_id')])->orderBy('id','desc')->first();
        @$balance = $bal->balance;
        if(!$balance){
            $balance = 0;
        }
        $supplier_balance = $balance-$request->post('amount');
        $suppliers = [
            'expenses_id'=>'1111111',
            'supplier_id'=>$request->post('supplier_id'),
            'amount'=>$request->post('amount'),
            'method'=>$request->post('payment_method'),
            'balance'=>$supplier_balance,
            'transaction_type'=>2,
        ];
        $projects = [
            'account_id'=>$request->post('account_id'),
            'project_id'=>'0',
            'amount'=>$request->post('amount'),
            'bank_id'=>0,
            'cheque_number'=>$request->post('reference'),
            'description'=>$request->post('name'),
            'method'=>$request->post('payment_method'),
            'transaction_type'=>3,
        ];
        Incomes::create($projects);
        SupplierPayments::create($suppliers);
        Payment::create($data);

        activity('FINANCES')
            ->log("Created a Payment")->causer(request()->user());
        return redirect()->back()->with([
            'success-notification'=>"Payment successfully Created"
        ]);
    }
    public function supplierTransactions()
    {
        activity('Suppliers')
            ->log("Accessed Suppliers")->causer(request()->user());

        return view('suppliers.transactions-create')->with([
            'cpage' => "suppliers",
            'suppliers'=>Supplier::all(),
            'accounts'=>Accounts::all(),
        ]);
    }
    public function index()
    {
        activity('SUPPLIERS')
            ->log("Accessed Suppliers")->causer(request()->user());
        return view('suppliers.index')->with([
            'cpage' => "suppliers",
            'suppliers'=>Supplier::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
        ]);
    }

    public function create()
    {

        return view('suppliers.create')->with([
            'cpage'=>"suppliers"
        ]);
    }

    public function show(Supplier $supplier )
    {
        $payments = $supplier->payments;
        $allocations = $supplier->materials;
        return view('suppliers.show')->with([
            'cpage'=>"suppliers",
            'supplier'=>$supplier,
            'allocations'=>$allocations,
            'payments'=>$payments
        ]);
    }
    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit')->with([
            'cpage' => "suppliers",
            'supplier' => $supplier
        ]);
    }
    public function store (StoreRequest $request, LabourerController $labourerController)
    {
        if(is_numeric($request->post('name'))){
            return back()->with(['error-notification'=>"Invalid Character Entered on Name"]);
        }
        if(is_numeric($request->post('location'))){
            return back()->with(['error-notification'=>"Invalid Character Entered on Location"]);
        }
       $data = $request->post();
        if($labourerController->validating($request->post('phone_number'))==0){
            // labourer is already part of this project
            return back()->with(['error-notification'=>"Invalid Phone number"]);
        }
       Supplier::create($data);
        activity('SUPPLIERS')
            ->log("Created a Supplier")->causer(request()->user());
       return redirect()->route('suppliers.index')->with([
           'success-notification'=>"Supplier successfully Created"
       ]);
    }

    public function update(UpdateRequest $request,Supplier $supplier,LabourerController $labourerController)
    {
      $data = $request->post();
        if(is_numeric($request->post('name'))){
            return back()->with(['error-notification'=>"Invalid Character Entered on Name"]);
        }
        if(is_numeric($request->post('location'))){
            return back()->with(['error-notification'=>"Invalid Character Entered on Location"]);
        }
        $data = $request->post();
        if($labourerController->validating($request->post('phone_number'))==0){
            // labourer is already part of this project
            return back()->with(['error-notification'=>"Invalid Phone number"]);
        }
      $supplier->update($data);
        activity('SUPPLIERS')
            ->log("Updated a Supplier")->causer(request()->user());
        return redirect()->route('suppliers.show',$supplier->id)->with([
            'success-notification'=>"Supplier successfully Updated"
        ]);
    }

    public function destroy(Request $request, Supplier $supplier)
    {

        $data = $request->post();
        DB::table('suppliers')
            ->where(['id' => $request->post('id')])
            ->update(['soft_delete' => '1']);
        $supplier->update($data);

        return redirect()->route('suppliers.index')->with([
            'success-notification'=>"Successfully Deleted"
        ]);
    }
}
