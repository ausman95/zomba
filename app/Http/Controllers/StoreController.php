<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\StockFlow;
use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getMaterialBalance($id,$departmentID){
        $balance= StockFlow::where(['material_id'=>$id,'department_id'=>$departmentID])->first();
        if($balance) {
            return $balance->balance;
        }else{
            return $balance = 0;
        }
    }
    public function index(Store $store)
    {
        activity('STORES')
            ->log("Accessed Stores")->causer(request()->user());
        $stores = StockFlow::where(['flow'=>1])
            ->groupBy('material_id','department_id')
            ->get();
        if(request()->user()->designation==='clerk'){
            $stores = StockFlow::where(['department_id'=>request()->user()->department_id])
                ->Where(['flow'=>1])
                ->groupBy('material_id')
                ->get();
        }
        return view('stores.index')->with([
            'cpage' => "stores",
            'stores'=>$stores
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Material $material)
    {
        $material = $material->material;
       // dd($material);
        return view('stores.show')->with([
            'cpage'=>"stores",
            'materials'=>$material
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
