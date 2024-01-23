<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Church extends Model
{
    use HasFactory;
    protected $fillable = [
        'name','member_id','zone_id'
    ];
    public function members()
    {
        return $this->hasMany(Member::class,'church_id')->where('soft_delete','=',0);
    }
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'member_id', 'id');
    }
    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class, 'zone_id', 'id');
    }
    public function payments()
    {
        return  $this->hasMany(ChurchPayment::class,'church_id');
    }
}
