@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-hand-holding-usd"></i> Loan Details
        </h4>
        <p>
            Details of Loan Application for {{ $loan->employee->name ?? 'N/A' }}
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('human-resources.index') }}">Human Resources</a></li>
                <li class="breadcrumb-item"><a href="{{route('loans.index')}}">Loans</a></li>
                <li class="breadcrumb-item active" aria-current="page">Details</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-3">
            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            Loan Application for {{ ucwords($loan->employee->name ?? 'N/A') }}
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"><strong>Employee:</strong> {{ ucwords($loan->labourer->name ?? 'N/A') }}</li>
                                <li class="list-group-item"><strong>Loan Amount:</strong> MWK {{ number_format($loan->loan_amount, 2) }}</li>
                                <li class="list-group-item"><strong>Interest Rate:</strong> {{ $loan->interest_rate ? $loan->interest_rate . '%' : 'N/A' }}</li>
                                <li class="list-group-item"><strong>Loan Term:</strong> {{ $loan->loan_duration_months ? $loan->loan_duration_months . ' Months' : 'N/A' }}</li>
                                <li class="list-group-item"><strong>Monthly Installment:</strong> MWK {{ $loan->monthly_repayment ? number_format($loan->monthly_repayment, 2) : 'N/A' }}</li>
                                <li class="list-group-item"><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($loan->start_date)->format('d M Y') }}</li>
                                <li class="list-group-item"><strong>End Date:</strong> {{ $loan->end_date ? \Carbon\Carbon::parse($loan->end_date)->format('d M Y') : 'N/A' }}</li>
                                <li class="list-group-item"><strong>Reason:</strong> {{ $loan->reason ?? 'N/A' }}</li>
                                <li class="list-group-item"><strong>Status:</strong>
                                    @if($loan->loan_status === 'approved')
                                        <span class="badge bg-success">Approved</span>
                                    @elseif($loan->loan_status === 'pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @elseif($loan->loan_status === 'rejected')
                                        <span class="badge bg-danger">Rejected</span>
                                    @elseif($loan->loan_status === 'active')
                                        <span class="badge bg-info">Active</span>
                                    @elseif($loan->loan_status === 'completed')
                                        <span class="badge bg-primary">Completed</span>
                                    @else
                                        <span class="badge bg-secondary">{{ucwords($loan->status)}}</span>
                                    @endif
                                </li>
                                <li class="list-group-item"><strong>Approved By:</strong> {{ $loan->updater->name ?? 'N/A' }}</li>
                                <li class="list-group-item"><strong>Approved At:</strong> {{ $loan->approved_at ? \Carbon\Carbon::parse($loan->approved_at)->format('d M Y H:i') : 'N/A' }}</li>
                                <li class="list-group-item"><strong>Balance Outstanding:</strong> MWK {{ number_format($loan->remaining_balance, 2) }}</li>
                            </ul>
                        </div>
                        <div class="card-footer text-end">
                            <a href="{{route('loans.index')}}" class="btn btn-secondary btn-md rounded-0">
                                <i class="fa fa-arrow-left"></i> Back to Loans
                            </a>
                            {{-- Conditional buttons based on loan status --}}
                            @if($loan->loan_status === 'pending')
                                <a href="{{ route('loans.approve', $loan->id) }}" class="btn btn-success btn-md rounded-0 ms-2">
                                    <i class="fa fa-check-circle"></i> Approve
                                </a>
                                <a href="{{ route('loans.reject', $loan->id) }}" class="btn btn-warning btn-md rounded-0 ms-2">
                                    <i class="fa fa-times-circle"></i> Reject
                                </a>
                                <a href="{{route('loans.edit',$loan->id)}}"
                                   class="btn btn-info btn-md rounded-0 ms-2">
                                    <i class="fa fa-edit"></i> Edit
                                </a>
                                <form action="{{route('loans.destroy',$loan->id)}}" method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-md rounded-0 ms-2">
                                        <i class="fa fa-trash"></i> Delete
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-6 mt-3 mt-lg-0">
                    <div class="card">
                        <div class="card-header">
                            Loan Repayments
                        </div>
                        <div class="card-body">
                            @if($loan->repayments->count() === 0)
                                <p class="text-muted">No repayments recorded for this loan yet.</p>
                            @else
                                <div style="overflow-x:auto;">
                                    <table class="table table-bordered table-hover table-striped" id="repayments-table">
                                        <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>PAYMENT DATE</th>
                                            <th>AMOUNT PAID</th>
                                            <th>METHOD</th>
                                            <th>RECEIVED BY</th>
                                            <th>NOTES</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php $r = 1; @endphp
                                        @foreach($loan->repayments as $repayment)
                                            <tr>
                                                <td>{{ $r++ }}</td>
                                                <td>{{ \Carbon\Carbon::parse($repayment->payment_date)->format('d M Y') }}</td>
                                                <td>MWK {{ number_format($repayment->amount_paid, 2) }}</td>
                                                <td>{{ ucwords($repayment->payment_method ?? 'N/A') }}</td>
                                                <td>{{ ucwords($repayment->receiver->name ?? 'N/A') }}</td>
                                                <td>{{ $repayment->notes ?? 'N/A' }}</td>
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

    {{-- Hidden form for delete action confirmation --}}
    <form action="" method="POST" id="delete-form" class="d-none">
        @csrf
        @method('DELETE')
    </form>
@stop

@section('scripts')
    <script src="{{asset('vendor/simple-datatable/simple-datatable.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> {{-- SweetAlert2 for confirmation --}}
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

        document.addEventListener('DOMContentLoaded', function () {
            // Initialize DataTable for repayments if there are any
            @if($loan->repayments->count() > 0)
            new simpleDatatables.DataTable("#repayments-table", {
                searchable: true,
                fixedheight: true,
                perPage: 5, // Adjust as needed
                labels: {
                    placeholder: "Search repayments...",
                    perPage: "{select} entries per page",
                    noRows: "No repayments found",
                    info: "Showing {start} to {end} of {rows} entries",
                    noResults: "No results match your search query",
                },
            });
            @endif

            // Delete form submission confirmation
            document.querySelectorAll(".delete-form").forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const formElement = this;
                    confirmationWindow(
                        "Confirm Deletion",
                        "Are you sure you want to delete this loan application? This action cannot be undone.",
                        "Yes, Delete",
                        function () {
                            formElement.submit();
                        }
                    );
                });
            });
        });
    </script>
@stop
