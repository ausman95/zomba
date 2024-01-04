<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transfer extends Model
{
    use HasFactory;
    protected $fillable = [
        'cheque_number', 'amount','to_account_id','from_account_id'
    ];

    public function accountTo(): BelongsTo
    {
        return $this->belongsTo(Banks::class, 'to_account_id', 'id');
    }
    public function accountFrom()
    {
        return  $this->belongsTo(Banks::class,'from_account_id','id');
    }

}
