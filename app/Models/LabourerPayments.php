<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LabourerPayments extends Model
{
    use HasFactory;
    protected $fillable = [
        'account_id','amount',
        'labourer_id','expense_name',
        'method','project_id','date',
        'balance','type','status',
        'description','created_by','updated_by'
    ];
    public function labourer(): BelongsTo
    {
        return $this->belongsTo(Labourer::class, 'labourer_id', 'id');
    }
    public function account(): BelongsTo
    {
        return $this->belongsTo(Accounts::class, 'account_id', 'id');
    }
}
