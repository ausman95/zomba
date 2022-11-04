@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">

        <h4>
            <i class="fa fa-list-ul"></i>Departments
        </h4>
        <p>
            Manage Department information
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('human-resources.index')}}">Human Resources</a></li>
                <li class="breadcrumb-item"><a href="{{route('departments.index')}}">Departments</a></li>
                <li class="breadcrumb-item"><a
                        href="{{route('departments.show',$department->id)}}">{{$department->name}}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Attendance Report</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <h3>
            {{$department->name}} - Attendance Report
        </h3>
        <div class="row">
            <div class="col-sm-12 col-md-8 col-lg-6">
                {{$targetDay}}
                <hr>
            </div>
        </div>
        @if($attendanceRecords->count() ===0 )
            <div class="card bg-danger">
                <div class="card-body">
                    This date has no attendance data.
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-6">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($attendanceRecords as $attendanceRecord)
                            <tr>
                                <td>{{$attendanceRecord->labourer->name}}</td>
                                <td>
                                    {{$attendanceRecord->status}} - {{$attendanceRecord->statusToHuman}}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif


    </div>
@stop

