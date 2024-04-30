@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">

        <h4>
            <i class="fa fa-users"></i>Bank Transfers
        </h4>
        <p>
            Bank Bank Transfers information
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('finances.index')}}">Finances</a></li>
                <li class="breadcrumb-item"><a href="{{route('banks.index')}}">Banks</a></li>
                <li class="breadcrumb-item"><a href="{{route('transfers.index')}}">Bank Transfers</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$transfer->id}}</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
{{--                <div class="col-sm-12 mb-2 col-md-4 col-lg-3">--}}
{{--                    <div class="card shadow-sm">--}}
{{--                        <div class="card-body election-banner-card p-1">--}}
{{--                            <img src="{{asset('images/avatar.png')}}" alt="avatar image" class="img-fluid">--}}
{{--                        </div>--}}
{{--                    </div>--}}
                    <div class="mt-3">
{{--                        <div>--}}
{{--                            <a href="{{route('transfers.edit',$transfer->id)}}"--}}
{{--                               class="btn btn-link text-primary text-undecorated">--}}
{{--                                <i class="fa fa-edit"></i>Update--}}
{{--                            </a>--}}
{{--                        </div>--}}
{{--                        <div class="">--}}
{{--                            <form action="{{route('transfers.destroy',$transfer->id)}}" method="POST" id="delete-form">--}}
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
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-striped">
                                        <caption style=" caption-side: top; text-align: center">BANK TRANSFER DETAILS</caption>
                                        <tbody>
                                        <tr>
                                            <td>Account Name From</td>
                                            <td>{{$transfer->accountFrom->account_name}}</td>
                                        </tr>
                                        <tr>
                                            <td>Account Number From</td>
                                            <td>{{$transfer->accountFrom->account_number}}</td>
                                        </tr>
                                        <tr>
                                            <td>Service Centre From</td>
                                            <td>{{$transfer->accountFrom->service_centre}}</td>
                                        </tr>
                                        <tr>
                                            <td>Amount Transferred</td>
                                            <td>{{number_format($transfer->amount)}}</td>
                                        </tr>
                                        <tr>
                                            <td>Cheque Number</td>
                                            <td>{{$transfer->cheque_number}}</td>
                                        </tr>
                                        <tr>
                                            <td>Account Name To</td>
                                            <td>{{$transfer->accountTo->account_name}}</td>
                                        </tr>
                                        <tr>
                                            <td>Account Number To</td>
                                            <td>{{$transfer->accountTo->account_number}}</td>
                                        </tr>
                                        <tr>
                                            <td>Service Centre To</td>
                                            <td>{{$transfer->accountTo->service_centre}}</td>
                                        </tr>
                                        <tr>
                                            <td>Date</td>
                                            <td>{{date('d F Y', strtotime($transfer->t_date))}}</td>
                                        </tr>
                                        <tr>
                                            <td>Created On</td>
                                            <td>{{date('d F Y', strtotime($transfer->created_at))}}</td>
                                        </tr>
                                        <tr>
                                            <td>Update ON</td>
                                            <td>{{date('d F Y', strtotime($transfer->updated_at))}}</td>
                                        </tr>
                                        <tr>
                                            <td>Update By</td>
                                            <td>{{\App\Models\Budget::userName($transfer->updated_by)}}</td>
                                        </tr>
                                        <tr>
                                            <td>Created By</td>
                                            <td>{{@\App\Models\Budget::userName($transfer->created_by)}}</td>
                                        </tr>
                                    </table>
                                    <div class="mt-3">
                                        <div>
                                            <a href="{{route('transfers.edit',$transfer->id)}}"
                                               class="btn btn-btn btn-primary text-undecorated">
                                                <i class="fa fa-edit"></i>Update
                                            </a>
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
