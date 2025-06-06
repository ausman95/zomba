<?php

namespace App\Http\Controllers;

use App\Http\Requests\members\StoreRequest;
use App\Http\Requests\members\UpdateRequest;
use App\Models\Church;
use App\Models\Member;
use App\Models\MemberMinistry;
use App\Models\MemberPayment;
use App\Models\Ministry;
use App\Models\Position;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MemberController extends Controller
{
    public function merge(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'member_id_to_delete' => 'required|numeric',
            'member_id_to_keep'      => 'required|numeric',
            'name'                => 'required|string', // Changed to string, as name is a string
        ]);

        // Ensure the members to merge are different
        if ($validatedData['member_id_to_keep'] === $validatedData['member_id_to_delete']) {
            return redirect()->route('members.merge')->with([ // Corrected route name
                'error-notification' => 'Cannot merge the same member.',
            ]);
        }

        // Update member_payments to point to the kept member
        MemberPayment::where('member_id', $validatedData['member_id_to_delete'])
            ->update(['member_id' => $validatedData['member_id_to_keep']]);

        // Get the phone number of the member to be deleted
        $deletedMember = Member::find($validatedData['member_id_to_delete']);
        $deletedMemberPhone = $deletedMember->phone_number;

        // Soft delete the merged member and update name
        Member::where('id', $validatedData['member_id_to_delete'])
            ->update(['soft_delete' => 1]);

        // Update the phone number of the member to keep
        Member::where('id', $validatedData['member_id_to_keep'])
            ->update(['phone' => $deletedMemberPhone, 'name' => $validatedData['name']]);

        // Log the member merge activity
        activity('members')
            ->log("Merged member ID {$validatedData['member_id_to_delete']} into member ID {$validatedData['member_id_to_keep']}") // Added more details in log
            ->causer(request()->user());

        // Redirect with a success message
        return redirect()->route('members.index')->with([
            'success-notification' => 'Members merged successfully.', // More descriptive message
        ]);
    }


    public function memberMerge()
    {
        $members = Member::where(['soft_delete'=>0])->orderBy('id','desc')->get();
        if(request()->user()->designation=='church'){
            $members = $this->getHomeChurchMembers();
        }
        return view('members.merge')->with([
            'cpage'=>"members",
            'members'=>$members,
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
    public function getHomeChurch()
    {
        return Member::where(['id'=>request()->user()->member_id])
            ->where(['soft_delete'=>0])
            ->orderBy('id','desc')
            ->first()->church_id;
    }
    public function getHomeChurchMembers()
    {
       return Member::where(['church_id'=>$this->getHomeChurch()])
            ->where(['soft_delete'=>0])
            ->orderBy('id','desc')
            ->get();
    }
    public function index()
    {
        $members = Member::where(['soft_delete'=>0])->orderBy('name','ASC')->get();
        if(request()->user()->designation=='church'){
            $members = $this->getHomeChurchMembers();
        }

        activity('Members')
            ->log("Accessed Members listing")->causer(request()->user());
        return view('members.index')->with([
            'cpage' => "members",
            'ministries'=>Ministry::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
            'churches'=>Church::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
            'members'=>$members,
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
        $home_church = 0;
        if(request()->user()->designation=='church'){
            $home_church = $this->getHomeChurch();
        }
        return view('members.create')->with([
            'cpage' => "members",
            'home_church'=>$home_church,
            'positions'=> Position::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
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
        $message ='Dear ' . $request->post('name') . ' You have been registered
        into the EASYMAOG System '.PHP_EOL.PHP_EOL. ' AREA 25, VICTORY TEMPLE';
        if($request->post('phone_number')!=0) {
            $receiptController->sendSms($request->post('phone_number'), $message);
        }
        if($request->post('phone')!=0) {
            $receiptController->sendSms($request->post('phone'), $message);
        }
        activity('members')
            ->log("Created a new member")->causer(request()->user());
        return redirect()->route('members.create')->with([
            'success-notification'=>"Successfully Created"
        ]);
    }
    public function show(Member $member)
    {
        $cpage = 'tithe';
        if(request()->user()->designation=='administrator'){
            $cpage = "members";
        }
        return view('members.show')->with([
            'cpage'=>$cpage,
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
            'positions'=> Position::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
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
