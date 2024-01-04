<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Month extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'start_date','end_date'
    ];
    public static function getActiveMonth(){
        return Month::where('start_date','<=',date('Y-m-d'))
            ->where('end_date','>=',date('Y-m-d'))
            ->first();
    }
}
