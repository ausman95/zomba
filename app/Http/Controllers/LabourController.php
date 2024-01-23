<?php

namespace App\Http\Controllers;
use App\Models\Announcement;
use App\Models\Labour;
use Illuminate\Http\Request;
use App\Http\Requests\Labours\StoreRequest;
use App\Http\Requests\Labours\UpdateRequest;
use Illuminate\Support\Facades\DB;

class LabourController extends Controller
{
    public function destroy(Request $request, Labour $labour)
    {

        $data = $request->post();
        DB::table('labours')
            ->where(['id' => $request->post('id')])
            ->update(['soft_delete' => '1']);
        $labour->update($data);

        return redirect()->route('labours.index')->with([
            'success-notification'=>"Successfully Deleted"
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        activity('HUMAN RESOURCES')
            ->log("Accessed Labours")->causer(request()->user());
        return view('labours.index')->with([
            'cpage' => "human-resources",
            'labours'=>Labour::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
        ]);
    }




    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('labours.create')->with([
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
        if(is_numeric($request->post('name'))){
            return back()->with(['error-notification'=>"Invalid Character Entered on Name"]);
        }
        $data = $request->post();

        Labour::create($data);
        activity('HUMAN RESOURCES')
            ->log("Created a Labour")->causer(request()->user());
        return redirect()->route('labours.index')->with([
            'success-notification'=>"Labour successfully Created"
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Labour $labour)
    {
        $labourers = $labour->labourers;
        return view('labours.show')->with([
            'cpage'=>"human-resources",
            'labourers'=>$labourers,
            'labour'=>$labour
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit( Labour $labour)
    {
        return view('labours.edit')->with([
            'cpage' => "human-resources",
            'labour' => $labour
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request,Labour $labour)
    {
        $data = $request->post();
        if(is_numeric($request->post('name'))){
            return back()->with(['error-notification'=>"Invalid Character Entered on Name"]);
        }
        $labour->update($data);
        activity('HUMAN RESOURCES')
            ->log("Updated a Labour")->causer(request()->user());
        return redirect()->route('labours.show',$labour->id)->with([
            'success-notification'=>"Labour successfully Updated"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
//    public function destroy(Labour  $labour)
//    {
//        try{
//            $labour->delete();
//            activity('HUMAN RESOURCES')
//                ->log("Deleted a Labour")->causer(request()->user());
//            return redirect()->route('labours.index')->with([
//                'success-notification'=>"Labour successfully Deleted"
//            ]);
//
//        }catch (\Exception $exception){
//            return redirect()->route('labours.index')->with([
//                'error-notification'=>"Something went Wrong ".$exception.getMessage()
//            ]);
//        }
//    }
}
