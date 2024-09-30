<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class BankTransaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'amount','bank_id',
        'balance','type','t_date',
        'method','account_id','specification',
        'description','created_by','updated_by'
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
