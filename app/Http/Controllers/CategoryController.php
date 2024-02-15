<?php

namespace App\Http\Controllers;

use App\Http\Requests\Categories\StoreRequest;
use App\Http\Requests\Categories\UpdateRequest;
use App\Models\Accounts;
use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        activity('ASSETS')
            ->log("Accessed Asset Category")->causer(request()->user());
        $category= Categories::where(['soft_delete'=>0])->orderBy('id','desc')->get();
        return view('categories.index')->with([
            'cpage' => "finances",
            'categories'=>$category,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('categories.create')->with([
            'cpage'=>"finances"
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        if(is_numeric($request->post('name'))){
            return back()->with(['error-notification'=>"Invalid Character Entered on Name"]);
        }
        $data = $request->post();
        Categories::create($data);
        activity('ASSETS')
            ->log("Created Asset Category")->causer(request()->user());
        return redirect()->route('categories.index')->with([
            'success-notification'=>"Successfully Created"
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Categories $category)
    {
        $asset = $category->assets;
        return view('categories.show')->with([
            'cpage'=>"finances",
            'category'=>$category,
            'assets'=>$asset,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Categories $category)
    {
        return view('categories.edit')->with([
            'cpage' => "finances",
            'category' => $category
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Categories $category)
    {
        if(is_numeric($request->post('name'))){
            return back()->with(['error-notification'=>"Invalid Character Entered on Name"]);
        }
        $data = $request->post();
        $category->update($data);
        activity('ASSETS')
            ->log("Edited Asset Category")->causer(request()->user());
        return redirect()->route('categories.show',$category->id)->with([
            'success-notification'=>"Successfully Updated"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
//    public function destroy(Categories $category)
//    {
//        try{
//            $category->delete();
//            activity('ASSETS')
//                ->log("Deleted Asset Category")->causer(request()->user());
//            return redirect()->route('categories.index')->with([
//                'success-notification'=>"Successfully Deleted"
//            ]);
//
//        }catch (\Exception $exception){
//            return redirect()->route('categories.index')->with([
//                'error-notification'=>"Something went Wrong ".$exception.getMessage()
//            ]);
//        }
//    }
    public function destroy(Request $request, Categories $categories)
    {

        $data = $request->post();
        DB::table('categories')
            ->where(['id' => $request->post('id')])
            ->update(['soft_delete' => '1']);
        $categories->update($data);

        return redirect()->route('categories.index')->with([
            'success-notification'=>"Successfully Deleted"
        ]);
    }
}
