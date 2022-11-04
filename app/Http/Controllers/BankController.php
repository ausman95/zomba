<?php

namespace App\Http\Controllers;

use App\Models\Banks;
use App\Models\Incomes;
use Illuminate\Http\Request;
use App\Http\Requests\Banks\StoreRequest;
use App\Http\Requests\Banks\UpdateRequest;


class BankController extends Controller
{
    public function index()
    {
        activity('BANKS')
            ->log("Accessed Banks")->causer(request()->user());

        $banks= Banks::orderBy('id','desc')->get();;
        return view('banks.index')->with([
            'cpage' => "banks",
            'banks'=>$banks
        ]);
    }

    public function create()
    {
        return view('banks.create')->with([
            'cpage'=>"banks"
        ]);
    }

    public function store(StoreRequest $request)
    {
        $data = $request->post();

        Banks::create($data);
        activity('BANKS')
            ->log("Created a Bank Account")->causer(request()->user());
        return redirect()->route('banks.index')->with([
            'success-notification'=>"Bank Account successfully Created"
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Banks $bank)
    {
        activity('BANKS')
            ->log("Accessed Banks")->causer(request()->user());
        $transactions = $bank->transactions;
      //  dd($incomes);
        return view('banks.show')->with([
            'cpage'=>"banks",
            'bank'=>$bank,
            'transactions' =>$transactions
        ]);
    }
    public function edit( Banks $bank)
    {
        return view('banks.edit')->with([
            'cpage' => "banks",
            'bank' => $bank
        ]);
    }
    public function update(UpdateRequest $request,Banks $bank)
    {
        $data = $request->post();

        $bank->update($data);
        activity('BANKS')
            ->log("Edited Banks")->causer(request()->user());
        return redirect()->route('banks.show',$bank->id)->with([
            'success-notification'=>"Bank Account successfully Updated"
        ]);
    }
    public function destroy(Banks  $bank)
    {
        try{
            $bank->delete();
            activity('BANKS')
                ->log("Deleted a Bank")->causer(request()->user());
            return redirect()->route('banks.index')->with([
                'success-notification'=>"Bank Account successfully Deleted"
            ]);

        }catch (\Exception $exception){
            return redirect()->route('banks.index')->with([
                'error-notification'=>"Something went Wrong ".$exception.getMessage()
            ]);
        }
    }

}
