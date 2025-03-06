<?php

namespace App\Http\Controllers;

use App\Http\Requests\Assets\StoreRequest;
use App\Http\Requests\Assets\UpdateRequest;
use App\Models\Accounts;
use App\Models\AssetRevaluation;
use App\Models\Assets;
use App\Models\Banks;
use App\Models\Categories;
use App\Models\Creditor;
use App\Models\CreditorStatement;
use App\Models\Labourer;
use App\Models\Material;
use App\Models\Month;
use App\Models\Payment;
use App\Models\Store;
use App\Models\Supplier;
use App\Models\SupplierPayments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssetController extends Controller
{
    public function capital()
    {
        $credits = DB::table('incomes')
            ->join('accounts', 'accounts.id','=','incomes.account_id')
            ->select('accounts.*',DB::raw('SUM(incomes.amount) as amount'))
            ->where(['accounts.type'=>2])
            ->where('incomes.transaction_type','=',2)
            ->groupBy('accounts.id')
            ->get();
        activity('ASSETS')
            ->log("Accessed the Capital page")->causer(request()->user());
        return view('assets.capital')->with([
            'cpage' => "assets",
            'transactions'=>$credits
        ]);
    }
    public function liabilities()
    {

        activity('ASSETS')
            ->log("Accessed the Liabilities page")->causer(request()->user());
        return view('assets.liabilities')->with([
            'cpage' => "assets",
            'banks'=>Banks::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
            'suppliers'=>Supplier::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
        ]);
    }
    public function currentAssets(Store $store)
    {

        activity('ASSETS')
            ->log("Accessed the Current Asset page")->causer(request()->user());
        return view('assets.current')->with([
            'cpage' => "assets",
            'banks'=>Banks::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
            'suppliers'=>Supplier::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
        ]);
    }
    public function index()
    {
        $categories = Categories::all();
        activity('ASSETS')
            ->log("Accessed Assets Listing")->causer(request()->user());
        $asset = Assets::where(['soft_delete'=>0])->orderBy('id','desc')->get();
        return view('assets.index')->with([
            'cpage' => "assets",
            'assets' =>$asset,
            'categories' =>$categories
        ]);
    }
    public function create()
    {
        $category = Categories::where(['soft_delete'=>0])->orderBy('id','desc')->get();
        return view('assets.create')->with([
            'cpage'=>"assets",
            'banks'=>Banks::all(),
            'accounts'=>Accounts::where(['type'=>2])->get(),
            'creditors'=>Creditor::all(),
            'categories'=>$category
        ]);
    }
    public function store(StoreRequest $request)
    {

        $data = $request->post();

        // Validate numeric fields
        if ($this->hasInvalidCharacter($request)) {
            return back()->with(['error-notification' => "Invalid Character Entered"]);
        }

        // Create the asset
        $asset = $this->createAsset($data);
        AssetRevaluation::create([
            'asset_id'   => $asset->id,
            'amount'     => $data['cost']*$data['quantity'], // New revaluation amount
            'created_by' => $request->user()->id,
            'updated_by' => $request->user()->id,
        ]);

        // Log activity for asset creation
        $this->logAssetCreation($request);

        // Handle payment methods
        if ($data['payment_method'] === 'cash' && !empty($data['bank_id'])) {
            $this->handleBankTransaction($data, $request);
        } elseif ($data['payment_method'] === 'credit' && !empty($data['creditor_id'])) {
            $this->handleCreditorStatement($data, $request);
        }

        // Redirect after successful asset creation
        return redirect()->route('assets.index')->with([
            'success-notification' => "Successfully Created"
        ]);
    }

    /**
     * Check for invalid numeric entries in certain fields
     */
    private function hasInvalidCharacter($request)
    {
        $fields = ['serial_number', 'name', 'location'];

        foreach ($fields as $field) {
            if (is_numeric($request->post($field))) {
                return true;
            }
        }

        return false;
    }

    /**
     * Create an asset with the provided data
     */
    private function createAsset($data)
    {
        return Assets::create($data);
    }

    /**
     * Log the creation of an asset
     */
    private function logAssetCreation($request)
    {
        activity('ASSETS')
            ->log("Created an Asset")->causer($request->user());
    }

    /**
     * Handle bank transaction for cash payments
     */
    private function handleBankTransaction($data, $request)
    {
        $transactions_name = "Payment for Asset: " . $data['name'];
        $monthID = Month::getActiveMonth();
        $reference = $data['reference'] ?? 'N/A';

        $bankTransactionData = [
            'account_id'     => 2,
            'amount'         => $data['cost'],  // Assuming cost is the amount
            'name'           => $transactions_name,
            't_date'         => $data['t_date'],
            'month_id'       => $monthID->id,
            'created_by'     => $data['created_by'],
            'updated_by'     => $data['updated_by'],
            'specification'  => $data['specification'] ?? 'Asset Purchase',
            'bank_id'        => $data['bank_id'],
            'type'           => 2,  // Assuming type 1 for payments
            'payment_method' => $data['payment_method'],
            'reference'      => $reference,
        ];

        // Create a bank transaction record
        Payment::create($bankTransactionData);
    }

    /**
     * Handle supplier payment for credit payments
     */
    private function handleCreditorStatement($data, $request)
    {
        $balance = CreditorStatement::where(['creditor_id' => $data['creditor_id']])
            ->orderBy('id', 'desc')
            ->first()
            ->balance ?? 0;

        $creditor_balance = $balance + ($data['cost']*$data['quantity']);
        $transaction_type = 2; // Default to credit (invoice)

        if ($data['payment_method'] === 'cash') {
            $transaction_type = 1; // Cash payment
            $creditor_balance = $balance; // Adjust balance for cash payment
        }

        $supplierPaymentData = [
            'creditor_id' => $data['creditor_id'],
            'account_id' => $data['account_id'], // You might need to determine the account ID
            'amount' => ($data['cost']*$data['quantity']),
            'type' => 'invoice', // or 'payment' if it's a cash payment
            'description' => 'Invoice for asset purchase', // More descriptive
            'balance' => $creditor_balance,
            'created_by' => $data['created_by'],
            'updated_by' => $data['updated_by'],
            'transaction_type' => $transaction_type,
            'creditor_invoice_id' => null, // You might need to determine the invoice ID
        ];

        if($data['payment_method'] === 'cash') {
            $supplierPaymentData['type'] = 'payment';
            $supplierPaymentData['description'] = 'Asset purchase cash payment';
        }

        // Create creditor statement record
        CreditorStatement::create($supplierPaymentData);
    }
    public function show(Assets $asset)
    {
        return view('assets.show')->with([
            'cpage'=>"assets",
            'asset'=>$asset,
        ]);
    }
    public function edit( Assets $asset)
    {
        $category = Categories::all();
        return view('assets.edit')->with([
            'cpage' => "assets",
            'asset' => $asset,
            'banks'=>Banks::all(),
            'suppliers'=>Supplier::all(),
            'categories' => $category
        ]);
    }
    public function update(UpdateRequest $request, Assets $asset)
    {
        // Retrieve all input data
        $data = $request->validated();

        // Validate that 'name' does not contain numeric characters
        if (is_numeric($data['name'])) {
            return back()->with(['error-notification' => "Invalid Character Entered in Name"]);
        }

        // Fetch the last revaluation amount for the asset, or default to 0 if none exists
        $lastRevaluation = AssetRevaluation::where('asset_id', $asset->id)->latest()->first();
        $oldCost = $lastRevaluation ? $lastRevaluation->amount : 0;

        // Update the asset with the new data
        $asset->update($data);

        // If the new 'cost' is different from the last revaluation amount, create a new revaluation record
        if ($data['cost'] != $oldCost) {
            AssetRevaluation::create([
                'asset_id'   => $asset->id,
                'amount'     => $data['cost']*$data['quantity'], // New revaluation amount
                'created_by' => $request->user()->id,
                'updated_by' => $request->user()->id,
            ]);
        }

        // Log the asset update activity
        activity('ASSETS')
            ->log("Updated an Asset")->causer($request->user());

        // Redirect to the asset show page with a success message
        return redirect()->route('assets.show', $asset->id)->with([
            'success-notification' => "Asset successfully updated."
        ]);
    }



//    public function destroy(Assets  $asset)
//    {
//        try{
//            $asset->delete();
//            activity('ASSETS')
//                ->log("Delete an Asset")->causer(request()->user());
//            return redirect()->route('assets.index')->with([
//                'success-notification'=>"Successfully Deleted"
//            ]);
//
//        }catch (\Exception $exception){
//            return redirect()->route('assets.index')->with([
//                'error-notification'=>"Something went Wrong ".$exception.getMessage()
//            ]);
//        }
//    }
    public function destroy(Request $request, Assets $assets)
    {
        // Retrieve the asset ID from the request
        $assetId = $request->post('id');

        // Permanently delete the asset
        DB::table('assets')->where('id', $assetId)->delete();

        // Permanently delete associated asset revaluation records
        DB::table('asset_revaluations')->where('asset_id', $assetId)->delete();

        return redirect()->route('assets.index')->with([
            'success-notification' => "Successfully Deleted"
        ]);
    }

}
