<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Budget extends Model
{
    use HasFactory;
    protected $fillable = [
        'amount', 'account_id',
        'start_date','end_date','year_id',
        'created_by','updated_by'
    ];
    public function account(): BelongsTo
    {
        return $this->belongsTo(Accounts::class,'account_id');
    }
    public function years(): BelongsTo
    {
        return $this->belongsTo(FinancialYear::class,'year_id');
    }
    public function getAllocated($accountId,$start_date,$end_date)
    {
        $amount = 0;
        $value =   BankTransaction::select(DB::raw('SUM(amount) as total'))
            ->where(['account_id'=>$accountId])
            ->whereBetween('t_date',[$start_date,$end_date])
            ->groupBy('account_id')
            ->first();
         if($value){
             $amount = $value->total;
         }
        return  $amount;
    }

    public function getAllTransactions($accountId,$end,$start)
    {
        $sql =sprintf("SELECT *
                FROM incomes WHERE account_id = $accountId AND
                                DATE(created_at) BETWEEN DATE('".$start."')
                                AND DATE('".$end."')");
        $querySet = DB::select($sql);
        $amount = Incomes::hydrate($querySet);
        return $amount;
    }
    public function project()
    {
        return $this->belongsTo(Project::class,'project_id');
    }
    public static function  userName($id)
    {
        $name =  User::where(['id'=>$id])->first();
        if($name){
            $verified = $name->name;
        }else{
            $verified = "N0T-YET";
        }
        return $verified;
    }
}
