<?php

namespace App\Http\Controllers;

use App\Http\Requests\Vacancies\UpdateRequest;
use App\Models\Duty;
use App\Models\News;
use App\Models\Paragraph;
use Illuminate\Support\Facades\Storage; // Import Storage facade if you're using it for file storage
use App\Models\Vacancy;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\DB;

class NewController extends Controller
{

    public function update(\App\Http\Requests\News\UpdateRequest $request, News $news)
    {
        // Validate the request data
        $validatedData = $request->validated();

        try {
            // Check if a new image is being uploaded
            if ($request->hasFile('image')) {
                // Delete the old image if it exists
                if (!empty($news->url)) {
                    $oldImagePath = public_path('img/blog/' . $news->url);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                // Upload and process the new image
                $image = $request->file('image');
                $newFileName = $news->id . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('img/blog/'), $newFileName);

                // Resize the uploaded image
                $file_path = public_path('img/blog/') . $newFileName;
                $this->resizeImage($file_path, 900, 600);

                // Update the news record with the new image name
                $validatedData['url'] = $newFileName;
            }

            // Update the news record with the new title and/or image name
            $news->update($validatedData);

            // Log the update action
            activity('news')->log("Updated a News")->causer(request()->user());

            // Redirect back to the news details page with a success notification
            return redirect()->route('news.show', $news->id)->with([
                'success-notification' => "Successfully Updated"
            ]);
        } catch (\Exception $e) {
            // Log any errors that occur
            \Log::error("Error updating news: " . $e->getMessage());
            // Redirect back with an error notification
            return back()->with(['error-notification' => "Failed to update news"]);
        }
    }
    public function edit( News $news)
    {
        return view('news.edit')->with([
            'cpage' => "news",
            'news'=>$news,
        ]);
    }
    public function destroy(Request $request)
    {
        // Find the news item by ID
        $newsItem = News::findOrFail($request->post('id'));

        // Delete the associated paragraphs
        $newsItem->paragraph()->delete();

        // Delete the associated image if it exists
        if (!empty($newsItem->url)) {
            $imagePath = public_path('img/blog/' . $newsItem->url);

            if (file_exists($imagePath)) {
                // Delete the image file
                unlink($imagePath);
            }
        }

        // Perform a soft delete by setting the deleted_at timestamp
        $newsItem->delete();

        return redirect()->route('news.index')->with([
            'success-notification' => "Successfully Deleted"
        ]);
    }
    public function index()
    {
        activity('News')
            ->log("Accessed News listing")->causer(request()->user());
        return view('news.index')->with([
            'cpage' => 'news',
            'news'=>News::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
        ]);
    }
    public function create()
    {
        return view('news.create')->with([
            'cpage'=>'news',
            'news'=>News::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
        ]);
    }
    public function determine()
    {
        return view('news.determine')->with([
            'cpage' => "news",
        ]);
    }
    public function determineNext(Request $request)
    {
        $data = $request->all();
        $request->validate([
            'title' => "required|string",
        ]);

        $request->session()->put('title',$data['title']);

        $report_list = \request()->session()->get('report_items');

        if (empty($report_list)) {
            $report_list = [];
        }

        return view('news.create')->with([
            'cpage' => "news",
            'report_list' => $report_list,
        ]);
    }
    public function addItemToList(Request $request)
    {
        $request->validate([
            'duties' => "required|string",
        ]);
        $items = $request->session()->get('report_items');
        if (empty($items)) {
            $items = [];
        }
        $id = $request->post('duties');
        $item_exists = collect($items)->firstWhere('duties', $id);
        if ($item_exists) {
            return back()->with(['error-notification' => "Item is already in list"]);
        }
        $items[] = [
            'duties' =>$request->post('duties'),
        ];
        $request->session()->put('report_items', $items);
        return back()->with(['success-notification' => "Item added to list"]);
    }
    public function removeItemFromList($item_id)
    {
        activity('News')
            ->log("Removing items on a  News")->causer(request()->user());
        $items = request()->session()->get('report_items');

        if (empty($items)) {
            $items = [];
        }


        $items_filtered = [];

        foreach ($items as $item) {
            if ($item['duties'] === $item_id) {
                continue;
            }

            $items_filtered[] = $item;
        }

        request()->session()->put('report_items', $items_filtered);

        return back()->with(['success-notification' => "Item removed from list"]);
    }
    public function store()
    {
        // Retrieve data from session
        $duties_items = request()->session()->get('report_items');
        $title = request()->session()->get('title');

        // Check if there are items and a title
        if (empty($duties_items) || empty($title)) {
            return back()->with(['error-notification' => "Cannot save a Vacancy with missing data"]);
        }

        // Create a new news item
        $newNews = [
            'created_by' => request()->user()->id,
            'updated_by' => request()->user()->id,
            'title' => $title,
        ];

        // Check if there's an uploaded image
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["image"])) {
            $file_name = $_FILES["image"]["name"];
            $file_tmp = $_FILES["image"]["tmp_name"];

            // Insert the news entry into the database
            $newNewsEntry = News::create($newNews);

            // Retrieve the ID of the inserted record
            $newNewsId = $newNewsEntry->id;

            // Rename the uploaded image to the ID of the record
            $newFileName = $newNewsId . '.' . pathinfo($file_name, PATHINFO_EXTENSION);
            $file_path = public_path('img' . DIRECTORY_SEPARATOR . 'blog' . DIRECTORY_SEPARATOR . $newFileName);

            if (move_uploaded_file($file_tmp, $file_path)) {
                // Resize the uploaded image
                $this->resizeImage($file_path, 900, 600);

                // Update the record in the database with the new image name
                $newNewsEntry->update(['url' => $newFileName]);
            } else {
                return back()->with(['error-notification' => "Failed to upload the image"]);
            }
        } else {
            // If no image was uploaded, create the news entry without an image
            $newNewsEntry = News::create($newNews);
        }

        // Create paragraph entries associated with the news item
        foreach ($duties_items as $duties_item) {
            Paragraph::create([
                'item' => $duties_item['duties'],
                'new_id' => $newNewsEntry->id,
                'created_by' => request()->user()->id,
                'updated_by' => request()->user()->id,
            ]);
        }

        // Log activity
        activity('news')->log("Created a news")->causer(request()->user());

        // Clear session data
        request()->session()->forget("report_items");
        request()->session()->forget("title");

        return redirect()->route('news.index')->with([
            'success-notification' => "Successfully created news"
        ]);
    }


// Function to resize the image
    public function resizeImage($file_path, $width, $height)
    {
        // Get the original image's extension
        $extension = pathinfo($file_path, PATHINFO_EXTENSION);

        // Create a new image from the original file based on the extension
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                $image = imagecreatefromjpeg($file_path);
                break;
            case 'png':
                $image = imagecreatefrompng($file_path);
                break;
            case 'gif':
                $image = imagecreatefromgif($file_path);
                break;
            default:
                throw new \Exception('Unsupported image format');
        }

        // Create a new true color image with the specified dimensions
        $resized_image = imagecreatetruecolor($width, $height);

        // Set the background color to transparent
        $transparent = imagecolorallocatealpha($resized_image, 0, 0, 0, 127);
        imagefill($resized_image, 0, 0, $transparent);
        imagesavealpha($resized_image, true);

        // Resize the original image to fit the new dimensions
        imagecopyresampled($resized_image, $image, 0, 0, 0, 0, $width, $height, imagesx($image), imagesy($image));

        // Save the resized image back to the file
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                imagejpeg($resized_image, $file_path);
                break;
            case 'png':
                imagepng($resized_image, $file_path);
                break;
            case 'gif':
                imagegif($resized_image, $file_path);
                break;
        }

        // Free up memory
        imagedestroy($image);
        imagedestroy($resized_image);
    }


    public function show(News $news)
    {
        return view('news.show')->with([
            'cpage'=>'news',
            'news'=>$news,
        ]);
    }

}
