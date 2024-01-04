<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberPayment extends Model
{
    use HasFactory;
    protected $fillable = [
        'amount','balance','member_id','name','transaction_type','payment_id','account_id'
    ];
    public function account(): BelongsTo
    {
        return $this->belongsTo(Accounts::class,'account_id');
    }
}
