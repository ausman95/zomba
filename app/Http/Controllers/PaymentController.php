<?php

namespace App\Http\Controllers;

use App\Http\Requests\Payments\StoreRequest;
use App\Models\Accounts;
use App\Models\Banks;
use App\Models\BankTransaction;
use App\Models\Church;
use App\Models\ChurchPayment;
use App\Models\Department;
use App\Models\Labourer;
use App\Models\LabourerPayments;
use App\Models\Member;
use App\Models\MemberPayment;
use App\Models\Ministry;
use App\Models\MinistryPayment;
use App\Models\Month;
use App\Models\Payment;
use App\Models\ProjectPayment;
use App\Models\Supplier;
use App\Models\SupplierPayments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
class PaymentController extends Controller
{
    public function allTransaction(Request $request)
    {
        $status = 0;
        $openingBalance = 0;
        $currentMonth = null;
        $transactions = collect(); // Initialize an empty collection for transactions

        $bankId = $request->post('bank_id');
        $startDate = $request->post('start_date');
        $monthId = $request->post('month_id');
        $endDate = $request->post('end_date');

        // Case 1: When both bank_id, start_date, and end_date are provided
        if ($bankId && $startDate &&$endDate) {


            // Fetch all previous bank transactions up to the start of the selected period
            $previousTransactions = Payment::where('bank_id', $bankId)
                ->where('t_date', '<', $startDate)
                ->get();

            // Calculate the opening balance from prior transactions
            foreach ($previousTransactions as $transaction) {
                if ($transaction->type == 1) {
                    $openingBalance += $transaction->amount; // Revenue
                } elseif ($transaction->type == 2) {
                    $openingBalance -= $transaction->amount; // Expense
                }
            }

            // Fetch current period's bank transactions
            $transactions = Payment::where('bank_id', $bankId)
                ->whereBetween('t_date', [$startDate, $endDate])
                ->orderBy('t_date', 'ASC')
                ->get();
        }

        // Case 2: When both bank_id and month_id are provided

        elseif ($bankId && $monthId) {
            $bankId = $request->post('bank_id');
            $month = Month::find($request->post('month_id'));

            if ($month) {
                $startDate = $month->start_date;
                $endDate = $month->end_date;
                $currentMonth = $month->name;

                // Fetch all previous bank transactions up to the start of the selected period
                $previousTransactions = Payment::where('bank_id', $bankId)
                    ->where('t_date', '<', $startDate)
                    ->get();

                // Calculate the opening balance from prior transactions
                foreach ($previousTransactions as $transaction) {
                    if ($transaction->type == 1) {
                        $openingBalance += $transaction->amount; // Revenue
                    } elseif ($transaction->type == 2) {
                        $openingBalance -= $transaction->amount; // Expense
                    }
                }

                // Fetch current period's bank transactions
                $transactions = Payment::where('bank_id', $bankId)
                    ->whereBetween('t_date', [$startDate, $endDate])
                    ->orderBy('t_date', 'ASC')
                    ->get();
            } else {
                return redirect()->back()->withErrors(['month_id' => 'Invalid month selected.']);
            }
        }

        // Case 3: If only start_date and end_date are provided (no bank)
        elseif ($startDate &&$endDate) {
            $startDate = $request->post('start_date');
            $endDate = $request->post('end_date');

            // Fetch all previous transactions up to the selected period (for all banks)
            $previousTransactions = Payment::where('t_date', '<', $startDate)
                ->get();

            // Calculate the opening balance from prior transactions
            foreach ($previousTransactions as $transaction) {
                if ($transaction->type == 1) {
                    $openingBalance += $transaction->amount; // Revenue
                } elseif ($transaction->type == 2) {
                    $openingBalance -= $transaction->amount; // Expense
                }
            }

            // Fetch current period's transactions
            $transactions = Payment::whereBetween('t_date', [$startDate, $endDate])
                ->orderBy('t_date', 'ASC')
                ->get();
        }



        // Paginate the bank transactions
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 1000;
        $currentResults = $transactions->slice(($currentPage - 1) * $perPage, $perPage)->all();

        $transactions = new LengthAwarePaginator($currentResults, $transactions->count(), $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);

        // Log the activity
        activity('FINANCES')
            ->log("Accessed Bank Transactions")
            ->causer($request->user());

        // Return the view with necessary data
        return view('receipts.all')->with([
            'cpage' => "finances",
            'payments' => $transactions,
            'status' => $status,
            'start_date'=>$startDate,
            'openingBalance' => $openingBalance,
            'currentMonth' => $currentMonth,
            'banks' => Banks::where('soft_delete', 0)->orderBy('id', 'desc')->get(),
            'months' => Month::where('soft_delete', 0)->orderBy('id', 'desc')->get(),
        ]);
    }

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
        return view('payments.index')->with([
            'cpage' => "finances",
            'payments'=>Payment::join('accounts', 'accounts.id','=','payments.account_id')
                ->select(
                    'payments.*',
                )
                ->whereBetween('t_date',[$month->start_date,$month->end_date])
                ->where(['accounts.type'=>2])
                ->orderBy('payments.id','desc')->get(),
            'months'=>Month::where(['soft_delete'=>0])->orderBY('id','desc')->get()
        ]);
    }

    public function generateReceipt(Request $request)
    {
        $request->validate([
            'month_id' => "required|numeric",
        ]);

        $month = Month::where('id', $request->post('month_id'))->first();

        activity('FINANCES')->log("Accessed Payments")->causer(request()->user());

        return view('payments.index')->with([
            'cpage' => "finances",
            'payments' => Payment::join('accounts', 'accounts.id','=','payments.account_id')
                ->select('payments.*')
                ->where('payments.t_date', '>=', $month->start_date)
                ->where('payments.t_date', '<=', $month->end_date)
                ->where('accounts.type', 2)
                ->orderBy('payments.id', 'desc')
                ->get(),
            'months' => Month::orderBy('id', 'desc')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $suppliers = Supplier::where(['soft_delete'=>0])->orderBy('id','desc')->get();
        $banks = Banks::where(['soft_delete'=>0])->orderBy('id','desc')->get();
        $labourer = Labourer::where(['soft_delete'=>0])->orderBy('id','desc')->get();
        $projects = Department::where(['soft_delete'=>0])->orderBy('id','desc')->get();
        $accounts = Accounts::where(['soft_delete'=>0])->orderBy('id','desc')->get();
        return view('payments.create')->with([
            'cpage'=>"finances",
            'suppliers'=>$suppliers,
            'banks'=>$banks,
            'members'=>Member::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
            'churches'=>Church::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
            'ministries'=>Ministry::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
            'projects'=>$projects,
            'labourers'=>$labourer,
            'accounts'=>$accounts
        ]);
    }

    public function store (StoreRequest $request)
    {
        $request->validate([
            'type' => "required",
        ]);
        if($request->post('t_date')>date('Y-m-d')){
            return back()->with(['error-notification'=>"Invalid Date Entered, You have Entered a Future Date"]);
        }
        $monthID  = Month::getActiveMonth();
        $data = $request->all();
        $reference = 'N/A';
        if($data['reference']){
            $reference = $data['reference'];
        }
        $transactions_name = 'Admin';
        switch ($data['type']){
            case '1':{
                $request->validate(['project_id' => "required"]);
                $transactions_name = Department::where(['id'=>$request->post('project_id')])->first()->name;
                break;
            }
            case '3':{
                $request->validate(['supplier_id' => "required"]);
                $transactions_name = Supplier::where(['id'=>$request->post('supplier_id')])->first()->name;
                break;
            }
            case '4':{
                $request->validate(['labourer_id' => "required"]);
                $labour_project_id = Labourer::where(['id'=>$request->post('labourer_id')])
                    ->first();
                $labour_project_id = $labour_project_id->department_id;
                $transactions_name = Labourer::where(['id'=>$request->post('labourer_id')])->first()->name;
                break;
            }
            case '5':{
                $request->validate(['member_id' => "required"]);
                $transactions_name = Member::where(['id'=>$request->post('member_id')])->first()->name;
                break;
            }
            case '6':{
                $request->validate(['church_id' => "required"]);
                $transactions_name = Church::where(['id'=>$request->post('church_id')])->first()->name;
                break;
            }
            case '7':{
                $request->validate(['ministry_id' => "required"]);
                $transactions_name = Ministry::where(['id'=>$request->post('ministry_id')])->first()->name;
                break;
            }
        }
        $account = Accounts::where(['id'=>$request->post('account_id')])->first();
        $account_type = $account->type;
        // dd($project_id);
        $bal = BankTransaction::where(['bank_id'=>$request->post('bank_id')])->orderBy('id','desc')->first();
        @$balance = $bal->balance;
        if(!$balance){
            $balance = 0;
        }
        if($account_type=='2'){
            if($request->post('amount')>$balance){
                return back()->with(['error-notification'=>"The bank balance is less than the amount requested"]);
            }else{
                $new_balance = $balance-$request->post('amount');
            }
        }else{
            $new_balance = $balance+$request->post('amount');
        }
        $transactions = [
            'description'=>$transactions_name.' For '.$account->name,
            'type'=>$account_type,
            'specification'=>$request->post('specification'),
            'account_id'=>$request->post('account_id'),
            'amount'=>$request->post('amount'),
            't_date'=>$request->post('t_date'),
            'bank_id'=>$request->post('bank_id'),
            'method'=>$request->post('payment_method'),
            'balance'=>$new_balance
        ];
        // Supplier Balance
        $bal = SupplierPayments::where(['supplier_id'=>$request->post('supplier_id')])->orderBy('id','desc')->first();
        @$balance = $bal->balance;
        if(!$balance){
            $balance = 0;
        }
        $supplier_balance = $balance-$request->post('amount');
        $data = $request->post();
        $raw_data = [
            'account_id'=>$data['account_id'],
            'amount'=>$data['amount'],
            'name'=>$transactions_name,
            't_date'=>$data['t_date'],
            'month_id'=>$monthID->id,
            'created_by'=>$request->post('created_by'),
            'updated_by'=>$request->post('updated_by'),
            'specification'=>$request->post('specification'),
            'bank_id'=>$data['bank_id'],
            'type'=>$data['type'],
            'payment_method'=>$data['payment_method'],
            'reference'=>$reference,
        ];
        if(Payment::where($raw_data)->first()){
            return redirect(
                route('payments.create') . "?id=pending")->with(
                ['error-notification'=>"You have already created this transaction Today. Check the Transaction Properly"]
            );
        }
        $payments = Payment::create($raw_data);
        $payment = $payments->id;
        $bala = ProjectPayment::where(['project_id'=>$request->post('project_id')])->orderBy('id','desc')->first();
        @$balances = $bala->balance;
        if(!$balances){
            $balances = 0;
        }
        if($account_type=='2'){
            $new_balances = $balances+$request->post('amount');
        }else{
            $new_balances = $balances-$request->post('amount');
        }
        if($request->type==4){
            $bala = LabourerPayments::where(['labourer_id'=>$request->post('labourer_id')])->orderBy('id','desc')->first();
            @$balances = $bala->balance;
            if(!$balances){
                $balances = 0;
            }
            $labourers = [
                'expense_name'=>$transactions_name.' For '.$account->name,
                'labourer_id'=>$request->post('labourer_id'),
                'amount'=>$request->post('amount'),
                'account_id'=>$request->post('account_id'),
                'description'=>$request->post('description'),
                'created_by'=>$request->post('created_by'),
                'updated_by'=>$request->post('updated_by'),
                'project_id'=>$labour_project_id,
                'balance'=>$balances-$request->post('amount'),
                'method'=>$request->post('payment_method'),
                'type'=>2,
            ];
            $project_payment = [
                'project_id'=>$labour_project_id,
                'amount'=>$request->post('amount'),
                'balance'=>$new_balances,
                'payment_name'=>$transactions_name.' For '.$account->name,
                'payment_type'=>'2'
            ];
            ProjectPayment::create($project_payment);
            LabourerPayments::create($labourers);
        }
        if($request->type==3){
            $suppliers = [
                'expenses_id'=>'1111111',
                'supplier_id'=>$request->post('supplier_id'),
                'amount'=>$request->post('amount'),
                'created_by'=>$request->post('created_by'),
                'updated_by'=>$request->post('updated_by'),
                'method'=>$request->post('payment_method'),
                'balance'=>$supplier_balance,
                'transaction_type'=>1,
            ];
            SupplierPayments::create($suppliers);
        }
        if($request->type==1){
            $project_payment = [
                'project_id'=>$request->post('project_id'),
                'amount'=>$request->post('amount'),
                'balance'=>$new_balances,
                'created_by'=>$request->post('created_by'),
                'updated_by'=>$request->post('updated_by'),
                'payment_name'=>$transactions_name.' For '.$account->name,
                'payment_type'=>'1'
            ];
            ProjectPayment::create($project_payment);
        }
        if($request->type==5){
            $bala = MemberPayment::where(['member_id'=>$request->post('member_id')])->orderBy('id','desc')->first();
            @$balances = $bala->balance;
            if(!$balances){
                $balances = 0;
            }
            $members = [
                'name'=>$transactions_name.' For '.$account->name,
                'member_id'=>$request->post('member_id'),
                'amount'=>$request->post('amount'),
                'created_by'=>$request->post('created_by'),
                'updated_by'=>$request->post('updated_by'),
                'account_id'=>$request->post('account_id'),
                'balance'=>$balances+$request->post('amount'),
                'payment_id'=>$payment,
                'transaction_type'=>2,
            ];
            MemberPayment::create($members);
        }
        if($request->type==6){
            $bala = ChurchPayment::where(['church_id'=>$request->post('church_id')])->orderBy('id','desc')->first();
            @$balances = $bala->balance;
            if(!$balances){
                $balances = 0;
            }
            $churches= [
                'name'=>$transactions_name.' For '.$account->name,
                'church_id'=>$request->post('church_id'),
                'amount'=>$request->post('amount'),
                'created_by'=>$request->post('created_by'),
                'updated_by'=>$request->post('updated_by'),
                'account_id'=>$request->post('account_id'),
                'balance'=>$balances+$request->post('amount'),
                'payment_id'=>$payment,
                'transaction_type'=>2,
            ];
            ChurchPayment::create($churches);
        }
        if($request->type==7){
            $bala = MinistryPayment::where(['ministry_id'=>$request->post('ministry_id')])->orderBy('id','desc')->first();
            @$balances = $bala->balance;
            if(!$balances){
                $balances = 0;
            }
            $ministries = [
                'name'=>$transactions_name.' For '.$account->name,
                'ministry_id'=>$request->post('ministry_id'),
                'amount'=>$request->post('amount'),
                'created_by'=>$request->post('created_by'),
                'updated_by'=>$request->post('updated_by'),
                'account_id'=>$request->post('account_id'),
                'balance'=>$balances+$request->post('amount'),
                'payment_id'=>$payment,
                'transaction_type'=>2,
            ];
            MinistryPayment::create($ministries);
        }
        BankTransaction::create($transactions);
        //code to generate an invoice
        activity('FINANCES')
            ->log("Created a Payment")->causer(request()->user());
        return redirect(
            route('payments.index') . "?id=success")->with(
            ['success-notification'=>"Successfully Created"]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
        return view('receipts.show')->with([
            'cpage'=>"finances",
            'transaction'=>$payment,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Payment $payment)
    {
        return view('receipts.edit')->with([
            'cpage'=>"finances",
            'transaction'=>$payment,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Payment $payment)
    {
        $data = $request->post();
        $payment->update($data);
        return redirect()->route('payments.show',$payment->id.'?verified='.$data['verified'])->with([
            'success-notification'=>"Successfully Updated"
        ]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,ReceiptController $receiptController)
    {
        $data=array('status'=>1,'updated_by'=> request()->user()->id) ;
        DB::table('payments')
            ->where(['id' => $request->post('id')])
            ->update($data);
        if($request->post('type')==6) {
            DB::table('church_payments')
                ->where(['payment_id' => $request->post('id')])
                ->update($data);
        }
        if($request->post('type')==6) {
            DB::table('ministry_payments')
                ->where(['payment_id' => $request->post('id')])
                ->update($data);
        }
        if($request->post('type')==5){
            DB::table('member_payments')
                ->where(['payment_id' => $request->post('id')])
                ->update($data);

            $member = MemberPayment::where(['member_payments.payment_id'=> $request->post('id')])
                ->join('members', 'members.id', '=', 'member_payments.member_id')
                ->first();
            if($member->phone_number!=0) {
                $message = 'Dear ' . $member->name . ' You have Paid ' . $request->post('account') .
                    ' Amounting to : MK ' . number_format($request->post('amount'), 2) .
                    PHP_EOL . ' AREA 25 VICTORY TEMPLE';
                $receiptController->sendSms($member->phone_number, $message);
            }
        }
        return redirect()->route('receipt.unverified')->with([
            'success-notification'=>"Successfully"
        ]);
    }
}
