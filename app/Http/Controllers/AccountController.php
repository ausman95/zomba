<?php

namespace App\Http\Controllers;

use App\Models\Accounts;
use App\Models\Announcement;
use App\Models\Banks;
use App\Models\Categories;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Http\Requests\Accounts\StoreRequest;
use App\Http\Requests\Accounts\UpdateRequest;
use Illuminate\Support\Facades\DB;


class AccountController extends Controller
{

    public function modify(Request $request, $id)
    {
        // Fetch the transaction
        $transaction = Payment::findOrFail($id);

        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0', // Ensure the amount is not negative
            'account_id' => 'required|exists:accounts,id', // Validate account ID exists in the accounts table
            'bank_id' => 'required|exists:banks,id', // Validate bank ID exists in the banks table
            'reference' => 'nullable|string|max:255', // Reference is optional
            't_date' => 'required|date', // Ensure it's a valid date
            'updated_by' => 'required|exists:users,id', // Ensure the user ID exists in the users table
        ]);

        // Update the transaction with validated data
        $transaction->update($validatedData);

        // Optionally, you can log the update action or any other related tasks

        // Redirect back with a success message
        return redirect()->route('accounts.show', $request->post('account_id'))
            ->with('success-notification', 'Transaction updated successfully!');
    }

    public function change($id)
    {
        // Fetch the transaction using the ID, or fail if not found
        $transaction = Payment::findOrFail($id);

        // Fetch all accounts and banks
        $accounts = Accounts::all();
        $banks = Banks::all();
        $cpage = 'finances';

        // Pass the transaction data, accounts, and banks to the view for editing
        return view('accounts.change', compact('cpage','transaction', 'accounts', 'banks'));
    }

    public function destroy(Request $request, Accounts $accounts)
    {

        $data = $request->post();
        DB::table('accounts')
            ->where(['id' => $request->post('id')])
            ->update(['soft_delete' => '1']);
        $accounts->update($data);

        return redirect()->route('accounts.index')->with([
            'success-notification'=>"Successfully Deleted"
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        activity('ACCOUNTS')
            ->log("Accessed Accounts listing")->causer(request()->user());
        return view('accounts.index')->with([
            'cpage' => "finances",
            'accounts'=>Accounts::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       return view('accounts.create')->with([
            'cpage'=>"finances",
           'categories'=>Categories::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name = $request->post('name');
        $type = $request->post('type'); // Assuming you have a 'type' field

        if (is_numeric($name)) {
            return back()->with(['error-notification' => "Invalid Character Entered on Name"]);
        }

        // Check for duplicate account by name and type
        $duplicateAccount = Accounts::where('name', $name)
            ->where('type', $type)
            ->exists();

        if ($duplicateAccount) {
            return back()->with(['error-notification' => "An account with this name and type already exists."]);
            // Or you could return false if this is part of a validation rule
            // return false;
        }

        $accountData = $request->post();

        Accounts::create($accountData);
        activity('ACCOUNTS')
            ->log("Created a new Account")
            ->causer($request->user());
        return redirect()->route('accounts.create')->with([
            'success-notification' => "Account successfully Created",
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Accounts $account)
    {
        $incomes = $account->incomes;
        return view('accounts.show')->with([
            'cpage'=>"finances",
            'account'=>$account,
            'transactions'=>$incomes
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit( Accounts $account)
    {
       return view('accounts.edit')->with([
            'cpage' => "finances",
           'categories'=>Categories::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
           'account' => $account
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request,Accounts $account)
    {
        $data = $request->post();
        if(is_numeric($request->post('name'))){
            return back()->with(['error-notification'=>"Invalid Character Entered on Name"]);
        }
        $account->update($data);
        activity('ACCOUNTS')
            ->log("Updated an Account")->causer(request()->user());
        return redirect()->route('accounts.show',$account->id)->with([
            'success-notification'=>"Account successfully Updated"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
//    public function destroy(Accounts  $account)
//    {
//        try{
//            $account->delete();
//            activity('ACCOUNTS')
//                ->log("Deleted an Account")->causer(request()->user());
//            return redirect()->route('accounts.index')->with([
//                'success-notification'=>"Account successfully Deleted"
//            ]);
//
//        }catch (\Exception $exception){
//            return redirect()->route('accounts.index')->with([
//                'error-notification'=>"Something went Wrong ".$exception.getMessage()
//            ]);
//        }
//    }
}
