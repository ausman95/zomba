
@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-wallet"></i> Finances
        </h4>
        <p>
            Finances
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Finances</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-3">
            <a href="{{ route('budgets.index') }}" class="btn btn-primary btn-md rounded-0">
                <i class="fas fa-file-archive"></i> Budgets
            </a>
            <a href="{{ route('banks.index') }}" class="btn btn-primary btn-md rounded-0">
                <i class="fas fa-university"></i> Banks  </a>
            <a href="{{ route('accounts.index') }}" class="btn btn-primary btn-md rounded-0">
                <i class="fas fa-wallet"></i> Accounts
            </a>
            <a href="{{ route('debtors.index') }}" class="btn btn-primary btn-md rounded-0">
                <i class="fas fa-users"></i> Debtors
            </a>
            <a href="{{ route('creditors.index') }}" class="btn btn-primary btn-md rounded-0">
                <i class="fas fa-user-friends"></i> Creditors
            </a>
{{--            <a href="{{ route('purchases.index') }}" class="btn btn-primary btn-md rounded-0">--}}
{{--                <i class="fas fa-shopping-cart"></i> Purchases--}}
{{--            </a>--}}
            <a href="{{ route('invoices.index') }}" class="btn btn-primary btn-md rounded-0">
                <i class="fas fa-shopping-cart"></i> Invoices
            </a>
            <a href="{{ route('pledges.index') }}" class="btn btn-primary btn-md rounded-0">
                <i class="fas fa-hand-holding-usd"></i> Pledges
            </a>
            <a href="{{ route('payments.index') }}" class="btn btn-primary btn-md rounded-0">
                <i class="fas fa-money-bill-wave"></i> Payments
            </a>
            <a href="{{ route('receipts.index') }}" class="btn btn-primary btn-md rounded-0">
                <i class="fas fa-receipt"></i> Receipts
            </a>
            <a href="{{ route('receipt.unverified') }}" class="btn btn-primary btn-md rounded-0">
                <i class="fas fa-exclamation-triangle"></i> Unverified
            </a>
            <a href="{{ route('receipt.all') }}" class="btn btn-primary btn-md rounded-0">
                <i class="fas fa-list-alt"></i> All
            </a>
            <a href="{{ route('analytics') }}" class="btn btn-primary btn-md rounded-0">
                <i class="fas fa-chart-line"></i> Reports
            </a>
            <div class="mt-3">
                <div class="row">
                    <div class="col-sm-12 mb-2 col-md-12 col-lg-12">
                        <div class="card " style="min-height: 30em;">
                            <div class="card-body px-1">
                                @if($banks->count() === 0)
                                    <i class="fa fa-info-circle"></i>There are no Bank Accounts!
                                @else
                                    <div style="overflow-x:auto;">
                                        <table class="table table-bordered table-hover table-striped" id="data-table">
                                            <caption style="caption-side: top; text-align: center">BANKS</caption>
                                            <thead>
                                            <tr>
                                                <th>NO</th>
                                                <th>ACCOUNT NAME</th>
                                                <th>BALANCE (MK)</th>
                                                <th>ACCOUNT NUMBER</th>
                                                <th>SERVICE CENTRE</th>
                                                <th>CREATED BY</th>
                                                <th>UPDATED BY</th>
                                                <th>ACTION</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <?php $c = 1; ?>
                                            @foreach($banks as $bank)
                                                <tr>
                                                    <td>{{ $c++ }}</td>
                                                    <td>{{ ucwords($bank->account_name) }}</td>
                                                    <td>
                                                        @if($bank->getBalance() < 0)
                                                            ({{ number_format(abs($bank->getBalance()), 2) }})
                                                        @else
                                                            {{ number_format($bank->getBalance(), 2) }}
                                                        @endif
                                                    </td>
                                                    <td>{{ ucwords($bank->account_number) }}</td>
                                                    <td>{{ ucwords($bank->service_centre) }}</td>
                                                    <td>{{ \App\Models\Budget::userName($bank->created_by) }}</td>
                                                    <td>{{ \App\Models\Budget::userName($bank->updated_by) }}</td>
                                                    <td class="pt-1">
                                                        <a href="{{ route('banks.show', $bank->id) }}" class="btn btn-primary btn-md rounded-0">
                                                            <i class="fa fa-list-ol"></i> Manage
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
