@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-money-bill-alt"></i>Payments
        </h4>
        <p>
            Manage Ministry Transactions
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('payments.index')}}">payments</a></li>
                <li class="breadcrumb-item active" aria-current="page">Ministry Transactions</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-3">
            <a href="{{route('payments.create')}}" class="btn btn-primary btn-md rounded-0">
                <i class="fa fa-plus-circle"></i>New Payment
            </a>
            <div class="mt-3">
                <div class="row">
                    <div class="col-sm-12 mb-2 col-md-12 col-lg-12">
                        <div class="card " style="min-height: 30em;">
                            <div class="card-body px-1">
                                @if($payments->count() === 0)
                                    <i class="fa fa-info-circle"></i>There are no payment!
                                @else
                                    <div style="overflow-x:auto;">
                                        <table class="table  table-bordered table-hover table-striped">
                                            <caption style=" caption-side: top; text-align: center">MINISTRY TRANSACTIONS</caption>
                                            <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>DATE</th>
                                            <th>MINISTRY</th>
                                            <th>AMOUNT (MK)</th>
                                            <th>ACCOUNT</th>
                                            <th>METHOD</th>
                                            <th>REF</th>
                                            <th>ACTION</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php  $c= 1;?>
                                        @foreach($payments as $payment)
                                            <tr>
                                                <td>{{$c++}}</td>
                                                <td>{{date('d F Y', strtotime($payment->created_at)) }}</td>
                                                <td>{{$payment->getMinistry($payment->id) }}</td>
                                                <td>{{number_format($payment->amount) }}</td>
                                                <td>{{ucwords($payment->account->name) }}</td>
                                                <td>
                                                    @if($payment->payment_method==1)
                                                        CASH
                                                    @elseif($payment->payment_method==3)
                                                        CHEQUE
                                                    @else
                                                        ONLINE TRANSFER
                                                    @endif
                                                </td>
                                                <td>{{ucwords($payment->reference) }}</td>
                                                <td>
                                                    <a href="{{route('ministry-receipt.generate')."?id={$payment->id}"}}" target="_blank" class="btn btn-primary rounded-0" style="margin: 2px">
                                                        <i class="fa fa-vote-yea"></i> Generate
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
