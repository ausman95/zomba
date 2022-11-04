<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contract extends Model
{
    use HasFactory;
    protected $fillable = [
        'labourer_id','contract_type_id','start_date','end_date','salary','url','status'
    ];


    public function contractType(): BelongsTo
    {
        return $this->belongsTo(ContractType::class,'contract_type_id');
    }
    public function labourer(): BelongsTo
    {
        return $this->belongsTo(Labourer::class,'labourer_id');
    }
    public function getAgeAttribute()
    {
        $start_date = Carbon::parse(now());
        $end_date = Carbon::parse($this->end_date);
            return $end_date->diffInDays($start_date)  ;

    }
}
