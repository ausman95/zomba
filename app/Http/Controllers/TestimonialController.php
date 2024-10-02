<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use App\Models\Member;
use Illuminate\Http\Request;
use App\Http\Requests\Testimonials\StoreRequest;
use App\Http\Requests\Testimonials\UpdateRequest;
use App\Http\Controllers\Controller;

class TestimonialController extends Controller
{
    public function index()
    {
        activity('TESTIMONIALS')
            ->log("Accessed Testimonials")->causer(request()->user());
        $testimonials = Testimonial::orderBy('id', 'desc')->get();
        return view('testimonials.index')->with([
            'cpage' => "members",
            'testimonials' => $testimonials
        ]);
    }

    public function create()
    {
        $members = Member::all();
        return view('testimonials.create')->with([
            'cpage' => "members",
            'members' => $members
        ]);
    }

    public function store(Request $request, NewController $controller)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'member_id' => 'required|exists:members,id',  // Ensure member_id exists in the members table
            'statement' => 'required|string|max:500',     // Statement is required, should be a string, and has a max length of 500 characters
            'url' => 'nullable|url',                      // Optional field, but if provided, it should be a valid URL
            'image' => 'nullable|image|max:2048',         // Optional image field, but if provided, it should be an image with a max size of 2MB
        ]);

        // Prepare data for creating a new testimonial
        $newTestimonialData = [
            'member_id' => $validatedData['member_id'],
            'statement' => $validatedData['statement'],
            'created_by' => $request->user()->id,  // Set the creator to the current authenticated user
            'updated_by' => $request->user()->id,  // Set the updater to the current authenticated user
        ];

        // Handle image upload if present
        if ($request->hasFile('image')) {
            // Retrieve temporary file details
            $file_tmp = $request->file('image')->getPathname();

            // Create the testimonial entry first
            $newTestimonialEntry = Testimonial::create($newTestimonialData);
            $newTestimonialId = $newTestimonialEntry->id;

            // Create a unique filename based on testimonial ID
            $newFileName = $newTestimonialId . '.' . $request->file('image')->getClientOriginalExtension();
            $file_path = public_path('img' . DIRECTORY_SEPARATOR . 'testimonials' . DIRECTORY_SEPARATOR . $newFileName);

            // Move the uploaded file and check for success
            if (move_uploaded_file($file_tmp, $file_path)) {
                // Resize the uploaded image using the controller function
                $controller->resizeImage($file_path, 456, 456);

                // Update the testimonial record with the new image file name
                $newTestimonialEntry->update(['url' => $newFileName]);
            } else {
                // If the file upload fails, return an error notification
                return back()->with(['error-notification' => "Failed to upload the image"]);
            }
        } else {
            // If no image is uploaded, just create the testimonial without image
            Testimonial::create($newTestimonialData);
        }

        // Log the creation of a new testimonial
        activity('TESTIMONIALS')->log("Created a Testimonial")->causer(request()->user());

        // Redirect to the testimonials index page with a success notification
        return redirect()->route('testimonials.index')->with(['success-notification' => "Testimonial successfully created!"]);
    }

    public function show(Testimonial $testimonial)
    {
        return view('testimonials.show')->with([
            'cpage' => "members",
            'testimonial' => $testimonial
        ]);
    }

    public function edit(Testimonial $testimonial)
    {
        $members = Member::all();
        return view('testimonials.edit')->with([
            'cpage' => "members",
            'testimonial' => $testimonial,
            'members' => $members
        ]);
    }

    public function update(Request $request, Testimonial $testimonial, NewController $controller)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'member_id' => 'required|exists:members,id',  // Ensure member_id exists in the members table
            'statement' => 'required|string|max:500',     // Statement is required, should be a string, and has a max length of 500 characters
            'url' => 'nullable|url',                      // Optional field, but if provided, it should be a valid URL
            'image' => 'nullable|image|max:2048',         // Optional image field, but if provided, it should be an image with a max size of 2MB
        ]);

        try {
            // Handle image upload if present
            if ($request->hasFile('image')) {
                // Delete old image if it exists
                if ($testimonial->image) {
                    $oldImagePath = public_path('img/testimonials/' . $testimonial->image);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                // Process the new image upload
                $image = $request->file('image');
                $newFileName = $testimonial->id . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('img/testimonials/'), $newFileName);

                // Resize the image
                $filePath = public_path('img/testimonials/' . $newFileName);
                $controller->resizeImage($filePath, 456, 456);

                // Update the validated data with the new image filename
                $validatedData['image'] = $newFileName;
            }

            // Update the testimonial with the validated data
            $testimonial->update($validatedData);

            // Log the update activity
            activity('TESTIMONIALS')->log("Updated a Testimonial")->causer(request()->user());

            // Redirect to the show page with a success notification
            return redirect()->route('testimonials.show', $testimonial->id)->with([
                'success-notification' => "Testimonial successfully updated!"
            ]);
        } catch (\Exception $e) {
            // Log the error and return an error notification
            \Log::error("Error updating testimonial: " . $e->getMessage());
            return back()->with(['error-notification' => "Failed to update testimonial. Please try again."]);
        }
    }

    public function destroy(Request $request, Testimonial $testimonial)
    {
        if (!empty($testimonial->url)) {
            $imagePath = public_path('img/testimonials/' . $testimonial->url);

            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $testimonial->delete();

        return redirect()->route('testimonials.index')->with([
            'success-notification' => "Successfully Deleted"
        ]);
    }
}
