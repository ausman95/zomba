<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditorInvoice extends Model
{
    use HasFactory;
    protected $fillable = [
        'creditor_id', // This is the most important one to add!
        'invoice_number',
        'invoice_date',
        'amount',
        'party',
        'description',
        'created_by',
        'updated_by',
    ];
}
