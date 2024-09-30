<?php

namespace App\Http\Controllers;

use App\Http\Requests\Announcement\StoreRequest;
use App\Http\Requests\Announcement\UpdateRequest;
use App\Models\Announcement;
use App\Models\Ministry;
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
            'cpage'=>"announcements",
            'ministries'=>Ministry::all()
        ]);
    }

    public function store(StoreRequest $request, NewController $newController)
    {
        $data = $request->post();

        if(is_numeric($request->post('title'))){
            return back()->with(['error-notification'=>"Invalid Character on Title"]);
        }
        if(is_numeric($request->post('body'))){
            return back()->with(['error-notification'=>"Invalid Character on Full Announcement"]);
        }
        $check_data = [
            'from'=>$data['from'],
            'date'=>$data['date'],
            'title'=>$data['title'],
            'body'=>$data['body']
        ];
        if(Announcement::where($check_data)->first()){
            // labourer is already part of this project
            return back()->with(['error-notification'=>"Invalid, The Announcement was already created"]);
        }



        // Create a new news item
        $newAnnoucement = [
            'from'=>$data['from'],
            'date'=>$data['date'],
            'title'=>$data['title'],
            'body'=>$data['body'],
            'created_by'=>request()->user()->id,
             'updated_by'=>request()->user()->id,
        ];

        // Check if there's an uploaded image
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["image"])) {
            $file_name = $_FILES["image"]["name"];
            $file_tmp = $_FILES["image"]["tmp_name"];

            // Insert the news entry into the database
            $newAnnoucementEntry = Announcement::create($newAnnoucement);

            // Retrieve the ID of the inserted record
            $newAnnoucementId = $newAnnoucementEntry->id;

            // Rename the uploaded image to the ID of the record
            $newFileName = $newAnnoucementId . '.' . pathinfo($file_name, PATHINFO_EXTENSION);
            $file_path = public_path('img' . DIRECTORY_SEPARATOR . 'blog' . DIRECTORY_SEPARATOR . $newFileName);

            if (move_uploaded_file($file_tmp, $file_path)) {
                // Resize the uploaded image
                $newController->resizeImage($file_path, 858, 460);
                // Update the record in the database with the new image name
                $newAnnoucementEntry->update(['url' => $newFileName]);
            } else {
                return back()->with(['error-notification' => "Failed to upload the image"]);
            }
        } else {
            // If no image was uploaded, create the news entry without an image
             Announcement::create($newAnnoucement);
        }

        // Log activity
        activity('Announcement')->log("Created an Announcement")->causer(request()->user());


        return redirect()->route('announcements.index')->with([
            'success-notification' => "Successfully created"
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
            'ministries'=>Ministry::all(),
            'announcement' => $announcement
        ]);
    }
    public function update(UpdateRequest $request, Announcement $announcement, NewController $newController)
    {
        // Validate the request data
        $validatedData = $request->post();


        try {
            // Check if a new image is being uploaded
            if ($request->hasFile('image')) {
                // Delete the old image if it exists
                if (!empty($announcement->url)) {
                    $oldImagePath = public_path('img/blog/' . $announcement->url);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                // Upload and process the new image
                $image = $request->file('image');
                $newFileName = $announcement->id . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('img/blog/'), $newFileName);

                // Resize the uploaded image
                $file_path = public_path('img/blog/') . $newFileName;
                $newController->resizeImage($file_path, 263, 177);

                // Update the news record with the new image name
                $validatedData['url'] = $newFileName;
            }

            // Update the news record with the new title and/or image name
            $announcement->update($validatedData);

            // Log the update action
            activity('announcements')->log("Updated an Announcement")->causer(request()->user());

            // Redirect back to the news details page with a success notification
            return redirect()->route('announcements.show', $announcement->id)->with([
                'success-notification' => "Successfully Updated"
            ]);
        } catch (\Exception $e) {
            // Log any errors that occur
            \Log::error("Error updating announcements: " . $e->getMessage());
            // Redirect back with an error notification including the error message
            return back()->with(['error-notification' => "Failed to update Announcements: " . $e->getMessage()]);
        }
    }
}
