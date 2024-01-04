<?php

namespace App\Http\Controllers;

use App\Models\Month;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MonthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        activity('MONTHS')
            ->log("Accessed Months listing")->causer(request()->user());
        $months= Month::orderBy('id','desc')->get();;
        return view('months.index')->with([
            'cpage' => "months",
            'months'=>$months,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store (Request $request)
    {
        $data = $request->post();
        $this->validate($request, ['end_date' => "required|date"]);
        $this->validate($request, ['start_date' => "required|date"]);
        $this->validate($request, ['name' => "required|string|unique:months,name"]);

        $check_data = [
            'end_date'=>$data['end_date'],
            //'start_date'=>$data['start_date'],
        ];
        $check = [
            'end_date'=>$data['start_date'],
          //  'start_date'=>$data['end_date'],
        ];
        $cdata = [
           // 'end_date'=>$data['end_date'],
            'start_date'=>$data['start_date'],
        ];
        $check_d = [
            //'end_date'=>$data['start_date'],
            'start_date'=>$data['end_date'],
        ];
        if($data['start_date'] >= $data['end_date']){
            // labourer is already part of this project
            return back()->with(['error-notification'=>"Check the Dates Properly and try again"]);
        }
        if(Month::where($check_d)->first()){
            // labourer is already part of this project
            return back()->with(['error-notification'=>"Check the Dates Properly and try again"]);
        }
        if(Month::where($cdata)->first()){
            // labourer is already part of this project
            return back()->with(['error-notification'=>"Check the Dates Properly and try again"]);
        }
        if(Month::where($check)->first()){
            // labourer is already part of this project
            return back()->with(['error-notification'=>"Check the Dates Properly and try again"]);
        }
        if(Month::where($check_data)->first()){
            // labourer is already part of this project
            return back()->with(['error-notification'=>"Check the Dates Properly and try again"]);
        }

        Month::create($data);
        activity('Months')
            ->log("Created a month")->causer(request()->user());
        return redirect()->back()->with([
            'success-notification'=>"Month successfully Created"
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Month $month)
    {
        return view('months.show')->with([
            'cpage'=>"months",
            'month'=>$month,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Month $month)
    {
        return view('months.edit')->with([
            'cpage' => "months",
            'month' => $month
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Month $month)
    {
        $data = $request->post();

        $this->validate($request, ['end_date' => "required|date"]);
        $this->validate($request, ['start_date' => "required|date"]);
        $this->validate($request, ['name' => "required|string"]);

        $month->update($data);

        activity('months')
            ->log("Updated an month")->causer(request()->user());
        return redirect()->route('months.show',$month->id)->with([
            'success-notification'=>"Month was successfully Updated"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Month $month)
    {
        try{
            $month->delete();
            activity('MONTHS')
                ->log("Deleted a Month")->causer(request()->user());
            return redirect()->route('months.index')->with([
                'success-notification'=>"A month was successfully Deleted"
            ]);

        }catch (\Exception $exception){
            return redirect()->route('months.index')->with([
                'error-notification'=>"Something went Wrong ".$exception.getMessage()
            ]);
    }
    }
}
