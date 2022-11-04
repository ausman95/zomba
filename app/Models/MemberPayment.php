<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberPayment extends Model
{
    use HasFactory;
    protected $fillable = [
        'amount','balance','member_id','name','transaction_type','payment_id'
    ];
}
