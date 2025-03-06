<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'labourer_id', 'loan_amount',
        'loan_start_date', 'loan_duration_months',
        'monthly_repayment', 'remaining_balance',
        'account_id',
        'created_by', 'updated_by'
    ];

    // Relationships

    /**
     * Get the labourer associated with the loan.
     */
    public function labourer()
    {
        return $this->belongsTo(Labourer::class, 'labourer_id');
    }
    public function account()
    {
        return $this->belongsTo(Accounts::class, 'account_id');
    }

    /**
     * Get the user who created the loan.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the loan.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the labourer payments associated with this loan.
     */
    public function labourerPayments()
    {
        return $this->hasMany(LabourerPayments::class, 'loan_id');
    }
}
