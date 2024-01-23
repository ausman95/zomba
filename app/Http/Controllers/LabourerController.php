<?php

namespace App\Http\Controllers;

use App\Models\Accounts;
use App\Models\Allocation;
use App\Models\Banks;
use App\Models\BankTransaction;
use App\Models\Department;
use App\Models\DepartmentRequisition;
use App\Models\Incomes;
use App\Models\Labour;
use App\Models\Labourer;
use App\Models\LabourerPayments;
use App\Models\Payment;
use App\Models\Project;
use App\Models\ProjectPayment;
use App\Models\SupplierPayments;
use Illuminate\Http\Request;
use App\Http\Requests\Labourers\StoreRequest;
use App\Http\Requests\Labourers\UpdateRequest;
use Illuminate\Support\Facades\DB;

class LabourerController extends Controller
{
    public function showLeaveByEmployee(Labourer $labourer)
    {
        return view('leaves.show')->with([
            'cpage'=>"human-resources",
            'labourer'=>$labourer,
            'leaves'=>$labourer->leaves
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function subcontractorsStore (Request $request)
    {
        $request->validate([
            'account_id'=>"required|numeric",
            'name'=>"required|string",
            'amount'=>"required|numeric",
            'description'=>"required|numeric",
            'payment_method'=>"required|numeric",
            'cheque_number'=>"required|string",
        ]);
        $data = $request->all();
        if($data['description']!='4') {
            $request->validate([
                'bank_id' => "required",
            ]);
        }

        $request->validate([
            'labourer_id' => "required",
            'account_id'=>"required",
        ]);

        $check_data = [
            'labourer_id'=>$data['labourer_id'],
            'project_id'=>$data['project_id']
        ];




        $account = Accounts::where(['id'=>$request->post('account_id')])->first();
        $account_name =$account->name;
        $account_type = $account->type;

        // dd($project_id);
        if($data['description']!='4') {
            $bal = BankTransaction::where(['bank_id' => $request->post('bank_id')])->orderBy('id','DESC')->first();
            @$balance = $bal->balance;
            if (!$balance) {
                $balance = 0;
            }
        }
        if($data['description']!='4') {
            if($account_type=='1'){
                if($request->post('amount')>$balance){
                    return back()->with(['error-notification'=>"The bank balance is less than the amount requested"]);
                }else{
                    $new_balance = $balance-$request->post('amount');
                }
            }else{
                $new_balance = $balance+$request->post('amount');
            }
            $transactions = [
                'account_name'=>$account_name,
                'type'=>$account_type,
                'amount'=>$request->post('amount'),
                'bank_id'=>$request->post('bank_id'),
                'method'=>$request->post('payment_method'),
                'balance'=>$new_balance
            ];
            BankTransaction::create($transactions);
        }

        $bala = ProjectPayment::where(['project_id'=>$request->post('project_id')])->orderBy('id','DESC')->first();
        @$balances = $bala->balance;
        if(!$balances){
            $balances = 0;
        }

        if($account_type=='2'){
            $new_balances = $balances+$request->post('amount');
        }else{
            $new_balances = $balances-$request->post('amount');
        }

        /// creating
        $bala = LabourerPayments::where(['labourer_id'=>$request->post('labourer_id')])->orderBy('id','DESC')->first();
        @$balances = $bala->balance;
        if($request->post('amount')>$balances){
            return back()->with(['error-notification'=>"The amount specified is greater than Subcontractor remaining balance"]);
        }
        if(!$balances){
            $balances = $request->post('amount');
        }else{
            $balances =  $balances-$request->post('amount') ;
        }
        $labourers = [
            'expense_name'=>$request->post('name'),
            'labourer_id'=>$request->post('labourer_id'),
            'amount'=>$request->post('amount'),
            'description'=>$request->post('description'),
            'project_id'=>$request->post('project_id'),
            'balance'=>$balances,
            'method'=>$request->post('payment_method'),
            'type'=>2,
        ];
        $project_payment = [
            'project_id'=>$request->post('project_id'),
            'amount'=>$request->post('amount'),
            'balance'=>$new_balances,
            'payment_name'=>$request->post('name'),
            'payment_type'=>'2'
        ];
        if(!$request->post('bank_id')){
            $bank_value = 0;
        }else{
            $bank_value = $request->post('bank_id');
        }
        $projects = [
            'account_id'=>$request->post('account_id'),
            'project_id'=>$request->post('project_id'),
            'amount'=>$request->post('amount'),
            'bank_id'=>$bank_value,
            'cheque_number'=>$request->post('cheque_number'),
            'description'=>"Payment",
            'method'=>$request->post('payment_method'),
            'transaction_type'=>4,
        ];
        ProjectPayment::create($project_payment);
        Incomes::create($projects);
        LabourerPayments::create($labourers);
        ///

        activity('FINANCES')
            ->log("Created a Payment")->causer(request()->user());
        return redirect()->back()->with([
            'success-notification'=>"Payment successfully Created"
        ]);
    }
    public function transactions()
    {
        activity('HUMAN RESOURCES')
            ->log("Accessed Labourers")->causer(request()->user());

        $labourer= Labourer::where(['type'=>2])->orderBy('id','desc')->get();;
        return view('labourers.transactions-create')->with([
            'cpage' => "human-resources",
            'labours'=>Labour::all(),
            'accounts'=>Accounts::all(),
            'banks'=>Banks::all(),
            'projects'=>Department::all(),
            'departments'=>Department::all(),
            'labourers'=>$labourer
        ]);
    }
    public function index()
    {
        activity('HUMAN RESOURCES')
            ->log("Accessed Labourers")->causer(request()->user());
        $labourer= Labourer::where(['type'=>3])->orderBy('id','desc')->get();;
        return view('labourers.index')->with([
            'cpage' => "human-resources",
            'labours'=>Labour::all(),
            'departments'=>Department::all(),
            'labourers'=>$labourer
        ]);
    }
    public function subcontractorsIndex()
    {
        activity('HUMAN RESOURCES')
            ->log("Accessed Labourers")->causer(request()->user());

        $labourer= Labourer::where(['type'=>2])->orderBy('id','desc')->get();;
        return view('labourers.subcontractors')->with([
            'cpage' => "human-resources",
            'labours'=>Labour::all(),
            'accounts'=>Accounts::all(),
            'banks'=>Banks::all(),
            'projects'=>Department::all(),
            'departments'=>Department::all(),
            'labourers'=>$labourer
        ]);
    }
    public function employeeIndex()
    {
        activity('HUMAN RESOURCES')
            ->log("Accessed Labourers")->causer(request()->user());
        $labourer= Labourer::where(['type'=>1])->where(['soft_delete'=>0])->orderBy('id','desc')->get();;
        return view('labourers.employees')->with([
            'cpage' => "human-resources",
            'labours'=>Labour::all(),
            'departments'=>Department::all(),
            'labourers'=>$labourer
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $labour = Labour::where(['soft_delete'=>0])->orderBy('id','desc')->get();
        $department = Department::where(['soft_delete'=>0])->orderBy('id','desc')->get();
        return view('labourers.create')->with([
            'cpage'=>"human-resources",
            'labours'=>$labour,
            'departments'=>$department
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return false
     */
    public function validating($phone)
    {
        $value = 0;
        if(preg_match('/^[0-9]{10}+$/', $phone)) {
            $value = 1;
        }
        return $value;
    }
    public function store(StoreRequest $request)
    {
        if(is_numeric($request->post('name'))){
            return back()->with(['error-notification'=>"Invalid Character Entered on Name"]);
        }
        $data = $request->post();
        if($this->validating($data['phone_number'])==0){
            // labourer is already part of this project
            return back()->with(['error-notification'=>"Invalid Phone number"]);
        }
        Labourer::create($data);
        activity('HUMAN RESOURCES')
            ->log("Created a Labourer")->causer(request()->user());
        return redirect()->route('labourers.employees')->with([
            'success-notification'=>"Successfully Created"
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Labourer $labourer)
    {
        $payments = $labourer->payments;

        return view('labourers.show')->with([
            'cpage'=>"human-resources",
            'labourer'=>$labourer,
            'transactions'=>$payments

        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit( Labourer $labourer)
    {
        $labour = Labour::all();
        $department = Department::all();
        return view('labourers.edit')->with([
            'cpage' => "human-resources",
            'labourer' => $labourer,
            'departments' => $department,
            'labours' => $labour
        ]);
    }
    public function allocateLabour( Labourer $labourer)
    {
        $project = Department::all();
        return view('labourers.allocate')->with([
            'cpage' => "human-resources",
            'labourer' => $labourer,
            'projects' => $project
        ]);
    }
    public function allocateLabourToProject(Allocation $allocation)
    {
        $data = $allocation->post();
        Allocation::create($data);
        activity('ALLOCATIONS')
            ->log("Created a  Labourer Allocation")->causer(request()->user());
        return redirect()->route('labourers.show',$allocation->post('labourer_id'))->with([
            'success-notification'=>"Successfully Allocated"
        ]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Labourer $labourer)
    {
        $data = $request->post();
        if(is_numeric($request->post('name'))){
            return back()->with(['error-notification'=>"Invalid Character Entered on Name"]);
        }
        $data = $request->post();
        if($this->validating($data['phone_number'])==0){
            // labourer is already part of this project
            return back()->with(['error-notification'=>"Invalid Phone number"]);
        }
        $labourer->update($data);
        activity('HUMAN RESOURCES')
            ->log("updated a Labourer")->causer(request()->user());
        return redirect()->route('labourers.employees',$labourer->id)->with([
            'success-notification'=>"Updated Successfully"
        ]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Labourer  $labourer)
    {

        $data = $request->post();
        DB::table('labourers')
            ->where(['id' => $request->post('id')])
            ->update(['soft_delete' => '1']);
        $labourer->update($data);

        return redirect()->route('labourers.employees')->with([
            'success-notification'=>"Successfully Deleted"
        ]);
    }
}
