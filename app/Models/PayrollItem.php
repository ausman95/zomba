<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayrollItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'payroll_id',
        'account_id',
        'amount',
        'type',
    ];
    public function payroll(): BelongsTo
    {
        return $this->belongsTo(Payroll::class);
    }

    /**
     * Get the account associated with the PayrollItem.
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Accounts::class);
    }
}
