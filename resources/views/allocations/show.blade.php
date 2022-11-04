@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">

        <h4>
            <i class="fa fa-users"></i>Allocation
        </h4>
        <p>
            Manage Allocation information
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('hr.index')}}">Human Resources</a></li>
                <li class="breadcrumb-item"><a href="{{route('members.index')}}">Labourers</a></li>
                <li class="breadcrumb-item"><a href="{{route('labours.index')}}">Labours</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$allocation->labourer->name}}</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 mb-2 col-md-4 col-lg-3">
                    <div class="card shadow-sm">
                        <div class="card-body election-banner-card p-1">
                            <img src="{{asset('images/avatar.png')}}" alt="avatar image" class="img-fluid">
                        </div>
                    </div>
                    <div class="mt-3">
                        <div>
                            <a href="{{route('allocations.edit',$allocation->id)}}"
                               class="btn btn-link text-primary text-undecorated">
                                <i class="fa fa-edit"></i>Update
                            </a>
                        </div>
{{--                        <div class="">--}}
{{--                            <form action="{{route('allocations.destroy',$allocation->id)}}" method="POST" id="delete-form">--}}
{{--                                @csrf--}}
{{--                                <input type="hidden" name="_method" value="DELETE">--}}
{{--                            </form>--}}
{{--                            <button class="btn btn-link text-danger text-undecorated" id="delete-btn">--}}
{{--                                <i class="fa fa-trash"></i>Delete--}}
{{--                            </button>--}}
{{--                        </div>--}}
                    </div>
                </div><!--./ overview -->
                <div class="col-sm-12 mb-2 col-md-8 col-lg-9">
                    <div class="row">
                        <div class="col-sm-12 col-md-7 col-lg-6">
                            <div class="card shadow-sm">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <tbody>
                                        <tr>
                                            <td>Name</td>
                                            <td>{{$allocation->name}}</td>
                                        </tr>

                                        <tr>
                                            <td>Created On</td>
                                            <td>{{$allocation->created_at}}</td>
                                        </tr>
                                        <tr>
                                            <td>Update ON</td>
                                            <td>{{$allocation->updated_at}}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
{{--                            <div class="mt-3">--}}
{{--                                <h6>Specification</h6>--}}
{{--                                <hr>--}}
{{--                                <p style="white-space: break-spaces;">{{ ucfirst($account->specifications)}}</p>--}}
{{--                            </div>--}}
                        </div>
                    </div>
                    <div class="mt-5">
                        <h5>
                            <i class="fa fa-microscope"></i>{{$labour->name}}s
                        </h5>
                        <div class="card">
                            <div class="card-body">
                                <i class="fa fa-info-circle"></i>Nothing at the moment
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
                confirmationWindow("Confirm Deletion", "Are you sure you want to delete this user account?", "Yes,Delete", function () {
                    $("#delete-form").submit();
                });
            });
        })
    </script>
@stop
