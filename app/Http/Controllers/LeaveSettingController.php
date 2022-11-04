<?php

namespace App\Http\Controllers;

use App\Models\LeaveSetting;
use Illuminate\Http\Request;

class LeaveSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        activity('HUMAN RESOURCES')
            ->log("Accessed Leave")->causer(request()->user());

        return view('leave-settings.index')->with([
            'cpage' => "leaves",
            'leaves'=>LeaveSetting::orderBy('id','desc')->get()
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
    public function store(Request $request)
    {
        $request->validate([
            'days_per_month'=>"required:numeric",
            'compassionate_days_per_year' => "required:numeric",
        ]);
        $data = $request->post();

        LeaveSetting::create($data);
        activity('HUMAN RESOURCES')
            ->log("Created a Leave Setting")->causer(request()->user());
        return redirect()->route('leave-settings.index')->with([
            'success-notification'=>"Successfully Created"
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(LeaveSetting $leaveSetting)
    {
        return view('leave-settings.show')->with([
            'cpage'=>"leaves",
            'leave'=>$leaveSetting
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(LeaveSetting $leaveSetting)
    {
        return view('leave-settings.edit')->with([
            'cpage' => "leaves",
            'leave' => $leaveSetting
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LeaveSetting $leaveSetting)
    {
        $request->validate([
            'days_per_month'=>"required:numeric",
            'compassionate_days_per_year' => "required:numeric",
        ]);
        $data = $request->post();

        $leaveSetting->update($data);
        activity('HUMAN RESOURCES')
            ->log("Updated a Leave Setting")->causer(request()->user());
        return redirect()->route('leave-settings.show',$leaveSetting->id)->with([
            'success-notification'=>"Successfully Updated"
        ]);
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
