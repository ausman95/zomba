<?php

namespace App\Http\Controllers;

use App\Http\Requests\members\StoreRequest;
use App\Http\Requests\members\UpdateRequest;
use App\Models\Church;
use App\Models\Member;
use App\Models\Ministry;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index()
    {
        activity('Members')
            ->log("Accessed Members listing")->causer(request()->user());

        return view('members.index')->with([
            'cpage' => "members",
            'members'=> Member::orderBy('id','desc')->get()
        ]);
    }
    public function create()
    {
        return view('members.create')->with([
            'cpage' => "members",
            'churches'=> Church::orderBy('id','desc')->get(),
            'ministries'=> Ministry::orderBy('id','desc')->get(),
        ]);
    }
    public function store(StoreRequest $request)
    {
        $data = $request->post();

        Member::create($data);
        activity('members')
            ->log("Created a new member")->causer(request()->user());
        return redirect()->back()->with([
            'success-notification'=>"Successfully Created"
        ]);
    }
    public function show(Member $member)
    {
        return view('members.show')->with([
            'cpage'=>"members",
            'member'=>$member,
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
    public function update(UpdateRequest $request,Member $member)
    {
        $data = $request->post();

        $member->update($data);
        activity('members')
            ->log("Updated an member")->causer(request()->user());
        return redirect()->route('members.show',$member->id)->with([
            'success-notification'=>"Successfully Updated"
        ]);
    }
}
