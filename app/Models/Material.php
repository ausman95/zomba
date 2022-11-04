<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;
    protected $fillable = [
        'name','specifications','units'
    ];
    public function getBalance2($materialID)
    {
        $balance_purchase = Purchase::where(['material_id'=>$materialID,'stores'=>1])->sum("quantity");
        $balance_store_returned = StockFlow::where(['material_id'=>$materialID,'flow'=>4])->sum("quantity");
        $balance_went_to_flow = StockFlow::where(['material_id'=>$materialID])->sum("quantity");
        $balance = $balance_purchase+$balance_store_returned-$balance_went_to_flow;
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
    public function flows()
    {
        $stock = StockFlow::where(['material_id'=>$this->id])->get();
        return $stock;
    }
    public function material()
    {
        return $this->hasMany(StockFlow::class,'material_id','id');
    }
}
