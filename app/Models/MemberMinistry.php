<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberMinistry extends Model
{
    use HasFactory;
    protected $fillable = [
        'ministry_id','member_id',
    ];
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class,'member_id');
    }
    public function ministry(): BelongsTo
    {
        return $this->belongsTo(Ministry::class,'ministry_id');
    }
}
