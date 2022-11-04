<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'status', 'labourer_id'];

    public function getNumberOfDays($id,$start_date,$end_date){
        $present = Attendance::where(['labourer_id'=>$id])
            ->whereBetween('created_at', [$start_date, $end_date])
            ->get();
        $absent = Attendance::where(['labourer_id'=>$id,'status'=>'0'])
            ->whereBetween('created_at', [$start_date, $end_date])
            ->get();
        return count($present)-count($absent);
    }
    public function getRate($id){
        $salary = Contract::where(['labourer_id'=>$id])
            ->first();
        if($salary){
            $rate = $salary->salary;
        }else{
            $rate = 0;
        }
        return $rate;
    }
    public function getAdvance($id,$start_date,$end_date){
        $advance = LabourerPayments::where(['labourer_id'=>$id])
            ->whereBetween('created_at', [$start_date, $end_date])
            ->first();
        if($advance){
            $rate = $advance->amount;
        }else{
            $rate = 0;
        }
        return $rate;
    }

    public static  function getAttendance($start_date,$end_date)
    {

        if(request()->user()->designation==='clerk'){
            $credits = DB::table('attendances')
                ->join('labourers', 'labourers.id', '=', 'attendances.labourer_id')
                ->join('labours', 'labours.id', '=', 'labourers.labour_id')
                ->join('departments', 'departments.id', '=', 'labourers.department_id')
                ->select('departments.name as department', 'labours.name as labour', 'labourers.*', DB::raw('count(attendances.id) as days'))
                ->where('attendances.status', '!=', '0')
                ->where('labourers.department_id', '=', request()->user()->department_id)
                ->whereBetween('attendances.date', [$start_date, $end_date])
                ->groupBy('labourers.id')
                ->get();
        }else {
            $credits = DB::table('attendances')
                ->join('labourers', 'labourers.id', '=', 'attendances.labourer_id')
                ->join('labours', 'labours.id', '=', 'labourers.labour_id')
                ->join('departments', 'departments.id', '=', 'labourers.department_id')
                ->leftJoin('contracts', 'labourers.id', '=', 'contracts.labourer_id')
                ->leftJoin('labourer_payments', 'labourers.id', '=', 'labourer_payments.labourer_id')
                ->select('contracts.salary as salary',
                    'departments.name as department',
                    'labours.name as labour',
                    'labourers.*',
                    DB::raw('sum(labourer_payments.amount) as amount'),
                    DB::raw('count(attendances.id) as days'))
                ->where('attendances.status', '!=', '0')
                ->whereBetween('attendances.date', [$start_date, $end_date])
                ->groupBy('labourers.id')
                ->get();
        }
        return $credits;
    }
    public function labourer()
    {
        return $this->belongsTo(Labourer::class);
    }

    public function getStatusToHumanAttribute()
    {
        $status = $this->status;

        if ($status == '0') {
            return 'Absent';
        }

        if ($status == '1') {
            return 'Present';
        }


        return 'Excused';
    }
}
