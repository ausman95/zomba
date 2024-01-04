@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-list-ol"></i>Announcements
        </h4>
        <p>
            Manage Announcements
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('announcements.index')}}">Announcements</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$announcement->title}}</li>
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
                            <i class="fa fa-microscope"></i>Announcement Information
                        </h5>
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table  table-bordered table-hover table-striped">
                                        <caption style=" caption-side: top; text-align: center">{{$announcement->title}}</caption>
                                        <tbody>
                                        <tr>
                                            <td>From</td>
                                            <td>{{$announcement->from}}</td>
                                        </tr>
                                        <tr>
                                            <td>Title</td>
                                            <td>{{$announcement->title}}</td>
                                        </tr>
                                        <tr>
                                            <td>Body</td>
                                            <td>{{$announcement->body}}</td>
                                        </tr>
                                        <tr>
                                            <td>Created On</td>
                                            <td>{{$announcement->created_at}}</td>
                                        </tr>
                                        <tr>
                                            <td>Update ON</td>
                                            <td>{{$announcement->updated_at}}</td>
                                        </tr>
                                    </table>
                                    <div class="mt-3">
                                        <div>
                                            @if(request()->user()->designation=='administrator')
                                            <a href="{{route('announcements.edit',$announcement->id)}}"
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
