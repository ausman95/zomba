<?php

namespace App\Http\Controllers;

use App\Http\Requests\Accounts\UpdateRequest;
use App\Models\Accounts;
use App\Models\Attendance;
use App\Models\Department;
use App\Models\HomeAttendance;
use App\Models\Labourer;
use App\Models\Member;
use App\Models\Month;
use App\Models\Program;
use Illuminate\Http\Request;

class HomeAttendanceController extends Controller
{
    public function generateAttendanceReport(Attendance $attendance, Request $request, MemberController $memberController)

    {
        $request->validate([
            'month_id' => "required|numeric",
        ]);
        $month = Month::where(['id' => $request->post('month_id')])->first();

        $attendances = HomeAttendance::where(['soft_delete'=>0])
            ->whereBetween('created_at',[$month->start_date,$month->end_date])
            ->orderBy('id','desc')->get();
        if(request()->user()->designation=='church'){
            $attendances = HomeAttendance::where(['church_id'=>$memberController->getHomeChurch()])
                ->where(['soft_delete'=>0])
                ->whereBetween('created_at',[$month->start_date,$month->end_date])
                ->orderBy('id','desc')
                ->get();
        }

        return view('home-attendances.index')->with([
            'months' => Month::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
            'attendances'=>$attendances,
            'start_date'=>$month->start_date,
            'end_date'=>$month->end_date,
            'cpage' => 'home-attendances'
        ]);
    }

    public function create(MemberController $controller)
    {
        $members = Member::where(['soft_delete'=>0])->orderBy('id','desc')->get();
        if(request()->user()->designation=='church'){
            $members = $controller->getHomeChurchMembers();
        }
        return view('home-attendances.form')->with([
            'members' => $members,
            'church_id'=>$controller->getHomeChurch(),
            'cpage' => 'home-attendances'
        ]);
    }
    public function index(MemberController $memberController)
    {
        if(Month::getActiveMonth()){
            $month =Month::getActiveMonth();
        }else{
            return redirect()->route('months.index')->with([
                'success-notification'=>"Please Create a new month"
            ]);
        }
        $attendances = HomeAttendance::where(['church_id'=>$memberController->getHomeChurch()])
            ->where(['soft_delete'=>0])
            ->whereBetween('created_at',[$month->start_date,$month->end_date])
            ->orderBy('id','desc')
            ->get();
        if(request()->user()->designation=='administrator') {
            $attendances = HomeAttendance::where(['soft_delete' => 0])
                ->whereBetween('created_at', [$month->start_date, $month->end_date])
                ->orderBy('id', 'desc')->get();
        }


        return view('home-attendances.index')->with([
            'months' => Month::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
            'attendances'=>$attendances,
            'start_date'=>$month->start_date,
            'end_date'=>$month->end_date,
            'cpage' => 'home-attendances'
        ]);
    }
    public function store(Request $request, MemberController $controller)
    {
      // retrieve all employees for the department
        $employees = $controller->getHomeChurchMembers();
        // record the attendance status of each employee
        foreach ($employees as $employee) {
            $status = $request->post('emp-' . $employee->id);
            if (!in_array($status, ['0', '1', '?'])) {
                $status = 0;
            }
            $data = [
                'member_id' => $employee->id,
                'church_id' => $request->post('church_id'),
                'updated_by' => $request->post('updated_by'),
                'created_by' => $request->post('created_by'),
                'status' => $status,
            ];
            $existingRecord = HomeAttendance::where($data)->first();
            if ($existingRecord) {
                $existingRecord->update($data);
            } else {
                HomeAttendance::create($data);
            }
        }
        return redirect()->route("home-attendances.index")->with([
            'success-notification' => "Successfully recorded attendances."
        ]);
    }
    public function show(HomeAttendance $homeAttendance)
    {
        return view('home-attendances.show')->with([
            'cpage'=>"home-attendances",
            'attendance'=>$homeAttendance,
        ]);
    }
    public function edit( HomeAttendance $homeAttendance, MemberController $controller)
    {
        $members = Member::where(['soft_delete'=>0])->orderBy('id','desc')->get();
        if(request()->user()->designation=='church'){
            $members = $controller->getHomeChurchMembers();
        }
        return view('home-attendances.edit')->with([
            'cpage' => "home-attendances",
            'members'=>$members,
            'church_id'=>$controller->getHomeChurch(),
            'attendance' => $homeAttendance
        ]);
    }
    public function update(Request $request,HomeAttendance $homeAttendance)
    {
        $data = $request->post();
        $homeAttendance->update($data);
        activity('home-attendances')
            ->log("Updated an home-attendances")->causer(request()->user());
        return redirect()->route('home-attendances.show',$request->post('id'))->with([
            'success-notification'=>"Successfully Updated"
        ]);
    }
}
