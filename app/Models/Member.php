<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Member extends Model
{
    use HasFactory;
    protected $fillable = [
        'name','church_id','position_id',
        'phone_number','gender','phone',
        'status','created_by','updated_by'
    ];
    public function getName($id)
    {
        $name = Member::where(['id'=>$id])->latest()->first();
        return  $name->name;
    }
    public function position(): BelongsTo // <--- ADD THIS METHOD
    {
        // Assuming your 'members' table has a 'position_id' foreign key
        // that links to the 'positions' table
        return $this->belongsTo(Position::class, 'position_id');
    }

    public static function getMemberByMinistry($id)
    {
        return Member::where(['member_ministries.ministry_id'=>$id])
            ->join('member_ministries', 'members.id', '=', 'member_ministries.member_id')
            ->orderByDesc('members.name')
            ->get();
    }
    public static function getMemberByMinistryBYGender($min, $gender)
    {
        return Member::where(['member_ministries.ministry_id'=>$min])
            ->where(['members.gender'=>$gender])
            ->join('member_ministries', 'members.id', '=', 'member_ministries.member_id')
            ->orderByDesc('members.name')
            ->get();
    }
    public static function getMemberByMinistryBYChurch($min, $church)
    {
        return Member::where(['member_ministries.ministry_id'=>$min])
            ->where(['members.church_id'=>$church])
            ->join('member_ministries', 'members.id', '=', 'member_ministries.member_id')
            ->orderByDesc('members.name')
            ->get();
    }
    public static function getMemberByMinistryByGenderByChurch($min,$gender,$church)
    {
        return Member::where(['member_ministries.ministry_id'=>$min])
            ->where(['members.gender'=>$gender])
            ->where(['members.church_id'=>$church])
            ->join('member_ministries', 'members.id', '=', 'member_ministries.member_id')
            ->orderByDesc('members.name')
            ->get();
    }
    public function allocations()
    {
        return $this->hasMany(MemberMinistry::class);
    }
    public function church(): BelongsTo
    {
        return $this->belongsTo(Church::class,'church_id');
    }
    public function ministry(): BelongsTo
    {
        return $this->belongsTo(Ministry::class,'ministry_id');
    }
    public function payments()
    {
        return  $this->hasMany(MemberPayment::class,'member_id')->orderByDesc('id');
    }
    public function pledges()
    {
        return  $this->hasMany(Pledge::class,'member_id')->orderByDesc('id');
    }
}
