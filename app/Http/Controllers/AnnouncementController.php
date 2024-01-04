<?php

namespace App\Http\Controllers;

use App\Http\Requests\Announcement\StoreRequest;
use App\Http\Requests\Announcement\UpdateRequest;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        activity('Announcements')
            ->log("Accessed Announcements")->causer(request()->user());

        return view('announcements.index')->with([
            'cpage' => "announcements",
            'announcements'=>Announcement::orderBy('id','desc')->get()
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

        $announcement->update($data);
        activity('Announcements')
            ->log("Edited an announcement")->causer(request()->user());
        return redirect()->route('announcements.show',$announcement->id)->with([
            'success-notification'=>"Successfully Updated"
        ]);
    }
}
