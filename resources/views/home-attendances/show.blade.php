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
                <li class="breadcrumb-item active" aria-current="page">{{$attendance->member->name}}</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 mb-2 col-md-8 col-lg-9">
                    <div class="row">
                        <div class="col-sm-12 col-md-7 col-lg-8">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table  table-bordered table-hover table-striped">
                                            <caption style=" caption-side: top; text-align: center">{{$attendance->member->name}} ATTENDANCES</caption>
                                            <tbody>
                                            <tr>
                                                <td>Member</td>
                                                <td>{{$attendance->member->name}}</td>
                                            </tr>
                                            <tr>
                                                <td>Church</td>
                                                <td>{{$attendance->member->church->name}}</td>
                                            </tr>
                                            <tr>
                                                <td>Status</td>
                                            <td>
                                                @if($attendance->status==1)
                                                    {{"PRESENT"}}
                                                @else
                                                    {{"ABSENT"}}
                                                @endif
                                            </td>
                                            <tr>
                                                <td>Created On</td>
                                                <td>{{date('d F Y', strtotime($attendance->created_at))}}</td>
                                            </tr>
                                            <tr>
                                                <td>Update ON</td>
                                                <td>{{date('d F Y', strtotime($attendance->updated_at))}}</td>
                                            </tr>
                                            <tr>
                                                <td>Created By</td>
                                            <td>{{\App\Models\Budget::userName($attendance->created_by)}}</td>
                                            </tr>
                                            <tr>
                                                <td>Updated By</td>
                                                <td>{{\App\Models\Budget::userName($attendance->updated_by)}}</td>
                                            </tr>
                                        </table>
                                        <div class="mt-3">
                                            <div>
                                                @if(request()->user()->designation!='member')
                                                <a href="{{route('home-attendances.edit',$attendance->id)}}"
                                                   class="btn btn-primary rounded-0" style="margin: 2px">
                                                    <i class="fa fa-edit"></i>Update
                                                </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script>
        $(document).ready(function () {
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
            $("#delete-btn").on('click', function () {
                confirmationWindow("Confirm Deletion", "Are you sure you want to delete this account?", "Yes,Delete", function () {
                    $("#delete-form").submit();
                });
            });
        })
    </script>
@stop
