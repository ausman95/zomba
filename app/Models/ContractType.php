<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractType extends Model
{
    use HasFactory;
    protected $fillable = [
        'name','description',
    ];
    public function labourers()
    {
        return $this->hasMany(Contract::class,'contract_type_id');
    }
}
