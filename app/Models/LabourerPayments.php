<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LabourerPayments extends Model
{
    use HasFactory;
    protected $fillable = [
        'amount','labourer_id','expense_name','method','project_id','balance','type','description'
    ];
    public function labourer(): BelongsTo
    {
        return $this->belongsTo(Labourer::class, 'labourer_id', 'id');
    }
    public function expense(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'expenses_id', 'id');
    }
}
