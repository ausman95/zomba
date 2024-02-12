<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Zone extends Model
{
    use HasFactory;
    protected $fillable = [
        'name','member_id',
        'created_by','updated_by'
    ];
    public function churches()
    {
        return $this->hasMany(Church::class,'zone_id')->where('soft_delete','=',0);
    }
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'member_id', 'id');
    }
}
