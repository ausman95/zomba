<?php

namespace App\Http\Controllers;

use App\Http\Requests\Attendances\StoreRequest;
use App\Http\Requests\Attendances\UpdateRequest;
use App\Models\Attendance;
use App\Models\Church;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        activity('Attendance')
            ->log("Accessed Attendance")->causer(request()->user());
        return view('attendances.index')->with([
            'cpage' => "attendances",
            'attendances'=> Attendance::orderBy('id','ASC')->get()
        ]);
    }
    public function store(StoreRequest $request)
    {
        $data = $request->post();
        if($request->post('date')>date('Y-m-d')){
            return back()->with(['error-notification'=>"Invalid Date Entered, You have a Future Date"]);
        }

        Attendance::create($data);
        activity('Attendance')
            ->log("Created a Attendance")->causer(request()->user());
        return redirect()->back()->with([
            'success-notification'=>"Successfully Created"
        ]);
    }
    public function create()
    {
        return view('attendances.create')->with([
            'cpage'=>"attendances",
            'churches'=>Church::all(),
        ]);
    }
    public function show(Attendance $attendance)
    {
        return view('attendances.show')->with([
            'cpage'=>"attendances",
            'attendance'=>$attendance,
        ]);
    }
    public function edit( Attendance $attendance)
    {
        return view('attendances.edit')->with([
            'cpage' => "attendances",
            'attendance'=>$attendance,
            'churches'=>Church::all(),
        ]);
    }
    public function update(UpdateRequest $request,Attendance $attendance)
    {
        $data = $request->post();
        $attendance->update($data);
        activity('Attendances')
            ->log("Updated an Attendance")->causer(request()->user());
        return redirect()->route('attendances.show',$attendance->id)->with([
            'success-notification'=>"Successfully Updated"
        ]);
    }
}
