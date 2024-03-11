@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-speaker-deck"></i>Home Cell Programs
        </h4>
        <p>
            Manage Programs
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('programs.index')}}">Programs</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{date('d F Y', strtotime($program->t_date))}}</li>
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
                            <i class="fa fa-microscope"></i>Program Information
                        </h5>
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table  table-bordered table-hover table-striped">
                                        <caption style=" caption-side: top; text-align: center">{{date('d F Y', strtotime($program->t_date))}}</caption>
                                        <tbody>
                                        <tr>
                                            <td>Date</td>
                                            <td>{{date('d F Y', strtotime($program->t_date))}}</td>
                                        </tr>
                                        <tr>
                                            <td>Venue</td>
                                            <td>{{$program->venue}}</td>
                                        </tr>
                                        <tr>
                                            <td>Facilitator</td>
                                            <td>{{$program->member->name}}</td>
                                        </tr>
                                        <tr>
                                            <td>Preacher</td>
                                            <td>{{$program->members->name}}</td>
                                        </tr>
                                        <tr>
                                            <td>Created On</td>
                                            <td>{{date('d F Y', strtotime($program->created_at))}}</td>
                                        </tr>
                                        <tr>
                                            <td>Update ON</td>
                                            <td>{{date('d F Y', strtotime($program->updated_at))}}</td>
                                        </tr>
                                        <tr>
                                            <td>Created By</td>
                                            <td>{{\App\Models\Budget::userName($program->created_by)}}</td>
                                        </tr>
                                        <tr>
                                            <td>Updated By</td>
                                            <td>{{\App\Models\Budget::userName($program->updated_by)}}</td>
                                        </tr>

                                    </table>
                                    <div class="mt-3">
                                        <div>
                                            @if(request()->user()->designation=='church')
                                            <a href="{{route('programs.edit',$program->id)}}"
                                               class="btn btn-primary rounded-0" style="margin: 2px">
                                                <i class="fa fa-edit"></i>Update
                                            </a>
                                                <button class="btn btn-danger btn-md rounded-0" id="delete-btn" style="margin: 5px">
                                                    <i class="fa fa-trash"></i>Delete
                                                </button>
                                                <form action="{{route('programs.destroy',$program->id)}}" method="POST" id="delete-form">
                                                    @csrf
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="id" value="{{$program->id}}">
                                                </form>
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
                        confirmationWindow("Confirm Deletion", "Are you sure you want to delete this Record?", "Yes,Delete", function () {
                            $("#delete-form").submit();
                        });
                    });
                })
            </script>
@stop
