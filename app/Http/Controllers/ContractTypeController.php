<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContractTypes\UpdateRequest;
use App\Http\Requests\ContractTypes\StoreRequest;
use App\Models\Announcement;
use App\Models\Contract;
use App\Models\ContractType;
use App\Models\Labourer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContractTypeController extends Controller
{
    public function index()
    {
        activity('CONTRACTS')
            ->log("Accessed Contract Types")->causer(request()->user());
        return view('contract-types.index')->with([
            'cpage' => "human-resources",
            'contracts' => ContractType::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
        ]);
    }

    public function create()
    {
        return view('contract-types.create')->with([
            'cpage' => "human-resources",
        ]);
    }

    public function store(StoreRequest $request)
    {
        if(is_numeric($request->post('name'))){
            return back()->with(['error-notification'=>"Invalid Character Entered on Name"]);
        }
        if(is_numeric($request->post('description'))){
            return back()->with(['error-notification'=>"Invalid Character Entered on Description"]);
        }
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
            'cpage' => "human-resources",
            'contract'=>$contractType,
            'labourers'=>$labourer
        ]);
    }

    public function edit(ContractType $contractType)
    {
        return view('contract-types.edit')->with([
            'cpage' => "human-resources",
            'contract' => $contractType
        ]);
    }
    public function update(UpdateRequest $request, ContractType $contractType)
    {
        $data = $request->post();
        if(is_numeric($request->post('name'))){
            return back()->with(['error-notification'=>"Invalid Character Entered on Name"]);
        }
        if(is_numeric($request->post('description'))){
            return back()->with(['error-notification'=>"Invalid Character Entered on Description"]);
        }
        $contractType->update($data);
        activity('Contracts Types')
            ->log("Updated a Contracts types")->causer(request()->user());
        return redirect()->route('contract-types.show',$contractType->id)->with([
            'success-notification'=>"Contract type successfully Updated"
        ]);
    }

    public function destroy(Request $request, ContractType $contractType)
    {

        $data = $request->post();
        DB::table('contract_types')
            ->where(['id' => $request->post('id')])
            ->update(['soft_delete' => '1']);
        $contractType->update($data);

        return redirect()->route('contract-types.index')->with([
            'success-notification'=>"Successfully Deleted"
        ]);
    }

}
