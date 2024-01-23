<?php

namespace App\Http\Controllers;

use App\Models\Accounts;
use App\Models\Announcement;
use Illuminate\Http\Request;
use App\Http\Requests\Accounts\StoreRequest;
use App\Http\Requests\Accounts\UpdateRequest;
use Illuminate\Support\Facades\DB;


class AccountController extends Controller
{
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
            'cpage'=>"finances"
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
        if(is_numeric($request->post('name'))){
            return back()->with(['error-notification'=>"Invalid Character Entered on Name"]);
        }
        $data = $request->post();

        Accounts::create($data);
        activity('ACCOUNTS')
            ->log("Created a new Account")->causer(request()->user());
        return redirect()->route('accounts.index')->with([
            'success-notification'=>"Account successfully Created"
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
