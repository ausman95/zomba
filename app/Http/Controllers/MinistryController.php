<?php

namespace App\Http\Controllers;

use App\Http\Requests\Ministries\StoreRequest;
use App\Http\Requests\Ministries\UpdateRequest;
use App\Models\Ministry;
use Illuminate\Http\Request;

class MinistryController extends Controller
{
    public function index()
    {
        activity('ministries')
            ->log("Accessed ministries listing")->causer(request()->user());

        return view('ministries.index')->with([
            'cpage' => "ministries",
            'ministries'=> Ministry::orderBy('id','desc')->get(),
        ]);
    }
    public function store(StoreRequest $request)
    {
        $data = $request->post();

        Ministry::create($data);
        activity('ministries')
            ->log("Created a new ministries")->causer(request()->user());
        return redirect()->back()->with([
            'success-notification'=>"Successfully Created"
        ]);
    }
    public function show(Ministry $ministry)
    {
        return view('ministries.show')->with([
            'cpage'=>"ministries",
            'ministry'=>$ministry,
            'transactions'=>$ministry->payments
        ]);
    }
    public function edit( Ministry $ministry)
    {
        return view('ministries.edit')->with([
            'cpage' => "ministries",
            'ministry' => $ministry
        ]);
    }
    public function update(UpdateRequest $request,Ministry $ministry)
    {
        $data = $request->post();

        $ministry->update($data);
        activity('Ministry')
            ->log("Updated an Ministry")->causer(request()->user());
        return redirect()->route('ministries.show',$ministry->id)->with([
            'success-notification'=>"Successfully Updated"
        ]);
    }
}
