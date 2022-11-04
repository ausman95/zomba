<?php

namespace App\Http\Controllers;

use App\Models\Allocation;
use App\Models\Labourer;
use App\Models\LabourerPayments;
use App\Models\ProjectPayment;
use Illuminate\Http\Request;
use App\Http\Requests\Allocations\StoreRequest;
class AllocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $data = $request->post();
        // something should be done here

        $check_data = [
            'labourer_id'=>$data['labourer_id'],
            'project_id'=>$data['project_id']
        ];

        if(Allocation::where($check_data)->first()){
            // labourer is already part of this project
            return back()->with(['error-notification'=>"Labourer is already assigned to this project"]);
        }
        $bala = LabourerPayments::where(['labourer_id'=>$request->post('labourer_id')])->latest()->first();
        @$balances = $bala->balance;
        if(!$balances){
            $balances = 0;
        }
        if(!$request->post('amount')){
            $amount = 0;
        }else{
            $amount =   $request->post('amount');
        }
        $data = [
            'labourer_id'=>$request->post('labourer_id'),
            'amount'=>$amount,
            'project_id'=>$request->post('project_id'),
            'expense_name'=>"Project Agreed Amount",
            'balance'=>$amount+$balances,
            'method'=>4,
            'type'=>1,
        ];
        $allocation= [
            'labourer_id'=>$request->post('labourer_id'),
            'amount'=>$amount,
            'project_id'=>$request->post('project_id'),
        ];
        Allocation::create($allocation);
        LabourerPayments::create($data);
        activity('ALLOCATIONS')
            ->log("Allocated a Labour ")->causer(request()->user());

        return redirect()->route('members.show',$request->labourer_id)->with([
            'success-notification'=>"Labourer successfully Allocated"
        ]);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Allocation $allocation)
    {
        return view('allocations.show')->with([
            'cpage'=>"allocations",
            'allocation'=>$allocation
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit( Labourer $allocate)
    {
       $labourer = Labourer::all();
        return view('allocations.edit')->with([
            'cpage' => "allocations",
            'allocate' => $allocate,
            'labourer' =>$labourer
        ]);
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
    public function destroy(Allocation  $allocation)
    {
        try{
            $allocation->delete();
            activity('ALLOCATIONS')
                ->log("Deleted an Allocation")->causer(request()->user());
            return redirect()->route('members.show',$allocation->labourer_id)->with([
                'success-notification'=>"La successfully Deleted"
            ]);

        }catch (\Exception $exception){
            return redirect()->route('members.members',$allocation->labourer_id)->with([
                'error-notification'=>"Something went Wrong ".$exception.getMessage()
            ]);
        }
    }
}
