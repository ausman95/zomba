<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'designation',
        'password',
        'position',
        'level',
        'department_id',
        'last_login_at',
        'last_login_ip_address',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function getName($id){
        $price = User::where(['id'=>$id])->first();
        return $price->last_name.' '.$price->first_name;
    }
    public function getNameAttribute()
    {
        $full_name = $this->first_name.' '.$this->last_name;

        return ucwords($full_name);
    }
    public function getTimeAttribute()
    {
        $start_date = Carbon::parse($this->last_login_at);
        $end_date = Carbon::parse(now());
         $hours = $end_date->diffInMinutes($start_date);
        if($hours >=1 && $hours <60){
        return $hours." minutes ago";
        }
        elseif($hours<1){
            return $end_date->diffInSeconds($start_date)." Seconds ago";
        }
        elseif($hours>=60 && $hours<1440){
            return $end_date->diffInHours($start_date)." hours ago";
        }
        elseif($hours>=1440){
            return $end_date->diffInDays($start_date)." days ago";
        }

    }
}
