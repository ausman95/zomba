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
                @if(request()->user()->designation!='clerk')
                <li class="breadcrumb-item"><a href="{{route('human-resources.index')}}">Human Resources</a></li>
                <li class="breadcrumb-item"><a href="{{route('departments.index')}}">Departments</a></li>
                @endif
                <li class="breadcrumb-item"><a
                        href="{{route('departments.show',$department->id)}}">{{$department->name}}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Attendance Form</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <h3>
            {{$department->name}}
        </h3>
        @if($employees->count() ===0 )
            <div class="card bg-danger">
                <div class="card-body">
                    This department has no employees.
                </div>
            </div>
        @endif
        <div class="row">
            <form action="{{route('attendance.save',$department->id)}}" method="POST">
                @csrf
                <div class="col-sm-12 col-md-8 col-lg-6">
                    <div class="form-group">
                        <label for="date">Date</label>
                        <input type="date" name="date" class="form-control @error('date') is-invalid @enderror">
                        @error('date')
                        <span class="invalid-feedback">
                            {{$message}}
                        </span>
                        @enderror
                    </div>
                    <hr>
                </div>
{{--                <input type="hidden" name="date" class="form-control @error('date') is-invalid @enderror" value="{{date('Y-m-d')}}">--}}

            @foreach($employees as $employee)
                    <div class="col-sm-12 col-md-8 col-lg-6">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-6">
                                    <label>{{$employee->name}}</label>
                                   ( <label>{{$employee->labour->name}}</label>)
                                </div>
                                <div class="col-6">
                                    <select name="emp-{{$employee->id}}" class="form-select">
                                        <option value="0" {{old('emp-'.$employee->id) == '0' ? 'selected' : ''}}>0 - Absent </option>
                                        <option value="1" {{old('emp-'.$employee->id) == '1' ? 'selected' : ''}}>1- Present</option>
                                        <option value="?" {{old('emp-'.$employee->id) == '?' ? 'selected' : ''}}>?- Excuse</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="col-sm-12 col-md-8 col-lg-6">
                    <hr>
                    <button class="btn btn-primary rounded-0">Save Attendance</button>
                </div>
            </form>
        </div>

    </div>
@stop

