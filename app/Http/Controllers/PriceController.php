<?php

namespace App\Http\Controllers;

use App\Http\Requests\Prices\StoreRequest;
use App\Http\Requests\Prices\UpdateRequest;
use App\Models\Allocation;
use App\Models\Categories;
use App\Models\Material;
use App\Models\Price;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PriceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $materials = Material::all();
        $suppliers = Supplier::all();
        activity('PRICES')
            ->log("Accessed prices")->causer(request()->user());
        $price= Price::orderBy('id','desc')->get();
        return view('prices.index')->with([
            'cpage' => "prices",
            'prices'=>$price,
            'suppliers'=>$suppliers,
            'materials'=>$materials,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $suppliers = Supplier::all();
        $materials = Material::all();
        return view('prices.create')->with([
            'cpage'=>"prices",
            'materials'=>$materials,
            'suppliers'=>$suppliers
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request, Price $price)
    {
        $data = $request->post();

        $check_data = [
            'material_id'=>$data['material_id'],
            'supplier_id'=>$data['supplier_id']
        ];

        if(Price::where($check_data)->first()){
            DB::table('prices')
                ->where(['material_id'=>$request->post('material_id')])
                ->where(['supplier_id'=>$request->post('supplier_id')])
                ->update(['price' => $request->post('price')]);
          //  $price->update($data);
        }else{
            Price::create($data);
        }

        activity('PRICES')
            ->log("Created a price")->causer(request()->user());
        return redirect()->back()->with([
            'success-notification'=>"Successfully Created"
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Price $price)
    {
        return view('prices.show')->with([
            'cpage'=>"prices",
            'price'=>$price,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Price $price)
    {
        $material = Material::all();
        $supplier = Supplier::all();
        return view('prices.edit')->with([
            'cpage' => "prices",
            'price' => $price,
            'suppliers' => $supplier,
            'materials' => $material
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Price $price)
    {
        $data = $request->post();

        $price->update($data);
        activity('PRICES')
            ->log("Updated a price")->causer(request()->user());
        return redirect()->route('prices.show',$price->id)->with([
            'success-notification'=>"Successfully Updated"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Price $price)
    {
        try{
            $price->delete();
            activity('PRICES')
                ->log("Deleted a price")->causer(request()->user());
            return redirect()->route('prices.index')->with([
                'success-notification'=>"Successfully Deleted"
            ]);

        }catch (\Exception $exception){
            return redirect()->route('prices.index')->with([
                'error-notification'=>"Something went Wrong ".$exception.getMessage()
            ]);
        }
    }
}
