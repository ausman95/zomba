<?php

namespace App\Http\Controllers;

use App\Http\Requests\Attendances\StoreRequest;
use App\Http\Requests\Attendances\UpdateRequest;
use App\Models\Attendance;
use App\Models\Church;
use App\Models\Ministry;
use App\Models\Month;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function generateReport()
    {
        activity('Attendance')
            ->log("Accessed Attendance")->causer(request()->user());
        return view('attendances.report')->with([
            'cpage' => "attendances",
            'months'=> Month::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
            'services'=> Service::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
            'ministries'=> Ministry::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
        ]);
    }
    public function index()
    {
        activity('Attendance')
            ->log("Accessed Attendance")->causer(request()->user());
        return view('attendances.index')->with([
            'cpage' => "attendances",
            'attendances'=> Attendance::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
        ]);
    }
    public function store(StoreRequest $request)
    {
        $data = $request->post();
        if($request->post('date')>date('Y-m-d')){
            return back()->with(['error-notification'=>"Invalid Date Entered, You have Entered a Future Date"]);
        }
        $check_data = [
            'date'=>$data['date'],
            'soft_delete'=>0,
            'service_id'=>$data['service_id'],
            'ministry_id'=>$data['ministry_id']
        ];
        $monthID  = Month::getActiveMonth();
        $data  = [
            'date'=>$data['date'],
            'soft_delete'=>0,
            'month_id'=>$monthID->id,
            'female'=>$data['female'],
            'male'=>$data['male'],
            'visitors'=>$data['visitors'],
            'service_id'=>$data['service_id'],
            'ministry_id'=>$data['ministry_id']
        ];
        if(Attendance::where($check_data)->first()){
            // labourer is already part of this project
            return back()->with(['error-notification'=>"Invalid, check your input and try again"]);
        }
        Attendance::create($data);
        activity('Attendance')
            ->log("Created a Attendance")->causer(request()->user());
        return redirect()->route('attendances.index')->with([
            'success-notification'=>"Successfully Created"
        ]);
    }
    public function create()
    {
        return view('attendances.create')->with([
            'cpage'=>"attendances",
            'ministries'=>Ministry::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
            'services'=>Service::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
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
            'ministries'=>Ministry::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
            'services'=>Service::where(['soft_delete'=>0])->orderBy('id','desc')->get()
        ]);
    }
    public function update(UpdateRequest $request,Attendance $attendance)
    {
        $data = $request->post();
        if($request->post('date')>date('Y-m-d')){
            return back()->with(['error-notification'=>"Invalid Date Entered, You have Entered a Future Date"]);
        }
        $attendance->update($data);
        activity('Attendances')
            ->log("Updated an Attendance")->causer(request()->user());
        return redirect()->route('attendances.show',$attendance->id)->with([
            'success-notification'=>"Successfully Updated"
        ]);
    }
    public function destroy(Request $request, Attendance $attendance)
    {
        $data = $request->post();
        DB::table('attendances')
            ->where(['id' => $request->post('id')])
            ->update(['soft_delete' => '1']);
        $attendance->update($data);
        return redirect()->route('attendances.index')->with([
            'success-notification'=>"Successfully Deleted"
        ]);
    }
}
