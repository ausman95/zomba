@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fab fa-acquisitions-incorporated"></i> Stock Movement
        </h4>
        <p>
            Create Stoke Movement
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('stores.index')}}">Stores</a></li>
                <li class="breadcrumb-item"><a href="{{route('stock-flows.index')}}">Stock Flow</a></li>
                <li class="breadcrumb-item"><a href="{{route('stock.next')}}">Selection</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create Stoke Movement</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
            <p>
                Submit Stoke Movement Materials.
            </p>

            <div class="mt-4 row">
                <div class="col-sm-12 col-md-3  mb-2">
                    <p>Select Material</p>
                    <form action="{{route('stock.enlist')}}" method="POST">
                        @csrf
                        <div class="form-group">
                            <select name="material_id" class="form-select select-relation" style="width: 100%">
                                <option value="">-- Select Material --</option>
                                @foreach($materials as $material)
                                    <option value="{{$material->id}}">{{$material->name}}
                                        - {{$material->specifications}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="number" class="form-control" name="quantity" placeholder="Quantity" required>
                        </div>
                        <div class="form-group">
                            <label>Activity (Use)</label>
                            <textarea name="activity" rows="2"
                                      class="form-control" placeholder="Activity (use of the material)" required></textarea>

                        </div>
                        <div class="form-group">
                            <input type="hidden" class="form-control" name="check_destin"  value="{{$_GET['destination']}}" placeholder="Quantity">
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary rounded-0" type="submit">
                                <i class="fa fa-plus-circle"></i>  Add Item
                            </button>
                        </div>
                    </form>
                </div>
                <div class="col-sm-12 mb-2 col-md-9">
                    <div class="card bg-light">
                        <div class="card-header">
                            Stoke Items List
                        </div>
                        @if(count($stock_list) === 0)
                            <div class="card-body p-5" style="min-height: 20em;">
                                <div class="text-center">
                                    List is empty
                                </div>
                            </div>
                        @else
                            <table class="table table-primary table-bordered table-hover table-striped" id="data-table">
                                <caption style=" caption-side: top; text-align: center">Stoke Items List</caption>
                                <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>MATERIAL</th>
                                    <th>QUANTITY</th>
                                    <th>ACTIVITY </th>
                                    <th>TOTAL AMOUNT(mk) </th>
                                    <th>STATUS</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($stock_list  as $list_item)
                                   <tr>
                                       <td>{{$loop->iteration}}</td>
                                       <td>{{$list_item['material_name']}} - {{$list_item['material_specification']}}</td>
                                       <td>{{$list_item['quantity'].' '.$list_item['units']}}</td>
                                       <td>{{$list_item['activity']}}</td>
                                       <td>{{number_format($list_item['total'])}}</td>
                                       <td>
                                           <a href="{{route('stock.delist',$list_item['material_id'])}}"
                                              title="Remove item"> <i
                                                   class="fa fa-minus-circle text-danger"></i>
                                           </a>
                                       </td>
                                   </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @endif
                        <div class="card-footer">
                            <?php if(@$_GET['project_id']){?>
                            <a href="{{route('stock.store')."?destination={$_GET['destination']}&project_id={$_GET['project_id']}"}}" class="btn btn-primary">
                                <i class="fa fa-paper-plane"></i>  Submit
                            </a>
                            <?php }else{?>
                            <a href="{{route('stock.store')."?destination={$_GET['destination']}"}}" class="btn btn-primary rounded-0">
                                <i class="fa fa-paper-plane"></i>  Submit
                            </a>
                            <?php }?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@stop

