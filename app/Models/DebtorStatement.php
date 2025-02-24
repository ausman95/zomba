<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DebtorStatement extends Model
{
    use HasFactory;
    protected $fillable = [
        'debtor_id',
        'account_id',
        'amount',
        'type',
        'description',
        'balance',
        'created_by',
        'updated_by',
        'debtor_invoice_id',
    ];
    public function debtor()
    {
        return $this->belongsTo(Debtor::class, 'debtor_id'); // Add 'debtor_id' if needed
    }
}
