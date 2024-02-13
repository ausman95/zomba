@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-money-bill-alt"></i>Pledges
        </h4>
        <p>
            Member Pledges
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('finances.index')}}">Finances</a></li>
                <li class="breadcrumb-item"><a href="{{route('pledges.index')}}">Pledges</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$pledge->id}}</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="col-sm-12 mb-2 col-md-8 col-lg-9">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <div class="card shadow-sm">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <tbody>
                                    <tr>
                                        <td>Member</td>
                                        <td>{{$pledge->member->name}}</td>
                                    </tr>
                                    <tr>
                                        <td>Member Contact</td>
                                        <td>{{$pledge->member->phone_number}}</td>
                                    </tr>
                                    <tr>
                                        <td>Account</td>
                                        <td>{{$pledge->account->name}}</td>
                                    </tr>
                                    <tr>
                                        <td>Amount</td>
                                        <td>{{number_format($pledge->amount,2)}}</td>
                                    </tr>
                                    <tr>
                                        <td>Created On</td>
                                        <td>{{$pledge->created_at}}</td>
                                    </tr>
                                    <tr>
                                        <td>Update ON</td>
                                        <td>{{$pledge->updated_at}}</td>
                                    </tr>
                                    <tr>
                                        <td>Update By</td>
                                        <td>{{\App\Models\Budget::userName($pledge->updated_by)}}</td>
                                    </tr>
                                    <tr>
                                        <td>Created By</td>
                                        <td>{{@\App\Models\Budget::userName($pledge->created_by)}}</td>
                                    </tr>
                                </table>
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
