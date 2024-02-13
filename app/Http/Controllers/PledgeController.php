<?php

namespace App\Http\Controllers;

use App\Http\Requests\Pledges\StoreRequest;
use App\Models\Accounts;
use App\Models\FinancialYear;
use App\Models\Member;
use App\Models\MemberPayment;
use App\Models\Month;
use App\Models\Pledge;
use App\Notifications\ContractNofication;
use Illuminate\Http\Request;

class PledgeController extends Controller
{
    public function generatePledge(Request $request)
    {
        $request->validate([
            'year_id' => "required|numeric",
        ]);
        $data = FinancialYear::where(['id' => $request->post('year_id')])->first();

        $start = $data->start_date;
        $end = $data->end_date;
        activity('Pledges')
            ->log("Accessed Pledges")->causer(request()->user());
        return view('pledges.index')->with([
            'cpage' => "pledges",
            'pledges'=>Pledge::whereBetween('pledges.created_at',[$start,$end])->get(),
            'year_id' => $request->post('year_id'),
            'years'=>FinancialYear::all()
        ]);
    }
    public function index()
    {
        activity('Pledges')
            ->log("Accessed Pledges")->causer(request()->user());
        return view('pledges.index')->with([
            'cpage' => "pledges",
            'pledges'=> Pledge::orderBy('id','desc')->get(),
        ]);
    }
    public function create()
    {
        return view('pledges.create')->with([
            'cpage'=>"pledges",
            'accounts'=>Accounts::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
            'members'=>Member::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
        ]);
    }
    public function store(StoreRequest $request, ReceiptController $receiptController)
    {
        $data = $request->post();
        // something should be done here
        if($request->post('date')>date('Y-m-d')){
            return back()->with(['error-notification'=>"Invalid Date Entered, You have Entered a Future Date"]);
        }
        $check_data = [
            'member_id'=>$data['member_id'],
            'date'=>$data['date'],
            'account_id'=>$data['account_id']
        ];

        if(Pledge::where($check_data)->first()){
            // labourer is already part of this project
            return back()->with(['error-notification'=>"Member has already Pledge"]);
        }
        $data = [
            'member_id'=>$data['member_id'],
            'account_id'=>$data['account_id'],
            'amount'=>$data['amount'],
            'date'=>$data['date'],
            'created_by'=>$data['created_by'],
            'updated_by'=>$data['updated_by'],
        ];
        Pledge::create($data);
        $member = Member::where(['id'=> $request->post('member_id')])
            ->first();
        $account = Accounts::where(['id'=> $request->post('account_id')])
            ->first();
        if($member->phone_number!=0) {
            $message = 'MALAWI ASSEMBLIES OF GOD ' .
                PHP_EOL . PHP_EOL . 'Dear ' . $member->name . PHP_EOL . PHP_EOL . ' You have Pledged ' .
                PHP_EOL . PHP_EOL . $account->name .
                ' Amounting to : MK ' . number_format($request->post('amount'), 2) . PHP_EOL
                . PHP_EOL . ' AREA 25 VICTORY TEMPLE';
            $receiptController->sendSms($member->phone_number, $message);
        }
        activity('Pledges')
            ->log("Created a Pledge")->causer(request()->user());
        return redirect()->route('pledges.index')->with([
            'success-notification'=>"Pledge successfully Created"
        ]);
    }
    public function show(Pledge $pledge)
    {
        return view('pledges.show')->with([
            'cpage'=>"finances",
            'pledge'=>$pledge,
        ]);
    }
}
