<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Assets extends Model
{
    use HasFactory;
    protected $fillable = [
        'depreciation','location',
        'condition','name',
        'life','t_date',
        'category_id','cost',
        'quantity','serial_number',
        'updated_by','created_by'
    ];
    public function category(): BelongsTo
    {
        return $this->belongsTo(Categories::class,'category_id');
    }
    public function getDays($year,$life,$cost,$depreciation,$quantity)
    {
        $start_date = Carbon::parse($year);
        $end_date = Carbon::parse(date('Y-m-d'));
        $years =  @$end_date->diffInYears(@$start_date);
        if($life==0){
            $residual = 0;
        }else{
            @$residual = $quantity*$cost*pow((100-$depreciation)/100,$years);
        }
        return @$residual;
    }
    public function services()
    {
        return $this->hasMany(AssetService::class,'asset_id');
    }
    public function userName($id)
    {
        return User::where(['id'=>$id])->first()->name;
    }
}
