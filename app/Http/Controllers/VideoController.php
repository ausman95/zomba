<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Retrieve all videos
        $videos = Video::all();
        $cpage = 'attendances'; // Set the current page variable
        return view('videos.index', compact('videos','cpage'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cpage = 'attendances'; // Set the current page variable
        return view('videos.create', compact('cpage')); // Pass $cpage to the view
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url',
        ]);

        // Convert the YouTube URL to embed format
        $videoId = null;
        if (preg_match('/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^&\n]{11})/', $request->url, $matches)) {
            $videoId = $matches[1]; // Extract video ID from the URL
        }

        // If video ID is found, create the embed URL
        if ($videoId) {

            $embedUrl = '//www.youtube.com/embed/'. $videoId;
           // die($embedUrl);
            // Create the video record
            Video::create([
                'title' => $request->title,
                'url' => $embedUrl, // Store the embed URL
                'created_by'=>$request->user()->id,
                'updated_by'=>$request->user()->id,
            ]);

            return redirect()->route('videos.index')->with('success-notification', 'Video created successfully.');
        } else {
            return redirect()->back()->withErrors(['url' => 'Invalid YouTube URL provided.']);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function show(Video $video)
    {
        $cpage = 'attendances'; // Set the current page variable
        return view('videos.show', compact('video','cpage'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function edit(Video $video)
    {
        $cpage = 'attendances'; // Set the current page variable
        return view('videos.edit', compact('video', 'cpage')); // Pass $video and $cpage to the view
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Video $video)
    {
        // Validate the request data
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $video->update([
            'title' => $request->title,
            'updated_by' => $request->user()->id, // Assuming you want to track who updated it
        ]);

        return redirect()->route('videos.index')->with('success-notification', 'Video updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function destroy(Video $video)
    {
        // Delete the image from storage if it exists
        if ($video->image) {
            Storage::disk('public')->delete($video->image);
        }

        // Delete the video record
        $video->delete();

        return redirect()->route('videos.index')->with('success', 'Video deleted successfully.');
    }
}
