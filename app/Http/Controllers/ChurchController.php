<?php

namespace App\Http\Controllers;

use App\Http\Requests\Churches\StoreRequest;
use App\Http\Requests\Churches\UpdateRequest;
use App\Models\Church;
use App\Models\Member;
use App\Models\Ministry;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChurchController extends Controller
{
    public function index()
    {
        activity('churches')
            ->log("Accessed churches listing")->causer(request()->user());

        return view('churches.index')->with([
            'cpage' => "churches",
            'churches'=> Church::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
        ]);
    }
    public function store(StoreRequest $request)
    {
        if(is_numeric($request->post('name'))){
            return back()->with(['error-notification'=>"Invalid Character Entered on Name"]);
        }
        $data = $request->post();

        Church::create($data);
        activity('churches')
            ->log("Created a new churches")->causer(request()->user());
        return redirect()->route('churches.index')->with([
            'success-notification'=>"Successfully Created"
        ]);
    }
    public function create()
    {
        return view('churches.create')->with([
            'cpage'=>"churches",
            'zones'=>Zone::all()
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
            'zones' => Zone::all(),
            'members' => Member::all(),
            'church' => $church
        ]);
    }
    public function update(UpdateRequest $request,Church $church)
    {
        $data = $request->post();
        if(is_numeric($request->post('name'))){
            return back()->with(['error-notification'=>"Invalid Character Entered on Name"]);
        }
        $church->update($data);
        activity('churches')
            ->log("Updated an church")->causer(request()->user());
        return redirect()->route('churches.show',$church->id)->with([
            'success-notification'=>"Successfully Updated"
        ]);
    }
    public function destroy(Request $request, Church $church)
    {

        $data = $request->post();
        DB::table('churches')
            ->where(['id' => $request->post('id')])
            ->update(['soft_delete' => '1']);
        $church->update($data);

        return redirect()->route('churches.index')->with([
            'success-notification'=>"Successfully Deleted"
        ]);
    }

}
