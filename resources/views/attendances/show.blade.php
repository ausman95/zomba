@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-list-ol"></i>Church Attendances
        </h4>
        <p>
            Manage Church Attendances
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('attendances.index')}}">Attendances</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$attendance->service->name}}</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-4 mb-2">
                    <div class="mt-5">
                        <h5>
                            <i class="fa fa-microscope"></i>Attendances Information
                        </h5>
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table  table-bordered table-hover table-striped">
                                    <caption style=" caption-side: top; text-align: center">{{$attendance->service->name}} Church</caption>
                                    <tbody>
                                    <tr>
                                        <td>Service Name</td>
                                        <td>{{$attendance->service->name}}</td>
                                    </tr>
                                    <tr>
                                        <td>Ministry Name</td>
                                        <td>{{@$attendance->ministry->name}}</td>
                                    </tr>
                                    <tr>
                                        <td>Male Attendance</td>
                                        <td>{{$attendance->male}}</td>
                                    </tr>
                                    <tr>
                                        <td>Female Attendance</td>
                                        <td>{{$attendance->female}}</td>
                                    </tr>
                                    <tr>
                                        <td>Visitors</td>
                                        <td>{{$attendance->visitors}}</td>
                                    </tr>
                                    <tr>
                                        <td>Created On</td>
                                        <td>{{$attendance->created_at}}</td>
                                    </tr>
                                    <tr>
                                        <td>Update ON</td>
                                        <td>{{$attendance->updated_at}}</td>
                                    </tr>
                                    <tr>
                                        <td>Update By</td>
                                        <td>{{\App\Models\Budget::userName($attendance->updated_by)}}</td>
                                    </tr>
                                    <tr>
                                        <td>Created By</td>
                                        <td>{{@\App\Models\Budget::userName($attendance->created_by)}}</td>
                                    </tr>
                                </table>
                                <div class="mt-3">
                                    <div>
                                        <a href="{{route('attendances.edit',$attendance->id)}}"
                                           class="btn btn-primary rounded-0" style="margin: 2px">
                                            <i class="fa fa-edit"></i>Update
                                        </a>
                                        <button class="btn btn-danger btn-md rounded-0" id="delete-btn" style="margin: 5px">
                                            <i class="fa fa-trash"></i>Delete
                                        </button>
                                        <form action="{{route('attendances.destroy',$attendance->id)}}" method="POST" id="delete-form">
                                            @csrf
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="id" value="{{$attendance->id}}">
                                        </form>
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
                confirmationWindow("Confirm Deletion", "Are you sure you want to delete this attendance?", "Yes,Delete", function () {
                    $("#delete-form").submit();
                });
            });
        })
    </script>
@stop
