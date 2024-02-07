<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Categories extends Model
{
    use HasFactory;
    protected $fillable = [
        'name','status','created_by','updated_by'
    ];
    public function assets()
    {
        return $this->hasMany(Assets::class,'category_id','id');
    }

    public function userName($id)
    {
        return User::where(['id'=>$id])->first()->name;
    }
}
