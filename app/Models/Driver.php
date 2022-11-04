<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Driver extends Model
{
    use HasFactory;
    protected $fillable = [
        'account_id', 'labourer_id','start_date','amount','end_date',
    ];
    public function labourer(): BelongsTo
    {
        return $this->belongsTo(Labourer::class,'labourer_id');
    }
    public function account(): BelongsTo
    {
        return $this->belongsTo(Accounts::class,'account_id');
    }
    public function getDays($start,$end)
    {
        $start_date = Carbon::parse($start);
        $end_date = Carbon::parse($end);
        return $start_date->diffInDays($end_date);
    }
}
