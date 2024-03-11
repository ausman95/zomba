<?php

namespace App\Http\Controllers;

use App\Http\Requests\Zones\StoreRequest;
use App\Http\Requests\Zones\UpdateRequest;
use App\Models\Church;
use App\Models\Member;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ZoneController extends Controller
{
    public function index()
    {
        activity('zones')
            ->log("Accessed zones listing")->causer(request()->user());

        return view('zones.index')->with([
            'cpage' => "churches",
            'zones'=> Zone::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
        ]);
    }
    public function store(StoreRequest $request)
    {
        if(is_numeric($request->post('name'))){
            return back()->with(['error-notification'=>"Invalid Character Entered on Name"]);
        }
        $data = $request->post();

        Zone::create($data);
        activity('zones')
            ->log("Created a new Zone")->causer(request()->user());
        return redirect()->route('zones.index')->with([
            'success-notification'=>"Successfully Created"
        ]);
    }
    public function show(Zone $zone)
    {
        return view('zones.show')->with([
            'cpage'=>"churches",
            'zone'=>$zone,
            'churches'=>$zone->churches
        ]);
    }
    public function create()
    {
        return view('zones.create')->with([
            'cpage'=>"churches",
        ]);
    }
    public function edit(Zone $zone)
    {
        return view('zones.edit')->with([
            'cpage' => "churches",
            'members'=>Member::all(),
            'zone' => $zone
        ]);
    }
    public function update(UpdateRequest $request,Zone $zone)
    {
        $data = $request->post();
        if(is_numeric($request->post('name'))){
            return back()->with(['error-notification'=>"Invalid Character Entered on Name"]);
        }
        $zone->update($data);
        activity('churches')
            ->log("Updated an zones")->causer(request()->user());
        return redirect()->route('zones.show',$zone->id)->with([
            'success-notification'=>"Successfully Updated"
        ]);
    }
    public function destroy(Request $request, Zone $zone)
    {

        $data = $request->post();
        DB::table('zones')
            ->where(['id' => $request->post('id')])
            ->update(['soft_delete' => '1']);
        $zone->update($data);

        return redirect()->route('zones.index')->with([
            'success-notification'=>"Successfully Deleted"
        ]);
    }

}
