<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequisitionApproval extends Model
{
    use HasFactory;
    protected $fillable = [
        'status', 'user_id', 'requisition_id'
    ];
}
