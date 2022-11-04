<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChurchPayment extends Model
{
    use HasFactory;
    protected $fillable = [
        'amount','balance','church_id','name','transaction_type','payment_id'
    ];
}
