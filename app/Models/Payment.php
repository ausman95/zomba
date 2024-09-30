<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'account_id', 'bank_id',
        'name','amount',
        'payment_method','reference',
        'type','t_date','month_id','specification',
        'created_by','updated_by','pledge'
    ];
    public function getBudgetByAccountId($status,$accountId,$start_date,$end_date)
    {

        // financial year get number of days
        $year = FinancialYear::getYear($start_date,$end_date);
        $fin_start_date = Carbon::parse($year->start_date);
        $fin_end_date = Carbon::parse($year->end_date);
        $fin_day_different  = $fin_start_date->diffInDays($fin_end_date);

        // get number of selected day
        $select_start_date = Carbon::parse($start_date);
        $select_end_date = Carbon::parse($end_date);
        $different_selected_days  = $select_start_date->diffInDays($select_end_date);

        $value_amount = 0;
        if($status==1) {
            $value = Budget::where(['account_id' => $accountId])
                ->where(['year_id' => $year->id])
                ->select(
                    DB::raw('SUM(amount) as amount')
                )
                ->groupBy('account_id')
                ->first();
        }else{
            $value = Budget::where(['category_id' => $accountId])
                ->join('accounts', 'accounts.id', '=', 'budgets.account_id')
                ->where(['year_id' => $year->id])
                ->select(
                    DB::raw('SUM(amount) as amount')
                )
                ->groupBy('category_id')
                ->first();
        }
        if($value){
            if($fin_day_different<$different_selected_days){
                $value_amount = $value->amount*$fin_day_different/$different_selected_days;
            }else{
                $value_amount = $value->amount*$different_selected_days/$fin_day_different;
            }
        }

        // find budget proportional

        return $value_amount;
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Accounts::class,'account_id');
    }
    public function bank(): BelongsTo
    {
        return $this->belongsTo(Banks::class,'bank_id');
    }

    public function getMember($id)
    {
        $name = MemberPayment::where(['payment_id'=>$id])->first();
        $member_id=  $name->member_id;
        $name = Member::where(['id'=>$member_id])->first();
        return $name->name;
    }
    public function getMinistry($id)
    {
        $name = MinistryPayment::where(['payment_id'=>$id])->first();
        $ministry_id =  $name->ministry_id;
        $name = Ministry::where(['id'=>$ministry_id])->first();
        return $name->name;
    }
    public function getHome($id)
    {
        $name = ChurchPayment::where(['payment_id'=>$id])->first();
        $home_id=  $name->church_id;
        $name = Church::where(['id'=>$home_id])->first();
        return $name->name;
    }

    public function getMemberHome($id)
    {
        $name = MemberPayment::where(['payment_id'=>$id])->first();
        $member_id=  $name->member_id;
        $name = Member::where(['id'=>$member_id])->first();
        $id =  $name->church_id;
        $name = Church::where(['id'=>$id])->first();
        return $name->name;
    }
    public function getMemberMinistry($id)
    {
        $name = MemberPayment::where(['payment_id'=>$id])->first();
        $member_id=  $name->member_id;
        $name = Member::where(['id'=>$member_id])->first();
        $id =  $name->church_id;
        $name = Ministry::where(['id'=>$id])->first();
        return $name->name;
    }
}
