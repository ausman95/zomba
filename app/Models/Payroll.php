<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'labourer_id',
        'month_id','payroll_date',
        'total_amount','created_by','updated_by',
        'status'
    ];

    public function labourer(): BelongsTo
    {
        return $this->belongsTo(Labourer::class);
    }

    public function month(): BelongsTo
    {
        return $this->belongsTo(Month::class);
    }
    public function payrollItems(): HasMany
    {
        return $this->hasMany(PayrollItem::class);
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
