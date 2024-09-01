<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Banks extends Model
{
    use HasFactory;
    protected $fillable = [
        'account_name', 'account_type','account_number','service_centre','bank_name','created_by','updated_by'
    ];
    public function transfersTo()
    {
        return  $this->hasMany(Transfer::class,'to_account_id');
    }
    public function transactions()
    {
        return  $this->hasMany(BankTransaction::class,'bank_id')->orderBy('t_date','ASC');
    }
    public function incomes()
    {
        return  $this->hasMany(Incomes::class,'bank_id');
    }


    public function getBalance()
    {

        $bal = BankTransaction::where(['bank_id'=>$this->id])->latest()->first();
        @$balance = $bal->balance;
        if(!$balance){
            $balance = 0;
        }
        return $balance;

    }
}
