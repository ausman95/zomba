@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">

        <h4>
            <i class="fa fa-car"></i>Driver Allowances
        </h4>
        <p>
            Manage Driver Allowances
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('drivers.index')}}">Driver Allowances</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$driver->labourer->name}}  {{$driver->account->name}}</li>
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
                                <div class="card-body px-1">
                                <div class="table-responsive">
                                    <table class="table  table-bordered table-hover table-striped" id="data-table">
                                        <caption style=" caption-side: top; text-align: center">DRIVER ALLOWANCE INFORMATION</caption>
                                        <tbody>
                                        <tr>
                                            <td>Driver</td>
                                            <td>{{$driver->labourer->name}}</td>
                                        </tr>
                                        <tr>
                                            <td>Account</td>
                                            <td>{{$driver->account->name}}</td>
                                        </tr>
                                        <tr>
                                            <td>Start Date</td>
                                            <td>{{date('d F Y', strtotime($driver->start_date)) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Start Date</td>
                                            <td>{{date('d F Y', strtotime($driver->end_date)) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Days Remaining</td>
                                            <td style="text-align: left">{{$driver->getDays($driver->start_date,$driver->end_date) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Created On</td>
                                            <td>{{date('d F Y', strtotime($driver->created_at)) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Update ON</td>
                                            <td>{{date('d F Y', strtotime($driver->updated_at)) }}</td>
                                        </tr>
                                    </table>
                                    <div class="mt-3">
                                        <div>
                                            <a href="{{route('drivers.edit',$driver->id)}}"
                                               class="btn btn-primary rounded-0" style="margin: 2px">
                                                <i class="fa fa-edit"></i>Update
                                            </a>
                                        </div>
                                    </div>
                                </div>
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
