<?php

namespace App\Http\Controllers;

use App\Http\Requests\Budgets\StoreRequest;
use App\Http\Requests\Budgets\UpdateRequest;
use App\Models\Accounts;
use App\Models\Budget;
use App\Models\FinancialYear;
use App\Models\Incomes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BudgetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        activity('BUDGETS')
            ->log("Accessed a Budget")->causer(request()->user());
        $budget = Budget::orderBy('id','desc')->get();
        return view('budgets.index')->with([
            'cpage' => "finances",
            'budgets'=>$budget
        ]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('budgets.create')->with([
            'cpage'=>"finances",
            'accounts' => Accounts::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
            'years' => FinancialYear::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreRequest $request)
    {
        if($request->post('start_date')>$request->post('end_date')){
            return back()->with(['error-notification'=>"Invalid End Date Entered"]);
        }

        $data = $request->post();
        $check_data = [
            'account_id'=>$data['account_id'],
            'start_date'=>$data['start_date'],
            'year_id'=>$data['year_id'],
            'end_date'=>$data['end_date']
        ];

        if(Budget::where($check_data)->first()){
            // labourer is already part of this project
            return back()->with(['error-notification'=>"The Budget has been already  allocated for this account for this year"]);
        }

        Budget::create($data);
        activity('BUDGETS')
            ->log("Created a Budget")->causer(request()->user());
        return redirect()->route('budgets.index')->with([
            'success-notification'=>"Budget successfully Created"
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Budget $budget)
    {
        return view('budgets.show')->with([
            'cpage'=>"finances",
            'budget'=>$budget,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit( Budget $budget)
    {
        $accounts = Accounts::all();
        return view('budgets.edit')->with([
            'cpage' => "finances",
            'budget' => $budget,
            'years' => FinancialYear::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
            'accounts' => $accounts,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Budget $budget)
    {
        $data = $request->post();
        if($request->post('start_date')>$request->post('end_date')){
            return back()->with(['error-notification'=>"Invalid End Date Entered"]);
        }
        $budget->update($data);
        activity('BUDGETS')
            ->log("Updated a Budget")->causer(request()->user());
        return redirect()->route('budgets.show',$budget->id)->with([
            'success-notification'=>"Budget successfully Updated"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Budget $budget)
    {
        try{
            $budget->delete();
            activity('BUDGETS')
                ->log("Deleted a Budget")->causer(request()->user());
            return redirect()->route('budgets.index')->with([
                'success-notification'=>"Budget successfully Deleted"
            ]);

        }catch (\Exception $exception){
            return redirect()->route('budgets.index')->with([
                'error-notification'=>"Something went Wrong ".$exception.getMessage()
            ]);
        }
    }
}
