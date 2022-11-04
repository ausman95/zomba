@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-search"></i>Employee Leave
        </h4>
        <p>
            Manage Employees Leave
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('human-resources.index')}}">Human Resources</a></li>
                <li class="breadcrumb-item active" aria-current="page">Leave</li>
            </ol>
        </nav>
        <div class="mb-5">
            <a href="{{route('leaves.create')}}" class="btn btn-primary btn-md rounded-0">
                <i class="fa fa-plus-circle"></i>New Leave
            </a>
            <a href="{{route('leave-settings.index')}}" class="btn btn-primary btn-md rounded-0">
                <i class="fa fa-list-ol"></i> Leave Settings
            </a>
            <hr>
            <p>
                Select Year then click generate.
            </p>
            <div class="col-sm-12 mb-2 md-4">
                <p class="text-black-50">
                    Employee Leave Summary
                </p>

            </div>
            <div class="mt-4 row">
                <div class="col-sm-12 col-md-2 mb-2">
                    <form action="{{route('leave.summary')}}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Years</label>
                            <select name="year_id"
                                    class="form-select select-relation @error('year_id') is-invalid @enderror" style="width: 100%">
                                @foreach($years as $year)
                                    <option value="{{$year->id}}"
                                        {{old('year_id')===$year->id ? 'selected' : ''}}>{{date('d F Y', strtotime($year->start_date))}} To {{date('d F Y', strtotime($year->end_date))}}</option>
                                @endforeach
                            </select>
                            @error('year_id')
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
                        @if(!@$year_id)
                            <div class="card-body p-5" style="min-height: 20em;">
                                <div class="text-center">
                                    <div class="alert alert-danger">
                                        Data not available at the moment!.
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="ul list-group list-group-flush">
                                <div class="card " style="min-height: 30em;">
                                    <div class="card-body px-1">
                                        @if($employees->count() === 0)
                                            <i class="fa fa-info-circle"></i>There are no Leave Available!
                                        @else
                                            <div style="overflow-x:auto;">
                                                <table class="table  table-bordered table-hover table-striped">
                                                    <caption style=" caption-side: top; text-align: center">EMPLOYEE LEAVE SUMMARY FROM {{date('d F Y', strtotime($start_date))}} TO {{date('d F Y', strtotime($end_date))}}</caption>
                                                    <thead>
                                                    <tr>
                                                        <th>NO</th>
                                                        <th>EMPLOYEE</th>
                                                        <th>PROFESSIONAL</th>
                                                        <th>DAYS PER MONTH</th>
                                                        <th>LEAVE DAYS PER YEAR</th>
                                                        <th>LEAVE DAYS TAKEN</th>
                                                        <th>BALANCE</th>
{{--                                                        <th>COMPASSIONATE PER YEAR</th>--}}
{{--                                                        <th>COMPASSIONATE TAKEN</th>--}}
{{--                                                        <th>COMPASSIONATE BALANCE</th>--}}
                                                        <th>ACTION</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php  $c= 1;?>
                                                    @foreach($employees as $leave)
                                                        <tr>
                                                            <td>{{$c++}}</td>
                                                            <td>{{ucwords($leave->name) }}</td>
                                                            <td>{{strtoupper($leave->labour->name) }}</td>
                                                            <td>{{ucwords($leave->getDays()/12) }}</td>
                                                            <td>{{ucwords($leave->getDays()) }}</td>
                                                            <td>
                                                                @if($leave->getBalanceNormal($leave->id,$start_date,$end_date))
                                                                {{ucwords($leave->getDays()-$leave->getBalanceNormal($leave->id,$start_date,$end_date)) }}
                                                                @else
                                                                0
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if($leave->getBalanceNormal($leave->id,$start_date,$end_date))
                                                                {{ucwords($leave->getBalanceNormal($leave->id,$start_date,$end_date)) }}
                                                                @else
                                                                    {{$leave->getDays()}}
                                                                @endif
                                                            </td>
{{--                                                            <td>{{number_format($leave->getCompassionate()) }}</td>--}}
{{--                                                            <td>--}}
{{--                                                                @if($leave->getBalanceCompassionate($leave->id,$start_date,$end_date))--}}
{{--                                                                    {{ucwords($leave->getCompassionate()-$leave->getBalanceCompassionate($leave->id,$start_date,$end_date)) }}--}}
{{--                                                                @else--}}
{{--                                                                    0--}}
{{--                                                                @endif--}}
{{--                                                            </td>--}}
{{--                                                            <td>--}}
{{--                                                                @if($leave->getBalanceCompassionate($leave->id,$start_date,$end_date))--}}
{{--                                                                    {{ucwords($leave->getBalanceCompassionate($leave->id,$start_date,$end_date)) }}--}}
{{--                                                                @else--}}
{{--                                                                    {{$leave->getCompassionate()}}--}}
{{--                                                                @endif--}}
{{--                                                            </td>--}}
                                                            <td class="pt-1">
                                                                <a href="{{route('labourer.leave',$leave->id)}}"
                                                                   class="btn btn-primary btn-md rounded-0">
                                                                    <i class="fa fa-list-ol"></i> Details
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
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
@section('scripts')
    <script src="{{asset('vendor/simple-datatable/simple-datatable.js')}}"></script>
    <script>
        function confirmationWindow(title, message, primaryLabel, callback) {
            Swal.fire({
                title: title,
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: primaryLabel
            }).then((result) => {
                if (result.isConfirmed) {
                    callback();
                }
            })
        }


        $(document).ready(function () {
            $(".delete-btn").on('click', function () {
                $url = $(this).attr('data-target-url');

                $("#delete-form").attr('action', $url);
                confirmationWindow("Confirm Deletion", "Are you sure you want to delete this position?", "Yes,Delete", function () {
                    $("#delete-form").submit();
                })
            });
        })
    </script>
@stop

