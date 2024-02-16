<?php

namespace App\Http\Controllers;

use App\Http\Requests\FinancialYears\StoreRequest;
use App\Http\Requests\FinancialYears\UpdateRequest;
use App\Models\FinancialYear;
use Illuminate\Http\Request;

class FinancialYearController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        activity('FINANCES')
            ->log("Accessed Financial year")->causer(request()->user());
        $financial_year = FinancialYear::orderBy('id','desc')->get();;
        return view('financial-years.index')->with([
            'cpage' => "settings",
            'fyears'=>$financial_year,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('financial-years.create')->with([
            'cpage'=>"settings"
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
        $start_date =$data['start_date'];
        $end_date = $data['end_date'];

        if($start_date>=$end_date){
            // labourer is already part of this project
            return back()->with(['error-notification'=>"Please input a correct financial year end date"]);
        }
        FinancialYear::create($data);
        activity('FINANCES')
            ->log("Created Financial year")->causer(request()->user());
        return redirect()->route('financial-years.index')->with([
            'success-notification'=>"Financial Year successfully Created"
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(FinancialYear $financial_year)
    {
        return view('financial-years.show')->with([
            'cpage'=>"settings",
            'financial_year'=>$financial_year
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(FinancialYear $financial_year)
    {
        return view('financial-years.edit')->with([
            'cpage' => "settings",
            'financial_year' => $financial_year
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request,FinancialYear $financial_year)
    {
        $data = $request->post();

        $financial_year->update($data);
        activity('FINANCES')
            ->log("Updated a Financial year")->causer(request()->user());
        return redirect()->route('financial-years.show',$financial_year->id)->with([
            'success-notification'=>"Financial Year Succefully Updated"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(FinancialYear $financial_year)
    {
        try{
            $financial_year->delete();
            activity('FINANCES')
                ->log("Deleted a Financial year")->causer(request()->user());
            return redirect()->route('financial-years.index')->with([
                'success-notification'=>"Fiancial Years successfully Deleted"
            ]);

        }catch (\Exception $exception){
            return redirect()->route('financial-years.index')->with([
                'error-notification'=>"Something went Wrong ".$exception.getMessage()
            ]);
        }
    }
}
