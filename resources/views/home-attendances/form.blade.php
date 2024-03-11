@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">

        <h4>
            <i class="fa fa-list-ol"></i> Member Home Cell Attendances
        </h4>
        <p>
            Attendances
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{route('home-attendances.index')}}">Attendances</a></li>
                <li class="breadcrumb-item active" aria-current="page">Attendance Form</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <h3>
{{--            {{$department->name}}--}}
        </h3>
        @if($members->count() ===0 )
            <div class="card bg-danger">
                <div class="card-body">
                    This department has no employees.
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-sm-12 col-md-8 col-lg-6">
                <form action="{{route('home-attendances.store')}}" method="POST">
                    @csrf
                    <input type="hidden"  name="church_id" value="{{$church_id}}" required>
                    <input type="hidden"  name="updated_by" value="{{request()->user()->id}}" required>
                    <input type="hidden"  name="created_by" value="{{request()->user()->id}}" required>
                    @foreach($members as $employee)
                        <div class="form-group">
                            <div class="row">
                                <div class="col-7">
                                    <label>{{$employee->name}}</label>
                                </div>
                                <div class="col-5">
                                    <select name="emp-{{$employee->id}}" class="form-select">
                                        <option value="0" {{old('emp-'.$employee->id) == '0' ? 'selected' : ''}}>0 - Absent </option>
                                        <option value="1" {{old('emp-'.$employee->id) == '1' ? 'selected' : ''}}>1- Present</option>
                                    </select>
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

    </div>
@stop

