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
        $banks = Banks::all();
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
        $start = $request->post('start_date');
        $end = $request->post('end_date');
        activity('ANALYTICS')
            ->log("Accessed Financial Reports")->causer(request()->user());
        $banks = Banks::all();

//        $users = DB::table('users')
//            ->join('contacts', 'users.id', '=', 'contacts.user_id')
//            ->join('orders', 'users.id', '=', 'orders.user_id')
//            ->select('users.*', 'contacts.phone', 'orders.price')
//            ->get();

        return view('finances.reports')->with([
            'cpage' => "finances",
            'statement' => $request->post('statement'),
            'start_date'=>$request->post('start_date'),
            'end_date'=>$request->post('end_date'),
            'accounts' => Accounts::all(),
            'credits' => Accounts::getAccountBalanceDebits($request->post('start_date'),$request->post('end_date')),
            'debits' => Incomes::accountAll($request->post('start_date'),$request->post('end_date')),
            'admins' => Accounts::getAccountBalanceAdmin($request->post('start_date'),$request->post('end_date')),
        ]);
    }
}
