<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentRequisitionItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'amount', 'description', 'department_requisition_id','reason','personale'
    ];
    public function getPrice($id){
        $price = Price::where(['material_id'=>$id])->first();
        if($price) {
            return $price->price;
        }else{
            return false;
        }
    }
    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function requisition()
    {
        return $this->belongsTo(Requisition::class);
    }
}
