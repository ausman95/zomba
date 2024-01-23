<?php

namespace App\Http\Controllers;

use App\Http\Requests\Ministries\StoreRequest;
use App\Http\Requests\Ministries\UpdateRequest;
use App\Models\Ministry;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MinistryController extends Controller
{
    public function index()
    {
        activity('ministries')
            ->log("Accessed ministries listing")->causer(request()->user());

        return view('ministries.index')->with([
            'cpage' => "settings",
            'ministries'=> Ministry::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
        ]);
    }
    public function store(StoreRequest $request)
    {
        if(is_numeric($request->post('name'))){
            return back()->with(['error-notification'=>"Invalid Character Entered on Name"]);
        }
        $data = $request->post();

        Ministry::create($data);
        activity('ministries')
            ->log("Created a new ministries")->causer(request()->user());
        return redirect()->route('ministries.index')->with([
            'success-notification'=>"Successfully Created"
        ]);
    }
    public function show(Ministry $ministry)
    {
        return view('ministries.show')->with([
            'cpage'=>"settings",
            'ministry'=>$ministry,
            'transactions'=>$ministry->payments
        ]);
    }
    public function create()
    {
        return view('ministries.create')->with([
            'cpage'=>"settings",
        ]);
    }
    public function edit( Ministry $ministry)
    {
        return view('ministries.edit')->with([
            'cpage' => "settings",
            'ministry' => $ministry
        ]);
    }
    public function update(UpdateRequest $request,Ministry $ministry)
    {
        $data = $request->post();
        if(is_numeric($request->post('name'))){
            return back()->with(['error-notification'=>"Invalid Character Entered on Name"]);
        }
        $ministry->update($data);
        activity('Ministry')
            ->log("Updated an Ministry")->causer(request()->user());
        return redirect()->route('ministries.show',$ministry->id)->with([
            'success-notification'=>"Successfully Updated"
        ]);
    }
    public function destroy(Request $request, Ministry $ministry)
    {

        $data = $request->post();
        DB::table('ministries')
            ->where(['id' => $request->post('id')])
            ->update(['soft_delete' => '1']);
        $ministry->update($data);

        return redirect()->route('ministries.index')->with([
            'success-notification'=>"Successfully Deleted"
        ]);
    }
}
