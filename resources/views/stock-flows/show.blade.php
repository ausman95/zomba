@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">

        <h4>
            <i class="fa fa-list-ol"></i>Stock Movement
        </h4>
        <p>
            Manage Stock information
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('stores.index')}}">Stores</a></li>
                <li class="breadcrumb-item"><a href="{{route('stock-flows.index')}}">Stock Flow</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$stockFlow->material->name}}</li>
            </ol>
        </nav>

        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 mb-2 col-md-4 col-lg-3">
                    <div class="card shadow-sm">
                        <div class="card-body election-banner-card p-1">
                            <img src="{{asset('images/avatar.png')}}" alt="avatar image" class="img-fluid">
                        </div>
                    </div>
                    <div class="mt-3">
                        <div>
                            @if($stockFlow->status!='ACKNOWLEDGED')
                                <div class="">
                                    <form action="{{route('stock-flows.destroy',$stockFlow->id)}}" method="POST" id="delete-form">
                                        @csrf
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="request_id" value="{{$stockFlow->id}}">
                                    </form>
                                    <button class="btn btn-primary rounded-0" style="margin: 2px" id="delete-btn">
                                        <i class="fa fa-edit"></i>Acknowledge
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div><!--./ overview -->
                <div class="col-sm-12 mb-2 col-md-8 col-lg-9">
                    <div class="row">
                        <h5>
                            <i class="fa fa-microscope"></i>Stock Flow Information
                        </h5>
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-striped" id="data-table">
                                        <caption style=" caption-side: top; text-align: center">{{$stockFlow->material->name}} INFORMATION</caption>
                                        <tbody>
                                        <tr>
                                            <td>Material</td>
                                            <td>{{$stockFlow->material->name}}- {{$stockFlow->material->specifications}}</td>
                                        </tr>
                                        <tbody>
                                        <tr>
                                            <td>Quantity</td>
                                            <td>{{$stockFlow->quantity}}</td>
                                        </tr>
                                        <tr>
                                            <td>Department</td>
                                            <td>{{$stockFlow->department->name}}</td>
                                        </tr>
                                        <tr>
                                            <td>Description</td>
                                            <td>
                                                @if($stockFlow->flow==1)
                                                    {{ucwords('IN-STOCK') }}
                                                @elseif($stockFlow->flow==2)
                                                    {{'OUT-STOCK'}}
                                                @else
                                                    {{'DISPOSED / DAMAGED'}}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Status</td>
                                            <td>{{$stockFlow->status}}</td>
                                        </tr>

                                        <tr>
                                            <td>Created On</td>
                                            <td>{{$stockFlow->created_at}}</td>
                                        </tr>
                                        <tr>
                                            <td>Updated On</td>
                                            <td>{{$stockFlow->updated_at}}</td>
                                        </tr>
                                    </table>
                                </div>
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
                confirmationWindow("Confirm ACKNOWLEDGED", "Are you sure you want to ACKNOWLEDGED these Materials?", "Yes,Continue", function () {
                    $("#delete-form").submit();
                });
            });
            $("#delete-bt").on('click', function () {
                confirmationWindow("Confirm De~Allocation", "Are you sure you want to Remove this Labourer?", "Yes,Continue", function () {
                    $("#delete-forms").submit();
                });
            });
        })
    </script>
@stop
