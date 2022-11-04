<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Requisition extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'project_id', 'status'];


    public function checkProjectUser($request)
    {
        $check_data = RequisitionApproval::where(['requisition_id'=>$request])->get();
        if($check_data){
            $level = User::where(['id'=>$check_data->user_id])->get();
            return $level->level;
        }
        return false;
    }

    public function requisitionItems()
    {
        return $this->hasMany(RequisitionItem::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class,'project_id','id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
