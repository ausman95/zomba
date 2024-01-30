<?php

namespace App\Http\Controllers;

use App\Models\Accounts;
use App\Models\Banks;
use App\Models\Finance;
use App\Models\Incomes;
use App\Models\Material;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{

    public function getAccountBalance($start_date,$end_date)
    {
        return $value = Incomes::where(['account_id'=>$this->id])
            ->whereBetween('created_at',[$start_date,$end_date])
            ->groupBy('account_id')->sum("amount");
    }
    public function index()
    {
        activity('FINANCES')
            ->log("Accessed Finances")->causer(request()->user());
        $banks = Banks::where(['soft_delete'=>0])->orderBy('id','desc')->get();
        return view('finances.index')->with([
            'cpage' => "finances",
            'banks' => $banks
        ]);
    }
    public function financialStatement()
    {
        return view('finances.reports')->with([
            'cpage' => "finances"
        ]);
    }
    public function generateFinancialStatement(Accounts $accounts, Request $request)
    {
        $request->validate([
            'statement' => "required|numeric",
            'start_date'=>"required|date",
            'end_date'=>"required|date",
        ]);
        if($request->post('end_date')< $request->post('start_date')){
            return back()->with(['error-notification'=>"Please Specify the date correctly!"]);
        }
//        if($request->post('end_date')>date('Y-m-d')){
//            return back()->with(['error-notification'=>"Invalid End Date Entered, You have Entered a Future Date"]);
//        }
        if($request->post('start_date')>date('Y-m-d')){
            return back()->with(['error-notification'=>"Invalid Start Date Entered, You have Entered a Future Date"]);
        }
        activity('ANALYTICS')
            ->log("Accessed Financial Reports")->causer(request()->user());


        return view('finances.reports')->with([
            'cpage' => "finances",
            'statement' => $request->post('statement'),
            'start_date'=>$request->post('start_date'),
            'end_date'=>$request->post('end_date'),
            'accounts' => Accounts::all(),
            'credits' => Accounts::getAccountBalanceDebits($request->post('start_date'),$request->post('end_date')),
            'debits' => Incomes::accountAll($request->post('start_date'),$request->post('end_date')),
            'expenses' => Incomes::accountExpensesAll($request->post('start_date'),$request->post('end_date')),
            'admins' => Accounts::getAccountBalanceAdmin($request->post('start_date'),$request->post('end_date')),
        ]);
    }
}
