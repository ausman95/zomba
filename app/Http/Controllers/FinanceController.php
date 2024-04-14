<?php

namespace App\Http\Controllers;

use App\Models\Accounts;
use App\Models\Banks;
use App\Models\Finance;
use App\Models\Incomes;
use App\Models\Material;
use App\Models\Month;
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
            'cpage' => "finances",
            'months'=>Month::where(['soft_delete'=>0])->orderBy('id','desc')->get()
        ]);
    }
    public function generateFinancialStatement(Accounts $accounts, Request $request)
    {
        $request->validate([
            'statement' => "required|numeric",
            'start_date'=>"required|numeric",
            'end_date'=>"required|numeric",
        ]);
        $statement = $request->post('statement');
        if($request->post('end_date')< $request->post('start_date')){
            return back()->with(['error-notification'=>"Please Specify the date correctly!"]);
        }
        $from_month   = Month::where(['id' =>$request->post('start_date')])->first();
        $to_month = Month::where(['id' =>$request->post('end_date')])->first();

        $start = $from_month->start_date;
        $end = $to_month->end_date;

        activity('ANALYTICS')
            ->log("Accessed Financial Reports")->causer(request()->user());


        return view('finances.reports')->with([
            'cpage' => "finances",
            'statement' => $statement,
            'start_date'=>$start,
            'end_date'=>$end,
            'months'=>Month::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
            'all'=>Accounts::allCrAccounts($start,$end),
            'catCredits' => Accounts::getAccountCatDebits($start,$end),
            'catDebits' => Accounts::getAccountCateExpenses($start,$end),
            'accounts' => Accounts::allAccounts($start,$end),
            'credits' => Accounts::getAccountBalanceDebits($statement,$start,$end),
            'debits' => Incomes::accountAll($start,$end),
            'expenses' => Incomes::accountExpensesAll($statement,$start,$end),
            'admins' => Accounts::getAccountBalanceAdmin($statement,$start,$end),
        ]);
    }
}
