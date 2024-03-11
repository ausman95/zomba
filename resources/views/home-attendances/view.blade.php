@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">

        <h4>
            <i class="fa fa-list-ul"></i>Site
        </h4>
        <p>
            Manage Site Attendance Report
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
                <li class="breadcrumb-item active" aria-current="page">Attendance Report</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <h3>
            {{$department->name}} - Attendance Report
        </h3>
        <div class="mt-4 row">
            <div class="col-sm-12 col-md-2 mb-2">
                <form action="{{route('register.produce',$department->id)}}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Select Month</label>
                        <select name="month_id"
                                class="form-select select-relation @error('month_id') is-invalid @enderror" style="width: 100%">
                            @foreach($months as $month)
                                <option value="{{$month->id}}"
                                    {{old('$month')===$month->id ? 'selected' : ''}}>{{$month->name}}</option>
                            @endforeach
                        </select>
                        @error('month_id')
                        <span class="invalid-feedback">
                               {{$message}}
                        </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary rounded-0" type="submit">
                            <i class="fa fa-print"></i>  Generate
                        </button>
                    </div>
                </form>
            </div>
            <div class="col-sm-12 mb-2 col-md-10">
                <div class="card bg-light">
                    <div class="card-header">
                        Statement
                    </div>
                    @if(!@$month_id)
                        <div class="card-body p-5" style="min-height: 20em;">
                            <div class="text-center">
                                <div class="alert alert-danger">
                                    Report not available at the moment!.
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="ul list-group list-group-flush">
                            <div class="card " style="min-height: 30em;">
                                <div class="card-body px-1">
                                    @if(!$month->count() ===0 )
                                        <div class="card bg-danger">
                                            <div class="card-body">
                                                No Data
                                            </div>
                                        </div>
                                    @else
                                        <div class="row">
                                            <div class="col-sm-12 col-md-12 col-lg-12">
                                                <table class="table table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th>NO</th>
                                                        <th>EMPLOYEE</th>
                                                        <th>TYPE</th>
                                                        <th>DEPARTMENT</th>
                                                        <th>NUMBER OF DAYS</th>
                                                        <th>ACTION</th>
                                                        {{--                                                            @if(request()->user()->designation!='clerk')--}}
                                                        {{--                                                                <th>RATE/DAY</th>--}}
                                                        {{--                                                                <th>GROSS(MK)</th>--}}
                                                        {{--                                                                <th>ADVANCE(MK)</th>--}}
                                                        {{--                                                                <th>NET(MK)</th>--}}
                                                        {{--                                                            @endif--}}
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php $c = 1;?>
                                                    @foreach($employees as $employee)
                                                        <tr>
                                                            <td>{{$c++}}</td>
                                                            <td>{{$employee->name}}</td>
                                                            <td>{{$employee->labour->name}}</td>
                                                            <td>{{$employee->department->name}}</td>
                                                            <td>{{$employee->getNumberOfDaysByMonth($employee->id,$start_date,$end_date)}} </td>
                                                            <td class="pt-1">
                                                                <a href="{{route('attendance.show',$employee->id)}}"
                                                                   class="btn btn-md btn-primary rounded-0">
                                                                    <i class="fa fa-list-ol"></i>   View All
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
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
           </div>
@stop

