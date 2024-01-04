<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BankTransaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'amount','bank_id','balance','type','method','account_id','description'
    ];
    public function banks(): BelongsTo
    {
        return $this->belongsTo(Banks::class,'bank_id');
    }
    public function account(): BelongsTo
    {
        return $this->belongsTo(Accounts::class,'account_id');
    }
}
