<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MinistryPayment extends Model
{
    use HasFactory;
    protected $fillable = [
        'amount','balance',
        'ministry_id','name',
        'transaction_type','payment_id',
        'account_id','created_by','updated_by'
    ];
    public function account(): BelongsTo
    {
        return $this->belongsTo(Accounts::class,'account_id');
    }
}
