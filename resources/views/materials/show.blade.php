@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">

        <h4>
            <i class="bx bx-abacus"></i>Materials
        </h4>
        <p>
            Manage Material information
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('materials.index')}}">Material</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$material->name}}</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 mb-2 col-md-8 col-lg-9">
                    <div class="row">
                        <div class="col-sm-12 col-md-7 col-lg-8">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-striped" id="data-table">
                                        <caption style=" caption-side: top; text-align: center">{{$material->name}} INFORMATION</caption>
                                        <tbody>
                                        <tr>
                                            <td>Name</td>
                                            <td>{{$material->name}}</td>
                                        </tr>
                                        <tr>
                                            <td>Unit of Measurement</td>
                                            <td>{{$material->units}}</td>
                                        </tr>
                                        <tr>
                                            <td>Created On</td>
                                            <td>{{$material->created_at}}</td>
                                        </tr>
                                        <tr>
                                            <td>Specification</td>
                                            <td>{{ ucfirst($material->specifications)}}</td>
                                        </tr>
                                        <tr>
                                            <td>Created ON</td>
                                            <td>{{$material->created_at}}</td>
                                        </tr>
                                        <tr>
                                            <td>Update ON</td>
                                            <td>{{$material->updated_at}}</td>
                                        </tr>
                                    </table>
                                    <div class="mt-3">
                                        <div>
                                            <a href="{{route('materials.edit',$material->id)}}"
                                               class="btn btn-primary rounded-0" style="margin: 2px">
                                                <i class="fa fa-edit"></i>Update
                                            </a>
                                        </div>
                                        <div class="">
{{--                                            @if( request()->user()->designation==='administrator')--}}
{{--                                                <form action="{{route('materials.destroy',$material->id)}}" method="POST" id="delete-form">--}}
{{--                                                    @csrf--}}
{{--                                                    <input type="hidden" name="_method" value="DELETE">--}}
{{--                                                </form>--}}
{{--                                                <button class="btn btn-danger rounded-0" style="margin: 2px" id="delete-btn">--}}
{{--                                                    <i class="fa fa-trash"></i>Delete--}}
{{--                                                </button>--}}
{{--                                            @endif--}}
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-5">
                    <h5>
                        <i class="fa fa-microscope"></i>Activities
                    </h5>
                    <div class="card">
                        <div class="card-body">
                            @if($flows->count() === 0)
                                <i class="fa fa-info-circle"></i>There are no material flow!
                            @else
                                <div style="overflow-x:auto;">
                                <table class="table  table1 table-bordered table-hover table-striped" id="data-table">
                                    <caption style=" caption-side: top; text-align: center">MATERIAL FLOW INFORMATION</caption>
                                    <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>DEPARTMENT</th>
                                        <th>MATERIAL</th>
                                        <th>AMOUNT (MK)</th>
                                        <th>QUANTITY</th>
                                        <th>SPECIFICATION</th>
                                        <th>FLOW (TO)</th>
                                        <th>QTY IN STORE (REMAINING)</th>
                                        <th>DATE</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php  $c= 1; $balance = 0;$balance_qty = 0;$qty = 0;?>
                                    @foreach($flows as $flow)
                                        <tr>
                                            <td>{{$c++}}</td>
                                            <td style="text-align: center">{{($flow->department->name) }}</td>
                                            <td >{{ucwords($flow->material->name) }} - {{$flow->material->specifications}}</td>
                                            <td style="text-align: center">{{number_format($flow->amount) }}</td>
                                            <td style="text-align: center">{{number_format($flow->quantity) }} -  {{$flow->material->units}}</td>
                                            <td style="text-align: center">{{number_format($flow->quantity) }}</td>
                                            <td>
                                                @if($flow->flow==1)
                                                    {{ucwords('IN-STOCK') }}
                                                @elseif($flow->flow==2)
                                                    {{'OUT-STOCK'}}
                                                @else
                                                    {{'LOST TRACK'}}
                                                @endif
                                            </td>
                                            <td style="text-align: center">{{number_format($flow->balance) }}</td>
                                            <td>{{ucwords($flow->created_at) }}</td>
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
    </div>
@stop

@section('scripts')
    <script src="{{asset('vendor/simple-datatable/simple-datatable.js')}}"></script>
    <script>
        $(document).ready(function () {

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


            $("#delete-btn").on('click', function () {
                confirmationWindow("Confirm Deletion", "Are you sure you want to delete this Record?", "Yes,Delete", function () {
                    $("#delete-form").submit();
                });
            });
        })
    </script>
@stop
