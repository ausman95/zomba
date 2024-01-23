<?php

namespace App\Http\Controllers;

use App\Http\Requests\Zones\StoreRequest;
use App\Http\Requests\Zones\UpdateRequest;
use App\Models\Member;
use App\Models\Service;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    public function index()
    {
        activity('Church-Services')
            ->log("Accessed Services listing")->causer(request()->user());

        return view('church-services.index')->with([
            'cpage' => "settings",
            'services'=> Service::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
        ]);
    }
    public function store(\App\Http\Requests\ChurchServices\StoreRequest $request)
    {
        if(is_numeric($request->post('name'))){
            return back()->with(['error-notification'=>"Invalid Character Entered on Name"]);
        }
        $data = $request->post();

        Service::create($data);
        activity('Church-Services')
            ->log("Created a new Services")->causer(request()->user());
        return redirect()->route('services.index')->with([
            'success-notification'=>"Successfully Created"
        ]);
    }
    public function show(Service $service)
    {
        return view('church-services.show')->with([
            'cpage'=>"settings",
            'service'=>$service,
        ]);
    }
    public function create()
    {
        return view('church-services.create')->with([
            'cpage'=>"settings",
        ]);
    }
    public function edit(Service $service)
    {
        return view('church-services.edit')->with([
            'cpage' => "settings",
            'service' => $service
        ]);
    }
    public function update(UpdateRequest $request,Service $service)
    {
        $data = $request->post();
        if(is_numeric($request->post('name'))){
            return back()->with(['error-notification'=>"Invalid Character Entered on Name"]);
        }
        $service->update($data);
        activity('church-services')
            ->log("Updated an Church Service")->causer(request()->user());
        return redirect()->route('services.show',$service->id)->with([
            'success-notification'=>"Successfully Updated"
        ]);
    }
    public function destroy(Request $request, Service $service)
    {
        $data = $request->post();
        DB::table('services')
            ->where(['id' => $request->post('id')])
            ->update(['soft_delete' => '1']);
        $service->update($data);

        return redirect()->route('services.index')->with([
            'success-notification'=>"Successfully Deleted"
        ]);
    }
}
