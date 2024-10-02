@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-list-ol"></i>Home Church Attendances
        </h4>
        <p>
            Manage Church Attendances
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Church Attendances</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-3">
            @if(request()->user()->designation=='administrator')
            <a href="{{route('attendances.create')}}" class="btn btn-primary btn-md rounded-0">
                <i class="fa fa-plus-circle"></i> New Attendance
            </a>
            <a href="{{route('member-attendance.reports')}}" class="btn btn-primary btn-md rounded-0">
                <i class="fa fa-file-archive"></i>Report
            </a>
                <a href="{{ route('videos.index') }}" class="btn btn-primary btn-md rounded-0">
                    <i class="fa fa-video"></i> Videos
                </a>
            @endif
            <div class="mt-3">
                <div class="row">
                    <div class="col-sm-12 mb-2 col-md-12 col-lg-12">
                        <div class="card " style="min-height: 30em;">
                            <div class="card-body px-1">
                                @if($attendances->count() === 0)
                                    <i class="fa fa-info-circle"></i>There are no Church Attendances!
                                @else
                                    <div style="overflow-x:auto;">
                                        <table class="table  table-bordered table-hover table-striped">
                                            <caption style=" caption-side: top; text-align: center">CHURCH ATTENDANCES</caption>
                                            <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>DATE</th>
                                            <th>SERVICE</th>
                                            <th>MINISTRY</th>
                                            <th>MALE</th>
                                            <th>FEMALE</th>
                                            <th>VISITORS</th>
                                            <th>CREATED BY</th>
                                            <th>UPDATED BY</th>
                                            <th>ACTION</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php  $c= 1;?>
                                        @foreach($attendances as $attendance)
{{--                                            @if(request()->user()->division_id==1 || request()->user()->division_id==$church->section->district->division->id)--}}
                                                <tr>
                                                    <td>{{$c++}}</td>
                                                    <td>{{date('d F Y', strtotime($attendance->date)) }}</td>
                                                    <td>{{ucwords($attendance->service->name) }}</td>
                                                    <td>{{ucwords(@$attendance->ministry->name) }}</td>
                                                    <td>{{ucwords($attendance->male) }}</td>
                                                    <td>{{ucwords($attendance->female) }}</td>
                                                    <td>{{ucwords($attendance->visitors) }}</td>
                                                    <td>{{\App\Models\Budget::userName($attendance->created_by)}}</td>
                                                    <td>{{\App\Models\Budget::userName($attendance->updated_by)}}</td>
                                                    <td class="pt-1">
                                                        @if(request()->user()->designation=='administrator')
                                                        <a href="{{route('attendances.show',$attendance->id)}}"
                                                           class="btn btn-primary btn-md rounded-0">
                                                           <i class="fa fa-list-ol"></i> Manage
                                                        </a>
                                                        @endif
                                                    </td>
                                            </tr>
{{--                                            @else--}}
{{--                                                <tr>--}}
{{--                                                    <td>{{$c++}}</td>--}}
{{--                                                    <td>{{ucwords($church->name) }}</td>--}}
{{--                                                    <td>{{ucwords($church->section->name) }}</td>--}}
{{--                                                    <td>{{ucwords($church->leader()) }}</td>--}}
{{--                                                    <td>{{count($church->homeCells) }}</td>--}}
{{--                                                    <td class="pt-1">--}}
{{--                                                        ---}}
{{--                                                    </td>--}}
{{--                                                </tr>--}}
{{--                                            @endif--}}
                                        @endforeach
                                        </tbody>
                                    </table>
                                    </div>
                                @endif
                            </div>
                        </div>

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

