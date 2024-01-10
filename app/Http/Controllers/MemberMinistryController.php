<?php

namespace App\Http\Controllers;

use App\Http\Requests\Allocations\StoreRequest;
use App\Models\MemberMinistry;
use Illuminate\Http\Request;

class MemberMinistryController extends Controller
{
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
    public function show(MemberMinistry $memberMinistry)
    {
       // die($memberMinistry);
        try{
            $memberMinistry->delete();
            activity('ALLOCATIONS')
                ->log("Deleted an Allocation")->causer(request()->user());
            return redirect()->back()->with([
                'success-notification'=>" Successfully Deleted"
            ]);

        }catch (\Exception $exception){
            return redirect()->back()->with([
                'error-notification'=>"Something went Wrong ".$exception.getMessage()
            ]);
        }
    }

}
