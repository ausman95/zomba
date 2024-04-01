<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Banks;
use App\Models\BankTransaction;
use App\Models\Incomes;
use Illuminate\Http\Request;
use App\Http\Requests\Banks\StoreRequest;
use App\Http\Requests\Banks\UpdateRequest;
use Illuminate\Support\Facades\DB;


class BankController extends Controller
{
    public function generateReport(Request $request)
    {
        $request->validate([
            'bank_id' => "required|numeric",
            'start_date' => "required|date",
            'end_date' => "required|date",
        ]);
        activity('FINANCES')
            ->log("Accessed Bank Statements")->causer(request()->user());
        return view('banks.statements')->with([
            'cpage' => "finances",
            'transactions'=>BankTransaction::whereBetween('t_date',[$request->post('start_date'),
                $request->post('end_date')])
                ->where(['bank_id'=>$request->post('bank_id')])
                ->orderBy('id','desc')->get(),
            'payments'=>1,
            'start_date'=>$request->post('start_date'),
            'end_date'=>$request->post('end_date'),
            'bank'=>Banks::where(['id'=>$request->post('bank_id')])->first(),
            'banks'=>Banks::where(['soft_delete'=>0])->orderBy('id','desc')->get(),

        ]);
    }
    public function index()
    {
        activity('BANKS')
            ->log("Accessed Banks")->causer(request()->user());
        return view('banks.index')->with([
            'cpage' => "finances",
            'banks'=>Banks::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
        ]);
    }
    public function statements()
    {
        activity('BANKS')
            ->log("Accessed Banks Statements")->causer(request()->user());
        return view('banks.statements')->with([
            'cpage' => "finances",
            'banks'=>Banks::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
            'payments'=>0
        ]);
    }

    public function destroy(Request $request, Banks $banks)
    {

        $data = $request->post();
        DB::table('banks')
            ->where(['id' => $request->post('id')])
            ->update(['soft_delete' => '1']);
        $banks->update($data);

        return redirect()->route('banks.index')->with([
            'success-notification'=>"Successfully Deleted"
        ]);
    }

    public function create()
    {
        return view('banks.create')->with([
            'cpage'=>"finances"
        ]);
    }

    public function store(StoreRequest $request)
    {
        if(is_numeric($request->post('bank_name'))){
            return back()->with(['error-notification'=>"Invalid Character Entered on Bank Name"]);
        }
        if(is_numeric($request->post('account_name'))){
            return back()->with(['error-notification'=>"Invalid Character Entered on Account Name"]);
        }
        if(is_numeric($request->post('service_centre'))){
            return back()->with(['error-notification'=>"Invalid Character Entered on Service Centre"]);
        }
        if(is_numeric($request->post('account_type'))){
            return back()->with(['error-notification'=>"Invalid Character Entered on Account Type"]);
        }
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
            'cpage'=>"finances",
            'bank'=>$bank,
            'transactions' =>$transactions
        ]);
    }
    public function edit( Banks $bank)
    {
        return view('banks.edit')->with([
            'cpage' => "finances",
            'bank' => $bank
        ]);
    }
    public function update(UpdateRequest $request,Banks $bank)
    {
        $data = $request->post();
        if(is_numeric($request->post('bank_name'))){
            return back()->with(['error-notification'=>"Invalid Character Entered on Bank Name"]);
        }
        if(is_numeric($request->post('account_name'))){
            return back()->with(['error-notification'=>"Invalid Character Entered on Account Name"]);
        }
        if(is_numeric($request->post('service_centre'))){
            return back()->with(['error-notification'=>"Invalid Character Entered on Service Centre"]);
        }
        if(is_numeric($request->post('account_type'))){
            return back()->with(['error-notification'=>"Invalid Character Entered on Account Type"]);
        }
        $bank->update($data);
        activity('BANKS')
            ->log("Edited Banks")->causer(request()->user());
        return redirect()->route('banks.show',$bank->id)->with([
            'success-notification'=>"Bank Account successfully Updated"
        ]);
    }
//    public function destroy(Banks  $bank)
//    {
//        try{
//            $bank->delete();
//            activity('BANKS')
//                ->log("Deleted a Bank")->causer(request()->user());
//            return redirect()->route('banks.index')->with([
//                'success-notification'=>"Bank Account successfully Deleted"
//            ]);
//
//        }catch (\Exception $exception){
//            return redirect()->route('banks.index')->with([
//                'error-notification'=>"Something went Wrong ".$exception.getMessage()
//            ]);
//        }
//    }

}
