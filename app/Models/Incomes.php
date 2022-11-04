<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Incomes extends Model
{
    use HasFactory;

    protected $fillable = [
        'method','account_id', 'project_id', 'amount', 'description', 'bank_id','transaction_type','cheque_number'
    ];
    public function getPrice($id){
        $price = Price::where(['material_id'=>$id])->first();
        if($price) {
            return $price->price;
        }else{
            return false;
        }
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

    public function getAccountBalance($start_date,$end_date)
    {
        return $value = Incomes::where(['account_id'=>$this->id])->whereBetween('created_at',[$start_date,$end_date])->groupBy('account_id')->sum("amount");
//        $value_2 = Incomes::where(['account_id'=>$this->id,'method'=>2])->sum("amount");
//        $balance = $value - $value_2 ;
//        return  $balance;
    }
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    public function account()
    {
        return $this->belongsTo(Accounts::class);
    }
    public function getQuantity($materialID)
    {
        $balance_purchase = Purchase::where(['material_id'=>$materialID,'stores'=>1])->sum("quantity");
        $balance_store_returned = StockFlow::where(['material_id'=>$materialID,'flow'=>4])->sum("quantity");
        $balance_went_to_flow = StockFlow::where(['material_id'=>$materialID,'flow'=>[1,2,3]])->sum("quantity");
        $balance = $balance_purchase+$balance_store_returned-$balance_went_to_flow;
        return $balance;
    }
}
