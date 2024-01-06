<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\HumanResource;
use App\Models\Labourer;
use Illuminate\Http\Request;

class HumanResourceController extends Controller
{
    public function index(HumanResource $departments)
    {
        activity('HUMAN RESOURCES')
            ->log("Accessed Human Resources")->causer(request()->user());

        $employees = Labourer::all();
        $department = $departments->getDepartments();
        $employee = $departments->getEmployees();
        $female = $departments->getFemale();
        $labour = $departments->getLabours();
        $sub = $departments->getSubContractor();
        $employed = $departments->getEmployed();
        return view('human-resources.index')->with([
            'cpage' => "human-resources",
            'employees'=>$employees,
            'department'=>$department,
            'employee'=>$employee,
            'employed'=>$employed,
            'sub'=>$sub,
            'female'=>$female,
            'labour'=>$labour
        ]);
    }
}
