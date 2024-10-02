<?php

namespace App\Http\Controllers;

use App\Models\Information;
use Illuminate\Http\Request;

class InformationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cpage = 'hr';
        $informations = Information::where(['soft_delete'=>0])->orderBy('id','desc')->get();
        activity('COMPANY-INFORMATION')
            ->log("Accessed Company Information listing")->causer(request()->user());
        return view('informations.index')->with([
            'cpage' => $cpage,
            'informations'=>$informations,
        ]);
    }

    public function create()
    {
        $cpage = 'hr';
        return view('informations.determine')->with([
            'cpage' => $cpage,
        ]);
    }
    public function edit(CompanyInformation $companyInformation)
    {
        $cpage = 'hr';
        if(request()->user()->designation==='department'){
            $cpage = 'companies';
        }
        return view('company-information.edit')->with([
            'cpage' => $cpage,
            'company'=>$companyInformation,
        ]);
    }
    public function determineNext(Request $request)
    {
        $data = $request->all();
        $request->validate([
            'goal' => "required|string",
            'mission' => "required|string",
            'vision' => "required|string",
            'what_we_do' => "required|string",
            'who_we_are' => "required|string",
        ]);
        $request->session()->put('goal',$data['goal']);
        $request->session()->put('vision',$data['vision']);
        $request->session()->put('mission',$data['mission']);
        $request->session()->put('what_we_do',$data['what_we_do']);
        $request->session()->put('who_we_are',$data['who_we_are']);

        $report_list = \request()->session()->get('report_items');

        if (empty($report_list)) {
            $report_list = [];
        }
        $cpage = 'hr';
        return view('informations.create')->with([
            'cpage' => $cpage,
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
    public function store(Request $request , NewController $controller)
    {
        // Retrieve data from session
        $mission = $request->session()->get('mission');
        $vision = $request->session()->get('vision');
        $what_we_do = $request->session()->get('what_we_do');
        $goal = $request->session()->get('goal');
        $who_we_are = $request->session()->get('who_we_are');

        // Check if there are items
        if ( is_null($mission) || is_null($vision)
            || is_null($what_we_do) || is_null($goal) || is_null($who_we_are)) {
            return back()->with(['error-notification' => "Cannot save company information with missing data"]);
        }

        // Prepare the new company information entry
        $newInfo = [
            'created_by' => $request->user()->id,
            'updated_by' => $request->user()->id,
            'vision' => $vision,
            'mission' => $mission,
            'what_we_do' => $what_we_do,
            'goal' => $goal,
            'who_we_are' => $who_we_are,
        ];

        // Check if there's an uploaded image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $newFileName = time() . '.' . $image->getClientOriginalExtension();
            $file_path = public_path('img' . DIRECTORY_SEPARATOR . 'blog' . DIRECTORY_SEPARATOR . $newFileName);

            if (move_uploaded_file($image->getPathName(), $file_path)) {
                // Resize the uploaded image
                $controller->resizeImage($file_path, 900, 600);
                $newInfo['url'] = $newFileName;
            } else {
                return back()->with(['error-notification' => "Failed to upload the image"]);
            }
        }

        // Insert the company information entry into the database
      Information::create($newInfo);


        // Log activity
        activity('company-information')->log("Created company information")->causer($request->user());

        // Clear session data
        $request->session()->forget([
            "report_items",
            "mission",
            "vision",
            "what_we_do",
            "goal",
            "company_id",
            "who_we_are",
        ]);

        return redirect()->route('informations.index')->with([
            'success-notification' => "Successfully created company information"
        ]);
    }
    public function show(Information $Information)
    {
        $cpage = 'hr';
        return view('informations.show')->with([
            'cpage'=>$cpage,
            'company'=>$Information,
        ]);
    }
    public function destroy(Request $request)
    {
        // Find the news item by ID
        $infoItem = Information::findOrFail($request->post('id'));

        // Delete the associated image if it exists
        if (!empty($infoItem->url)) {
            $imagePath = public_path('img/blog/' . $infoItem->url);

            if (file_exists($imagePath)) {
                // Delete the image file
                unlink($imagePath);
            }
        }

        // Perform a soft delete by setting the deleted_at timestamp
        $infoItem->delete();

        return redirect()->route('informations.index')->with([
            'success-notification' => "Successfully Deleted"
        ]);
    }
}
