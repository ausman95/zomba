<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    use HasFactory;
    protected $fillable = [
        'name','status'
    ];
    public function assets()
    {
        return $this->hasMany(Assets::class,'category_id','id');
    }
}
