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
                <li class="breadcrumb-item active" aria-current="page">Attendances</li>
            </ol>
        </nav>
        <div class="mb-3">
            @if(request()->user()->designation=='church')
            <a href="{{route('home-attendances.create')}}" class="btn btn-primary btn-md rounded-0">
                <i class="fa fa-plus-circle"></i>New Attendance
            </a>
            @endif
            <hr>
            <p>
                Input start date and end date then click generate.
            </p>
            <div class="col-sm-12 mb-2 md-4">
                <p class="text-black-50">
                    Attendances
                </p>
            </div>
            <div class="mt-4 row">
                <div class="col-sm-12 col-md-2 mb-2">
                    <form action="{{route('home-attendance.generate')}}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Month</label>
                            <select name="month_id"
                                    class="form-select select-relation @error('month_id') is-invalid @enderror" style="width: 100%">
                                @foreach($months as $month)
                                    <option value="{{$month->id}}"
                                        {{old('month')===$month->id ? 'selected' : ''}}>{{$month->name}}</option>
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
                        @if(!$attendances)
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
                                       <div class="row">
                                                <div class="col-sm-12 col-md-12 col-lg-12">
                                                    <table class="table table-bordered table-hover table-striped" id="data-table">
                                                        <caption style=" caption-side: top; text-align: center">PAYROLL FROM {{date('j F, Y', strtotime(@$start_date))}} - {{date('j F, Y', strtotime(@$end_date))}}</caption>
                                                        <thead>
                                                        <tr>
                                                            <th>NO</th>
                                                            <th>DATE</th>
                                                            <th>MEMBER</th>
                                                            <th>HOME CHURCH</th>
                                                            <th>STATUS</th>
                                                            <th>ACTION</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php $c = 1;?>
                                                        @foreach($attendances as $attendance)
                                                            <tr>
                                                                <td>{{$c++}}</td>
                                                                <td>{{date('d F Y', strtotime($attendance->created_at))}}</td>
                                                                <td>{{$attendance->member->name}}</td>
                                                                <td>{{$attendance->member->church->name}}</td>
                                                                <td>
                                                                    @if($attendance->status==1)
                                                                        {{"PRESENT"}}
                                                                    @else
                                                                        {{"ABSENT"}}
                                                                    @endif
                                                                </td>
                                                                <td class="pt-1">
                                                                    <a href="{{route('home-attendances.show',$attendance->id)}}"
                                                                       class="btn btn-primary btn-md rounded-0">
                                                                        <i class="fa fa-list-ol"></i> Manage
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
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

