<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Labourer extends Model
{
    use HasFactory;
    protected $fillable = [
       'department_id','name','type', 'phone_number', 'gender','labour_id'
    ];
    public function getDays()
    {
        $days = LeaveSetting::orderBy('id','desc')->first();
        if($days){
            return @$days->days_per_month*12;
        }else{
            return false;
        }

    }
    public function getCompassionate()
    {
        $days = LeaveSetting::orderBy('id','desc')->first();
        return @$days->compassionate_days_per_year;
    }
    public function getBalanceNormal($id,$start_date,$end_date)
    {
        $days = Leave::where(['employee_id'=>$id])
            ->whereBetween('created_at', [$start_date, $end_date])
            ->orderBy('id','desc')->first();
        if($days){
            return $days->leave_balance;
        }else{
            return false;
        }

    }
    public function getBalanceCompassionate($id,$start_date,$end_date)
    {
        $days = Leave::where(['employee_id'=>$id])
            ->whereBetween('created_at', [$start_date, $end_date])
            ->orderBy('id','desc')->first();
        if($days){
            return $days->compassionate_balance;
        }else{
            return false;
        }

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
    public function getNumberOfDays($id){
        $present = Attendance::where(['labourer_id'=>$id])->get();
        $absent = Attendance::where(['labourer_id'=>$id,'status'=>'0'])->get();
        return count($present)-count($absent);
    }
    public function getNumberOfDaysByMonth($id,$start_date,$end_date){
        $present = Attendance::where(['labourer_id'=>$id])
            ->whereBetween('date', [$start_date, $end_date])
            ->get();
        $absent = Attendance::where(['labourer_id'=>$id,'status'=>'0'])
            ->whereBetween('date', [$start_date, $end_date])
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
    public function getDepartmentID(){
        $department = Labourer::where(['id'=>$this->id])->first();
        return $department->department_id;
    }
    public function getDepartmentName(){
        $department = Labourer::where(['labourer_id'=>$this->id])->first();
        return $department->department;
    }
    public function payments()
    {
        return  $this->hasMany(LabourerPayments::class,'labourer_id');
    }
    public function attendance()
    {
        return  $this->hasMany(Attendance::class,'labourer_id');
    }
    public function getPeriodAttribute()
    {
        $start_date = Carbon::parse($this->professional_start);
        $end_date = Carbon::parse(date('Y-m-d'));
        return $end_date->diffInYears($start_date)." Years";
    }
    public function getBalance($id)
    {
        $balance =LabourerPayments::where(['labourer_id'=>$id])->orderBy('id','desc')->first();
        return @$balance->balance;

    }
    public function getAgeAttribute()
    {
        $start_date = Carbon::parse($this->date_of_birth);
        $end_date = Carbon::parse(date('Y-m-d'));
        return $end_date->diffInYears($start_date)." Years";
    }

    public function labour(): BelongsTo
    {
        return $this->belongsTo(Labour::class);
    }
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class,'department_id');
    }
    public function service(): BelongsTo
    {
        return $this->belongsTo(Labour::class,"labour_id");
    }

    public function labourers()
    {
        return $this->hasManyThrough(Labourer::class,Allocation::class);
    }
    public function leaves()
    {
        return $this->hasMany(Leave::class,'employee_id');
    }

    public function allocations()
    {
        return $this->hasMany(Allocation::class);
    }


    public function allocate()
    {
        $sql =sprintf("SELECT * FROM allocations WHERE project_id IN
                            (SELECT id FROM labourers WHERE labourer_id=%d)",$this->id);

        $querySet = DB::select($sql);
        return Incomes::hydrate($querySet);
    }
    public function getType($id)
    {
        $bala = Labourer::where(['id'=>$id])->latest()->first();
       return  $bala->type;
    }
    public function getName($id)
    {
        $name = Labourer::where(['id'=>$id])->latest()->first();
        return  $name->name;
    }


}
