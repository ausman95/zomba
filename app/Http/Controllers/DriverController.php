<?php

namespace App\Http\Controllers;

use App\Http\Requests\Drivers\StoreRequest;
use App\Http\Requests\Drivers\UpdateRequest;
use App\Models\Accounts;
use App\Models\Driver;
use App\Models\Labourer;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function index()
    {
        activity('Allowances')
            ->log("Accessed Allowances listing")->causer(request()->user());
        return view('drivers.index')->with([
            'cpage' => "drivers",
            'drivers'=>Driver::orderBy('id','desc')->get(),
        ]);
    }
    public function create()
    {
        return view('drivers.create')->with([
            'cpage'=>"drivers",
            'accounts'=>Accounts::all(),
            'drivers'=>Labourer::all(),
        ]);
    }
    public function store(StoreRequest $request)
    {
        $data = $request->post();

        Driver::create($data);
        activity('ALLOWANCES')
            ->log("Created a new Driver Allowance")->causer(request()->user());
        return redirect()->back()->with([
            'success-notification'=>"Allowance successfully Created"
        ]);
    }
    public function show(Driver $driver)
    {
        return view('drivers.show')->with([
            'cpage'=>"drivers",
            'driver'=>$driver
        ]);
    }
    public function edit( Driver $driver)
    {
        return view('drivers.edit')->with([
            'cpage' => "drivers",
            'driver'=>$driver,
            'accounts'=>Accounts::all(),
            'labourers'=>Labourer::all(),
        ]);
    }
    public function update(UpdateRequest $request,Driver $driver)
    {
        $data = $request->post();
        $driver->update($data);
        activity('Allowances')
            ->log("Updated an Allowance")->causer(request()->user());
        return redirect()->route('drivers.show',$driver->id)->with([
            'success-notification'=>"Allowance successfully Updated"
        ]);
    }
}
