<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Debtor extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'phone_number',
        'address',
        'email',
        'created_by',
        'updated_by',
    ];

    // Relationships (if needed) - Example: A creditor has many creditor statements
    public function latestStatement()
    {
        return $this->hasOne(DebtorStatement::class)->orderBy('created_at', 'desc');
    }

    public function statements() {
        return $this->hasMany(DebtorStatement::class)->orderBy('created_at', 'desc'); // Order statements by date
    }
    public function invoices()
    {
        return $this->hasMany(CreditorInvoice::class);
    }
}
