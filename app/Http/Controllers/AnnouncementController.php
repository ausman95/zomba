<?php

namespace App\Http\Controllers;

use App\Http\Requests\Announcement\StoreRequest;
use App\Http\Requests\Announcement\UpdateRequest;
use App\Models\Announcement;
use App\Models\Month;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnnouncementController extends Controller
{
    public function destroy(Request $request, Announcement $announcement)
    {

        $data = $request->post();
        DB::table('announcements')
            ->where(['id' => $request->post('id')])
            ->update(['soft_delete' => '1']);
        $announcement->update($data);

        return redirect()->route('announcements.index')->with([
            'success-notification'=>"Successfully Deleted"
        ]);
    }

    public function index()
    {
        activity('Announcements')
            ->log("Accessed Announcements")->causer(request()->user());

        return view('announcements.index')->with([
            'cpage' => "announcements",
            'announcements'=>Announcement::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
        ]);
    }

    public function create()
    {
        return view('announcements.create')->with([
            'cpage'=>"announcements"
        ]);
    }

    public function store(StoreRequest $request)
    {
        $data = $request->post();
        if(is_numeric($request->post('from'))){
            return back()->with(['error-notification'=>"Invalid Character Entered on From"]);
        }
        if(is_numeric($request->post('title'))){
            return back()->with(['error-notification'=>"Invalid Character on Title"]);
        }
        if(is_numeric($request->post('body'))){
            return back()->with(['error-notification'=>"Invalid Character on Full Announcement"]);
        }
        $check_data = [
            'from'=>$data['from'],
            'title'=>$data['title'],
            'body'=>$data['body']
        ];
        if(Announcement::where($check_data)->first()){
            // labourer is already part of this project
            return back()->with(['error-notification'=>"Invalid, The Announcement was already created"]);
        }
        Announcement::create($data);
        activity('Announcements')
            ->log("Created an Announcement")->causer(request()->user());
        return redirect()->route('announcements.index')->with([
            'success-notification'=>"Successfully Created"
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Announcement $announcement)
    {
        activity('Announcements')
            ->log("Accessed Announcements")->causer(request()->user());
        return view('announcements.show')->with([
            'cpage'=>"announcements",
            'announcement'=>$announcement,
        ]);
    }
    public function edit(Announcement $announcement)
    {
        return view('announcements.edit')->with([
            'cpage' => "announcements",
            'announcement' => $announcement
        ]);
    }
    public function update(UpdateRequest $request,Announcement $announcement)
    {
        $data = $request->post();
        if(is_numeric($request->post('from'))){
            return back()->with(['error-notification'=>"Invalid Character Entered on From"]);
        }
        if(is_numeric($request->post('title'))){
            return back()->with(['error-notification'=>"Invalid Character on Title"]);
        }
        if(is_numeric($request->post('body'))){
            return back()->with(['error-notification'=>"Invalid Character on Full Announcement"]);
        }
        $announcement->update($data);
        activity('Announcements')
            ->log("Edited an announcement")->causer(request()->user());
        return redirect()->route('announcements.show',$announcement->id)->with([
            'success-notification'=>"Successfully Updated"
        ]);
    }
}
