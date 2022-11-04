@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">

        <h4>
            <i class="fa fa-list-ul"></i>Attendances
        </h4>
        <p>
            Manage Attendance
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('human-resources.index')}}">Human Resources</a></li>
                <li class="breadcrumb-item active" aria-current="page">Attendances</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
            <p>
                Select month and then click generate.
            </p>
            <div class="col-sm-12 mb-2 md-4">
                <p class="text-black-50">
                    Register
                </p>

            </div>
            <div class="mt-4 row">
                <div class="col-sm-12 col-md-2 mb-2">
                    <form action="{{route('register.generate')}}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Start Date</label>
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
                                                                <td>{{$employee->labour}}</td>
                                                                <td>{{$employee->department}}</td>
                                                                <td>{{number_format($employee->days)}} </td>
{{--                                                                @if(request()->user()->designation!='clerk')--}}
{{--                                                                    <td>{{number_format($employee->salary/30)}}</td>--}}
{{--                                                                    <td>{{number_format($employee->days*($employee->salary/30))}} </td>--}}
{{--                                                                    <td>{{number_format($employee->amount)}} </td>--}}
{{--                                                                    <td>{{number_format(($employee->days*($employee->salary/30)-$employee->amount))}} </td>--}}
{{--                                                                @endif--}}
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
    </div>
@stop

