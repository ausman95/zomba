@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-list-ul"></i>Material list
        </h4>
        <p>
            Manage Material list
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Stores</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-3">
            @if(request()->user()->designation!='other' )
            <a href="{{route('stock-flows.index')}}" class="btn btn-primary btn-md rounded-0">
                <i class="fa fa-exchange-alt"></i>Material Flow
            </a>
            @endif
            <div class="mt-3">
                <div class="row">
                    <div class="col-sm-12 mb-2 col-md-12 col-lg-12">
                        <div class="card " style="min-height: 30em;">
                            <div class="card-body px-1">
                                @if($stores->count() === 0)
                                    <i class="fa fa-info-circle"></i>There are no Material!
                                @else
                                    <div style="overflow-x:auto;">
                                    <table class="table table-bordered table-hover table-striped" id="data-table">
                                        <caption style=" caption-side: top; text-align: center">MATERIALS IN STORES</caption>
                                        <thead>
                                        <tr>
                                            <th>NO</th>
                                            @if(request()->user()->designation!='clerk')
                                            <th>DEPARTMENT </th>
                                            @endif
                                            <th>MATERIAL</th>
                                            <th>QUANTITY BALANCE</th>
{{--                                            @if(request()->user()->designation!='other' )--}}
{{--                                            <th>ACTION</th>--}}
{{--                                            @endif--}}
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php  $c= 1;?>
                                        @foreach($stores as $store)
                                            <tr>
                                                <td>{{$c++}}</td>
                                                @if(request()->user()->designation!='clerk')
                                                <td>{{ucwords($store->department->name) }}</td>
                                                @endif
                                                <td>{{ucwords($store->material->name) }} - {{$store->material->specifications}}</td>
                                                <td>{{number_format($store->getBalance2($store->department->id,$store->material->id)).' '.$store->material->units }}</td>
{{--                                                @if(request()->user()->designation!='other' )--}}
{{--                                                <td class="pt-1">--}}
{{--                                                    <a href="{{route('materials.show',$store->material_id)}}"--}}
{{--                                                       class="btn btn-primary btn-md rounded-0">--}}
{{--                                                      <i class="fa fa-list-ol"></i>  Details--}}
{{--                                                    </a>--}}
{{--                                                </td>--}}
{{--                                                @endif--}}
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
    </div>

@stop

@section('scripts')
    <script src="{{asset('vendor/simple-datatable/simple-datatable.js')}}"></script>
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
