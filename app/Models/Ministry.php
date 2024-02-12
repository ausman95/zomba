<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ministry extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'created_by',
        'updated_by'
    ];
    public function members()
    {
        return $this->hasMany(MemberMinistry::class,'ministry_id');
    }
    public function payments()
    {
        return  $this->hasMany(MinistryPayment::class,'ministry_id');
    }
}
