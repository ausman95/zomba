@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">

        <h4>
            <i class="fa fa-users"></i>Labourer
        </h4>
        <p>
            Manage labourer information
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('human-resources.index')}}">Human Resources</a></li>
                <li class="breadcrumb-item"><a href="{{route('contracts.index')}}">Contracts</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$contract->labourer->name}}</li>
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
                            <a href="{{route('contracts.edit',$contract->id)}}"
                               class="btn btn-primary btn-md rounded-0" style="margin: 5px">
                                <i class="fa fa-edit"></i>Update
                            </a>
                            <button class="btn btn-danger btn-md rounded-0" id="delete-btn" style="margin: 5px">
                                <i class="fa fa-trash"></i>Delete
                            </button>
                            <form action="{{route('contracts.destroy',$contract->id)}}" method="POST" id="delete-form">
                                @csrf
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="id" value="{{$contract->id}}">
                            </form>
                        </div>
{{--                        <div class="">--}}
{{--                            <form action="{{route('contracts.destroy',$contract->id)}}" method="POST" id="delete-form">--}}
{{--                                @csrf--}}
{{--                                <input type="hidden" name="_method" value="DELETE">--}}
{{--                            </form>--}}
{{--                            <button class="btn btn-danger btn-md rounded-0" id="delete-btn" style="margin: 5px">--}}
{{--                                <i class="fa fa-trash"></i>Delete--}}
{{--                            </button>--}}
{{--                        </div>--}}
                    </div>
                </div><!--./ overview -->
                <div class="col-sm-12 mb-2 col-md-8 col-lg-9">
                    <div class="row">
                            <h5>
                                <i class="fa fa-microscope"></i>Personal Information
                            </h5>
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover table-striped" id="data-table">
                                            <caption style=" caption-side: top; text-align: center">{{$contract->labourer->name}} INFORMATION</caption>
                                            <tbody>
                                        <tr>
                                            <td>Employee</td>
                                            <td>{{$contract->labourer->name}}</td>
                                        </tr>
                                        <tbody>
                                        <tr>
                                            <td>Phone Number</td>
                                            <td>{{$contract->labourer->phone_number}}</td>
                                        </tr>
                                        <tr>
                                            <td>Contract</td>
                                            <td>{{$contract->contractType->name}}</td>
                                        </tr>
                                        <tr>
                                            <td>Salary (MK)</td>
                                            <td>{{number_format($contract->salary)}}</td>
                                        </tr>
                                        <tr>
                                            <td>Contract Start</td>
                                            <td>{{$contract->start_date}}</td>
                                        </tr>
                                        <tr>
                                            <td>Contract End</td>
                                            <td>{{$contract->end_date}}</td>
                                        </tr>
                                        <tr>
                                            <td>Contract Due</td>
                                            <td>
                                                @if($contract->status=='ENDED')
                                                    0
                                                @else
                                                @if($contract->getAgeAttribute()>30)
                                                    {{$contract->getAgeAttribute()/30}} Months
                                                @else
                                                    {{$contract->getAgeAttribute()}} Days
                                                @endif
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Professional </td>
                                            <td>{{$contract->labourer->labour->name}}</td>
                                        </tr>
                                        <tr>
                                            <td>Status</td>
                                            <td>
                                                @if($contract->labourer->type==1)
                                                    {{'Employed'}}
                                                @elseif($contract->labourer->type==2)
                                                    {{'Sub-Contactor'}}
                                                @else
                                                {{'Temporary Workers'}}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Created On</td>
                                            <td>{{$contract->labourer->created_at}}</td>
                                        </tr>
                                        <tr>
                                            <td>Updated On</td>
                                            <td>{{$contract->labourer->updated_at}}</td>
                                        </tr>
                                        <tr>
                                            <td>Status</td>
                                            <td>
                                                @if($contract->soft_delete==1)
                                                    <p style="color: red">Deleted, and Reserved for Audit</p>
                                                @else
                                                    Active
                                                @endif
                                            </td>
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
                confirmationWindow("Confirm Deletion", "Are you sure you want to delete this Record?", "Yes,Delete", function () {
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
