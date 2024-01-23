<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\HumanResource;
use App\Models\Labour;
use App\Models\Labourer;
use Illuminate\Http\Request;

class HumanResourceController extends Controller
{
    public function index(HumanResource $departments)
    {
        activity('HUMAN RESOURCES')
            ->log("Accessed Human Resources")->causer(request()->user());
        return view('human-resources.index')->with([
            'cpage' => "human-resources",
            'employee'=>Labourer::where(['soft_delete'=>0])->get(),
            'department'=>Department::where(['soft_delete'=>0])->get(),
            'labour'=>Labour::where(['soft_delete'=>0])->get(),
        ]);
    }
}
