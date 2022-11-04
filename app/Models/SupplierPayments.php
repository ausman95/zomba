<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplierPayments extends Model
{
    use HasFactory;
    protected $fillable = [
        'amount','supplier_id','address','expenses_id','transaction_type','method','balance'
    ];
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }
    public function expense(): BelongsTo
    {
        return $this->belongsTo(Expenses::class, 'expenses_id', 'id');
    }
}
