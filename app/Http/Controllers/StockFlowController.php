<?php

namespace App\Http\Controllers;

use App\Http\Requests\StockFlows\StoreRequest;
use App\Models\Department;
use App\Models\Material;
use App\Models\Price;
use App\Models\Project;
use App\Models\ProjectPayment;
use App\Models\RequisitionItem;
use App\Models\StockFlow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockFlowController extends Controller
{
    public function show(StockFlow $stockFlow)
    {
        return view('stock-flows.show')->with([
            'cpage'=>"stores",
            'stockFlow'=>$stockFlow,
        ]);
    }
    public function addItemToList(Request $request, StockFlow $flow)
    {
        activity('Stock Movement')
            ->log("Adding items on a Requisition")->causer(request()->user());
        $request->validate([
            'material_id' => "required|numeric|exists:materials,id",
            'quantity' => "required|numeric|min:1",
            'activity' => "required|string"
        ]);
       // $get_id = $request->post('check_destin');
        //die($request->post('check_destin')=='2');
        $balance = $flow->getBalance2(request()->user()->department_id,$request->post('material_id'));
        if($request->post('check_destin')=='1'){
            if($request->post('quantity')>$balance){
                return back()->with(['error-notification'=>"Quantity Specified is greater than the quantity store"]);
            }
        }
        if($request->post('check_destin')=='3'){
            if($request->post('quantity')>$balance){
                return back()->with(['error-notification'=>"Quantity Specified is greater than the quantity store"]);
            }
        }

        $items = $request->session()->get('stock_items');


        if (empty($items)) {
            $items = [];
        }

        $material = Material::find($request->post('material_id'));

        $item_exists = collect($items)->firstWhere('material_id', $material->id);

        if ($item_exists) {
            return back()->with(['error-notification' => "Item is already in list"]);
        }
        $price = Price::where(['material_id'=>$request->post('material_id')])->first()->price;
        if(!$price){
            return back()->with(['error-notification'=>"The Material you are trying to purchase does not have a price tag"]);
        }
        $items[] = [
            'material_id' => $request->post('material_id'),
            'material_name' => $material->name,
            'material_specification' => $material->specifications,
            'quantity' => $request->post('quantity'),
            'activity' => $request->post('activity'),
            'units' =>  $material->units,
            'total' =>$request->post('quantity')*$price,
        ];

        $request->session()->put('stock_items', $items);

        return back()->with(['success-notification' => "Item added to list"]);
    }

    public function removeItemFromList($item_id)
    {
        activity('Stock Movement')
            ->log("Removing items on a  Stack")->causer(request()->user());
        $items = request()->session()->get('stock_items');


        if (empty($items)) {
            $items = [];
        }


        $items_filtered = [];

        foreach ($items as $item) {
            if ($item['material_id'] === $item_id) {
                continue;
            }

            $items_filtered[] = $item;
        }

        request()->session()->put('stock_items', $items_filtered);

        return back()->with(['success-notification' => "Item removed from list"]);
    }
    public function determine ()
    {
        $project = Department::all();
        return view('stock-flows.save')->with([
            'projects' => $project,
            'cpage' => "stores",
        ]);
    }

    public function addItem(Request $request)
    {

        $request->validate([
            'destination' => "required",
        ]);
        $data = $request->all();
        if($data['destination']=='1'){
            $request->validate([
                'project_id' => "required",
            ]);
        }

        if($data['destination']=='1' || $data['destination']=='4'){
            $project_id = $data['project_id'];
            return redirect(
                route('stock-flows.create')."?destination={$data['destination']}&project_id={$project_id}");
        }else{
            $department_name = 'Others';
            return redirect(
                route('stock-flows.create')."?destination={$data['destination']}");
        }


    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function materialMovement(StockFlow $stockFlow, Request $request)

    {
        $request->validate([
            'project_id' => "required|numeric",
            'material_id' => "required|numeric",
        ]);

        $department = Department::where(['id'=>$request->post('project_id')])->first();
        $material = Material::where(['id'=>$request->post('material_id')])->first();

        activity('Stock flow')
            ->log("Accessed an stores")->causer(request()->user());
        if(request()->user()->designation==='clerk'){
            $stock_flow= StockFlow::where(['department_id'=>request()->user()->department_id])
                ->orderBy('id','DESC')->get();
        }else{
            $stock_flow= StockFlow::where(['department_id'=>$request->post('project_id')])
                ->where(['material_id'=>$request->post('material_id')])
                ->orderBy('id','DESC')->get();
        }
        return view('stock-flows.index')->with([
            'cpage' => "stores",
            'material_name'=>$material->name,
            'department_name'=>$department->name,
            'departments'=>Department::all(),
            'materials'=>Material::all(),
            'flows'=>$stock_flow,
        ]);

    }

    public function index()
    {
        activity('STORES')
            ->log("Accessed an stores")->causer(request()->user());
        $stock_flow= StockFlow::all();
        if(request()->user()->designation==='clerk'){
            $stock_flow= StockFlow::where(['department_id'=>request()->user()->department_id])
                ->orderBy('id','DESC')->get();
        }else{
            $stock_flow= StockFlow::orderBy('id', 'desc')
                ->get();
        }
        return view('stock-flows.index')->with([
            'cpage' => "stores",
            'departments'=>Department::all(),
            'materials'=>Material::all(),
            'flows'=>$stock_flow,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Project $project)
    {

        $stock_list = \request()->session()->get('stock_items');


        if (empty($stock_list)) {
            $stock_list = [];
        }

        return view('stock-flows.create')->with([
            'project' => $project,
            'materials' => Material::all(),
            'cpage' => "stores",
            'stock_list' => $stock_list
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StockFlow $flow)
    {

        $items = request()->session()->get('stock_items');

        if (empty($items)) {
            $items = [];
        }

        if (count($items) === 0) {
            return back()->with(['error-notification' => "Can not save a stock movement with an empty list"]);
        }
        if($_GET['destination']==2){
            foreach ($items as $stock) {
                $balance = $flow->getBalance2(request()->user()->department_id,$stock['material_id']);
                StockFlow::create([
                    'material_id' => $stock['material_id'],
                    'amount' => $stock['total'],
                    'quantity' => $stock['quantity'],
                    'activity' => $stock['activity'],
                    'status' => 'ACKNOWLEDGED',
                    'department_id' => request()->user()->department_id,
                    'balance' => $balance+$stock['quantity'],
                    'flow' => 1,
                ]);
            }
        }
        elseif($_GET['destination']==4){
            foreach ($items as $stock) {
                $balance = $flow->getBalance2(request()->user()->department_id,$stock['material_id']);
                StockFlow::create([
                    'material_id' => $stock['material_id'],
                    'amount' => $stock['total'],
                    'activity' => $stock['activity'],
                    'quantity' => $stock['quantity'],
                    'status' => 'ACKNOWLEDGED',
                    'department_id' => request()->user()->department_id,
                    'balance' => $balance+$stock['quantity'],
                    'flow' => 1,
                ]);
            }
        }elseif($_GET['destination']==3){
            foreach ($items as $stock) {
                $balance = $flow->getBalance2(request()->user()->department_id,$stock['material_id']);
                StockFlow::create([
                    'material_id' => $stock['material_id'],
                    'amount' => $stock['total'],
                    'activity' => $stock['activity'],
                    'quantity' => $stock['quantity'],
                    'status' => 'ACKNOWLEDGED',
                    'department_id' => request()->user()->department_id,
                    'balance' => $balance-$stock['quantity'],
                    'flow' => 2,
                ]);
            }
        }
        else{
            foreach ($items as $stock) {
                StockFlow::create([
                    'material_id' => $stock['material_id'],
                    'amount' => $stock['total'],
                    'quantity' => $stock['quantity'],
                    'activity' => $stock['activity'],
                    'status' => 'ACKNOWLEDGED',
                    'department_id' => request()->user()->department_id,//$_GET['project_id'],
                    'balance' => $flow->getBalance2(request()->user()->department_id,$stock['material_id'])-$stock['quantity'],
                    'flow' =>2,
                ]);

                StockFlow::create([
                    'material_id' => $stock['material_id'],
                    'amount' => $stock['total'],
                    'quantity' => $stock['quantity'],
                    'activity' => $stock['activity'],
                    'status' => 'PENDING',
                    'department_id' => $_GET['project_id'],
                    'balance' => $flow->getBalance2($_GET['project_id'],$stock['material_id'])+$stock['quantity'],
                    'flow' =>1,
                ]);
            }

        }

        request()->session()->put("stock_items", []);

        activity('STORES')
            ->log("Created Stock Movements")->causer(request()->user());
        return redirect()->route('stock-flows.index')->with([
            'success-notification'=>"Recorded Successfully"
        ]);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


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
    public function destroy(Request $request,StockFlow $stockFlow)
    {
        $data = $request->post();
        DB::table('stock_flows')
            ->where(['id'=>$request->post('request_id')])
            ->update(['status' => 'ACKNOWLEDGED']);
        $stockFlow->update($data);
        activity('REQUISITIONS')
            ->log("ACKNOWLEDGED the Materials ")->causer(request()->user());
        return redirect()->route('stock-flows.index')->with([
            'success-notification'=>"Successfully Updated"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
//    public function destroy($id)
//    {
//        //
//    }
}
