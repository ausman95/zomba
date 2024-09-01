<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Announcement extends Model
{
    use HasFactory;
    protected $fillable = [
        'ministry_id', 'title','body','end_date','start_date','created_by','updated_by','url'
    ];
    public function ministry(): BelongsTo
    {
        return $this->belongsTo(Ministry::class);
    }
}
