<?php

namespace App\Http\Controllers;

use App\Models\Allocation;
use App\Models\Labourer;
use App\Models\LabourerPayments;
use App\Models\MemberMinistry;
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
            'member_id'=>$data['member_id'],
            'ministry_id'=>$data['ministry_id']
        ];

        if(MemberMinistry::where($check_data)->first()){
            // labourer is already part of this project
            return back()->with(['error-notification'=>"Member is already assigned to this Ministry"]);
        }
        MemberMinistry::create($data);
        activity('ALLOCATIONS')
            ->log("Allocated a Member to a Ministry ")->causer(request()->user());
        return redirect()->route('members.show',$data['member_id'])->with([
            'success-notification'=>"Successful"
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
    public function destroy(MemberMinistry  $memberMinistry)
    {
        try{
            $memberMinistry->delete();
            activity('ALLOCATIONS')
                ->log("Deleted an Allocation")->causer(request()->user());
            return redirect()->route('members.show',$memberMinistry->labourer_id)->with([
                'success-notification'=>" Successfully Deleted"
            ]);

        }catch (\Exception $exception){
            return redirect()->route('members.show',$memberMinistry->labourer_id)->with([
                'error-notification'=>"Something went Wrong ".$exception.getMessage()
            ]);
        }
    }
}
