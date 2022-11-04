<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ministry extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];
    public function members()
    {
        return $this->hasMany(Member::class,'ministry_id');
    }
    public function payments()
    {
        return  $this->hasMany(MinistryPayment::class,'ministry_id');
    }
}
