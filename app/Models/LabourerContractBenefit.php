<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabourerContractBenefit extends Model
{
    use HasFactory;

    protected $fillable = [
        'labourer_contract_id', // Add this line
        'account_id',
        'amount',
        'created_by',
        'updated_by',
    ];

    public function contract()
    {
        return $this->belongsTo(LabourerContract::class);
    }

    public function account()
    {
        return $this->belongsTo(Accounts::class);
    }
}
