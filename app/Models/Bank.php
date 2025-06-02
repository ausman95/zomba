<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Bank extends Model
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
        return $this->hasMany(Payment::class, 'bank_id')->where('status', 0)
            ->orderBy('t_date', 'ASC');
    }

    public function incomes()
    {
        return  $this->hasMany(Incomes::class,'bank_id');
    }


    public function getBalance()
    {
        // Calculate the sum of amounts where type = 1
        $revenue = Payment::where('bank_id', $this->id)
            ->where('status', 1)
            ->where('type', 1)
            ->sum('amount');

        // Calculate the sum of amounts where type = 2
        $expenses = Payment::where('bank_id', $this->id)
            ->where('type', 2)
            ->where('status', 1)
            ->sum('amount');

        // Return the difference between revenue and expenses
        return $revenue - $expenses;
    }

}
