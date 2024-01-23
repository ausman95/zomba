<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance','ministry_id','service_id',
        'date','male','female','visitors','month_id'
    ];
    public function ministry (){
        return $this->belongsTo(Ministry::class,'ministry_id');
    }
    public function service (){
        return $this->belongsTo(Service::class,'service_id');
    }
}
