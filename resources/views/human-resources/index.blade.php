@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-users"></i>Human Resource
        </h4>
        <p>
            Human Resource
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Human Resource</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-3">
            <a href="{{route('departments.index')}}" class="btn btn-primary btn-md rounded-0">
                <i class="fa fa-folder"></i>Departments
            </a>
            <a href="{{route('labours.index')}}" class="btn btn-primary btn-md rounded-0">
                <i class="fa fa-list-ol"></i>Positions
            </a>
            <a href="{{route('labourers.employees')}}" class="btn btn-primary btn-md rounded-0">
                <i class="fa fa-people-arrows"></i>Employees
            </a>
            <a href="{{route('contracts.index')}}" class="btn btn-primary btn-md rounded-0">
                <i class="fa fa-file-archive"></i>Contracts
            </a>
{{--            <a href="{{route('attendance.index')}}" class="btn btn-primary btn-md rounded-0">--}}
{{--                <i class="fa fa-file-archive"></i>Payroll--}}
{{--            </a>--}}
{{--            <a href="{{route('register.index')}}" class="btn btn-primary btn-md rounded-0">--}}
{{--                <i class="fa fa-file-alt"></i>Register--}}
{{--            </a>--}}
{{--            <a href="{{route('leaves.index')}}" class="btn btn-primary btn-md rounded-0">--}}
{{--                <i class="fa fa-list-ol"></i>Leave--}}
{{--            </a>--}}
            <div class="mt-3">
                <div class="row">
                    <div class="col-sm-12 mb-2 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-body px-1">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="card shadow-sm my-2">
                                            <div class="card-body">
                                                <h5>Departments</h5>
                                                <div>
                                                    Total
                                                </div>
                                                <div class="d-flex my-2 justify-content-between">
                                                    <h3 class="text-primary"> {{count($department)}}
                                                        <a href="{{route('departments.index')}}"
                                                           class="btn btn-md btn-primary rounded-0">
                                                            Go &rarr;
                                                        </a></h3>
                                                    <div>
                                                        <i class="fa fa-file-archive fa-2x"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- ./ analytic -->
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="card shadow-sm my-2">
                                            <div class="card-body">
                                                <h5>Positions</h5>
                                                <div>
                                                    Total
                                                </div>
                                                <div class="d-flex my-2 justify-content-between">
                                                    <h3 class="text-primary"> {{count($labour)}}
                                                        <a href="{{route('labours.index')}}"
                                                           class="btn btn-md btn-primary rounded-0">
                                                            Go &rarr;
                                                        </a></h3>
                                                    <div>
                                                        <i class="fa fa-file-archive fa-2x"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- ./ analytic -->
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="card shadow-sm my-2">
                                            <div class="card-body">
                                                <h5>Employees</h5>
                                                <div>
                                                    Total
                                                </div>
                                                <div class="d-flex my-2 justify-content-between">
                                                    <h3 class="text-primary"> {{count($employee)}}
                                                        <a href="{{route('labourers.employees')}}"
                                                           class="btn btn-md btn-primary rounded-0">
                                                            Go &rarr;
                                                        </a></h3>
                                                    <div>
                                                        <i class="fa fa-file-archive fa-2x"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- ./ analytic -->
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
{{--                    <div class="col-sm-12 mb-2 col-md-12 col-lg-12">--}}
{{--                        <div class="card " style="min-height: 30em;">--}}
{{--                            <div class="card-body px-1">--}}
{{--                                @if($employees->count() === 0)--}}
{{--                                    <i class="fa fa-info-circle"></i>There are no  employees!--}}
{{--                                @else--}}
{{--                                    <table class="table table-borderless table-striped" id="data-table">--}}
{{--                                        <thead>--}}
{{--                                        <tr>--}}
{{--                                            <th>No</th>--}}
{{--                                            <th>Name</th>--}}
{{--                                            <th>Gender</th>--}}
{{--                                            <th>Department</th>--}}
{{--                                            <th>Phone</th>--}}
{{--                                            <th>Age</th>--}}
{{--                                            <th>Professional</th>--}}
{{--                                            <th>Experience</th>--}}
{{--                                            <th></th>--}}
{{--                                        </tr>--}}
{{--                                        </thead>--}}
{{--                                        <tbody>--}}
{{--                                        <?php $c = 1;?>--}}
{{--                                        @foreach($employees as $labourer)--}}
{{--                                            <tr>--}}
{{--                                                <td>{{$c++}}</td>--}}
{{--                                                <td>{{$labourer->name}}</td>--}}
{{--                                                <td>{{$labourer->gender}}</td>--}}
{{--                                                <td>{{$labourer->department->name}}</td>--}}
{{--                                                <td>{{$labourer->phone_number}}</td>--}}
{{--                                                <td>{{$labourer->getAgeAttribute()}}</td>--}}
{{--                                                <td>{{$labourer->labour->name}}</td>--}}
{{--                                                <td>{{$labourer->period}}</td>--}}
{{--                                                <td class="pt-1">--}}
{{--                                                    <a href="{{route('members.show',$labourer->id)}}"--}}
{{--                                                       class="btn btn-md btn-outline-success">--}}
{{--                                                        <i class="fa fa-list-ol"></i>  Manage--}}
{{--                                                    </a>--}}
{{--                                                </td>--}}
{{--                                            </tr>--}}
{{--                                        @endforeach--}}
{{--                                        </tbody>--}}
{{--                                    </table>--}}
{{--                                @endif--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                    </div>--}}
                </div>
            </div>
        </div>
    </div>


@stop

