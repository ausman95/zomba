<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;
    protected $fillable = [
        'title', 'url',
        'created_by','updated_by'
    ];

    public function paragraph()
    {
        return $this->hasMany(Paragraph::class,'new_id');
    }
}
