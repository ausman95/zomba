<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DepartmentRequisition extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'department_id', 'status'];

    public function notes()
    {
        return $this->hasMany(RequisitionDepartmentApproval::class,'department_requisition_id');
    }
    public function departmentRequisition()
    {
        return $this->hasMany(DepartmentRequisitionItem::class);
    }
    public function checkProjectUser($request)
    {
        $check_data = RequisitionDepartmentApproval::where(['department_requisition_id'=>$request])->orderBy('id','DESC')->first();
        if($check_data){
            $level = User::where(['id'=>$check_data->user_id])->first();
            return $level->level;
        }
        return false;
    }
    public function findUser($request)
    {
        $check_data = RequisitionDepartmentApproval::where(['department_requisition_id'=>$request])->orderBy('id','DESC')->first();
        if($check_data){
            $level = User::where(['id'=>$check_data->user_id])->first();
            return $level->first_name.' '.$level->last_name;
        }
        return false;
    }
    public function department()
    {
        return $this->belongsTo(Department::class,'department_id','id');
    }
    public function getUser($level)
    {
        return User::where(['level'=>$level])->get();
    }
    public function getUserFinal()
    {
        return User::where(['designation'=>'accountant'])->get();
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
