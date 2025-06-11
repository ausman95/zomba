<?php

namespace App\Http\Controllers;

Require '../vendor/autoload.php';
use AfricasTalking\SDK\AfricasTalking;

use App\Http\Requests\Payments\StoreRequest;
use App\Models\Account;
use App\Models\Accounts;
use App\Models\Bank;
use App\Models\Banks;
use App\Models\BankTransaction;
use App\Models\Church;
use App\Models\ChurchPayment;
use App\Models\Debtor;
use App\Models\DebtorStatement;
use App\Models\Department;
use App\Models\Labourer;
use App\Models\Member;
use App\Models\MemberPayment;
use App\Models\Ministry;
use App\Models\MinistryPayment;
use App\Models\Month;
use App\Models\Payment;
use App\Models\Pledge;
use App\Models\ProjectPayment;
use App\Models\Receipt;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReceiptController extends Controller
{
    var $connObj,
        $userName = 'easymaog', // username here e.g easymaog //
        $senderId = 'EASYMAOG', // sender ID here //
        $apiKey   = 'atsk_cef9faaa47b5e5451867634e42b4b27d105d95e3830906f3b618b6d0e0ca233dee1efdef'; //api key here //

    function __construct()
    {
        $service       = new AfricasTalking($this->userName,$this->apiKey);
        $this->connObj = $service->sms();
    }
    public function generateReceipt(Request $request)
    {
        // Validate the request data
        $request->validate([
            'month_id'   => 'nullable|numeric',
            'bank_id'    => 'nullable|numeric',
            'start_date' => 'nullable|date',
            // Custom validation rule: If start_date is provided, end_date must also be provided
            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->filled('start_date') && !$request->filled('end_date')) {
                        $fail('The end date is required when a start date is provided.');
                    }
                    if ($request->filled('end_date') && !$request->filled('start_date')) {
                        $fail('The start date is required when an end date is provided.');
                    }
                },
            ],
        ]);

        // --- Retrieve filter parameters from the request ---
        $selectedBankId = $request->input('bank_id');
        $selectedMonthId = $request->input('month_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // --- Fetch dropdown data for filters ---
        $banks = Banks::where('soft_delete', 0)->orderBy('id', 'desc')->get();
        $months = Month::where('soft_delete', 0)->orderBy('id', 'desc')->get(); // Fetch all months for the filter dropdown

        // --- Prepare variables for dynamic title and selected filter display ---
        $selectedBank = null;
        if ($selectedBankId) {
            $selectedBank = Banks::find($selectedBankId);
        }

        $currentMonthName = null;
        $effectiveStartDate = null;
        $effectiveEndDate = null;

        // Determine effective dates and month name based on filters
        if ($selectedMonthId) {
            $requestedMonth = Month::find($selectedMonthId);
            if ($requestedMonth) {
                $currentMonthName = $requestedMonth->name;
                $effectiveStartDate = $requestedMonth->start_date;
                $effectiveEndDate = $requestedMonth->end_date;
            }
        }

        // If explicit start/end dates are provided, they override month dates
        if ($startDate) {
            $effectiveStartDate = $startDate;
        }
        if ($endDate) {
            $effectiveEndDate = $endDate;
        }

        // Fallback to active month if no filters are applied and no dates derived
        if (!$effectiveStartDate && !$effectiveEndDate) {
            $activeMonth = Month::getActiveMonth();
            if ($activeMonth) {
                $effectiveStartDate = $activeMonth->start_date;
                $effectiveEndDate = $activeMonth->end_date;
                $currentMonthName = $activeMonth->name;
                $selectedMonthId = $activeMonth->id; // Ensure dropdown defaults if no filter
            } else {
                // Handle case where no active month exists (redirect or default to today's date range)
                // For now, let's assume getActiveMonth() always returns something or the check earlier handles it
                $effectiveStartDate = now()->startOfMonth()->toDateString();
                $effectiveEndDate = now()->endOfMonth()->toDateString();
                $currentMonthName = now()->format('F Y');
            }
        }


        // --- Build the payments query ---
        $query = Payment::join('accounts', 'accounts.id', '=', 'payments.account_id')
            ->select('payments.*') // Select all columns from payments table
            ->where('accounts.type', 1) // Filter for account type 1 (e.g., Income accounts)
            ->where('payments.soft_delete', 0); // Filter for status 0 (e.g., Verified receipts)

        // Apply bank filter
        // IMPORTANT: Your original code had 'accounts.id' here for bank_id, but it should be 'payments.bank_id'
        if ($selectedBankId) {
            $query->where('payments.bank_id', $selectedBankId);
        }

        // Apply date range filter using effective dates
        if ($effectiveStartDate && $effectiveEndDate) {
            $query->whereBetween('payments.t_date', [$effectiveStartDate, $effectiveEndDate]);
        } elseif ($effectiveStartDate) {
            $query->where('payments.t_date', '>=', $effectiveStartDate);
        } elseif ($effectiveEndDate) {
            $query->where('payments.t_date', '<=', $effectiveEndDate);
        }

        $query->orderBy('payments.id', 'desc');

        // --- Paginate the payments ---
        $perPage = 1000; // Consistent with your `index` method
        $payments = $query->with(['account', 'bank']) // Eager load relationships
        ->paginate($perPage);

        // --- Set opening balance (for consistency) ---
        $openingBalance = 0; // Default or calculate if needed for this view's specific purpose

        activity('FINANCES')
            ->log("Generated Receipt Report" .
                " Bank ID: " . ($selectedBankId ?? 'All') .
                " Month ID: " . ($selectedMonthId ?? 'N/A') .
                " Start Date: " . ($effectiveStartDate ?? 'N/A') .
                " End Date: " . ($effectiveEndDate ?? 'N/A'))
            ->causer(request()->user());

        return view('receipts.index')->with([
            'cpage'           => 'finances',
            'payments'        => $payments,
            'months'          => $months,
            'banks'           => $banks, // Pass filtered banks data

            // Variables for filter retention and dynamic titles
            'selectedBankId'  => (int)$selectedBankId,
            'selectedMonthId' => (int)$selectedMonthId,
            'startDate'       => $effectiveStartDate,
            'endDate'         => $effectiveEndDate,
            'selectedBank'    => $selectedBank,
            'currentMonth'    => $currentMonthName,
            'openingBalance'  => $openingBalance,

            // Other variables your view might need (ensure they are defined or removed if not used)
            'churches'        => \App\Models\Church::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
            'ministries'      => \App\Models\Ministry::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
            'account_id'      => '1', // Hardcoded in your original code
            'description'     => 0, // Hardcoded in your original code
            'type'            => '0', // Hardcoded in your original code
            'account_name'    => "SCHOOL FEES", // Hardcoded in your original code
            'term_name'       => $currentMonthName, // Using the determined month name
            'accounts'        => \App\Models\Accounts::where(['soft_delete'=>0])->orderBy('id','ASC')->get(),
            'soft_delete'          => 0, // Consistent with payments.status filter
        ]);
    }
    public function bankReconciliations(Request $request)
    {
        // Initialize variables for the view
        $transactions = collect(); // Default to an empty collection
        $openingBalance = 0;
        $banks = Bank::all();
        $cpage = 'finances';
        $selectedBankId = $request->input('bank_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $bank = null;

        // Check if any filter parameters are present in the request
        // This determines if we should load data or show the "input filter" message
        $hasFilters = $request->filled('bank_id') || $request->filled('start_date') || $request->filled('end_date');

        if ($hasFilters) {
            // --- 1. Validation (only if filters are present) ---
            $request->validate([
                'bank_id'    => 'nullable|numeric|exists:banks,id',
                'start_date' => 'nullable|date',
                'end_date'   => 'nullable|date|after_or_equal:start_date',
            ]);

            // Custom validation for date range consistency
            if ($request->filled('start_date') xor $request->filled('end_date')) {
                return redirect()->back()->withErrors([
                    'date_range' => 'Both Start Date and End Date are required when filtering by a date range.'
                ])->withInput();
            }

            // --- Calculate Opening Balance ---
            $openingBalanceQuery = Payment::query()
                ->where('soft_delete', 0)
                // Now directly check 'type' on the payments table itself
                ->whereIn('type', [1, 2]); // Assuming type 1 is Revenue, type 2 is Expenditure

            if ($request->filled('bank_id')) {
                $openingBalanceQuery->where('bank_id', $selectedBankId);
                $bank = Bank::find($selectedBankId);
            }

            if ($request->filled('start_date')) {
                $openingBalance = $openingBalanceQuery
                    ->where('t_date', '<', $startDate)
                    ->get()
                    ->sum(function ($payment) {
                        // Use $payment->type directly
                        return ($payment->type ?? null) == 1 ? $payment->amount : -$payment->amount;
                    });
            } else {
                // If no start date, opening balance is from the very beginning for the selected bank/all banks
                $openingBalance = $openingBalanceQuery
                    ->get()
                    ->sum(function ($payment) {
                        // Use $payment->type directly
                        return ($payment->type ?? null) == 1 ? $payment->amount : -$payment->amount;
                    });
            }

            // --- 2. Query Building for Current Period Transactions ---
            $query = Payment::query();
            $query->with(['account', 'bank']); // Keep eager loading 'account' and 'bank' for display in the table
            $query->where('soft_delete', 0);
            // Now directly check 'type' on the payments table for the main query as well
            $query->whereIn('type', [1, 2]); // Filter for relevant types

            if ($request->filled('bank_id')) {
                $query->where('bank_id', $selectedBankId);
            }

            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('t_date', [$startDate, $endDate]);
            } elseif ($request->filled('start_date')) {
                $query->where('t_date', '>=', $startDate);
            } elseif ($request->filled('end_date')) {
                $query->where('t_date', '<=', $endDate);
            }

            $query->orderBy('t_date', 'asc')
                ->orderBy('id', 'asc');

            // --- 3. Get Paginated Results ---
            $transactions = $query->paginate(10000);

            // --- 4. Logging ---
            activity('FINANCES')->log("Accessed Bank Reconciliations page with filters: " . json_encode($request->all()))->causer(Auth::user());
        } else {
            // Log that the page was accessed without filters
            activity('FINANCES')->log("Accessed Bank Reconciliations page (first load, no filters).")->causer(Auth::user());
        }
        $accounts = Account::all();

        // --- Return View ---
        return view('receipts.unverified', compact( // Assuming 'finances.bank_reconciliation' is the correct view for this method
            'cpage',
            'transactions',
            'banks',
            'bank',
            'accounts',
            'selectedBankId',
            'startDate',
            'endDate',
            'openingBalance',
            'hasFilters' // Pass this flag to the view
        ));
    }
    public function churchReportGenerate(Request $request, Receipt $receipt)
    {
        $accountID = $request->post('account_id');
        $to_month_id = $request->post('to_month_id');
        $from_month_id= $request->post('from_month_id');
        $description= $request->post('description');
        if(!$description){
            $description = $request->session()->get('description');
        }
        if(!$to_month_id){
            $to_month_id = $request->session()->get('to_month_id');
        }
        if(!$from_month_id){
            $from_month_id = $request->session()->get('from_month_id');
        }
        if(!$accountID){
            $accountID = $request->session()->get('account_id');
        }

        $request->session()->put('account_id',$accountID );
        $request->session()->put('to_month_id', $to_month_id);
        $request->session()->put('from_month_id', $from_month_id);

        $to_month= Month::where(['id' =>$to_month_id])->first();
        $from_month = Month::where(['id' =>$from_month_id])->first();

        $start = $from_month->start_date;
        $end = $to_month->end_date;

        $receipts =  $receipt->getMembers($start,$end,$accountID);

        activity('Receipts')
            ->log("Accessed Receipts")->causer(request()->user());
        return view('receipts.division-reports')->with([
            'cpage' => "finances",
            'account_id'=>$accountID,
            'account_name'=>@Accounts::where(['id'=>$accountID])->first()->name,
            'receipts'=>$receipts,
            'getMonths'=>$receipt->getMonthsTransaction($start,$end,$accountID),
            'description'=>$description,
            'churches'=>$receipts,
            'from_month_name'=>$to_month->name,
            'to_month_name'=>$from_month->name,
            'month_id' => $request->post('month_id'),
            'months'=>Month::orderBy('id','desc')->get(),
            'accounts'=>Accounts::where(['type'=>1])->orderBy('id','ASC')->get()
        ]);
    }

    public function churchReports()
    {
        activity('Receipts')
            ->log("Accessed Receipts")->causer(request()->user());
        return view('receipts.division-reports')->with([
            'cpage' => "analytics",
            'months'=>Month::orderBy('id','desc')->get(),
            'accounts'=>Accounts::where(['type'=>1])->orderBy('id','ASC')->get()
        ]);
    }
    function sendSms($to, $message)
    {
        // Check if $to starts with '0' and is 10 digits long
        if (strlen($to) == 10 && substr($to, 0, 1) === '0') {
            $to = '+265' . substr($to, 1); // Remove the leading '0' and add '+265'
        }

        // Check if $to starts with '265' without a leading '+'
        elseif (substr($to, 0, 3) === '265' && substr($to, 0, 1) !== '+') {
            $to = '+' . $to; // Add leading '+'
        }

        try {
            return $this->connObj->send([
                'to' => [$to],
                'message' => $message,
                'from' => $this->senderId
            ]);
        } catch (Exception $e) {
            return $e;
        }
    }

    public function index(Request $request)
    {
        // --- Determine the active month ---
        // This month will be the default if no specific month filter is applied
        if (Month::getActiveMonth()) {
            $activeMonth = Month::getActiveMonth();
        } else {
            // Redirect if no active month is set, as it's crucial for the view
            return redirect()->route('months.index')->with([
                'success-notification' => "Invalid Month, Call Your System Administrator"
            ]);
        }

        // --- Retrieve filter parameters from the request ---
        // These will be null on initial GET request, or hold submitted values on POST
        $selectedBankId = $request->input('bank_id');
        $selectedMonthId = $request->input('month_id', $activeMonth->id); // Default to active month ID
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // --- Prepare variables for dynamic title and selected filter display ---
        $selectedBank = null;
        if ($selectedBankId) {
            $selectedBank = Banks::find($selectedBankId);
        }

        $currentMonthName = null; // Renamed to avoid confusion with Month model instance
        $effectiveStartDate = $activeMonth->start_date; // Default to active month's start/end
        $effectiveEndDate = $activeMonth->end_date;

        // If a month_id is provided, try to find it and set effective dates/name
        if ($selectedMonthId) {
            $requestedMonth = Month::find($selectedMonthId);
            if ($requestedMonth) {
                $currentMonthName = $requestedMonth->name;
                // If explicit start/end dates are not provided, use the selected month's dates
                if (!$startDate && !$endDate) {
                    $effectiveStartDate = $requestedMonth->start_date;
                    $effectiveEndDate = $requestedMonth->end_date;
                }
            } else {
                // Handle case where selectedMonthId is invalid
                // You might want to redirect with an error or default to active month
                $currentMonthName = $activeMonth->name;
                $selectedMonthId = $activeMonth->id; // Reset to active month if invalid
            }
        } else {
            $currentMonthName = $activeMonth->name; // Default to active month's name
        }

        // Override effective dates if explicit start/end dates are provided
        if ($startDate) {
            $effectiveStartDate = $startDate;
        }
        if ($endDate) {
            $effectiveEndDate = $endDate;
        }

        // --- Fetch dropdown data for filters ---
        $banks = Banks::where('soft_delete', 0)->orderBy('id', 'desc')->get();
        $months = Month::where('soft_delete', 0)->orderBy('id', 'desc')->get();
        $churches = Church::where(['soft_delete' => 0])->orderBy('id', 'desc')->get();
        $ministries = Ministry::where(['soft_delete' => 0])->orderBy('id', 'desc')->get();
        $accounts = Accounts::where(['soft_delete' => 0])->orderBy('id', 'ASC')->get();


        // --- Build the payments query for "VERIFIED RECEIPTS" table ---
        $paymentsQuery = Payment::join('accounts', 'accounts.id', '=', 'payments.account_id')
            ->select('payments.*') // Select all columns from payments table
            ->where(['accounts.type' => 1]) // Filter for account type 1 (e.g., Income accounts)
            ->where(['payments.soft_delete' => 0]) // Filter for status 0 (e.g., Verified receipts)
            ->where(['accounts.soft_delete' => 0]); // Filter for active accounts

        // Apply bank filter if selected
        if ($selectedBankId) {
            $paymentsQuery->where('payments.bank_id', $selectedBankId);
        }

        // Apply date range filter using effective dates
        if ($effectiveStartDate && $effectiveEndDate) {
            $paymentsQuery->whereBetween('payments.t_date', [$effectiveStartDate, $effectiveEndDate]);
        } elseif ($effectiveStartDate) {
            $paymentsQuery->where('payments.t_date', '>=', $effectiveStartDate);
        } elseif ($effectiveEndDate) {
            $paymentsQuery->where('payments.t_date', '<=', $effectiveEndDate);
        }

        // Order the results
        $paymentsQuery->orderBy('payments.id', 'desc');

        // --- Paginate the payments ---
        $perPage = 1000; // Consistent with your `allTransaction` method
        $payments = $paymentsQuery->with(['account', 'bank']) // Eager load relationships for performance
        ->paginate($perPage);

        // --- Set opening balance (for consistency, even if not explicitly displayed in this table) ---
        // For the "Verified Receipts" page, an opening balance might not be a primary display item
        // unless you're showing a running balance across all transactions.
        // For now, we'll set it to 0 or derive it if necessary for specific use cases in the view.
        $openingBalance = 0; // Default or calculate if needed for this view's specific purpose

        // --- Log activity ---
        activity('Receipts')
            ->log("Accessed Receipts" .
                " Bank ID: " . ($selectedBankId ?? 'All') .
                " Month ID: " . ($selectedMonthId ?? 'N/A') .
                " Start Date: " . ($effectiveStartDate ?? 'N/A') .
                " End Date: " . ($effectiveEndDate ?? 'N/A'))
            ->causer($request->user());

        // --- Return the view with all necessary data ---
        return view('receipts.index')->with([
            'cpage' => "finances",
            'months' => $months,
            'churches' => $churches,
            'ministries' => $ministries,
            'accounts' => $accounts, // Pass all accounts for any filtering/display
            'banks' => $banks,

            // Values for dropdowns and date inputs to retain state
            'selectedBankId' => (int)$selectedBankId, // Cast to int for comparison
            'selectedMonthId' => (int)$selectedMonthId, // Cast to int for comparison
            'startDate' => $effectiveStartDate, // Use the effective date for input value
            'endDate' => $effectiveEndDate,   // Use the effective date for input value

            // Variables for dynamic titles and data display
            'selectedBank' => $selectedBank,
            'currentMonth' => $currentMonthName, // Pass the name of the current/selected month
            'openingBalance' => $openingBalance, // Pass for consistency

            // Main data for the table
            'payments' => $payments, // The paginated collection of payments

            // Other specific values you are passing (review if dynamic or hardcoded)
            'account_id' => $request->input('account_id', '1'),
            'description' => $request->input('description', 0),
            'type' => $request->input('type', '0'),
            'account_name' => "SCHOOL FEES", // This remains hardcoded based on your original code
            'term_name' => $currentMonthName, // Using currentMonthName for consistency

            // Status is 0 for "Verified Receipts", which is handled in query
            'soft_delete' => 0,
        ]);
    }

    public function unverified(Request $request)
    {
        // Log user activity
        activity('Receipts')->log("Accessed Unverified Receipts (Payments) page.")->causer(request()->user());

        // --- Filter Logic ---
        $query = Payment::query(); // *** CRUCIAL CHANGE: Querying the Payment model ***

        // Eager load relationships that Payment model has to prevent N+1 query issues
        // Assuming Payment model has 'bank' and 'account' relationships defined
        $query->with(['bank', 'account']);

        // Base filter: Only show unverified payments (status = 0)
        // Ensure 'status' column exists on your 'payments' table
        $query->where('payments.soft_delete', 0);

        // Filter by soft_delete on related accounts
        // This assumes your Payment model has an 'account' relationship
        $query->whereHas('account', function ($q) {
            $q->where('soft_delete', 0);
        });

        // Initialize filter variables from the request
        $selectedBankId = $request->input('bank_id');
        $selectedMinistryId = $request->input('ministry_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $selectedMonthId = $request->input('month_id');

        // Initialize variables for the view, especially for captions
        $bank = null;
        $selectedMonth = null;
        $selectedAccount = null; // Assuming you might have a filter for accounts directly on payments

        // Apply Bank filter
        if ($request->filled('bank_id')) {
            $query->where('payments.bank_id', $selectedBankId);
            $bank = Bank::find($selectedBankId); // Fetch the Bank model for caption
        }

        // Apply Ministry filter (Assuming Payment is linked to Account, and Account is linked to Ministry)
        if ($request->filled('ministry_id')) {
            // This is a common pattern for filtering through a relationship
            $query->whereHas('account', function ($q) use ($selectedMinistryId) {
                $q->where('ministry_id', $selectedMinistryId); // Assuming 'ministry_id' is on the 'accounts' table
            });
        }

        // Apply Date filters
        if ($request->filled('start_date')) {
            $query->whereDate('payments.t_date', '>=', $startDate);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('payments.t_date', '<=', $endDate);
        }

        // Apply Month filter
        if ($request->filled('month_id')) {
            $month = Month::find($selectedMonthId);
            if ($month) {
                $query->whereYear('payments.t_date', $month->year)
                    ->whereMonth('payments.t_date', $month->month_number);
                $selectedMonth = $month; // Pass the Month model for caption
            }
        }

        // Order the results
        $query->orderBy('payments.id', 'desc');

        // Get paginated results
        $transactions = $query->paginate(15); // Adjust items per page as needed

        // --- Data for Filter Dropdowns and View Defaults ---
        $banks = Bank::all();
        $months = Month::where('soft_delete', 0)->orderBy('id', 'desc')->get();
        $ministries = Ministry::where('soft_delete', 0)->orderBy('id', 'desc')->get();
        $accounts = Account::where('soft_delete', 0)->orderBy('id', 'ASC')->get(); // For dropdowns or general use

        // Set 'cpage' variable if it's not handled by middleware
        $cpage = "finances";

        // --- Return View ---
        return view('receipts.unverified', compact(
            'cpage',
            'transactions', // <--- Changed to transactions
            'months',
            'banks',
            'ministries',
            'accounts',
            'selectedBankId',
            'selectedMinistryId',
            'startDate',
            'endDate',
            'selectedMonthId',
            'bank',
            'selectedMonth'
        ));
    }

    public function create()
    {
        $suppliers = Supplier::where(['soft_delete'=>0])->orderBy('id','desc')->get();
        $banks = Banks::where(['soft_delete'=>0])->orderBy('id','asc')->get();
        $labourer = Labourer::where(['soft_delete'=>0])->orderBy('id','desc')->get();
        $projects = Department::where(['soft_delete'=>0])->orderBy('id','desc')->get();
        $accounts = Accounts::where(['type'=>1])->where(['soft_delete'=>0])->orderBy('id','ASC')->get();
        return view('receipts.create')->with([
            'cpage'=>"finances",
            'suppliers'=>$suppliers,
            'banks'=>$banks,
            'members'=>Member::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
            'churches'=>Church::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
            'ministries'=>Ministry::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
            'projects'=>$projects,
            'debtors'=>Member::orderBy('id','desc')->get(),
            'labourers'=>$labourer,
            'accounts'=>$accounts
        ]);
    }
    public function store (StoreRequest $request, LabourerController $labourerController)
    {

        $request->validate([
            'type' => "required",
        ]);
        if(!$request->post('specification')){
            $specification = 0;
        }else{
            $specification = $request->post('specification');
        }
        if($request->post('t_date')>date('Y-m-d')){
            return back()->with(['error-notification'=>"Invalid Date Entered, You have Entered a Future Date"]);
        }
        $data = $request->all();

        $monthID  = Month::getActiveMonth();

        $reference = 'N/A';
        if($data['reference']){
            $reference = $data['reference'];
        }
        $transactions_name = 'Admin';

        switch ($data['type']){
            case '1':{
                $transactions_name = 'Main Church ';
                break;
            }
            case '5':{
                $request->validate(['member_id' => "required"]);
                $transactions_name = Member::where(['id'=>$request->post('member_id')])->first()->name;
                break;
            }
            case '2':{
                $request->validate(['debtor_id' => "required"]);
                $transactions_name = Debtor::where(['id'=>$request->post('debtor_id')])->first()->name;
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
            $new_balance = $balance-$request->post('amount');
        }else{
            $new_balance = $balance+$request->post('amount');
        }
        $transactions = [
            'description'=>$transactions_name.' For '.$account->name,
            'type'=>$account_type,
            't_date'=>$data['t_date'],
            'specification'=>$specification,
            'account_id'=>$request->post('account_id'),
            'amount'=>$request->post('amount'),
            'bank_id'=>$request->post('bank_id'),
            'method'=>$request->post('payment_method'),
            'balance'=>$new_balance
        ];
        $data = $request->post();
        $pledge = $data['pledge'];
        $raw_data = [
            'account_id'=>$data['account_id'],
            'amount'=>$data['amount'],
            'name'=>$transactions_name,
            't_date'=>$data['t_date'],
            'bank_id'=>$data['bank_id'],
            'created_by'=>$request->post('created_by'),
            'updated_by'=>$request->post('updated_by'),
            'month_id'=>$monthID->id,
            'specification'=>$specification,
            'type'=>$account_type,
            'pledge'=>$data['pledge'],
            'payment_method'=>$data['payment_method'],
            'reference'=>$reference,
        ];
//        if(Payment::where($raw_data)->first()){
//            return redirect(
//                route('receipts.create') . "?id=pending")->with(
//                ['error-notification'=>"You have already created this transaction Today. Check the Transaction Properly"]
//            );
//        }
        $payments = Payment::create($raw_data);

        $payment = $payments->id;

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
                't_date'=>$request->post('t_date'),
                'month_id'=>$monthID->id,
                'pledge'=>$pledge,
                'created_by'=>$request->post('created_by'),
                'updated_by'=>$request->post('updated_by'),
                'account_id'=>$request->post('account_id'),
                'balance'=>$balances+$request->post('amount'),
                'payment_id'=>$payment,
                'transaction_type'=>2,
            ];
            $last_id = MemberPayment::create($members);
            $member = Member::where(['id'=> $request->post('member_id')])
                ->first();

            if ($member->phone_number) {
                // Attempt to standardize and validate the phone number
                $standardizedPhoneNumber = $labourerController->validating($member->phone_number);
                // If standardization was successful (i.e., not false)
                if ($standardizedPhoneNumber !== false) {
                    $message = 'Dear ' . $member->name .' on '.date('d F Y', strtotime($last_id->t_date)). ', you Paid ' . $request->post('account') .
                        ' Amounting to : MK ' . number_format($request->post('amount'), 2).'. AREA 25, VICTORY TEMPLE';
                    $this->sendSms($standardizedPhoneNumber, $message);
                }
            }
// For $member->phone (if it's a separate field)
            if ($member->phone) {
                $standardizedPhone = $labourerController->validating($member->phone);
                if ($standardizedPhone !== false) {
                    $message = 'Dear ' . $member->name .' on '.date('d F Y', strtotime($last_id->t_date)). ', you Paid ' . $request->post('account') .
                        ' Amounting to : MK ' . number_format($request->post('amount'), 2).'. AREA 25, VICTORY TEMPLE';
                    $this->sendSms($standardizedPhone, $message);
                }
            }
            $order = new DeliveryController();
            if($request->post('amount')>0){
                $order->generateMemberReceipt($last_id->id,$monthID->name,$request->post('t_date'),$specification);
            }
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
                'account_id'=>$request->post('account_id'),
                'created_by'=>$request->post('created_by'),
                'updated_by'=>$request->post('updated_by'),
                'balance'=>$balances+$request->post('amount'),
                'payment_id'=>$payment,
                'transaction_type'=>2,
            ];
            $last_id = ChurchPayment::create($churches);
            $order = new DeliveryController();
            if($request->post('amount')>0) {
                $order->generateHomeReceipt($last_id->id, $monthID->name);
            }
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
            $last_id =MinistryPayment::create($ministries);
            $order = new DeliveryController();
            if($request->post('amount')>0) {
                $order->generateMinistryReceipt($last_id->id, $monthID->name);
            }
        }
        if($request->type==1){
            $order = new DeliveryController();
            if($request->post('amount')>0) {
                $order->generateAdminReceipt($payments->id, $monthID->name);
            }
        }
        BankTransaction::create($transactions);
        //code to generate an invoice
        activity('FINANCES')
            ->log("Created a Receipt")->causer(request()->user());
        return redirect(
            route('receipts.create') . "?id=success")->with(
            ['success-notification'=>"Successfully Created"]
        );
    }

}
