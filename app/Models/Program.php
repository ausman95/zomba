<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Program extends Model
{
    use HasFactory;
    protected $fillable = [
        'preacher_id',
        'facilitator_id',
        't_date',
        'venue',
        'church_id',
        'created_by',
        'updated_by'
    ];
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class,'facilitator_id','id');
    }
    public function church(): BelongsTo
    {
        return $this->belongsTo(Church::class,'church_id','id');
    }
    public function members(): BelongsTo
    {
        return $this->belongsTo(Member::class,'preacher_id','id');
    }

}
