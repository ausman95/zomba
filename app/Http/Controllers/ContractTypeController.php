<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContractTypes\UpdateRequest;
use App\Http\Requests\ContractTypes\StoreRequest;
use App\Models\Contract;
use App\Models\ContractType;
use App\Models\Labourer;
use Illuminate\Http\Request;

class ContractTypeController extends Controller
{
    public function index()
    {
        activity('CONTRACTS')
            ->log("Accessed Contracts")->causer(request()->user());
        $contracts= ContractType::all();
        return view('contract-types.index')->with([
            'cpage' => "contract-types",
            'contracts' => $contracts
        ]);
    }

    public function create()
    {
        return view('contract-types.create')->with([
            'cpage' => "contract-types",
        ]);
    }

    public function store(StoreRequest $request)
    {
        $data = $request->post();

        ContractType::create($data);
        activity('CONTRACT TYPE')
            ->log("Created a Contract Type")->causer(request()->user());
        return redirect()->route('contract-types.index')->with([
            'success-notification'=>"Contract Type successfully Created"
        ]);
    }
    public function show(ContractType $contractType)
    {
        $labourer = $contractType->labourers;
        return view('contract-types.show')->with([
            'cpage'=>"contract-types",
            'contract'=>$contractType,
            'labourers'=>$labourer
        ]);
    }

    public function edit(ContractType $contractType)
    {
        return view('contract-types.edit')->with([
            'cpage' => "contract-types",
            'contract' => $contractType
        ]);
    }
    public function update(UpdateRequest $request, ContractType $contractType)
    {
        $data = $request->post();

        $contractType->update($data);
        activity('Contracts Types')
            ->log("Updated a Contracts types")->causer(request()->user());
        return redirect()->route('contract-types.show',$contractType->id)->with([
            'success-notification'=>"Contract type successfully Updated"
        ]);
    }

    public function destroy(ContractType $contractType)
    {
        try{
            $contractType->delete();
            activity('Contract Types')
                ->log("Deleted a Contract Type")->causer(request()->user());
            return redirect()->route('contract-types.index')->with([
                'success-notification'=>"Contract type successfully Deleted"
            ]);

        }catch (\Exception $exception){
            return redirect()->route('contract-types.index')->with([
                'error-notification'=>"Something went Wrong ".$exception.getMessage()
            ]);
        }
    }
}
