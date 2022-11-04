@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-users"></i>Users
        </h4>
        <p>
            User accounts
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Users</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-3">
            <a href="{{route('users.create')}}" class="btn btn-primary btn-md rounded-0">
                <i class="fa fa-user-plus"></i>New User
            </a>
            <a href="{{route('user.audit')}}" class="btn btn-primary btn-md rounded-0">
                <i class="fa fa-file-archive"></i>Audit Trail
            </a>
            <div class="mt-3">
                <div class="row">
                    <div class="col-sm-12 mb-2 col-md-12 col-lg-12">
                        <div class="card " style="min-height: 30em;">
                            <div class="card-body px-1">
                                @if($users->count() === 0)
                                    <i class="fa fa-info-circle"></i>There are no  users!
                                @else
                                    <div style="overflow-x:auto;">
                                        <table class="table table-bordered table-hover table-striped" id="data-table">
                                            <caption style=" caption-side: top; text-align: center">USERS</caption>
                                            <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>NAME</th>
                                            <th>PHONE #</th>
                                            <th>EMAIL/USERNAME</th>
                                            <th>ROLE</th>
                                            <th>LEVEL</th>
                                            <th>POSITION</th>
                                            <th>LAST LOGIN</th>
                                            <th>STATUS</th>
                                            <th>ACTION</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $c = 1;?>
                                        @foreach($users as $user)
                                            <tr>
                                                <td>{{$c++}}</td>
                                                <td>{{$user->first_name.' '.$user->last_name}}</td>
                                                <td>{{$user->phone_number}}</td>
                                                <td>{{$user->email}}</td>
                                                <td>{{$user->designation}}</td>
                                                <td>{{$user->level}}</td>
                                                <td>{{$user->position}}</td>
                                                <td>{{$user->getTimeAttribute()}}</td>
                                                <td>
                                                    @if(Cache::has('user-is-online-' . $user->id))
                                                        <span class="text-success">Online</span>
                                                    @else
                                                        <span class="text-danger">Offline</span>
                                                    @endif
                                                </td>
                                                <td class="pt-1">
                                                    <a href="{{route('users.show',$user->id)}}"
                                                       class="btn btn-primary btn-md rounded-0">
                                                      <i class="fa fa-list-ol"></i>  Manage
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
