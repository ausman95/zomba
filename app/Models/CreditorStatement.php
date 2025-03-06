<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditorStatement extends Model
{
    use HasFactory;
    protected $fillable = [
        'creditor_id',
        'account_id',
        'amount',
        'type',
        'description',
        'balance',
        'created_by',
        'updated_by',
        'creditor_invoice_id',
        // Add any other fields you want to be mass-assignable here
    ];

    public function creditor()
    {
        return $this->belongsTo(Creditor::class, 'creditor_id'); // Add 'creditor_id' if needed
    }
}
