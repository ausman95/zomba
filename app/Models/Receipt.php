<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Receipt extends Model
{
    use HasFactory;

//    public function getMonthsTransaction($start,$end,$accountID)
//    {
//        return  MemberPayment::where(['member_payments.account_id'=>$accountID])
//            ->whereBetween('member_payments.t_date',[$start,$end])
//            ->join('months', 'months.id', '=', 'member_payments.month_id')
//            ->groupBy(['months.id'])
//            ->get();
//    }

    public function getMonthsTransaction($start, $end, $accountID)
    {
        return MemberPayment::where(['member_payments.account_id' => $accountID])
            ->whereBetween('member_payments.t_date', [$start, $end])
            ->join('months', function ($join) {
                $join->on('member_payments.t_date', '>=', 'months.start_date')
                    ->on('member_payments.t_date', '<=', 'months.end_date');
            })
//            ->select('months.id as month_id', 'months.name as month_name')
            ->groupBy('months.id', 'months.name')
            ->orderBy('months.id')
            ->get();
    }
    public function getMembers($start,$end,$accountID)
    {
        return  MemberPayment::where(['account_id'=>$accountID])
            ->whereBetween('member_payments.t_date',[$start,$end])
            ->join('members', 'members.id', '=', 'member_payments.member_id')
            ->join('positions', 'positions.id', '=', 'members.position_id')
            ->select(
                'members.name as pastor',
                'positions.name as position',
                'members.id as member_id',
                'member_payments.account_id as account_id',
                DB::raw('SUM(member_payments.amount) as amount')
            )
            ->groupBy(['members.id'])
            ->orderByDesc(DB::raw('SUM(member_payments.amount)'))
            ->get();
    }

}
