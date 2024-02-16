<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Accounts extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'type','created_by','updated_by','category_id'
    ];

    public function getAccountBalance($start_date,$end_date)
    {
        return $value = BankTransaction::where(['account_id'=>$this->id])
            ->whereBetween('created_at',[$start_date,$end_date])
            ->groupBy('account_id')->sum("amount");
    }

    public static  function getAccountBalanceDebits($statement,$start_date,$end_date)
    {
        if($statement==3){
            $credits = BankTransaction::join('accounts', 'accounts.id', '=', 'bank_transactions.account_id')
                ->join('categories', 'categories.id', '=', 'accounts.category_id')
                ->select('categories.*', DB::raw('SUM(bank_transactions.amount) as amount'))
                ->where(['accounts.type' => 1])
                ->where('accounts.soft_delete', '=', 0)
                ->where('accounts.id', '!=', 134)
                ->whereBetween('bank_transactions.created_at', [$start_date, $end_date])
                ->groupBy('categories.id')
                ->get();
        }else {
            $credits = DB::table('bank_transactions')
                ->join('accounts', 'accounts.id', '=', 'bank_transactions.account_id')
                ->select('accounts.*', DB::raw('SUM(bank_transactions.amount) as amount'))
                ->where(['accounts.type' => 1])
                ->where('accounts.soft_delete', '=', 0)
                ->where('accounts.id', '!=', 134)
                ->whereBetween('bank_transactions.created_at', [$start_date, $end_date])
                ->groupBy('accounts.id')
                ->get();
        }
        return $credits;
    }
    public static  function getAccountBalanceByAccountIdDebits($categoryId,$start_date,$end_date)
    {
            return BankTransaction::join('accounts', 'accounts.id', '=', 'bank_transactions.account_id')
                ->select('accounts.*', DB::raw('SUM(bank_transactions.amount) as amount'))
                ->where(['accounts.category_id' => $categoryId])
                ->where('accounts.soft_delete', '=', 0)
                ->where('accounts.id', '!=', 134)
                ->whereBetween('bank_transactions.created_at', [$start_date, $end_date])
                ->groupBy('accounts.id')
                ->get();
    }
    public static  function getAccountCatDebits($start_date,$end_date)
    {
            return Accounts::join('bank_transactions', 'accounts.id', '=', 'bank_transactions.account_id')
                ->join('categories', 'categories.id', '=', 'accounts.category_id')
                ->select('categories.*', DB::raw('SUM(bank_transactions.amount) as amount'))
                ->where(['accounts.type' => 1])
                ->where('accounts.soft_delete', '=', 0)
                ->where('accounts.id', '!=', 134)
                ->whereBetween('bank_transactions.created_at', [$start_date, $end_date])
                ->groupBy('categories.id')
                ->get();
    }
    public static  function getAccountCateExpenses($start_date,$end_date)
    {
        return Accounts::join('bank_transactions', 'accounts.id', '=', 'bank_transactions.account_id')
            ->join('categories', 'categories.id', '=', 'accounts.category_id')
            ->select('categories.*', DB::raw('SUM(bank_transactions.amount) as amount'))
            ->where(['accounts.type' => 2])
            ->where('accounts.soft_delete', '=', 0)
            ->where('accounts.id', '!=', 134)
            ->whereBetween('bank_transactions.created_at', [$start_date, $end_date])
            ->groupBy('categories.id')
            ->get();
    }
    public static  function allAccounts($start_date,$end_date)
    {
        return Accounts::join('bank_transactions', 'accounts.id','=','bank_transactions.account_id')
            ->select('accounts.*')
            ->where('accounts.id','!=',134)
            ->where(['soft_delete'=>0])
            ->whereBetween('bank_transactions.created_at',[$start_date,$end_date])
            ->groupBy('accounts.id')
            ->get();
    }
    public static  function allCrAccounts($start_date,$end_date)
    {
        return Accounts::join('payments', 'accounts.id','=','payments.account_id')
            ->select('accounts.*')
            ->where('accounts.id','!=',134)
            ->where('accounts.type','=',1)
            ->where(['soft_delete'=>0])
            ->whereBetween('payments.t_date',[$start_date,$end_date])
            ->groupBy('accounts.id')
            ->get();
    }

    public static  function getAccountBalanceAdmin($statement,$start_date,$end_date)
    {
        if($statement==3){
            $credits = BankTransaction::join('accounts', 'accounts.id', '=', 'bank_transactions.account_id')
                ->join('categories', 'categories.id', '=', 'accounts.category_id')
                ->select('categories.*', DB::raw('SUM(bank_transactions.amount) as amount'))
                ->where(['accounts.type' => 2])
                ->where('accounts.id', '!=', 134)
//                ->where('bank_transactions.type', '=', 2)
                ->whereBetween('bank_transactions.t_date', [$start_date, $end_date])
                ->groupBy('categories.id')
                ->get();
        }else {
            $credits = DB::table('bank_transactions')
                ->join('accounts', 'accounts.id', '=', 'bank_transactions.account_id')
                ->select('accounts.*', DB::raw('SUM(bank_transactions.amount) as amount'))
                ->where(['accounts.type' => 2])
                ->where('accounts.id', '!=', 134)
//                ->where('bank_transactions.type', '=', 2)
                ->whereBetween('bank_transactions.t_date', [$start_date, $end_date])
                ->groupBy('accounts.id')
                ->get();
        }
        return $credits;
    }

    public function incomes()
    {
        return  $this->hasMany(BankTransaction::class,'account_id');
    }
    public function category()
    {
        return  $this->belongsTo(Categories::class,'category_id');
    }
    public function userName($id)
    {
        return User::where(['id'=>$id])->first()->name;
    }
}
