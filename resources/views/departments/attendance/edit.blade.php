@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">

        <h4>
            <i class="fa fa-list-ul"></i>Attendance
        </h4>
        <p>
            Edit Attendance information
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                @if(request()->user()->designation!='clerk')
                <li class="breadcrumb-item"><a href="{{route('human-resources.index')}}">Human Resources</a></li>
                <li class="breadcrumb-item"><a href="{{route('departments.index')}}">Departments</a></li>
                @endif
                <li class="breadcrumb-item"><a
                        href="{{route('departments.show',$attendance->labourer->department_id)}}">{{$attendance->labourer->department->name}}</a>
                </li>
                <li class="breadcrumb-item"><a
                        href="{{route('attendance.view',$attendance->labourer->id)}}">Attendance</a>
                </li>
                <li class="breadcrumb-item"><a
                        href="{{route('attendance.show',$attendance->labourer->id)}}">{{$attendance->labourer->name}}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Attendance</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <h3>
            {{$attendance->labourer->name}}
        </h3>
        <div class="row">
            <form action="{{route('attendance.update',$attendance->id)}}" method="POST">
                @csrf
                <div class="col-sm-12 col-md-4">
                    <div class="form-group">
                        <input type="hidden" name="id" value="{{$attendance->id}}">
                        <label for="date">Date</label>
                        <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" value="{{$attendance->date}}" disabled>
                        @error('date')
                        <span class="invalid-feedback">
                            {{$message}}
                        </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="date">Status</label>
                        <select name="status" class="form-select select-relation">
                            <option value="{{$attendance->status}}">
                                @if($attendance->status ==0)
                                    0-Absent
                                @elseif($attendance->status ==1)
                                    1-Present
                                @else
                                    ?-Excuse
                                @endif
                            </option>
                            <option value="0">0 -
                                Absent
                            </option>
                            <option value="1"> 1- Present
                            </option>
                            <option value="?">?- Excuse</option>
                        </select>
                        @error('date')
                        <span class="invalid-feedback">
                            {{$message}}
                        </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary rounded-0">
                         <i class="fa fa-edit"></i>   Update
                        </button>
                    </div>
                    <hr>
                </div>
            </form>
        </div>

    </div>
@stop

