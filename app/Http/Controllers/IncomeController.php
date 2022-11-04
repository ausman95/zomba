<?php

namespace App\Http\Controllers;

use App\Models\Accounts;
use App\Models\Banks;
use App\Models\Incomes;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Requests\Incomes\StoreRequest;
use App\Http\Requests\Incomes\UpdateRequest;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $incomes = Incomes::where(['transaction_type'=>1])->get();
      //  dd($incomes);
        return view('incomes.index')->with([
            'cpage' => "incomes",
            'incomes'=>$incomes
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $projects = Project::all();
        $accounts  = Accounts::all();
        $banks  = Banks::all();

        return view('incomes.create')->with([
            'cpage'=>"incomes",
            'projects' => $projects,
            'banks' =>  $banks,
            'accounts'=>$accounts
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store (StoreRequest $request)
    {
        $data = $request->post();
        Incomes::create($data);
        return redirect()->route('incomes.index')->with([
            'success-notification'=>"Income successfully Created"
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
