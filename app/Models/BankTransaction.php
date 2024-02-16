<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BankTransaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'amount','bank_id',
        'balance','type',
        'method','account_id',
        'description','created_by','updated_by'
    ];
    public function banks(): BelongsTo
    {
        return $this->belongsTo(Banks::class,'bank_id');
    }
    public function account(): BelongsTo
    {
        return $this->belongsTo(Accounts::class,'account_id');
    }
//    public function getBudgetByAccountId($accountId,$start_date,$end_date)
//    {
//        $start_date = Carbon::parse($serviceDueDate);
//        $end_date = Carbon::parse(date('Y-m-d'));
//         $start_date->diffInDays($end_date);
//
//         $value = Budget::where(['account_id'=>$accountId])
//            ->whereBetween('created_at',[$start_date,$end_date])
//            ->groupBy('account_id')->sum("amount");
//    }
}
