@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('vendor/simple-datatable/simple-datatable.css') }}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4><i class="fa fa-file-contract"></i> Staffs Loans</h4>
        <p>Manage Staffs Loans</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('human-resources.index') }}">Human Resources</a></li>
                <li class="breadcrumb-item active" aria-current="page">Staffs Loans</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-3">
            <div class="mt-3">
                <div class="row">
                    <div class="col-sm-12 mb-2 col-md-12 col-lg-12">
                        <div class="card" style="min-height: 30em;">
                            <div class="card-body px-1">
                                @if ($loans->count() === 0)
                                    <i class="fa fa-info-circle"></i> There are no STAFF Loans!
                                @else
                                    <div style="overflow-x: auto;">
                                        <table class="table table-bordered table-hover table-striped" id="data-table">
                                            <caption style="caption-side: top; text-align: center">STAFF LOANS</caption>
                                            <thead>
                                            <tr>
                                                <th>NO</th>
                                                <th>STAFF NAME</th>
                                                <th>LOAN AMOUNT</th>
                                                <th>START DATE</th>
                                                <th>DURATION (MONTHS)</th>
                                                <th>MONTHLY REPAYMENT</th>
                                                <th>REMAINING BALANCE</th>
                                                <th>LOAN STATUS</th>
                                                <th>ACTIONS</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php $c = 1; @endphp
                                            @foreach ($loans as $loan)
                                                <tr>
                                                    <td>{{ $c++ }}</td>
                                                    <td>{{ @$loan->labourer->name }}</td>
                                                    <td>{{ number_format($loan->loan_amount, 2) }}</td>
                                                    <td>{{ date('d F Y', strtotime($loan->loan_start_date)) }}</td>
                                                    <td>{{ $loan->loan_duration_months }}</td>
                                                    <td>{{ number_format($loan->monthly_repayment, 2) }}</td>
                                                    <td>{{ number_format($loan->remaining_balance, 2) }}</td>
                                                    <td>{{ $loan->loan_status }}</td>
                                                    <td class="pt-1">
                                                        <a href="{{ route('loans.show', $loan->id) }}" class="btn btn-primary btn-md rounded-0">
                                                            <i class="fa fa-eye"></i> View Details
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
    <script src="{{ asset('vendor/simple-datatable/simple-datatable.js') }}"></script>
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

        $(document).ready(function() {
            $(".delete-btn").on('click', function() {
                $url = $(this).attr('data-target-url');
                $("#delete-form").attr('action', $url);
                confirmationWindow("Confirm Deletion", "Are you sure you want to delete this position?", "Yes,Delete",
                    function() {
                        $("#delete-form").submit();
                    })
            });
        })
    </script>
@stop
