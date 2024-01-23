<?php

namespace App\Http\Controllers;

use App\Http\Requests\Materials\StoreRequest;
use App\Http\Requests\Materials\UpdateRequest;
use App\Models\Accounts;
use App\Models\Material;
use App\Models\StockFlow;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaterialController extends Controller
{
    public function destroy(Request $request, Material $material)
    {

        $data = $request->post();
        DB::table('materials')
            ->where(['id' => $request->post('id')])
            ->update(['soft_delete' => '1']);
        $material->update($data);

        return redirect()->route('materials.index')->with([
            'success-notification'=>"Successfully Deleted"
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        activity('MATERIALS')
            ->log("Accessed Materials")->causer(request()->user());
        return view('materials.index')->with([
            'cpage' => "materials",
            'materials'=>Material::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('materials.create')->with([
            'cpage'=>"materials"
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store (StoreRequest $request)
    {
        if(is_numeric($request->post('specifications'))){
            return back()->with(['error-notification'=>"Invalid Character Entered on Specifications"]);
        }
        if(is_numeric($request->post('units'))){
            return back()->with(['error-notification'=>"Invalid Character Entered on Units"]);
        }
        if(is_numeric($request->post('name'))){
            return back()->with(['error-notification'=>"Invalid Character Entered on Name"]);
        }
        $data = $request->post();
        $check_data = [
            'name'=>$data['name'],
            'specifications'=>$data['specifications']
        ];

        if(Material::where($check_data)->first()){
            // labourer is already part of this project
            return back()->with(['error-notification'=>"The Material Alerady Exist"]);
        }

        Material::create($data);
        activity('MATERIALS')
            ->log("Created a Material")->causer(request()->user());
        return redirect()->route('materials.index')->with([
            'success-notification'=>"Material successfully Created"
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Material $material)
    {
        $stores = StockFlow::where(['material_id'=>$material->id])
            ->groupBy('department_id')
            ->orderBy('id','DESC')
            ->get();
        if(request()->user()->designation==='clerk'){
            $stores = StockFlow::where(['material_id'=>$material->id,'department_id'=>request()->user()->department_id])
                ->orderBy('id','DESC')
                ->get();
        }
        return view('materials.show')->with([
            'cpage'=>"materials",
            'material'=>$material,
            'flows' =>$stores
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit( Material $material)
    {
        return view('materials.edit')->with([
            'cpage' => "materials",
            'material' => $material
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request,Material $material)
    {
        $data = $request->post();
        if(is_numeric($request->post('specifications'))){
            return back()->with(['error-notification'=>"Invalid Character Entered on Specifications"]);
        }
        if(is_numeric($request->post('units'))){
            return back()->with(['error-notification'=>"Invalid Character Entered on Units"]);
        }
        if(is_numeric($request->post('name'))){
            return back()->with(['error-notification'=>"Invalid Character Entered on Name"]);
        }
        $material->update($data);
        activity('MATERIALS')
            ->log("Updated a Material")->causer(request()->user());
        return redirect()->route('materials.show',$material->id)->with([
            'success-notification'=>"Material successfully Updated"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
//    public function destroy(Material  $material)
//    {
//        try{
//            $material->delete();
//            activity('MATERIALS')
//                ->log("Deleted a Material")->causer(request()->user());
//            return redirect()->route('materials.index')->with([
//                'success-notification'=>"Material successfully Deleted"
//            ]);
//
//        }catch (\Exception $exception){
//            return redirect()->route('materials.index')->with([
//                'error-notification'=>"Something went Wrong ".$exception.getMessage()
//            ]);
//        }
//    }
}
