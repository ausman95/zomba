<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabourerContract extends Model
{
    use HasFactory;

    protected $fillable = [
        'labourer_id', 'created_by', 'updated_by'
    ];


    public function benefits()
    {
        return $this->hasMany(LabourerContractBenefit::class, 'labourer_contract_id');
    }

    public function labourer()
    {
        return $this->belongsTo(Labourer::class);
    }
}
