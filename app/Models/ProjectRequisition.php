<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectRequisition extends Model
{
    use HasFactory;
    protected $fillable = [
        'quantity', 'material_id', 'requisition_id','reason'
    ];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function requisition()
    {
        return $this->belongsTo(Requisition::class);
    }
}
