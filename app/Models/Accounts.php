<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Accounts extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'type'
    ];

    public function getAccountBalance($start_date,$end_date)
    {
        return $value = BankTransaction::where(['account_id'=>$this->id])
            ->whereBetween('created_at',[$start_date,$end_date])
            ->groupBy('account_id')->sum("amount");
    }

    public static  function getAccountBalanceDebits($start_date,$end_date)
    {
        $credits = DB::table('bank_transactions')
            ->join('accounts', 'accounts.id','=','bank_transactions.account_id')
            ->select('accounts.*',DB::raw('SUM(bank_transactions.amount) as amount'))
            ->where(['accounts.type'=>1])
//            ->where('bank_transactions.bank_id','!=',NULL)
            ->whereBetween('bank_transactions.created_at',[$start_date,$end_date])
            ->groupBy('accounts.id')
            ->get();
        return $credits;
    }

    public static  function getAccountBalanceAdmin($start_date,$end_date)
    {
        $credits = DB::table('bank_transactions')
            ->join('accounts', 'accounts.id','=','bank_transactions.account_id')
            ->select('accounts.*',DB::raw('SUM(bank_transactions.amount) as amount'))
            ->where(['accounts.type'=>2])
            ->where('bank_transactions.type','=',2)
            ->whereBetween('bank_transactions.t_date',[$start_date,$end_date])
            ->groupBy('accounts.id')
            ->get();
        return $credits;
    }

    public function incomes()
    {
        return  $this->hasMany(Incomes::class,'account_id');
    }
}
