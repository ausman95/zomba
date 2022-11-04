<?php

namespace App\Http\Controllers;
use App\Models\Labour;
use Illuminate\Http\Request;
use App\Http\Requests\Labours\StoreRequest;
use App\Http\Requests\Labours\UpdateRequest;
class LabourController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        activity('HUMAN RESOURCES')
            ->log("Accessed Labours")->causer(request()->user());

        $labour= Labour::orderBy('id','desc')->get();;
        return view('labours.index')->with([
            'cpage' => "labours",
            'labours'=>$labour
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
            'cpage'=>"labours"
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
            'cpage'=>"labours",
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
            'cpage' => "labours",
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
    public function destroy(Labour  $labour)
    {
        try{
            $labour->delete();
            activity('HUMAN RESOURCES')
                ->log("Deleted a Labour")->causer(request()->user());
            return redirect()->route('labours.index')->with([
                'success-notification'=>"Labour successfully Deleted"
            ]);

        }catch (\Exception $exception){
            return redirect()->route('labours.index')->with([
                'error-notification'=>"Something went Wrong ".$exception.getMessage()
            ]);
        }
    }
}
