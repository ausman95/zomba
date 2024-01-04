<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Store extends Model
{
    use HasFactory;
    public function getBalance2($departmentId,$materialID)
    {
        $balance = StockFlow::where(['material_id'=>$materialID,'department_id'=>$departmentId])->get()->last();
        if($balance){
            $balance = $balance->balance;
        }else{
            $balance =0;
        }
        return $balance;
    }
    public function getMaterials()
    {
        $sql =sprintf("SELECT m.id,m.name,s.name as supplier,m.specifications,units,
       sum(quantity) as quantity, sum(amount) as amount
                FROM purchases p join materials m on m.id  = p.material_id
                    JOIN suppliers s on s.id = p.supplier_id
                WHERE p.stores = 1 GROUP BY m.id order by p.created_at desc
                                ");
        $querySet = DB::select($sql);
        $amount = Incomes::hydrate($querySet);
        return $amount;
    }

    public function material()
    {
        return $this->belongsTo(Material::class,'material_id');
    }
    public  function getMaterialInStores()
    {
        $stores = StockFlow::where(['flow'=>1])
            ->groupBy('material_id','department_id')
            ->get();
        if(request()->user()->designation==='clerk'){
            $stores = StockFlow::where(['department_id'=>request()->user()->department_id])
                ->Where(['flow'=>1])
                ->groupBy('material_id')
                ->get();
        }
        return $stores;
    }

    public function flows()
    {
        $stock = StockFlow::where(['material_id'=>$this->id])->get();
        return $stock;
    }
}
