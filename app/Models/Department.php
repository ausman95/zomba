<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Department extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
    public function employees()
    {
        return  $this->hasMany(Labourer::class,'department_id');
    }
    public function budgets()
    {
        return  $this->hasMany(MaterialBudget::class,'department_id');
    }
    public function notes()
    {
        return  $this->hasMany(Note::class,'department_id');
    }
    /// this company is treating departments as projects hence this
    public function payments()
    {
        return  $this->hasMany(ProjectPayment::class,'project_id');
    }
}
