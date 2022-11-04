<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HumanResource extends Model
{
    use HasFactory;

    public function getDepartments()
    {
        return   Department::get('id')->count();
    }
    public function getEmployees()
    {
        return  Labourer::get('id')->count();
    }
    public function getLabours()
    {
        return  Labour::get('id')->count();
    }
    public function getFemale()
    {
      return  Labourer::where(['gender'=>'female'])->count();
    }
    public function getEmployed()
    {
        return  Labourer::where(['type'=>1])->count();
    }
    public function getSubContractor()
    {
        return  Labourer::where(['type'=>2])->count();
    }
}
