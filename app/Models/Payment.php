<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'account_id', 'bank_id','name','amount','payment_method','reference','type'
    ];
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
