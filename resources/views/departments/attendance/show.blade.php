@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">

        <h4>
            <i class="fa fa-list-ul"></i>Worker Attendance
        </h4>
        <p>
            Manage Worker Attendance
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                @if(request()->user()->designation!='clerk')
                <li class="breadcrumb-item"><a href="{{route('human-resources.index')}}">Human Resources</a></li>
                <li class="breadcrumb-item"><a href="{{route('departments.index')}}">SITE</a></li>
                @endif
                <li class="breadcrumb-item"><a
                        href="{{route('departments.show',$labourer->department->id)}}">{{$labourer->department->name}}</a></li>
                <li class="breadcrumb-item"><a
                        href="{{route('attendance.view',$labourer->department->id)}}">Attendance</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$labourer->name}}</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <h3>
            {{$labourer->name}} - Attendance Report
        </h3>

        @if($employees->count() ===0 )
            <div class="card bg-danger">
                <div class="card-body">
                    This Worker has no Attendance
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-6">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>NO</th>
                            <th>DATE</th>
                            <th>STATUS</th>
                            <th>ACTION</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $c = 1;?>
                        @foreach($employees as $employee)
                            <tr>
                                <td>{{$c++}}</td>
                                <td>{{date('d F Y', strtotime($employee->date))}} </td>
                                <td>{{$employee->getStatusToHumanAttribute()}}</td>
                                <td class="pt-1">
                                    <a href="{{route('attendance.edit',$employee->id)}}"
                                       class="btn btn-md btn-primary rounded-0">
                                        <i class="fa fa-edit"></i>   Edit
                                    </a>
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

