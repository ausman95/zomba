<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    use HasFactory;


    protected $fillable = [
        'amount', 'name', 'client_id', 'location', 'description', 'supervisor_id', 'start_date', 'end_date', 'status'
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }


    public function requisitions()
    {
        return $this->hasMany(Requisition::class);
    }
    public function getProjectName(){
        return Project::where(['id'=>$_GET['project_id']])->first()->name;
    }
    public function getDepartmentName(){
        return Department::where(['id'=>$_GET['department_id']])->first()->name;
    }
    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(Labourer::class, 'supervisor_id', 'id');
    }

    public function getPeriodAttribute()
    {
        $start_date = Carbon::parse($this->start_date);
        $end_date = Carbon::parse($this->end_date);
        return $end_date->diffInWeeks($start_date) . " weeks";
    }


    public function labourers()
    {
        return $this->hasMany(Allocation::class);
    }

    public function incomes()
    {
        return $this->hasMany(ProjectPayment::class, 'project_id');
    }


}
