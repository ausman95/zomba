<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance','church_id','date'
    ];

    public function church (){
        return $this->belongsTo(Church::class,'church_id');
    }
}
