<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Member extends Model
{
    use HasFactory;
    protected $fillable = [
        'name','church_id','phone_number','gender','ministry_id','status'
    ];
    public function church(): BelongsTo
    {
        return $this->belongsTo(Church::class,'church_id');
    }
    public function ministry(): BelongsTo
    {
        return $this->belongsTo(Ministry::class,'ministry_id');
    }
    public function payments()
    {
        return  $this->hasMany(MemberPayment::class,'member_id');
    }
}
