<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Leave extends Model
{
    use HasFactory;
    protected $fillable = [
        'start_date','end_date','employee_id', 'leave_days','leave_balance','compassionate_days','compassionate_balance'
    ];

    public function getDays()
    {
      $days = LeaveSetting::orderBy('id','desc')->first();
      if($days){
          return @$days->days_per_month*12;
      }else{
          return false;
      }

    }
    public function getCompassionate()
    {
        $days = LeaveSetting::orderBy('id','desc')->first();
        return @$days->compassionate_days_per_year;
    }
    public function getBalanceNormal($id,$start_date=false,$end_date=false)
    {
        if(!$start_date){
            $data = FinancialYear::where(['status' => 1])->first();

            $start_date = $data->start_date;
            $end_date = $data->end_date;
        }
        $days = Leave::where(['employee_id'=>$id])
            ->whereBetween('created_at', [$start_date, $end_date])
            ->orderBy('id','desc')->first();
        if($days){
            return $days->leave_balance;
        }else{
            return false;
        }

    }
    public function getBalanceCompassionate($id,$start_date=false,$end_date=false)
    {
        if(!$start_date){
            $data = FinancialYear::where(['status' => 1])->first();

            $start_date = $data->start_date;
            $end_date = $data->end_date;
        }
        $days = Leave::where(['employee_id'=>$id])
            ->whereBetween('created_at', [$start_date, $end_date])
            ->orderBy('id','desc')->first();
        if($days){
            return $days->compassionate_balance;
        }else{
            return false;
        }

    }
    public function labourer(): BelongsTo
    {
        return $this->belongsTo(Labourer::class,'employee_id');
    }
}
