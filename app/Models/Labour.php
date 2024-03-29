<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Labour extends Model
{
    use HasFactory;
    protected $fillable = [
        'name'
    ];
    public function labourers()
    {
        return $this->hasMany(Labourer::class,'labour_id')
            ->where(['soft_delete'=>0])->orderBy('id','desc');
    }
}
