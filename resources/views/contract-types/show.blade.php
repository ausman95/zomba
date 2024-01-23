@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">

        <h4>
            <i class="fa fa-list-ul"></i>Contract Types
        </h4>
        <p>
            Manage Contract Types information
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('human-resources.index')}}">Human Resources</a></li>
                <li class="breadcrumb-item"><a href="{{route('contracts.index')}}">Contracts</a></li>
                <li class="breadcrumb-item"><a href="{{route('contract-types.index')}}">Contract Types</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$contract->name}}</li>
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
                                        <table class="table table-bordered table-hover table-striped" id="data-table">
                                            <caption style=" caption-side: top; text-align: center">{{$contract->name}} INFORMATION</caption>
                                            <tbody>
                                            <tr>
                                                <td>Name</td>
                                                <td>{{$contract->name}}</td>
                                            </tr>
                                            <tr>
                                                <td>Description</td>
                                                <td>{{$contract->description}}</td>
                                            </tr>
                                            <tr>
                                                <td>Created On</td>
                                                <td>{{$contract->created_at}}</td>
                                            </tr>
                                            <tr>
                                                <td>Update ON</td>
                                                <td>{{$contract->updated_at}}</td>
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
                                        <div>
                                            <a href="{{route('contract-types.edit',$contract->id)}}"
                                               class="btn btn-primary btn-md rounded-0" style="margin: 5px">
                                                <i class="fa fa-edit"></i>Update
                                            </a>
                                            <button class="btn btn-danger btn-md rounded-0" id="delete-btn" style="margin: 5px">
                                                <i class="fa fa-trash"></i>Delete
                                            </button>
                                            <form action="{{route('contract-types.destroy',$contract->id)}}" method="POST" id="delete-form">
                                                @csrf
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="id" value="{{$contract->id}}">
                                            </form>
                                        </div>
{{--                                        <div class="">--}}
{{--                                            @if( request()->user()->designation==='administrator')--}}
{{--                                                <form action="{{route('contract-types.destroy',$contract->id)}}" method="POST" id="delete-form">--}}
{{--                                                    @csrf--}}
{{--                                                    <input type="hidden" name="_method" value="DELETE">--}}
{{--                                                </form>--}}
{{--                                                <button class="btn btn-danger btn-md rounded-0" id="delete-btn" style="margin: 5px">--}}
{{--                                                    <i class="fa fa-trash"></i>Delete--}}
{{--                                                </button>--}}
{{--                                            @endif--}}
{{--                                        </div>--}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-5">
                    <h5>
                        <i class="fa fa-microscope"></i>Employees
                    </h5>
                    <div class="card">
                        <div class="card-body">
                            <div class="card " style="min-height: 30em;">
                                <div class="card-body px-1">
                                    @if($labourers->count() === 0)
                                        <i class="fa fa-info-circle"></i>There are no  Employees!
                                    @else
                                        <div style="overflow-x:auto;">
                                        <table class="table1  table table-bordered table-hover table-striped" id="data-table">
                                            <caption style=" caption-side: top; text-align: center">EMPLOYEES</caption>
                                            <thead>
                                            <tr>
                                                <th>NO</th>
                                                <th>NAME</th>
                                                <th>DEPARTMENT</th>
                                                <th>PHONE</th>
                                                <th>PROFESSIONAL</th>
                                                <th>CONTRACT</th>
                                                <th>START</th>
                                                <th>END</th>
                                                <th>DUE</th>
                                                <th>ACTION</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php $c = 1;?>
                                            @foreach($labourers as $contract)
                                                @if($contract->getAgeAttribute()<60)
                                                    <tr style="color: red">
                                                @else
                                                    <tr>
                                                        @endif
                                                        <td>{{$c++}}</td>
                                                        <td>{{$contract->labourer->name}}</td>
                                                        <td>{{$contract->labourer->department->name}}</td>
                                                        <td>{{$contract->labourer->phone_number}}</td>
                                                        <td>{{$contract->labourer->labour->name}}</td>
                                                        <td>{{$contract->contractType->name}}</td>
                                                        <td>{{$contract->start_date}}</td>
                                                        <td>{{$contract->end_date}}</td>
                                                        <td>
                                                            @if($contract->getAgeAttribute()>30)
                                                                {{$contract->getAgeAttribute()/30}} Months
                                                            @else
                                                                {{$contract->getAgeAttribute()}} Days
                                                            @endif
                                                        </td>
                                                        <td class="pt-1">
                                                            <a href="{{route('contracts.show',$contract->id)}}"
                                                               class="btn btn-primary btn-md rounded-0">
                                                                <i class="fa fa-list-ol"></i>  Manage
                                                            </a>
                                                        </td>
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
