<?php

namespace App\Http\Controllers;

use App\Http\Requests\Supplier\StoreRequest;
use App\Http\Requests\Supplier\UpdateRequest;
use App\Models\Accounts;
use App\Models\Incomes;
use App\Models\Payment;
use App\Models\Supplier;
use App\Models\SupplierPayments;
use Illuminate\Http\Request;

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
        $suppliers = Supplier::orderBy('id','desc')->get();
        return view('suppliers.index')->with([
            'cpage' => "suppliers",
            'suppliers'=>$suppliers
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
    public function store (StoreRequest $request)
    {
       $data = $request->post();

       Supplier::create($data);
        activity('SUPPLIERS')
            ->log("Created a Supplier")->causer(request()->user());
       return redirect()->back()->with([
           'success-notification'=>"Supplier successfully Created"
       ]);
    }

    public function update(UpdateRequest $request,Supplier $supplier)
    {
      $data = $request->post();

      $supplier->update($data);
        activity('SUPPLIERS')
            ->log("Updated a Supplier")->causer(request()->user());
        return redirect()->route('suppliers.show',$supplier->id)->with([
            'success-notification'=>"Supplier successfully Updated"
        ]);
    }

    public function destroy (Supplier $supplier)
    {
        try{
            $supplier->delete();
            activity('SUPPLIERS')
                ->log("Deleted a Supplier")->causer(request()->user());
            return redirect()->route('suppliers.index')->with([
                'success-notification'=>"Supplier successfully Deleted"
            ]);

        }catch (\Exception $exception){
            return redirect()->route('suppliers.index')->with([
                'error-notification'=>"Something went Wrong ".$exception.getMessage()
            ]);
        }
    }
}
