@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">

        <h4>
            <i class="fa fa-users"></i>Bank Account
        </h4>
        <p>
            Bank Account information
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('finances.index')}}">Finances</a></li>
                <li class="breadcrumb-item"><a href="{{route('banks.index')}}">Bank Accounts</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$bank->name}}</li>
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
                            <a href="{{route('banks.edit',$bank->id)}}"
                               class="btn btn-link text-primary text-undecorated">
                                <i class="fa fa-edit"></i>Update
                            </a>
                        </div>
                        <div class="">
                            <form action="{{route('banks.destroy',$bank->id)}}" method="POST" id="delete-form">
                                @csrf
                                <input type="hidden" name="_method" value="DELETE">
                            </form>
                            <button class="btn btn-link text-danger text-undecorated" id="delete-btn">
                                <i class="fa fa-trash"></i>Delete
                            </button>
                        </div>
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
                                            <td>Account Name</td>
                                            <td>{{$bank->account_name}}</td>
                                        </tr>
                                        <tr>
                                            <td>Account Number</td>
                                            <td>{{$bank->account_number}}</td>
                                        </tr>
                                        <tr>
                                            <td>Service Centre</td>
                                            <td>{{$bank->service_centre}}</td>
                                        </tr>
                                        <tr>
                                            <td>Account Type</td>
                                            <td>{{$bank->account_type}}</td>
                                        </tr>
                                        <tr>
                                            <td>Created On</td>
                                            <td>{{$bank->created_at}}</td>
                                        </tr>
                                        <tr>
                                            <td>Update ON</td>
                                            <td>{{$bank->updated_at}}</td>
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
                            <i class="fa fa-microscope"></i>Transactions
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
