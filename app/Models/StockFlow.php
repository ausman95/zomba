<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockFlow extends Model
{
    use HasFactory;
    protected $fillable = [
        'material_id', 'quantity','amount','flow','department_id','balance','status','activity'
    ];
    public function flows()
    {
        return  $this->hasMany(StockFlow::class,'material_id');
    }
    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class, 'material_id', 'id');
    }
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }
    public function getBalance($materialID)
    {
        $balance_purchase = Purchase::where(['material_id'=>$materialID,'stores'=>1])->sum("quantity");
        $balance1 = StockFlow::where(['material_id'=>$materialID,'flow'=>1])->sum("quantity");
        $balance2 = StockFlow::where(['material_id'=>$materialID,'flow'=>2])->sum("quantity");
        $balance3= StockFlow::where(['material_id'=>$materialID,'flow'=>3])->sum("quantity");
        $balance4 = StockFlow::where(['material_id'=>$materialID,'flow'=>4])->sum("quantity");

        $balance = $balance_purchase+$balance4-($balance2+$balance3+$balance1);
        return $balance;
    }
    public function getPrice($id){
        $price = Price::where(['material_id'=>$id])->first();
        if($price) {
            return $price->price;
        }else{
            return false;
        }
    }
    public function getBalance2($departmentId,$materialID)
    {
        $balance = StockFlow::where(['material_id'=>$materialID,'department_id'=>$departmentId])->orderBy('id','DESC')->first();
        if($balance){
            $balance = $balance->balance;
        }else{
            $balance =0;
        }
        return $balance;
    }
//    public function getBalanceStores($materialID)
//    {
//        $balance_store_returned = StockFlow::where(['material_id'=>$materialID,'flow'=>4])->sum("quantity");
//        return $balance_store_returned;
//    }
}
