<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Supplier extends Model
{
    use HasFactory;
    protected $fillable = [
      'name','phone_number','location'
    ];
    public function payments()
    {
        return  $this->hasMany(SupplierPayments::class,'supplier_id')->orderByDesc('id');
    }

    public function getAmountBalance()
    {
        $balance_bank_dr = SupplierPayments::where(['supplier_id'=>$this->id,'method'=>1,'transaction_type'=>1])->sum("amount");
        $balance_bank_credit = SupplierPayments::where(['supplier_id'=>$this->id,'method'=>2,'transaction_type'=>2])->sum("amount");
        $balance_bank_cheque = SupplierPayments::where(['supplier_id'=>$this->id,'method'=>3,'transaction_type'=>1])->sum("amount");

        $balance = $balance_bank_credit-$balance_bank_dr-$balance_bank_cheque;
        return $balance;
    }
    public function getBalance($id)
    {
        $balance =SupplierPayments::where(['supplier_id'=>$id])->orderBy('id','desc')->first();
        return @$balance->balance;

    }
}
