<?php

namespace App\Http\Controllers;

use App\Http\Requests\Payments\StoreRequest;
use App\Models\Accounts;
use App\Models\Banks;
use App\Models\BankTransaction;
use App\Models\Church;
use App\Models\ChurchPayment;
use App\Models\Creditor;
use App\Models\CreditorStatement;
use App\Models\Department;
use App\Models\Labourer;
use App\Models\LabourerPayments;
use App\Models\Loan;
use App\Models\Member;
use App\Models\MemberPayment;
use App\Models\Ministry;
use App\Models\MinistryPayment;
use App\Models\Month;
use App\Models\Other;
use App\Models\OtherPayment;
use App\Models\Payment;
use App\Models\ProjectPayment;
use App\Models\Supplier;
use App\Models\SupplierPayments;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function allTransaction(Request $request)
    {
        // Initialize variables
        $openingBalance = 0;
        $currentMonthName = null; // Renamed to avoid confusion with Month model instance
        $selectedBank = null; // To hold the selected bank object for the view title if needed

        // Extract and sanitize input parameters
        $bankId = $request->input('bank_id'); // Use input() for GET/POST, more explicit
        $monthId = $request->input('month_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Define the transaction query, always filtering out inactive/deleted transactions
        $transactionsQuery = Payment::where('soft_delete', 0); // Always show active transactions

        // --- Determine the effective start and end dates based on filters ---
        if ($monthId) {
            $month = Month::find($monthId);
            if ($month) {
                // If a month is selected, its dates override explicit start/end dates
                $startDate = $month->start_date;
                $endDate = $month->end_date;
                $currentMonthName = $month->name;
            } else {
                // Handle invalid month selection gracefully
                return redirect()->back()->withErrors(['month_id' => 'Invalid month selected.']);
            }
        }

        // Apply bank filter if bank ID is provided
        if ($bankId) {
            $transactionsQuery->where('bank_id', $bankId);
            $selectedBank = Banks::find($bankId); // Fetch the bank details
        }

        // --- Calculate Opening Balance and Filter Transactions for the period ---
        // We only calculate opening balance if we have a valid start date to begin from
        if ($startDate) {
            // Calculate opening balance based on transactions *before* the start date
            $openingBalanceQuery = Payment::where('soft_delete', 0) // Only active payments
            ->where('t_date', '<', $startDate);

            if ($bankId) {
                $openingBalanceQuery->where('bank_id', $bankId);
            }

            $openingBalanceTransactions = $openingBalanceQuery->get();

            foreach ($openingBalanceTransactions as $transaction) {
                // Assuming type 1 is income (increases balance), type 2 is expense (decreases balance)
                if ($transaction->type == 1) { // Income
                    $openingBalance += $transaction->amount;
                } elseif ($transaction->type == 2) { // Expense
                    $openingBalance -= $transaction->amount;
                }
            }

            // Apply date range filter for the main transaction list
            if ($endDate) {
                $transactionsQuery->whereBetween('t_date', [$startDate, $endDate]);
            } else {
                // If only a start date is provided, get transactions from that date onwards
                $transactionsQuery->where('t_date', '>=', $startDate);
            }
        } elseif ($endDate) {
            // If only an end date is provided, get transactions up to that date
            $transactionsQuery->where('t_date', '<=', $endDate);
        }

        // Fetch current period's transactions, eager load relationships, and order them
        $transactions = $transactionsQuery->with(['account', 'bank']) // Eager load related models
        ->orderBy('t_date', 'ASC')
            ->get();

        // --- Pagination ---
        // The original pagination logic might be simplified if you plan to use Laravel's built-in paginate()
        // If you need all results first and then paginate, your current logic is fine,
        // but Laravel's `->paginate($perPage)` is generally more efficient for large datasets.
        $perPage = 10000;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentResults = $transactions->slice(($currentPage - 1) * $perPage, $perPage)->all();

        $paginatedTransactions = new LengthAwarePaginator($currentResults, $transactions->count(), $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);


        // --- Prepare data for the view ---
        $banks = Banks::where('soft_delete', 0)->orderBy('id', 'desc')->get(); // Use Bank model
        $months = Month::orderBy('id', 'desc')->get(); // Assuming Month model doesn't use soft_delete

        // Log the activity
        activity('FINANCES')
            ->log("Accessed Bank Transactions for Bank ID: " . ($bankId ?? 'All') .
                " Month ID: " . ($monthId ?? 'N/A') .
                " Start Date: " . ($startDate ?? 'N/A') .
                " End Date: " . ($endDate ?? 'N/A'))
            ->causer($request->user());

        // Return the view with necessary data
        return view('receipts.all', [
            'cpage' => "finances",
            'payments' => $paginatedTransactions, // Pass the paginated collection
            'status' => $status ?? 0, // Default to 0 if not set, though it seems unused
            'startDate' => $startDate, // Pass start date for view to retain filter
            'endDate' => $endDate, // Pass end date for view to retain filter
            'openingBalance' => $openingBalance,
            'currentMonth' => $currentMonthName,
            'banks' => $banks,
            'selectedBank' => $selectedBank, // Pass the selected bank object for view title
            'months' => $months,
            'selectedMonthId' => $monthId, // Pass selected month ID to pre-select dropdown
            'selectedBankId' => $bankId, // Pass selected bank ID to pre-select dropdown
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
    public function index(Request $request)
    {
        $query = Payment::query();

        // Initialize variables for the view to retain filter selections and for the caption
        $selectedBankId = $request->input('bank_id');
        $selectedMonthId = $request->input('month_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $selectedBank = null; // Will store the Bank model if selected
        $currentMonth = null; // Will store the month name if selected

        // 1. Apply Status Filter: Only show active payments (status = 0)
        // Ensure this is applied before other filters if it's a base requirement
        $query->where('soft_delete', 0); // Assuming 0 means active

        // 2. Apply Bank Filter (if provided)
        if ($request->filled('bank_id')) {
            $query->where('bank_id', $selectedBankId);
            $selectedBank = Banks::find($selectedBankId); // Fetch the bank model for the caption
        }

        // 3. Apply Month Filter (if provided)
        if ($request->filled('month_id')) {
            $month = Month::find($selectedMonthId);
            if ($month) {
                // Assuming 't_date' is the transaction date
                // And Month model has 'year' and 'month_number' or similar attributes
                $query->whereYear('t_date', $month->year)
                    ->whereMonth('t_date', $month->month_number);
                $currentMonth = $month->name; // Use the month's name for the caption
            }
        }

        // 4. Apply Start Date Filter (if provided)
        if ($request->filled('start_date')) {
            $query->whereDate('t_date', '>=', $startDate);
        }

        // 5. Apply End Date Filter (if provided)
        if ($request->filled('end_date')) {
            $query->whereDate('t_date', '<=', $endDate);
        }

        // Eager load relationships for the table (e.g., account, bank)
        // Keep this `with` here. The `where(['status'=>0])` below is redundant as it's already applied above.
        $query->with(['account', 'bank']);

        // Order by date, latest first
        $query->orderBy('t_date', 'desc');

        // ******************************************************
        // THE FIX: Use paginate() instead of get()
        // ******************************************************
        $payments = $query->paginate(10000); // You can adjust the number of items per page

        // Fetch all banks and months for the filter dropdowns
        $banks = Banks::all(); // Assuming you want all banks in the dropdown
        $months = Month::orderBy('id','desc')->get(); // Fetch months for the dropdown

        $cpage = 'finances'; // Controller page variable

        return view('payments.index', compact(
            'cpage',
            'payments',
            'banks',
            'months',
            'selectedBankId',
            'selectedMonthId',
            'startDate',
            'endDate',
            'selectedBank', // Pass the selected bank model
            'currentMonth'  // Pass the current month name
        ));
    }


    public function generateReceipt(Request $request)
    {
        $request->validate([
            'month_id' => "required|numeric",
        ]);

        $month = Month::where('id', $request->post('month_id'))->first();

        // Check if month exists to prevent errors
        if (!$month) {
            return redirect()->back()->with('error', 'Selected month not found.');
        }

        activity('FINANCES')->log("Accessed Payments Report for Month: {$month->name}")->causer(request()->user());

        $query = Payment::join('accounts', 'accounts.id', '=', 'payments.account_id')
            ->select('payments.*') // Select payments.* to get all payment columns
            ->where('payments.t_date', '>=', $month->start_date)
            ->where('payments.t_date', '<=', $month->end_date)
            ->where('accounts.type', 2) // Assuming 'type' 2 is for receipts
            ->where('payments.soft_delete', 0); // Assuming 0 means active/verified payments

        // Eager load relationships needed in the view
        $query->with(['account', 'bank']); // Add 'bank' if you need bank details

        $query->orderBy('payments.id', 'desc');

        // --- THE CRITICAL CHANGE ---
        $payments = $query->paginate(10000); // Use paginate() instead of get()
        // You can adjust the number of items per page (e.g., 10)

        // Pass filter values for the view to retain context and show/hide clear filters button
        $selectedBankId = null; // No bank filter in this specific report method
        $selectedMonthId = $month->id;
        $startDate = $month->start_date;
        $endDate = $month->end_date;
        $selectedBank = null; // No specific bank selected for this report
        $currentMonth = $month->name; // Pass the month name for the caption
        $cpage = 'finances'; // Controller page variable
        $months = Month::orderBy('id','desc')->get(); // Controller page variable

        return view('payments.index', compact(
            'cpage',
            'payments',
            'months',
            'selectedBankId',
            'selectedMonthId',
            'startDate',
            'endDate',
            'selectedBank',
            'currentMonth'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $creditors = Creditor::orderBy('id','desc')->get();
        $banks = Banks::where(['soft_delete'=>0])->orderBy('id','desc')->get();
        $labourer = Labourer::where(['soft_delete'=>0])->orderBy('id','desc')->get();
        $projects = Department::where(['soft_delete'=>0])->orderBy('id','desc')->get();
        $accounts = Accounts::where(['soft_delete'=>0])->orderBy('id','desc')->get();
        return view('payments.create')->with([
            'cpage'=>"finances",
            'creditors'=>$creditors,
            'banks'=>$banks,
            'others'=>Other::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
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
                $request->validate(['creditor_id' => "required"]);
                $transactions_name = Creditor::where(['id'=>$request->post('creditor_id')])->first()->name;
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
            case '8':{
                $request->validate(['other_id' => "required"]);
                $transactions_name = Other::where(['id'=>$request->post('other_id')])->first()->name;
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
            $new_balance = $balance-$request->post('amount');
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
        $bal = CreditorStatement::where(['creditor_id'=>$request->post('creditor_id')])->orderBy('id','desc')->first();
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
            'type'=>2,
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
        if ($request->type == 4) { // Employees

            $description = $request->post('description');
            $labourerId = $request->post('labourer_id');
            $amount = $request->post('amount');
            $accountId = $request->post('account_id');
            $createdBy = $request->post('created_by');
            $updatedBy = $request->post('updated_by');
            $paymentMethod = $request->post('payment_method');
            $transactionDate = $request->post('t_date'); // Assuming 't_date' is the date field
            $loanDurationMonths = $request->post('loan_duration_months');

            $labourerPaymentData = [
                'expense_name' => $transactions_name . ' For ' . $account->name,
                'labourer_id' => $labourerId,
                'amount' => $amount,
                'account_id' => $accountId,
                'description' => $description,
                'created_by' => $createdBy,
                'updated_by' => $updatedBy,
                'project_id' => $labour_project_id,
                'method' => $paymentMethod,
                'type' => 2,
                'date' => $transactionDate, // Add the date to the payment
            ];
            LabourerPayments::create($labourerPaymentData);
            if ($description == 2) { // Loan Creation
                $latestLoan = Loan::where(['labourer_id' => $labourerId])->orderBy('id', 'desc')->first();
                $previousBalance = $latestLoan ? $latestLoan->remaining_balance : 0;
                $loanData = [
                    'labourer_id' => $labourerId,
                    'loan_amount' => $amount,
                    'account_id' => $accountId,
                    'loan_start_date' => $transactionDate,
                    'loan_duration_months' => $loanDurationMonths,
                    'monthly_repayment' => $amount/$loanDurationMonths,
                    'created_by' => $createdBy,
                    'updated_by' => $updatedBy,
                    'remaining_balance' => $previousBalance + $amount,
                ];
                Loan::create($loanData);
            }
        }
        if($request->type==3){
            $statement = new CreditorStatement();
            $statement->creditor_id = $request->post('creditor_id');
            $statement->account_id = $request->input('account_id');
            $statement->creditor_invoice_id= '111111111';
            $statement->amount = $request->post('amount');
            $statement->type = 'Payment';
            $statement->balance = $supplier_balance;
            $statement->description = 'Creditor Payment';
            $statement->created_by = $request->input('created_by');
            $statement->updated_by = $request->input('updated_by');
            $statement->save();
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
        if($request->type==8){
            $bala = OtherPayment::where(['other_id'=>$request->post('other_id')])->orderBy('id','desc')->first();
            @$balances = $bala->balance;
            if(!$balances){
                $balances = 0;
            }
            $ministries = [
                'name'=>$transactions_name.' For '.$account->name,
                'other_id'=>$request->post('other_id'),
                'amount'=>$request->post('amount'),
                'created_by'=>$request->post('created_by'),
                'updated_by'=>$request->post('updated_by'),
                'account_id'=>$request->post('account_id'),
                'balance'=>$balances+$request->post('amount'),
                'payment_id'=>$payment,
                'transaction_type'=>2,
            ];
            OtherPayment::create($ministries);
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
            'banks'=>Banks::all(),
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
    public function destroy(Request $request, Payment $payment,ReceiptController $receiptController)
    {
        // 1. Validate the request (optional but recommended for deletion notes)
        $request->validate([
            'delete_notes' => 'nullable|string|max:1000',
        ]);

        try {
            // Get the deletion notes from the request
            $deletionNotes = $request->input('delete_notes');
            $user = Auth::user(); // Get the authenticated user

            // 2. Append deletion notes to the 'specification' field
            $originalSpecification = $payment->specification;
            $newSpecification = $originalSpecification;
            $timestamp = Carbon::now()->format('Y-m-d H:i:s');
            $userName = $user->name ?? 'N/A User';

            if (!empty($deletionNotes)) {
                $newSpecification .= "\n[MARKED DELETED by {$userName} on {$timestamp}]: {$deletionNotes}";
            } else {
                $newSpecification .= "\n[MARKED DELETED by {$userName} on {$timestamp}]";
            }

            // 3. Update the 'specification', 'status', 'updated_by' fields
            $payment->specification = $newSpecification;
            $payment->soft_delete = 1; // Assuming '1' means deleted/inactive for payments
            $payment->updated_by = $user->id; // Set the user who performed this action

            // 4. Save the changes to the database
            // Eloquent will automatically update `updated_at` here because timestamps are enabled.
            $payment->save();
            $message = 'GoodDay Sir, there was a reversal done by ' . request()->user()->name . ' for ' . @$payment->name . ',
            '.$payment->account->name.' : MK' . number_format($payment->amount,2).'Reason Being '.$payment->specification;
            $receiptController->sendSms('0888307368',$message); // sending sms
            $receiptController->sendSms('0999809706',$message); // sending sms
            $receiptController->sendSms('0999230536',$message); // sending sms
            $receiptController->sendSms('0881059655',$message); // sending sms

            // 5. Log the action (optional but good for auditing)
            Log::info("Payment ID: {$payment->id} status changed to 1 (marked deleted) by user {$user->id}. Original spec: '{$originalSpecification}'. New spec: '{$payment->specification}'");

            // 6. Redirect back with a success message
            return back()->with('success-notification', 'Transaction successfully marked as deleted and notes appended.');

        } catch (\Exception $e) {
            // 7. Handle any errors
            Log::error("Error marking payment ID: {$payment->id} as deleted. Error: " . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all(),
                'payment_id' => $payment->id
            ]);
            return back()->withInput()->with('error-notification', 'Failed to mark transaction as deleted: ' . $e->getMessage());
        }
    }
}
