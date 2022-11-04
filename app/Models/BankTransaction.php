<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BankTransaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'amount', 'account_name','bank_id','balance','type','method'
    ];
    public function banks(): BelongsTo
    {
        return $this->belongsTo(Banks::class,'bank_id');
    }
}
