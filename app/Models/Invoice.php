<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    public function creditor()
    {
        return $this->belongsTo(Creditor::class);
    }

    /**
     * Get the debtor that owns the invoice.
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
