<?php

namespace App\Http\Controllers;

use App\Http\Requests\Assets\StoreRequest;
use App\Http\Requests\Assets\UpdateRequest;
use App\Models\Assets;
use App\Models\Banks;
use App\Models\Categories;
use App\Models\Labourer;
use App\Models\Material;
use App\Models\Store;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssetController extends Controller
{
    public function capital()
    {
        $credits = DB::table('incomes')
            ->join('accounts', 'accounts.id','=','incomes.account_id')
            ->select('accounts.*',DB::raw('SUM(incomes.amount) as amount'))
            ->where(['accounts.type'=>2])
            ->where('incomes.transaction_type','=',2)
            ->groupBy('accounts.id')
            ->get();
        activity('ASSETS')
            ->log("Accessed the Capital page")->causer(request()->user());
        return view('assets.capital')->with([
            'cpage' => "assets",
            'transactions'=>$credits
        ]);
    }
    public function liabilities()
    {

        activity('ASSETS')
            ->log("Accessed the Liabilities page")->causer(request()->user());
        return view('assets.liabilities')->with([
            'cpage' => "assets",
            'banks'=>Banks::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
            'suppliers'=>Supplier::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
        ]);
    }
    public function currentAssets(Store $store)
    {

        activity('ASSETS')
            ->log("Accessed the Current Asset page")->causer(request()->user());
        return view('assets.current')->with([
            'cpage' => "assets",
            'banks'=>Banks::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
            'suppliers'=>Supplier::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
        ]);
    }
    public function index()
    {
        $categories = Categories::all();
        activity('ASSETS')
            ->log("Accessed Assets Listing")->causer(request()->user());
        $asset = Assets::where(['soft_delete'=>0])->orderBy('id','desc')->get();
        return view('assets.index')->with([
            'cpage' => "assets",
            'assets' =>$asset,
            'categories' =>$categories
        ]);
    }
    public function create()
    {
        $category = Categories::where(['soft_delete'=>0])->orderBy('id','desc')->get();
        return view('assets.create')->with([
            'cpage'=>"assets",
            'categories'=>$category
        ]);
    }
    public function store(StoreRequest $request)
    {
        $data = $request->post();
        if(is_numeric($request->post('serial_number'))){
            return back()->with(['error-notification'=>"Invalid Character Entered on Serial Number"]);
        }
        if(is_numeric($request->post('name'))){
            return back()->with(['error-notification'=>"Invalid Character Entered on Name"]);
        }
        if(is_numeric($request->post('location'))){
            return back()->with(['error-notification'=>"Invalid Character Entered on Location"]);
        }
        Assets::create($data);
        activity('ASSETS')
            ->log("Created an Asset")->causer(request()->user());
        return redirect()->route('assets.index')->with([
            'success-notification'=>"Successfully Created"
        ]);
    }
    public function show(Assets $asset)
    {
        return view('assets.show')->with([
            'cpage'=>"assets",
            'asset'=>$asset,
        ]);
    }
    public function edit( Assets $asset)
    {
        $category = Categories::all();
        return view('assets.edit')->with([
            'cpage' => "assets",
            'asset' => $asset,
            'categories' => $category
        ]);
    }
    public function update(UpdateRequest $request,Assets $asset)
    {
        $data = $request->post();
        if(is_numeric($request->post('name'))){
            return back()->with(['error-notification'=>"Invalid Character Entered on Name"]);
        }
        if(is_numeric($request->post('location'))){
            return back()->with(['error-notification'=>"Invalid Character Entered on Location"]);
        }
        $asset->update($data);
        activity('ASSETS')
            ->log("Updated an Asset")->causer(request()->user());
        return redirect()->route('assets.show',$asset->id)->with([
            'success-notification'=>"Successfully Updated"
        ]);
    }
//    public function destroy(Assets  $asset)
//    {
//        try{
//            $asset->delete();
//            activity('ASSETS')
//                ->log("Delete an Asset")->causer(request()->user());
//            return redirect()->route('assets.index')->with([
//                'success-notification'=>"Successfully Deleted"
//            ]);
//
//        }catch (\Exception $exception){
//            return redirect()->route('assets.index')->with([
//                'error-notification'=>"Something went Wrong ".$exception.getMessage()
//            ]);
//        }
//    }
    public function destroy(Request $request, Assets $assets)
    {

        $data = $request->post();
        DB::table('assets')
            ->where(['id' => $request->post('id')])
            ->update(['soft_delete' => '1']);
        $assets->update($data);

        return redirect()->route('assets.index')->with([
            'success-notification'=>"Successfully Deleted"
        ]);
    }
}
