@extends('layouts.app')
@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-list-ul"></i>Stock Flow list
        </h4>
        <p>
            Manage Stock Flow list
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('stores.index')}}">Stores</a></li>
                <li class="breadcrumb-item active" aria-current="page">Manage Stock Flows</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-3">
            @if(request()->user()->designation==='clerk')
            <a href="{{route('stock.next')}}" class="btn btn-primary btn-md rounded-0">
                <i class="fa fa-plus-circle"></i>New Stock Flow
            </a>
                <div class="mt-3">
                    <div class="row">
                        <div class="col-sm-12 mb-2 col-md-12 col-lg-12">
                            <div class="card " style="min-height: 30em;">
                                <div class="card-body px-1">
                                    @if($flows->count() === 0)
                                        <i class="fa fa-info-circle"></i>There are no Stock!
                                    @else
                                        <div style="overflow-x:auto;">
                                            <table class="table table-bordered table-hover table-striped" id="data-table">
                                                <caption style=" caption-side: top; text-align: center">STOCK FLOWS</caption>
                                                <thead>
                                                <tr>
                                                    <th>NO</th>
                                                    @if(request()->user()->designation!='clerk')
                                                        <th>DEPARTMENT / PROJECT</th>
                                                    @endif
                                                    <th>MATERIAL</th>
                                                    <th>QUANTITY</th>
                                                    <th>DESCRIPTION </th>
                                                    <th>BALANCE </th>
                                                    <th>STATUS </th>
                                                    <th>DATE</th>
                                                    <th>ACTION</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php  $c= 1; $balance = 0;$balance_qty = 0;$qty = 0;?>
                                                @foreach($flows as $flow)
                                                    <tr>
                                                        <td>{{$c++}}</td>
                                                        @if(request()->user()->designation!='clerk')
                                                            <td >{{ucwords($flow->department->name) }}</td>
                                                        @endif
                                                        <td >{{ucwords($flow->material->name) }} - {{$flow->material->specifications}}</td>
                                                        <td style="text-align: center">
                                                            @if($flow->flow==4)
                                                                {{number_format( $qty = $qty+$flow->quantity).' '.$flow->material->units}}
                                                            @else
                                                                {{number_format($flow->quantity).' '.$flow->material->units}}
                                                            @endif</td>

                                                        <td>
                                                            @if($flow->flow==1)
                                                                {{$flow->activity}} -    {{ucwords('IN-STOCK') }}
                                                            @elseif($flow->flow==2)
                                                                {{$flow->activity}} -    {{'OUT-STOCK'}}
                                                            @else
                                                                {{$flow->activity}} -    {{'DISPOSED / DAMAGED'}}
                                                            @endif
                                                        </td>
                                                        <td>{{number_format($flow->balance).' '.$flow->material->units }}</td>
                                                        <td>{{ucwords($flow->status) }}</td>
                                                        <td>{{ucwords($flow->created_at) }}</td>
                                                        <td>
                                                            <a href="{{route('stock-flows.show',$flow->id)}}"
                                                               class="btn btn-primary btn-md rounded-0">
                                                                <i class="fa fa-list-ol"></i>   Manage
                                                            </a>
                                                            <a href="{{route('delivery-note.generate')."?id={$flow->id}"}}" target="_blank" class="btn btn-primary rounded-0" style="margin: 2px">
                                                                <i class="fa fa-vote-yea"></i> Generate
                                                            </a></td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            @else
                <div class="mb-5">
                    <hr>
                    <p>
                        Select Site, Material and click generate.
                    </p>
                    <div class="col-sm-12 mb-2 md-4">
                        <p class="text-black-50">
                            Material Movement in Stores
                        </p>

                    </div>
                    <div class="mt-4 row">
                        <div class="col-sm-12 col-md-2 mb-2">
                            <form action="{{route('material.movements')}}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label>Site / Project</label>
                                    <select name="project_id"
                                            class="form-select select-relation @error('project_id') is-invalid @enderror" style="width: 100%">
                                        @foreach($departments as $department)
                                            <option value="{{$department->id}}"
                                                {{old('project_id')===$department->id ? 'selected' : ''}}>{{$department->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('project_id')
                                    <span class="invalid-feedback">
                               {{$message}}
                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Materials</label>
                                    <select name="material_id"
                                            class="form-select select-relation @error('material_id') is-invalid @enderror" style="width: 100%">
                                        @foreach($materials as $material)
                                            <option value="{{$material->id}}"
                                                {{old('material_id')===$material->id ? 'selected' : ''}}>{{$material->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('material_id')
                                    <span class="invalid-feedback">
                               {{$message}}
                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary rounded-0" type="submit">
                                        <i class="fa fa-print"></i>  Generate
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="col-sm-12 mb-2 col-md-10">
                            <div class="card bg-light">
                                <div class="card-header">
                                    Statement
                                </div>
                                @if(!@$department_name)
                                    <div class="card-body p-5" style="min-height: 20em;">
                                        <div class="text-center">
                                            <div class="alert alert-danger">
                                                Data not available at the moment!.
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="ul list-group list-group-flush">
                                        <div class="card " style="min-height: 30em;">
                                            <div class="card-body px-1">
                                                <div style="overflow-x:auto;">
                                                    <table class="table table-primary table-bordered table-hover table-striped" id="data-table">
                                                        <caption style=" caption-side: top; text-align: center">{{@$department_name}} - {{@$material_name}} USAGE</caption>
                                                        <thead>
                                                        <tr>
                                                            <th>NO</th>
                                                            <th>QUANTITY</th>
                                                            <th>DESCRIPTION </th>
                                                            <th>BALANCE </th>
                                                            <th>STATUS </th>
                                                            <th>DATE</th>
                                                            <th>ACTION</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php  $c= 1; $balance = 0;$balance_qty = 0;$qty = 0;?>
                                                        @foreach($flows as $flow)
                                                            <tr>
                                                                <td>{{$c++}}</td>
                                                                <td style="text-align: center">
                                                                    @if($flow->flow==4)
                                                                        {{number_format( $qty = $qty+$flow->quantity).' '.$flow->material->units}}
                                                                    @else
                                                                        {{number_format($flow->quantity).' '.$flow->material->units}}
                                                                    @endif</td>

                                                                <td>
                                                                    @if($flow->flow==1)
                                                                        {{$flow->activity}} -    {{ucwords('IN-STOCK') }}
                                                                    @elseif($flow->flow==2)
                                                                        {{$flow->activity}} -    {{'OUT-STOCK'}}
                                                                    @else
                                                                        {{$flow->activity}} -    {{'DISPOSED / DAMAGED'}}
                                                                    @endif
                                                                </td>
                                                                <td>{{number_format($flow->balance).' '.$flow->material->units }}</td>
                                                                <td>{{ucwords($flow->status) }}</td>
                                                                <td>{{ucwords($flow->created_at) }}</td>
                                                                <td>
                                                                    <a href="{{route('delivery-note.generate')."?id={$flow->id}"}}" target="_blank" class="btn btn-primary rounded-0" style="margin: 2px">
                                                                        <i class="fa fa-vote-yea"></i> Generate
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif


        </div>
    </div>

@stop

@section('scripts')
    <script>
        function confirmationWindow(title, message, primaryLabel, callback) {
            Swal.fire({
                title: title,
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: primaryLabel
            }).then((result) => {
                if (result.isConfirmed) {
                    callback();
                }
            })
        }


        $(document).ready(function () {
            $(".delete-btn").on('click', function () {
                $url = $(this).attr('data-target-url');

                $("#delete-form").attr('action', $url);
                confirmationWindow("Confirm Deletion", "Are you sure you want to delete this position?", "Yes,Delete", function () {
                    $("#delete-form").submit();
                })
            });
        })
    </script>
@stop
