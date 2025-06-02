<?php

namespace App\Http\Controllers;

use App\Models\Other;
use Illuminate\Http\Request;

class OtherController extends Controller
{
    public function index()
    {
        activity('payee')
            ->log("Accessed payee listing")->causer(request()->user());

        return view('others.index')->with([
            'cpage' => "finances",
            'others'=> Other::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
        ]);
    }
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            // Add other validation rules as needed
        ]);

        // Include created_by and updated_by in the validated data
        $validatedData['created_by'] = $request->user()->id;
        $validatedData['updated_by'] = $request->user()->id;

        // Create a new 'Other' model instance with the validated data
        Other::create($validatedData);

        // Log the user activity
        activity('other payee')
            ->log("Created a payee Zone")
            ->causer(request()->user());

        // Redirect the user to the 'others.index' route with a success notification
        return redirect()->back()->with([
            'success-notification' => "Successfully Created",
        ]);
    }
    public function create()
    {
        return view('others.create')->with([
            'cpage'=>"finances",
        ]);
    }
}
