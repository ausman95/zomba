<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MinistryPayment extends Model
{
    use HasFactory;
    protected $fillable = [
        'amount','balance','ministry_id','name','transaction_type','payment_id'
    ];
}
