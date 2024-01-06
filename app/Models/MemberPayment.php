<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class MemberPayment extends Model
{
    use HasFactory;
    protected $fillable = [
        'amount','balance',
        'member_id','name',
        'transaction_type','payment_id',
        'account_id','t_date','month_id'
    ];
    public function account(): BelongsTo
    {
        return $this->belongsTo(Accounts::class,'account_id');
    }
    public function getMemberAmount($memberID,$monthID,$accountID)
    {
        $amount = MemberPayment::where('member_id','=',$memberID)
            ->where('month_id','=',$monthID)
            ->where('account_id','=',$accountID)
            ->select(DB::raw('SUM(amount) as amount'))
            ->groupBy('member_id')
            ->first();
        if(@!$amount){
            return 0;
        }
        return $amount->amount;
    }
}
