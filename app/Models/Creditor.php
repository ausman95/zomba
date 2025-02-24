<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Creditor extends Model
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

    public function invoices()
    {
        return $this->hasMany(CreditorInvoice::class);
    }
    public function latestStatement()
    {
        return $this->hasOne(CreditorStatement::class)->orderBy('created_at', 'desc');
    }

    public function statements() {
        return $this->hasMany(CreditorStatement::class)->orderBy('created_at', 'desc'); // Order statements by date
    }

    public function creator() {
        return $this->belongsTo(User::class, 'created_by'); // Define creator relationship
    }

    public function updater() {
        return $this->belongsTo(User::class, 'updated_by'); // Define updater relationship
    }
}
