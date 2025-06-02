<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'type', 'created_by', 'updated_by', 'category_id'
    ];

    public function getAccountBalance($start_date, $end_date)
    {
        return Payment::where(['account_id' => $this->id])
            ->whereBetween('t_date', [$start_date, $end_date])
            ->groupBy('account_id')->sum("amount");
    }

    public static function getAccountBalanceDebits($statement, $start_date, $end_date)
    {
        if ($statement == 3) {
            $credits = Payment::join('accounts', 'accounts.id', '=', 'payments.account_id')
                ->join('categories', 'categories.id', '=', 'accounts.category_id')
                ->select('categories.*', DB::raw('SUM(payments.amount) as amount'))
                ->where(['accounts.type' => 1])
                ->where('categories.status', '=', 2)
                ->where('accounts.soft_delete', '=', 0)
                ->where('accounts.id', '!=', 134)
                ->whereBetween('payments.t_date', [$start_date, $end_date])
                ->groupBy('categories.id')
                ->get();
        } else {
            $credits = DB::table('payments')
                ->join('accounts', 'accounts.id', '=', 'payments.account_id')
                ->select('accounts.*', DB::raw('SUM(payments.amount) as amount'))
                ->where(['accounts.type' => 1])
                ->where('accounts.soft_delete', '=', 0)
                ->where('accounts.id', '!=', 134)
                ->whereBetween('payments.t_date', [$start_date, $end_date])
                ->groupBy('accounts.id')
                ->get();
        }
        return $credits;
    }

    public static function getAccountBalanceByAccountIdDebits($categoryId, $start_date, $end_date)
    {
        return Payment::join('accounts', 'accounts.id', '=', 'payments.account_id')
            ->select('accounts.*', DB::raw('SUM(payments.amount) as amount'))
            ->where(['accounts.category_id' => $categoryId])
            ->where('accounts.soft_delete', '=', 0)
            ->where('accounts.id', '!=', 134)
            ->whereBetween('payments.t_date', [$start_date, $end_date])
            ->groupBy('accounts.id')
            ->get();
    }

    public static function getAccountCatDebits($start_date, $end_date)
    {
        return Accounts::join('payments', 'accounts.id', '=', 'payments.account_id')
            ->join('categories', 'categories.id', '=', 'accounts.category_id')
            ->select('categories.*', DB::raw('SUM(payments.amount) as amount'))
            ->where('categories.status', '=', 2)
            ->where(['accounts.type' => 1])
            ->where('accounts.soft_delete', '=', 0)
            ->where('accounts.id', '!=', 134)
            ->whereBetween('payments.t_date', [$start_date, $end_date])
            ->groupBy('categories.id')
            ->get();
    }

    public static function getAccountCateExpenses($start_date, $end_date)
    {
        return Accounts::join('payments', 'accounts.id', '=', 'payments.account_id')
            ->join('categories', 'categories.id', '=', 'accounts.category_id')
            ->select('categories.*', DB::raw('SUM(payments.amount) as amount'))
            ->where(['accounts.type' => 2])
            ->where('categories.status', '=', 2)
            ->where('accounts.soft_delete', '=', 0)
            ->where('accounts.id', '!=', 134)
            ->whereBetween('payments.t_date', [$start_date, $end_date])
            ->groupBy('categories.id')
            ->get();
    }

    public static function allAccounts($start_date, $end_date)
    {
        return Accounts::join('payments', 'accounts.id', '=', 'payments.account_id')
            ->join('categories', 'categories.id', '=', 'accounts.category_id')
            ->select('accounts.*')
            ->where('categories.status', '=', 2)
            ->where('accounts.id', '!=', 134)
            ->where(['accounts.soft_delete' => 0])
            ->whereBetween('payments.t_date', [$start_date, $end_date])
            ->groupBy('accounts.id')
            ->get();
    }

    public static function allCrAccounts($start_date, $end_date)
    {
        return Accounts::join('payments', 'accounts.id', '=', 'payments.account_id')
            ->join('categories', 'categories.id', '=', 'accounts.category_id')
            ->select('accounts.*')
            ->where('accounts.id', '!=', 134)
            ->where('accounts.type', '=', 1)
            ->where(['accounts.soft_delete' => 0])
            ->whereBetween('payments.t_date', [$start_date, $end_date])
            ->groupBy('accounts.id')
            ->get();
    }

    public static function getAccountBalanceAdmin($statement, $start_date, $end_date)
    {
        if ($statement == 3) {
            $credits = Payment::join('accounts', 'accounts.id', '=', 'payments.account_id')
                ->join('categories', 'categories.id', '=', 'accounts.category_id')
                ->select('categories.*', DB::raw('SUM(payments.amount) as amount'))
                ->where(['accounts.type' => 2])
                ->where('categories.status', '=', 2)
                ->where('accounts.id', '!=', 134)
                ->whereBetween('payments.t_date', [$start_date, $end_date])
                ->groupBy('categories.id')
                ->get();
        } else {
            $credits = DB::table('payments')
                ->join('accounts', 'accounts.id', '=', 'payments.account_id')
                ->join('categories', 'categories.id', '=', 'accounts.category_id')
                ->select('accounts.*', DB::raw('SUM(payments.amount) as amount'))
                ->where(['accounts.type' => 2])
                ->where('categories.status', '=', 2)
                ->where('accounts.id', '!=', 134)
                ->whereBetween('payments.t_date', [$start_date, $end_date])
                ->groupBy('accounts.id')
                ->get();
        }
        return $credits;
    }

    public function incomes()
    {
        return $this->hasMany(Payment::class, 'account_id');
    }

    public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }

    public function userName($id)
    {
        return User::where(['id' => $id])->first()->name;
    }
}
