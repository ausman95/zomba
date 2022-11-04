<?php

namespace App\Http\Controllers;

use App\Models\FinancialYear;
use App\Models\Labour;
use App\Models\Labourer;
use App\Models\Leave;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        activity('Leave')
            ->log("Accessed Leave listing")->causer(request()->user());
        return view('leaves.index')->with([
            'cpage' => "accounts",
            'years'=>FinancialYear::all(),
            'leaves'=>Leave::groupBy('employee_id')->orderBy('id','desc')->get(),
        ]);
    }
    public function leaveSummary(Leave $leave, Request $request)

    {
        $request->validate([
            'year_id' => "required|numeric",
        ]);
        $data = FinancialYear::where(['id' => $request->post('year_id')])->first();

        $start = $data->start_date;
        $end = $data->end_date;
        activity('Leave')
            ->log("Accessed Leave")->causer(request()->user());
        return view('leaves.index')->with([
            'cpage' => "leaves",
            'years' => FinancialYear::all(),
            'year_id' => $request->post('year_id'),
            'start_date' => $start,
            'end_date' => $end,
            'leaves'=>Leave::groupBy('employee_id')->orderBy('id','desc')->get(),
            'employees' => Labourer::where(['type'=>1])->orderBy('name','ASC')->get(),
        ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('leaves.create')->with([
            'cpage'=>"accounts",
            'employees'=>Labourer::where(['type'=>1])->orderBy('name','asc')->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Leave $leave)
    {
        $request->validate([
            'employee_id'=>"required:numeric",
            'start_date' => "required:date",
            'end_date' => "required:date",
            'type' => "required:numeric",
        ]);
        if($request->post('start_date')>=$request->post('end_date')){
            return back()->with(['error-notification'=>"Please Specifiy the dates correctly"]);
        }
        $start_date = Carbon::parse($request->post('start_date'));
        $end_date = Carbon::parse($request->post('end_date'));
        $number_of_leave_days = $end_date->diffInDays($start_date);
        $year = FinancialYear::where(['status'=>1])->orderBy('id','desc')->first();
        $start_date = @$year->start_date;
        $end_date = @$year->end_date;
        if($request->post('type')==1){
            if(!$leave->getDays()){
                return back()->with(['error-notification'=>"Please Configure the leave settings first"]);
            }
            $compassionate = 0;
            $balance_com = $leave->getBalanceCompassionate($request->post('employee_id'),$start_date,$end_date);
            $normal = $number_of_leave_days;
            if(!$leave->getBalanceNormal($request->post('employee_id'),$start_date,$end_date)){
                if($number_of_leave_days>$leave->getDays()){
                    return back()->with(['error-notification'=>"The number of days are more than the balance remaining"]);
                }else{
                    $balance_normal = $leave->getDays()-$number_of_leave_days ;
                }
            }else{
                if($number_of_leave_days > $leave->getBalanceNormal($request->post('employee_id'),$start_date,$end_date)){
                    return back()->with(['error-notification'=>"The number of days are more than the balance remaining"]);
                }else{
                    $balance_normal = $leave->getBalanceNormal($request->post('employee_id'),$start_date,$end_date)-($number_of_leave_days) ;
                }
            }

        }else{
            $normal = 0;
            $balance_normal = $leave->getBalanceNormal($request->post('employee_id'),$start_date,$end_date);
            if(!$leave->getCompassionate()){
                return back()->with(['error-notification'=>"Please Configure the Compationate leave settings first"]);
            }
            $compassionate = $number_of_leave_days;
            if(!$leave->getBalanceCompassionate($request->post('employee_id'),$start_date,$end_date)){
                if($number_of_leave_days>$leave->getCompassionate()){
                    return back()->with(['error-notification'=>"The number of days are more than the balance remaining"]);
                }else{
                    $balance_com = $leave->getCompassionate()-$number_of_leave_days ;
                }
            }else{
                if($number_of_leave_days>$leave->getBalanceCompassionate($request->post('employee_id'),$start_date,$end_date)){
                    return back()->with(['error-notification'=>"The number of days are more than the balance remaining"]);
                }else{
                    $balance_com = $leave->getBalanceCompassionate($request->post('employee_id'),$start_date,$end_date)-($number_of_leave_days) ;
                }
            }
        }
        $data = [
            'employee_id'=>$request->post('employee_id'),
            'start_date'=>$request->post('start_date'),
            'end_date'=>$request->post('end_date'),
            'leave_days'=>$number_of_leave_days,
            'compassionate_days'=>$compassionate,
            'leave_balance'=>$balance_normal,
            'compassionate_balance'=>$balance_com
        ];
        Leave::create($data);
        activity('HUMAN RESOURCES')
            ->log("Created a Leave")->causer(request()->user());
        return redirect()->route('leaves.create')->with([
            'success-notification'=>"Successfully Created"
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
