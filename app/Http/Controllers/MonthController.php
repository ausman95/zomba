<?php

namespace App\Http\Controllers;

use App\Models\Month;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        return view('months.index')->with([
            'cpage' => "settings",
            'months'=>Month::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('months.create')->with([
            'cpage' => "settings",
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the name field to ensure it's not numeric
        if (is_numeric($request->post('name'))) {
            return back()->with(['error-notification' => "Invalid Character Entered on Name"]);
        }

        // Gather the data from the request
        $data = $request->post();

        // Validate required fields and their formats
        $this->validate($request, [
            'name' => 'required|string|unique:months,name',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        // Check if the start date is greater than or equal to the end date
        if ($data['start_date'] >= $data['end_date']) {
            return back()->with(['error-notification' => "Check the Dates Properly and try again"]);
        }

        // If no conflict, create the new month record
        Month::create($data);

        // Log the creation activity
        activity('Months')->log("Created a month")->causer(request()->user());

        // Redirect to the index with a success message
        return redirect()->route('months.index')->with([
            'success-notification' => "Month successfully Created"
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
            'cpage' => "settings",
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
            'cpage' => "settings",
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
        if(is_numeric($request->post('name'))){
            return back()->with(['error-notification'=>"Invalid Character Entered on Name"]);
        }
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
    public function destroy(Request $request, Month $month)
    {

        $data = $request->post();
        DB::table('months')
            ->where(['id' => $request->post('id')])
            ->update(['soft_delete' => '1']);
        $month->update($data);

        return redirect()->route('months.index')->with([
            'success-notification'=>"Successfully Deleted"
        ]);
    }
//    public function destroy(Month $month)
//    {
//        try{
//            $month->delete();
//            activity('MONTHS')
//                ->log("Deleted a Month")->causer(request()->user());
//            return redirect()->route('months.index')->with([
//                'success-notification'=>"A month was successfully Deleted"
//            ]);
//
//        }catch (\Exception $exception){
//            return redirect()->route('months.index')->with([
//                'error-notification'=>"Something went Wrong ".$exception.getMessage()
//            ]);
//    }
//    }
}
