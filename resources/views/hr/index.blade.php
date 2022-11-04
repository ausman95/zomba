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
            <a href="{{route('members.index')}}" class="btn btn-outline-primary btn-md rounded-0">
                <i class="fa fa-people-arrows"></i>Labourers
            </a>
            <div class="mt-3">
                <div class="row">
                    <div class="col-sm-12 mb-2 col-md-12 col-lg-12">
                        <div class="card " style="min-height: 30em;">
                            <div class="card-body px-1">
                                @if($employees->count() === 0)
                                    <i class="fa fa-info-circle"></i>There are no  employees!
                                @else
                                    <table class="table table-borderless table-striped" id="data-table">
                                        <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Gender</th>
                                            <th>Phone</th>
                                            <th>Department</th>
                                            <th>Professional</th>
                                            <th>Experience</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($employees as $employee)
                                            <tr>
                                                <td>{{$employee->name}}</td>
                                                <td>{{$employee->gender}}</td>
                                                <td>{{$employee->phone_number}}</td>
{{--                                                <td>{{$employee->labour->name}}</td>--}}
                                                <td>{{$employee->period}}</td>
                                                <td class="pt-1">
                                                    <a href="{{route('human-resources.show',$employee->id)}}"
                                                       class="btn btn-md btn-success">
                                                        Manage
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


@stop

