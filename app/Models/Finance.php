<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Finance extends Model
{
    use HasFactory;
    public function getAccountBalance($start_date,$end_date)
    {
        return $value = Incomes::where(['account_id'=>$this->id])->whereBetween('created_at',[$start_date,$end_date])->groupBy('account_id')->sum("amount");
//        $value_2 = Incomes::where(['account_id'=>$this->id,'method'=>2])->sum("amount");
//        $balance = $value - $value_2 ;
//        return  $balance;
    }
    public static  function accountAll($start_date,$end_date)
    {
        $credits = DB::table('incomes')
            ->join('accounts', 'accounts.id','=','incomes.account_id')
            ->select('accounts.*',DB::raw('SUM(incomes.amount) as amount'))
            ->where(['accounts.type'=>2])
            ->where('incomes.transaction_type','!=',2)
            ->whereBetween('incomes.created_at',[$start_date,$end_date])
            ->groupBy('accounts.id')
            ->get();
        return $credits;
    }

}
