<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Information extends Model
{
    use HasFactory;
    protected $fillable = [
        'vision', 'url',
        'mission','what_we_do',
        'who_we_are','vision','goal',
        'created_by','updated_by'
    ];
}
