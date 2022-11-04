<?php

namespace App\Http\Controllers;

use App\Http\Requests\Churches\StoreRequest;
use App\Http\Requests\Churches\UpdateRequest;
use App\Models\Church;
use Illuminate\Http\Request;

class ChurchController extends Controller
{
    public function index()
    {
        activity('churches')
            ->log("Accessed churches listing")->causer(request()->user());

        return view('churches.index')->with([
            'cpage' => "churches",
            'churches'=> Church::orderBy('id','desc')->get(),
        ]);
    }
    public function store(StoreRequest $request)
    {
        $data = $request->post();

        Church::create($data);
        activity('churches')
            ->log("Created a new churches")->causer(request()->user());
        return redirect()->back()->with([
            'success-notification'=>"Successfully Created"
        ]);
    }
    public function show(Church $church)
    {
        return view('churches.show')->with([
            'cpage'=>"churches",
            'church'=>$church,
            'transactions'=>$church->payments
        ]);
    }
    public function edit( Church $church)
    {
        return view('churches.edit')->with([
            'cpage' => "churches",
            'church' => $church
        ]);
    }
    public function update(UpdateRequest $request,Church $church)
    {
        $data = $request->post();

        $church->update($data);
        activity('churches')
            ->log("Updated an church")->causer(request()->user());
        return redirect()->route('churches.show',$church->id)->with([
            'success-notification'=>"Successfully Updated"
        ]);
    }
}
