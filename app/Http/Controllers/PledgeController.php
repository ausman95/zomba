<?php

namespace App\Http\Controllers;

use App\Http\Requests\Pledges\StoreRequest;
use App\Models\FinancialYear;
use App\Models\Member;
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
            'pledges'=> Pledge::orderBy('id','desc')->get()
        ]);
    }
    public function create()
    {
        return view('pledges.create')->with([
            'cpage'=>"pledges",
            'years'=>FinancialYear::all(),
            'members'=>Member::all()
        ]);
    }
    public function store(StoreRequest $request)
    {
        $data = $request->post();
        // something should be done here

        $check_data = [
            'member_id'=>$data['member_id'],
            'year_id'=>$data['year_id']
        ];

        if(Pledge::where($check_data)->first()){
            // labourer is already part of this project
            return back()->with(['error-notification'=>"Member already Pledge"]);
        }
        $data = [
            'member_id'=>$data['member_id'],
            'year_id'=>$data['year_id'],
            'amount'=>$data['amount'],
            'balance'=>$data['amount']*12
        ];
        Pledge::create($data);
        $year_id = FinancialYear::where(['id'=>$data['year_id']])->first();
        $message = 'PLEDGE PAYMENT' . PHP_EOL . ' You have Pledged MK '.number_format($data['amount']+12) . PHP_EOL . '
        for the Year of '.$year_id->name . PHP_EOL . 'Log in to check on https://synod.marcsystems.africa/login' . PHP_EOL . ' Blantyre Synod App';
        $columns = Member::where(['id' => $request->post('member_id')])->first();
        if($columns->email){
            $columns->notify(new ContractNofication($message));
        }else{
            $this->sendSms($columns->phone_number, $message);
        }
        activity('Pledges')
            ->log("Created a Pledge")->causer(request()->user());
        return redirect()->route('pledges.index')->with([
            'success-notification'=>"Pledge successfully Created"
        ]);
    }
}
