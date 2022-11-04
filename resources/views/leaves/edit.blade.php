@extends('layouts.app')


@section('content')
    <div class="container-fluid ps-1 pt-4">

        <h4>
            <i class="fa fa-search"></i>Leave Settings
        </h4>
        <p>
            Update Employee Leave Settings
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('human-resources.index')}}">Human Resources</a></li>
                <li class="breadcrumb-item"><a href="{{route('leave-settings.index')}}">Leave Settings</a></li>
                <li class="breadcrumb-item active" aria-current="page">Updating Details</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-4">
                    <form action="{{route('leave-settings.update',$leave->id)}}" method="POST" autocomplete="off">
                        @csrf
                        <input type="hidden" name="_method" value="PATCH">
                        <div class="form-group">
                            <label>Number of days per Month</label>
                            <input type="text" name="days_per_month"
                                   class="form-control @error('days_per_month') is-invalid @enderror"
                                   value="{{old('days_per_month') ?? $leave->days_per_month}}"
                                   placeholder="">
                            @error('name')
                            <span class="invalid-feedback">
                               {{$message}}
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Number of compassionate days per year</label>
                            <input type="text" name="compassionate_days_per_year"
                                   class="form-control @error('compassionate_days_per_year') is-invalid @enderror"
                                   value="{{old('compassionate_days_per_year') ?? $leave->compassionate_days_per_year}}"
                                   placeholder="">
                            @error('name')
                            <span class="invalid-feedback">
                               {{$message}}
                            </span>
                            @enderror
                        </div>
                        <div class="form-group ">
                            <button class="btn btn-md btn-primary rounded-0">
                                <i class="fa fa-edit"></i>Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
