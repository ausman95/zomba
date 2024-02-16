<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialYear extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'start_date','end_date','status'
    ];
    public static function getYear($start_date,$end_date){
        return FinancialYear::where('start_date','<=',$start_date)
            ->where('end_date','>=',$end_date)
            ->first();
    }
}
