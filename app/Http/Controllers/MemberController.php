<?php

namespace App\Http\Controllers;

use App\Http\Requests\members\StoreRequest;
use App\Http\Requests\members\UpdateRequest;
use App\Models\Church;
use App\Models\Member;
use App\Models\MemberMinistry;
use App\Models\MemberPayment;
use App\Models\Ministry;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MemberController extends Controller
{
    public function merge(Request $request )
    {
        $request->validate([
            'member_id_to_delete' => "required|numeric",
            'member_to_keep' => "required|numeric",
        ]);
        if($request->post('member_to_keep')==$request->post('member_id_to_delete')){
            return redirect()->route('member.merge')->with([
                'error-notification'=>"Check the Inputs and Try again "
            ]);
        }
        MemberPayment::where(['member_id' => $request->post('member_id_to_delete')])
            ->update(['member_id' => $request->post('member_to_keep')]);

//        Member::where(['id' => $request->post('member_id_to_delete')])
//            ->delete();

        Member::where(['id' => $request->post('member_id_to_delete')])
            ->update(['soft_delete' => 1]);

        activity('members')
            ->log("Merged some members")->causer(request()->user());
        return redirect()->route('members.index')->with([
            'success-notification'=>"Successfully"
        ]);
    }
    public function memberMerge()
    {

        return view('members.merge')->with([
            'cpage'=>"members",
            'members'=>Member::orderBy('name','ASC')->get(),
        ]);
    }
    public function allocateMinistry( Member $member)
    {
        return view('members.allocate')->with([
            'cpage' => "members",
            'member'=>$member,
            'ministries' => Ministry::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
        ]);
    }
    public function index()
    {
        activity('Members')
            ->log("Accessed Members listing")->causer(request()->user());
        return view('members.index')->with([
            'cpage' => "members",
            'ministries'=>Ministry::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
            'churches'=>Church::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
            'members'=> Member::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
        ]);
    }
    public function generateReport(Request $request)
    {
        $church_id = $request->post('church_id');
        $ministry_id = $request->post('ministry_id');
        $gender = $request->post('gender');

        if($gender&&empty($ministry_id)&&empty($church_id)){
            $members = Member::where(['gender'=>$gender])->where(['soft_delete'=>0])->orderBy('id','desc')->get();
        }elseif($gender&&empty($ministry_id)&&$church_id){
            $members = Member::where(['church_id'=>$church_id])->where(['gender'=>$gender])->where(['soft_delete'=>0])->orderBy('id','desc')->get();
        }elseif(empty($gender)&&empty($ministry_id)&&$church_id){
            $members = Member::where(['church_id'=>$church_id])->where(['soft_delete'=>0])->orderBy('id','desc')->get();
        }
        elseif(empty($gender)&&empty($church_id)&&$ministry_id){
            $members = Member::getMemberByMinistry($ministry_id);
        }
        elseif(empty($gender)&&$church_id&&$ministry_id){
            $members = Member::getMemberByMinistryBYChurch($ministry_id,$church_id);
        }
        elseif($gender&&$church_id&&$ministry_id){
            $members = Member::getMemberByMinistryByGenderByChurch($ministry_id,$gender,$church_id);
        }
        elseif($gender&&empty($church_id)&&$ministry_id){
            $members = Member::getMemberByMinistryByGender($ministry_id,$gender);
        }else{
          $members = Member::where(['soft_delete'=>0])->orderBy('id','desc')->get();
        }
        activity('Members')
            ->log("Accessed Members")->causer(request()->user());
        return view('members.index')->with([
            'cpage' => "members",
            'report'=>1,
            'ministries'=>Ministry::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
            'churches'=>Church::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
            'members'=> $members
        ]);
    }
    public function create()
    {
        return view('members.create')->with([
            'cpage' => "members",
            'churches'=> Church::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
            'ministries'=> Ministry::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
        ]);
    }
    public function store(ReceiptController $receiptController, StoreRequest $request, LabourerController $labourerController)
    {
        if(is_numeric($request->post('name'))){
            return back()->with(['error-notification'=>"Invalid Character Entered on Member Name"]);
        }
        $data = $request->post();
//        if($labourerController->validating($data['phone_number'])==0){
//            // labourer is already part of this project
//            return back()->with(['error-notification'=>"Invalid Phone number"]);
//        }
        $member = Member::create($data);
        $ministries = Ministry::where(['soft_delete'=>0])->orderBy('id','desc')->get();
        foreach ($ministries as $ministry) {
            $status = $request->post('min-' . $ministry->id);
            $data = [
                'member_id' => $member->id,
                'ministry_id' => $ministry->id,
            ];
            if($status){
                MemberMinistry::create($data);
            }
        }
        if($request->post('phone_number')!=0) {
            $message = 'MALAWI ASSEMBLIES OF GOD' .
                PHP_EOL . PHP_EOL . 'Dear ' . $request->post('name') . PHP_EOL . PHP_EOL . ' You have been registered into the new church system ' .
                PHP_EOL . PHP_EOL . PHP_EOL . ' AREA 25 VICTORY TEMPLE';
            $receiptController->sendSms($request->post('phone_number'), $message);
        }
        activity('members')
            ->log("Created a new member")->causer(request()->user());
        return redirect()->route('members.index')->with([
            'success-notification'=>"Successfully Created"
        ]);
    }
    public function show(Member $member)
    {
        return view('members.show')->with([
            'cpage'=>"members",
            'member'=>$member,
            'allocations'=>$member->allocations,
            'transactions'=>$member->payments
        ]);
    }
    public function edit( Member $member)
    {
        return view('members.edit')->with([
            'cpage' => "members",
            'member' => $member,
            'churches'=> Church::orderBy('id','desc')->get(),
            'ministries'=> Ministry::orderBy('id','desc')->get(),
        ]);
    }
    public function update(UpdateRequest $request,Member $member,LabourerController $labourerController)
    {
        $data = $request->post();
        if(is_numeric($request->post('name'))){
            return back()->with(['error-notification'=>"Invalid Character Entered on Member Name"]);
        }
//        if($labourerController->validating($request->post('phone_number'))==0){
//            // labourer is already part of this project
//            return back()->with(['error-notification'=>"Invalid Phone number"]);
//        }
        $member->update($data);
        activity('members')
            ->log("Updated an member")->causer(request()->user());
        return redirect()->route('members.show',$member->id)->with([
            'success-notification'=>"Successfully Updated"
        ]);
    }
    public function destroy(Request $request, Member $member)
    {

        $data = $request->post();
        DB::table('members')
            ->where(['id' => $request->post('id')])
            ->update(['soft_delete' => '1']);
        $member->update($data);

        return redirect()->route('members.index')->with([
            'success-notification'=>"Successfully Deleted"
        ]);
    }

}
