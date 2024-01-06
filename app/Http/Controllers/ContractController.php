<?php

namespace App\Http\Controllers;

use App\Http\Requests\Contracts\StoreRequest;
use App\Http\Requests\Contracts\UpdateRequest;
use App\Models\Contract;
use App\Models\ContractType;
use App\Models\Labourer;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    public function index()
    {
        activity('CONTRACTS')
            ->log("Accessed Contracts")->causer(request()->user());
        $contracts= Contract::all();
        return view('contracts.index')->with([
            'cpage' => "human-resources",
            'contracts' => $contracts
        ]);
    }
    public function create()
    {
        return view('contracts.create')->with([
            'cpage'=>"human-resources",
            'labourers'=>Labourer::all(),
            'contract_types'=>ContractType::all()
        ]);
    }
    public function store(StoreRequest $request)
    {
        $data = $request->post();
        $check_data = [
            'labourer_id'=>$data['labourer_id'],
        ];

        if(Contract::where($check_data)->first()){
            // labourer is already part of this project
            return back()->with(['error-notification'=>"The Employee is already on a contract"]);
        }
        Contract::create($data);
        activity('CONTRACTS')
            ->log("Created a Contracts")->causer(request()->user());
        return redirect()->route('contracts.create')->with([
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
    public function destroy(Contract $contract)
    {
        try{
            $contract->delete();
            activity('CONTRACTS')
                ->log("Deleted a Contracts")->causer(request()->user());
            return redirect()->route('contracts.index')->with([
                'success-notification'=>"Contract successfully Deleted"
            ]);

        }catch (\Exception $exception){
            return redirect()->route('contracts.index')->with([
                'error-notification'=>"Something went Wrong ".$exception.getMessage()
            ]);
        }
    }
    public function update(UpdateRequest $request, Contract $contract)
    {
        $data = $request->post();

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
