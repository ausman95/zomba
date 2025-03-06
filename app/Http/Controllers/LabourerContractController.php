<?php

namespace App\Http\Controllers;

use App\Models\Accounts;
use App\Models\Contract;
use App\Models\ContractType;
use App\Models\Labourer;
use App\Models\LabourerContract;
use App\Models\LabourerContractBenefit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LabourerContractController extends Controller
{
    public function show(LabourerContract $contract)
    {
        // Load the benefits associated with the contract
        $benefits = $contract->benefits;
        $cpage = 'human-resources';

        // Pass the contract and benefits to the view
        return view('contracts.show', compact('cpage','contract', 'benefits'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'labourer_id' => 'required|exists:labourers,id',
            'benefits' => 'required|array',
            'benefits.*.account_id' => 'required|exists:accounts,id',
            'benefits.*.amount' => 'required|numeric|min:0',
        ]);

        // Create the main contract record
        $contract = LabourerContract::create([
            'labourer_id' => $request->labourer_id,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        // Create the benefit records, ensuring uniqueness
        foreach ($request->benefits as $benefit) {
            // Check if a benefit record already exists
            $existingBenefit = LabourerContractBenefit::where([
                'labourer_contract_id' => $contract->id,
                'account_id' => $benefit['account_id'],
            ])->first();

            if (!$existingBenefit) {
                LabourerContractBenefit::create([
                    'labourer_contract_id' => $contract->id,
                    'account_id' => $benefit['account_id'],
                    'amount' => $benefit['amount'],
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                ]);
            }
        }

        return redirect()->route('contracts.index')->with('success-notification', 'Contract created successfully.');
    }
    public function destroy(Request $request, Contract $contract)
    {

        $data = $request->post();
        DB::table('contracts')
            ->where(['id' => $request->post('id')])
            ->update(['soft_delete' => '1']);
        $contract->update($data);

        return redirect()->route('contracts.index')->with([
            'success-notification'=>"Successfully Deleted"
        ]);
    }
    public function create()
    {

        return view('contracts.create')->with([
            'cpage'=>"human-resources",
            'accounts' => Accounts::orderBy('name', 'ASC')->get(),
            'labourers'=>Labourer::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
        ]);
    }
    public function index()
    {
        activity('CONTRACTS')
            ->log("Accessed Contracts")->causer(request()->user());
        return view('contracts.index')->with([
            'cpage' => "human-resources",
            'contracts' => LabourerContract::orderBy('id','desc')->get(),
        ]);
    }
}
