<?php

namespace App\Http\Controllers;

use App\Http\Requests\Departments\StoreRequest;
use App\Http\Requests\Departments\UpdateRequest;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        activity('DEPARTMENT')
            ->log("Accessed Departments")->causer(request()->user());
        $department = Department::orderBy('id','desc')->get();;
        return view('departments.index')->with([
            'cpage' => "human-resources",
            'departments'=>$department
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('departments.create')->with([
            'cpage'=>"human-resources"
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $data = $request->post();

        Department::create($data);
        activity('DEPARTMENT')
            ->log("Created a Department")->causer(request()->user());
        return redirect()->route('departments.index')->with([
            'success-notification'=>"Departments successfully Created"
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Department $department)
    {
        $labourers = $department->employees;
        $incomes = $department->payments;
        return view('departments.show')->with([
            'cpage'=>"human-resources",
            'department'=>$department,
            'incomes' =>$incomes,
            'labourers' => $labourers
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Department $department)
    {
        return view('departments.edit')->with([
            'cpage' => "human-resources",
            'department' => $department
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Department $department)
    {
        $data = $request->post();

        $department->update($data);
        activity('DEPARTMENT')
            ->log("Updated a Department")->causer(request()->user());
        return redirect()->route('departments.show',$department->id)->with([
            'success-notification'=>"Departments successfully Updated"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Department $department)
    {
        try{
            $department->delete();
            activity('DEPARTMENT')
                ->log("Deleted a Department")->causer(request()->user());
            return redirect()->route('departments.index')->with([
                'success-notification'=>"Department successfully Deleted"
            ]);

        }catch (\Exception $exception){
            return redirect()->route('departments.index')->with([
                'error-notification'=>"Something went Wrong ".$exception.getMessage()
            ]);
        }
    }
}
