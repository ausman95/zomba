<?php

namespace App\Http\Controllers;

use App\Models\Accounts;
use App\Models\Banks;
use App\Models\BankTransaction;
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

        if($data['from_account_id']===$data['to_account_id']){
            return back()->with(['error-notification'=>"You are trying to transfer Money to the  same account"]);
        }


        $bal = BankTransaction::where(['bank_id'=>$request->post('from_account_id')])->latest()->first();
        @$balance = $bal->balance;
        if(!$balance){
            $balance = 0;
        }
//        if($request->post('amount')>$balance){
//            return back()->with(['error-notification'=>"The bank balance is less than the amount requested"]);
//        }else{
//            $new_balance = $balance-$request->post('amount');
//        }
        $new_balance = $balance-$request->post('amount');
        $transactions = [
            'description'=>'BANK TRANSFER',
            'type'=>2,
            'amount'=>$request->post('amount'),
            'bank_id'=>$request->post('from_account_id'),
            'method'=>'Bank',
            'account_id'=>134,
            'balance'=>$new_balance
        ];
        Transfer::create($data);
        BankTransaction::create($transactions);
        ///////////////////
        $bal = BankTransaction::where(['bank_id'=>$request->post('to_account_id')])->latest()->first();
        @$balance = $bal->balance;
        if(!$balance){
            $balance = 0;
        }
        $new_balance = $balance+$request->post('amount');
        $transaction = [
            'description'=>'BANK TRANSFER',
            'type'=>1,
            'account_id'=>134,
            'amount'=>$request->post('amount'),
            'bank_id'=>$request->post('to_account_id'),
            'method'=>'Bank',
            'balance'=>$new_balance
        ];
        ///////

        BankTransaction::create($transaction);
        activity('FINANCES')
            ->log("Created a Bank Transfer")->causer(request()->user());
        return redirect()->route('transfers.index')->with([
            'success-notification'=>"Bank Transfer successfully Created"
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
