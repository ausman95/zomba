<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditorStatement extends Model
{
    use HasFactory;
    public function creditor()
    {
        return $this->belongsTo(Creditor::class, 'creditor_id'); // Add 'creditor_id' if needed
    }
}
