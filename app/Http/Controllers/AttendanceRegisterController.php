<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Department;
use App\Models\Labourer;
use App\Models\Month;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceRegisterController extends Controller
{
    public function generateAttendanceReport(Attendance $attendance, Request $request)

    {
        $request->validate([
            'month_id' => "required|numeric",
            'type' => "required|numeric",
        ]);
        $data = Month::where(['id' => $request->post('month_id')])->first();

        $start = $data->start_date;
        $end = $data->end_date;
        activity('ANALYTICS')
            ->log("Accessed Attendance Reports")->causer(request()->user());
        if ($request->post('type') == 1) {
            return view('departments.attendance.index')->with([
                'cpage' => "finances",
                'months' => Month::all(),
                'month_id' => $request->post('month_id'),
                'start_date' => $start,
                'end_date' => $end,
                'type'=>1,
                'employees' => Labourer::where(['type'=>1])->orderBy('name','ASC')->get(),
            ]);
        }
        elseif ($request->post('type') == 2) {
            return view('departments.attendance.index')->with([
                'cpage' => "finances",
                'months' => Month::all(),
                'month_id' => $request->post('month_id'),
                'start_date' => $start,
                'end_date' => $end,
                'type'=>1,
                'employees' => Labourer::where(['type'=>3])->orderBy('name','ASC')->get(),
            ]);
        }

    }

    public function showAttendanceForm(Department $department)
    {
        return view('departments.attendance.form')->with([
            'department' => $department,
            'employees' => $department->employees,
            'cpage' => "Attendance"
        ]);
    }

    public function employeeRegister()
    {
        return view('departments.attendance.all')->with([
            'attendances' => Labourer::all(),
            'months' => Month::all(),
            'cpage' => "Attendance"
        ]);
    }

    public function employeeRegisterByMonth(Attendance $attendance, Request $request)

    {
        $request->validate([
            'month_id' => "required|numeric",
        ]);
        $data = Month::where(['id' => $request->post('month_id')])->first();

        $start = $data->start_date;
        $end = $data->end_date;
        activity('ANALYTICS')
            ->log("Accessed Attendance Reports")->causer(request()->user());

        return view('departments.attendance.all')->with([
            'cpage' => "finances",
            'months' => Month::all(),
            'month_id' => $request->post('month_id'),
            'start_date' => $start,
            'end_date' => $end,
            'employees' => Attendance::getAttendance($start, $end),
        ]);
    }


    public function editAttendanceByEmployee(Attendance $attendance)
    {
        return view('departments.attendance.edit')->with([
            'attendance' => $attendance,
            'cpage' => "attendance"
        ]);
    }

    public function update(Request $request, Attendance $attendance)
    {
        $data = $request->post();
        DB::table('attendances')
            ->where(['id' => $request->post('id')])
            ->update(['status' => $request->post('status')]);
        $attendance->update($data);
        activity('ATTENDANCE')
            ->log("Updated an Attendance")->causer(request()->user());
        return redirect()->back()->with([
            'success-notification' => "Attendance successfully Updated"
        ]);
    }

    public function showAttendanceByEmployee(Labourer $labourer)
    {
        return view('departments.attendance.show')->with([
            'employees' => $labourer->attendance,
            'labourer' => $labourer,
            'cpage' => "Attendance"
        ]);
    }

    public function index()
    {
        return view('departments.attendance.index')->with([
            'employees' => Labourer::all(),
            'months' => Month::all(),
            'cpage' => "Attendance"
        ]);
    }

    public function showAttendance(Department $department)
    {
        return view('departments.attendance.view')->with([
            'department' => $department,
            'months' => Month::all(),
            'employees' => $department->employees,
            'cpage' => "Attendance"
        ]);
    }

    public function produceAttendance(Department $department, Request $request)

    {
        $request->validate([
            'month_id' => "required|numeric",
        ]);
        $data = Month::where(['id' => $request->post('month_id')])->first();

        $start = $data->start_date;
        $end = $data->end_date;
        activity('ANALYTICS')
            ->log("Accessed Attendance Reports")->causer(request()->user());

        return view('departments.attendance.view')->with([
            'cpage' => "finances",
            'months' => Month::all(),
            'month_id' => $request->post('month_id'),
            'start_date' => $start,
            'department' => $department,
            'end_date' => $end,
            'employees' => Labourer::where(['department_id' => $department->id])->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->post();
        $this->validate($request, ['date' => "required|date"]);
        $this->validate($request, ['labourer_id' => "required|numeric"]);

        Attendance::create($data);
        activity('ATTENDANCE')
            ->log("Created a Attendance")->causer(request()->user());
        return redirect()->back()->with([
            'success-notification' => "Attendance successfully Created"
        ]);
    }


    public function showAttendanceReport(Department $department)
    {
        $this->validate(request(), ['date' => "required|date"]);

        $tDate = \request('date');

        $day = Carbon::parse($tDate)->format('D, d M Y');
        return view('departments.attendance.report')->with([
            'department' => $department,
            'attendanceRecords' => Attendance::where('date', $tDate)->get(),
            'cpage' => "Attendance",
            'targetDay' => $day
        ]);
    }


    public function saveAttendanceData(Request $request, Department $department)
    {
        $this->validate($request, ['date' => "required|date"]);

        $date = $request->post('date');
        // retrieve all employees for the department
        $employees = $department->employees;

        // record the attendance status of each employee
        foreach ($employees as $employee) {
            $status = $request->post('emp-' . $employee->id);

            // check if status is one of 0,1 or ?
            // if not set it to 0
            if (!in_array($status, ['0', '1', '?'])) {
                $status = 0;
            }

            $data = [
                'date' => $date,
                'labourer_id' => $employee->id,
                'status' => $status
            ];

            $existingRecord = Attendance::where($data)->first();

            if ($existingRecord) {
                $existingRecord->update($data);
            } else {
                Attendance::create($data);
            }
        }


        return redirect()->route("departments.show", $department->id)->with([
            'success-notification' => "Successfully recorded attendance for the department."
        ]);
    }
}
