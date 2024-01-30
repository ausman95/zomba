<?php

namespace App\Http\Controllers;

use App\Http\Requests\Payments\StoreRequest;
use App\Models\Accounts;
use App\Models\Banks;
use App\Models\BankTransaction;
use App\Models\Church;
use App\Models\ChurchPayment;
use App\Models\Cohort;
use App\Models\Department;
use App\Models\District;
use App\Models\Division;
use App\Models\Fee;
use App\Models\Labourer;
use App\Models\LabourerPayments;
use App\Models\Member;
use App\Models\MemberPayment;
use App\Models\Ministry;
use App\Models\MinistryPayment;
use App\Models\Month;
use App\Models\Pastor;
use App\Models\Payment;
use App\Models\ProjectPayment;
use App\Models\Receipt;
use App\Models\Section;
use App\Models\Students;
use App\Models\StudentTransaction;
use App\Models\Supplier;
use App\Models\SupplierPayments;
use App\Models\Term;
use Illuminate\Http\Request;

class ReceiptController extends Controller
{
    public function generateReceipt(Request $request)
    {
        $request->validate([
            'month_id' => "required|numeric",
        ]);
        $month =  Month::where(['id'=>$request->post('month_id')])
            ->first();

        activity('FINANCES')
            ->log("Accessed Payments")->causer(request()->user());
        return view('receipts.index')->with([
            'cpage' => "finances",
            'payments'=>Payment::join('accounts', 'accounts.id','=','payments.account_id')
                ->select(
                    'payments.*',
                )
                ->whereBetween('t_date',[$month->start_date,$month->end_date])
                ->where(['accounts.type'=>1])
                ->orderBy('payments.id','desc')->get(),
            'months'=>Month::orderBY('id','desc')->get()
        ]);
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
            'cpage' => "finances",
            'months'=>Month::orderBy('id','desc')->get(),
            'accounts'=>Accounts::where(['type'=>1])->orderBy('id','ASC')->get()
        ]);
    }

    public function sendSms($number, $message)
    {
        //dd($message);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://telcomw.com/api-v2/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0   ,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'api_key' => 'eHTIUfunQ4UgDMQKtblY',
                'password' => '@asakala1',
                'text' => $message,
                'numbers' => $number,
                'from' => 'EASYMAOG'),
        ));

        $response = curl_exec($curl);
         curl_close($curl);
        return $response;
        //die();

    }
    public function index(Receipt $receipt)
    {
//        $month = Month::getActiveMonth();
//        dd($month->id);
        if(Month::getActiveMonth()){
            $month=Month::getActiveMonth();
        }else{
            return redirect()->route('months.index')->with([
                'success-notification'=>"Invalid Month, Call Your System Administrator"
            ]);
        }


        activity('Receipts')
            ->log("Accessed Receipts")->causer(request()->user());
        return view('receipts.index')->with([
            'cpage' => "finances",
            'months'=>Month::where(['accounts.soft_delete'=>0])->orderBy('id','desc')->get(),
            'churches'=>Church::where(['accounts.soft_delete'=>0])->orderBy('id','desc')->get(),
            'ministries'=>Ministry::where(['accounts.soft_delete'=>0])->orderBy('id','desc')->get(),
            'account_id'=>'1',
            'description'=>0,
            'type'=>'0',
            'account_name'=>"SCHOOL FEES",
            'term_name'=>Month::where(['accounts.soft_delete'=>0])->where(['id'=>$month->id])->first()->name,
            'payments'=> BankTransaction::join('accounts', 'accounts.id','=','bank_transactions.account_id')
                ->select(
                    'bank_transactions.*',
                )
                ->whereBetween('t_date',[$month->start_date,$month->end_date])
                ->where(['accounts.type'=>1])
                ->where(['accounts.soft_delete'=>0])
                ->orderBy('bank_transactions.id','desc')->get(),
            'accounts'=>Accounts::where(['soft_delete'=>0])->orderBy('id','ASC')->get()
        ]);
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
            'account_id'=>$request->post('account_id'),
            'amount'=>$request->post('amount'),
            'bank_id'=>$request->post('bank_id'),
            'method'=>$request->post('payment_method'),
            'balance'=>$new_balance
        ];

        $data = $request->post();

        $raw_data = [
            'account_id'=>$data['account_id'],
            'amount'=>$data['amount'],
            'name'=>$transactions_name,
            't_date'=>$data['t_date'],
            'bank_id'=>$data['bank_id'],
            'month_id'=>$monthID->id,
            'type'=>$data['type'],
            'payment_method'=>$data['payment_method'],
            'reference'=>$reference,
        ];
        if(Payment::where($raw_data)->first()){
            return redirect(
                route('receipts.create') . "?id=pending")->with(
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
                'account_id'=>$request->post('account_id'),
                'balance'=>$balances+$request->post('amount'),
                'payment_id'=>$payment,
                'transaction_type'=>2,
            ];
            $last_id = MemberPayment::create($members);
            $member = Member::where(['id'=>$request->post('member_id')])->first();
            if($member->phone_number!=0) {
                $message = 'MALAWI ASSEMBLIES OF GOD ' .
                    PHP_EOL . PHP_EOL . 'Dear ' . $member->name . PHP_EOL . PHP_EOL . ' You have Paid ' .
                    PHP_EOL . PHP_EOL . Accounts::where(['id' => $request->post('account_id')])->first()->name .
                    ' Amounting to : MK ' . number_format($data['amount'], 2) . PHP_EOL
                    . PHP_EOL . ' AREA 25 VICTORY TEMPLE';
                $this->sendSms($member->phone_number, $message);
            }
            $order = new DeliveryController();
            if($request->post('amount')>0){
                $order->generateMemberReceipt($last_id->id,$monthID->name);
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
