@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="bx bx-abacus"></i>&nbsp; Material Prices
        </h4>
        <p>
            Material Price information
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('suppliers.index')}}">Suppliers</a></li>
                <li class="breadcrumb-item"><a href="{{route('prices.index')}}">Prices</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$price->material->name}}</li>
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
                            <div class="card">
                                <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table  table-bordered table-hover table-striped" id="data-table">
                                        <tbody>
                                        <tr>
                                            <td>Material</td>
                                            <td>{{$price->material->name}}</td>
                                        </tr>
                                        <tr>
                                            <td>Specifications</td>
                                            <td>{{$price->material->specifications}}</td>
                                        </tr>
                                        <tr>
                                            <td>Price /Item / KG</td>
                                            <td>{{$price->price}}</td>
                                        </tr>
                                        <tr>
                                            <td>Supplier</td>
                                            <td>{{$price->supplier->name}}</td>
                                        </tr>
                                        <tr>
                                            <td>Created On</td>
                                            <td>{{$price->created_at}}</td>
                                        </tr>
                                        <tr>
                                            <td>Update ON</td>
                                            <td>{{$price->updated_at}}</td>
                                        </tr>
                                    </table>
                                    <div>
                                        <a href="{{route('prices.edit',$price->id)}}"
                                           class="btn btn-primary rounded-0" style="margin: 2px">
                                            <i class="fa fa-edit"></i>Update
                                        </a>
                                    </div>
{{--                                    @if( request()->user()->designation==='administrator')--}}
{{--                                        <div class="">--}}
{{--                                            <form action="{{route('prices.destroy',$price->id)}}" method="POST" id="delete-form">--}}
{{--                                                @csrf--}}
{{--                                                <input type="hidden" name="_method" value="DELETE">--}}
{{--                                            </form>--}}
{{--                                            <button class="btn btn-danger rounded-0" style="margin: 2px" id="delete-btn">--}}
{{--                                                <i class="fa fa-trash"></i>Delete--}}
{{--                                            </button>--}}
{{--                                        </div>--}}
{{--                                    @endif--}}
                                </div>
                                </div>
                            </div>
{{--                            <div class="mt-3">--}}
{{--                                <h6>Specification</h6>--}}
{{--                                <hr>--}}
{{--                                <p style="white-space: break-spaces;">{{ ucfirst($account->specifications)}}</p>--}}
{{--                            </div>--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
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
                confirmationWindow("Confirm Deletion", "Are you sure you want to delete this record?", "Yes,Delete", function () {
                    $("#delete-form").submit();
                });
            });
        })
    </script>
@stop
