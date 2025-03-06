<?php

namespace App\Http\Controllers;

use App\Http\Requests\Contracts\StoreRequest;
use App\Http\Requests\Contracts\UpdateRequest;
use App\Models\Accounts;
use App\Models\Announcement;
use App\Models\Contract;
use App\Models\ContractType;
use App\Models\Labourer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContractController extends Controller
{


    public function store(StoreRequest $request)
    {
        $data = $request->post();
        $check_data = [
            'labourer_id'=>$data['labourer_id'],
            'soft_delete'=>0
        ];
        if($request->post('start_date')>$request->post('end_date')){
            return back()->with(['error-notification'=>"Invalid End Date Entered"]);
        }

        if(Contract::where($check_data)->first()){
            // labourer is already part of this project
            return back()->with(['error-notification'=>"The Employee is already on a contract"]);
        }
        Contract::create($data);
        activity('CONTRACTS')
            ->log("Created a Contracts")->causer(request()->user());
        return redirect()->route('contracts.index')->with([
            'success-notification'=>"Contract successfully Created"
        ]);
    }
    public function show(Contract $contract)
    {
        return view('contracts.show')->with([
            'cpage'=>"human-resources",
            'contract'=>$contract
        ]);
    }
//    public function destroy(Contract $contract)
//    {
//        try{
//            $contract->delete();
//            activity('CONTRACTS')
//                ->log("Deleted a Contracts")->causer(request()->user());
//            return redirect()->route('contracts.index')->with([
//                'success-notification'=>"Contract successfully Deleted"
//            ]);
//
//        }catch (\Exception $exception){
//            return redirect()->route('contracts.index')->with([
//                'error-notification'=>"Something went Wrong ".$exception.getMessage()
//            ]);
//        }
//    }
    public function update(UpdateRequest $request, Contract $contract)
    {
        $data = $request->post();
        if($request->post('start_date')>$request->post('end_date')){
            return back()->with(['error-notification'=>"Invalid End Date Entered"]);
        }
        $contract->update($data);
        activity('CONTRACTS')
            ->log("Updated a Contracts")->causer(request()->user());
        return redirect()->route('contracts.show',$contract->id)->with([
            'success-notification'=>"Contracts successfully Updated"
        ]);
    }
    public function edit(Contract $contract)
    {
        return view('contracts.edit')->with([
            'cpage' => "human-resources",
            'contract' => $contract,
            'labourers'=>Labourer::all(),
            'contract_types'=>ContractType::all()
        ]);
    }
}
