<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'other_id',
        'amount',
        'created_by',
        'updated_by',
        'account_id',
        'balance',
        'payment_id',
        'transaction_type',
    ];
}
