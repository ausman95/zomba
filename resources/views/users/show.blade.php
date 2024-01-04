@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">

        <h4>
            <i class="fa fa-users"></i>Users
        </h4>
        <p>
            Manage user
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                @if(request()->user()->designation==='administrator')
                <li class="breadcrumb-item"><a href="{{route('users.index')}}">Users</a></li>
                @endif
                <li class="breadcrumb-item active" aria-current="page">{{$user->first_name.' '.$user->last_name}}</li>
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
                            <a href="{{route('users.edit',$user->id)}}"
                               class="btn btn-primary btn-md rounded-0" style="margin: 2px">
                                <i class="fa fa-edit"></i>Update
                            </a>
                            <a href="{{route('user.reset')."?user_id={$user->id}&id={$user->id}"}}"
                               class="btn btn-primary btn-md rounded-0" style="margin: 2px">
                                <i class="fa fa-unlock-alt"></i>Reset Password
                            </a>
                        </div>
{{--                        <div class="">--}}
{{--                            @if(request()->user()->designation==='administrator')--}}
{{--                            <form action="{{route('users.destroy',$user->id)}}" method="POST" id="delete-form">--}}
{{--                                @csrf--}}
{{--                                <input type="hidden" name="_method" value="DELETE">--}}
{{--                            </form>--}}
{{--                            <button class="btn btn-danger btn-md rounded-0" id="delete-btn" style="margin: 2px">--}}
{{--                                <i class="fa fa-trash"></i>Delete--}}
{{--                            </button>--}}
{{--                            @endif--}}
{{--                        </div>--}}
                    </div>
                </div><!--./ overview -->
                <div class="col-sm-12 mb-2 col-md-8 col-lg-9">
                    <div class="row">
                        <div class="col-sm-12 col-md-7 col-lg-8">
                            <div class="card ">
                                <div class="card-body px-1">
                                    <div class="table-responsive">
                                        <table class="table table-bordered  table-hover table-striped" id="data-table">
                                            <tbody>
                                            <tr>
                                                <td>Firstname</td>
                                                <td>{{$user->first_name}}</td>
                                            </tr>
                                            <tr>
                                                <td>Lastname</td>
                                                <td>{{$user->last_name}}</td>
                                            </tr>
                                            <tr>
                                                <td>Email</td>
                                                <td>{{$user->email}}</td>
                                            </tr>
                                            <tr>
                                                <td>Phone Number</td>
                                                <td>{{$user->phone_number}}</td>
                                            </tr>
                                            <tr>
                                                <td>Role</td>
                                                <td>{{$user->designation}}</td>
                                            </tr>
                                            <tr>
                                                <td>Created at</td>
                                                <td>{{$user->created_at}}</td>
                                            </tr>
                                            <tr>
                                                <td>Updated at</td>
                                                <td>{{$user->updated_at}}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2">

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
