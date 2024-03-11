<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HomeAttendance extends Model
{
    use HasFactory;
    protected $fillable = [
        'member_id',
        'status',
        'church_id',
        'created_by',
        'updated_by'
    ];
    public function church(): BelongsTo
    {
        return $this->belongsTo(Church::class,'church_id','id');
    }
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class,'member_id');
    }
}
