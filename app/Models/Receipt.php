<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Receipt extends Model
{
    use HasFactory;


    public function getStudentBalances($termID)
    {
        return  DB::table('student_transactions')
            ->join('students', 'students.id', '=', 'student_transactions.student_id')
            ->join('classes', 'classes.id', '=', 'students.class_id')
            ->join('cohorts', 'cohorts.id', '=', 'students.cohort_id')
            ->join('accounts', 'accounts.id', '=', 'student_transactions.account_id')
            ->select(
                'student_transactions.amount',
                'student_transactions.created_at',
                'students.name as student',
                'classes.name as class',
                'accounts.name as account',
                'student_transactions.balance as balance',
                'cohorts.name as cohort',
                'students.id as key',
                'student_transactions.id as id',
            )
            ->where(['student_transactions.soft_delete'=>1])
            ->where('student_transactions.balance','>',0)
            ->where(['student_transactions.term_id'=>$termID])
            ->orderByDesc('student_transactions.id')
            ->get();
    }
    public function getReceiptByTerm($termID)
    {
        return  Payment::join('accounts', 'accounts.id', '=', 'payments.account_id')
            ->join('classes', 'classes.id', '=', 'students.class_id')
            ->join('cohorts', 'cohorts.id', '=', 'students.cohort_id')
            ->join('accounts', 'accounts.id', '=', 'student_transactions.account_id')
            ->select(
                'student_transactions.amount',
                'student_transactions.t_date',
                'students.name as student',
                'classes.name as class',
                'accounts.name as account',
                'student_transactions.balance as balance',
                'cohorts.name as cohort',
                'students.id as key',
                'student_transactions.id as id',
            )
            ->where(['student_transactions.soft_delete'=>1])
            ->where(['student_transactions.term_id'=>$termID])
            ->orderByDesc('student_transactions.id')
            ->get();
    }
    public function getReceiptByTermByAccountByCohort($termID,$accountID,$cohortID)
    {
        return  DB::table('student_transactions')
            ->join('students', 'students.id', '=', 'student_transactions.student_id')
            ->join('classes', 'classes.id', '=', 'students.class_id')
            ->join('cohorts', 'cohorts.id', '=', 'students.cohort_id')
            ->join('accounts', 'accounts.id', '=', 'student_transactions.account_id')
            ->select(
                'student_transactions.amount',
                'student_transactions.created_at',
                'students.name as student',
                'classes.name as class',
                'accounts.name as account',
                'student_transactions.balance as balance',
                'cohorts.name as cohort',
                'students.id as key',
                'student_transactions.id as id',
            )
            ->where(['student_transactions.soft_delete'=>1])
            ->where(['student_transactions.term_id'=>$termID])
            ->where(['students.cohort_id'=>$cohortID])
            ->where(['student_transactions.account_id'=>$accountID])
            ->orderByDesc('student_transactions.id')
            ->get();
    }
}
