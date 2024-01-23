@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-calendar-check"></i>Months
        </h4>
        <p>
            Manage Account information
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('setting.index')}}">Settings</a></li>
                <li class="breadcrumb-item"><a href="{{route('months.index')}}">Months</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$month->name}}</li>
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
                                        <caption style=" caption-side: top; text-align: center">{{$month->name}}</caption>
                                        <tbody>
                                        <tr>
                                            <td>Name</td>
                                            <td>{{$month->name}}</td>
                                        </tr>
                                        <tr>
                                            <td>Start Date</td>
                                            <td>{{date('d F Y', strtotime($month->start_date)) }}</td>
                                        </tr>
                                        <tr>
                                            <td>End Date</td>
                                            <td>{{date('d F Y', strtotime($month->end_date)) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Created On</td>
                                            <td>{{date('d F Y', strtotime($month->created_at)) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Update ON</td>
                                            <td>{{date('d F Y', strtotime($month->updated_date)) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Status</td>
                                            <td>
                                                @if($month->soft_delete==1)
                                                    <p style="color: red">Deleted, and Reserved for Audit</p>
                                                @else
                                                    Verified
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                    <div class="mt-3">
                                        <div>
                                            <a href="{{route('months.edit',$month->id)}}"
                                               class="btn btn-primary rounded-0" style="margin: 2px">
                                                <i class="fa fa-edit"></i>Update
                                            </a>
                                            <button class="btn btn-danger btn-md rounded-0" id="delete-btn" style="margin: 5px">
                                                <i class="fa fa-trash"></i>Delete
                                            </button>
                                            <form action="{{route('months.destroy',$month->id)}}" method="POST" id="delete-form">
                                                @csrf
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="id" value="{{$month->id}}">
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
