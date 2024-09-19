<?php

namespace App\Http\Controllers;

use App\Models\Accounts;
use App\Models\Banks;
use App\Models\BankTransaction;
use App\Models\Payment;
use App\Models\Transfer;
use Illuminate\Http\Request;
use App\Http\Requests\Transfers\StoreRequest;
use App\Http\Requests\Transfers\UpdateRequest;

class TransferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        activity('FINANCES')
            ->log("Accessed Bank Transfers")->causer(request()->user());
        $transfers= Transfer::where(['soft_delete'=>0])->orderBy('id','desc')->get();
        return view('transfers.index')->with([
            'cpage' => "finances",
            'transfers'=>$transfers
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('transfers.create')->with([
            'cpage'=>"finances",
            'banks'=>Banks::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $data = $request->post();

        // Prevent transfer to the same account
        if ($data['from_account_id'] === $data['to_account_id']) {
            return back()->with(['error-notification' => "You are trying to transfer money to the same account."]);
        }

        // Handle 'from' account balance deduction
        $fromAccountBalance = BankTransaction::where('bank_id', $data['from_account_id'])->latest()->value('balance') ?? 0;
        $newFromBalance = $fromAccountBalance - $data['amount'];

        $fromTransaction = [
            'description' => 'BANK TRANSFER',
            'type' => 2, // Deduction
            'amount' => $data['amount'],
            'bank_id' => $data['from_account_id'],
            'method' => 'Bank',
            't_date' => $data['t_date'],
            'account_id' => 134,
            'balance' => $newFromBalance
        ];

        // Create transfer and deduct from 'from' account
        Transfer::create($data);
        BankTransaction::create($fromTransaction);

        // Record payment for 'from' account
        $fromPayment = [
            'account_id' => 134, // Adjust as needed
            'bank_id' => $data['from_account_id'],
            'name' => 'BANK TRANSFER OUT',
            'amount' => $data['amount'],
            'payment_method' => 'Bank',
            'reference' => 'BANK TRANSFER',
            'type' => 2, // Deduction
            't_date' => $data['t_date'],
            'month_id' => $data['month_id'] ?? null,
            'created_by' => $request->user()->id,
            'updated_by' => $request->user()->id,
            'status' => 1, // Assuming active transaction
            'pledge' => 0, // Default value for pledge, adjust as needed
            'specification' => 'Transfer from account ' . $data['from_account_id']
        ];
        Payment::create($fromPayment);

        // Handle 'to' account balance addition
        $toAccountBalance = BankTransaction::where('bank_id', $data['to_account_id'])->latest()->value('balance') ?? 0;
        $newToBalance = $toAccountBalance + $data['amount'];

        $toTransaction = [
            'description' => 'BANK TRANSFER',
            'type' => 1, // Addition
            'amount' => $data['amount'],
            'bank_id' => $data['to_account_id'],
            'method' => 'Bank',
            't_date' => $data['t_date'],
            'account_id' => 134,
            'balance' => $newToBalance
        ];

        // Create the transaction for the 'to' account
        BankTransaction::create($toTransaction);

        // Record payment for 'to' account
        $toPayment = [
            'account_id' => 134, // Adjust as needed
            'bank_id' => $data['to_account_id'],
            'name' => 'BANK TRANSFER IN',
            'amount' => $data['amount'],
            'payment_method' => 'Bank',
            'reference' => 'BANK TRANSFER',
            'type' => 1, // Addition
            't_date' => $data['t_date'],
            'month_id' => $data['month_id'] ?? null,
            'created_by' => $request->user()->id,
            'updated_by' => $request->user()->id,
            'status' => 1, // Assuming active transaction
            'pledge' => 0, // Default value for pledge, adjust as needed
            'specification' => 'Transfer to account ' . $data['to_account_id']
        ];
        Payment::create($toPayment);

        // Log the bank transfer activity
        activity('FINANCES')
            ->log("Created a Bank Transfer")
            ->causer($request->user());

        // Redirect with success message
        return redirect()->route('transfers.index')->with([
            'success-notification' => "Bank Transfer successfully created."
        ]);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Transfer $transfer)
    {
        return view('transfers.show')->with([
            'cpage'=>"finances",
            'transfer'=>$transfer
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Transfer $transfer)
    {
        return view('transfers.edit')->with([
            'cpage'=>"finances",
            'transfer'=>$transfer
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Transfer $transfer)
    {
        $data = $request->post();
        if(empty($request->post('t_date'))){
            return back()->with(['error-notification'=>"Date is Empty"]);
        }
        $transfer->update($data);
        return redirect()->route('transfers.show',$transfer->id)->with([
            'success-notification'=>"Successfully Updated"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy (Transfer $transfer)
    {
        try{
            $transfer->delete();
            activity('FINANCES')
                ->log("Deleted a Bank Transfer")->causer(request()->user());
            return redirect()->route('transfers.index')->with([
                'success-notification'=>"Bank Transfer successfully Deleted"
            ]);

        }catch (\Exception $exception){
            return redirect()->route('transfers.index')->with([
                'error-notification'=>"Something went Wrong ".$exception.getMessage()
            ]);
        }
    }
}
