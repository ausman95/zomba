<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Purchase extends Model
{
    use HasFactory;
    protected $fillable = [
        'date',  'material_id',
        'supplier_id','quantity',
        'quantity','amount','payment_type',
        'bank_id','stores','account_id',
        'reference','department_id'
    ];

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class, 'material_id', 'id');
    }
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }
    public function account(): BelongsTo
    {
        return $this->belongsTo(Accounts::class, 'account_id', 'id');
    }
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }
    public function bank(): BelongsTo
    {
        return $this->belongsTo(Banks::class, 'bank_id', 'id');
    }

}
