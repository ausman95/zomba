<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectPayment extends Model
{
    use HasFactory;
    protected $fillable = [
        'project_id', 'payment_name',
        'amount','payment_type',
        'balance','created_by','updated_by'
    ];
}
