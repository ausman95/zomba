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
        return $value = Incomes::where(['account_id'=>$this->id])
            ->whereBetween('created_at',[$start_date,$end_date])
            ->groupBy('account_id')->sum("amount");
    }

    public static  function getAccountBalanceDebits($start_date,$end_date)
    {
        $credits = DB::table('incomes')
            ->join('ministries', 'ministries.id','=','incomes.account_id')
            ->select('ministries.*',DB::raw('SUM(incomes.amount) as amount'))
            ->where(['ministries.type'=>1])
            ->where('incomes.transaction_type','!=',2)
            ->where('incomes.bank_id','!=',NULL)
            ->whereBetween('incomes.created_at',[$start_date,$end_date])
            ->groupBy('ministries.id')
            ->get();
        return $credits;
    }

    public static  function getAccountBalanceAdmin($start_date,$end_date)
    {
        $credits = DB::table('incomes')
            ->join('ministries', 'ministries.id','=','incomes.account_id')
            ->select('ministries.*',DB::raw('SUM(incomes.amount) as amount'))
            ->where(['ministries.type'=>1])
            ->where('incomes.transaction_type','=',2)
            ->where('incomes.project_id','!=',0)
            ->whereBetween('incomes.created_at',[$start_date,$end_date])
            ->groupBy('ministries.id')
            ->get();
        return $credits;
    }

    public function incomes()
    {
        return  $this->hasMany(Incomes::class,'account_id');
    }
}
