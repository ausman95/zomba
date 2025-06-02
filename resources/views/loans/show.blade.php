@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('vendor/simple-datatable/simple-datatable.css') }}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4><i class="fa fa-money-bill-alt"></i> Loan Details</h4>
        <p>View Loan Details</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('human-resources.index') }}">Human Resources</a></li>
                <li class="breadcrumb-item"><a href="{{ route('loans.index') }}">Staff Loans</a></li>
                <li class="breadcrumb-item active" aria-current="page">Loan Details</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>

        <div class="mt-3">
            <div class="card-footer d-flex flex-wrap align-items-center mb-3"> {{-- Added d-flex and flex-wrap --}}
                <a href="{{ route('loans.index') }}" class="btn btn-secondary btn-md rounded-0 me-2 mb-2">
                    <i class="fa fa-arrow-left"></i> Back to Loans
                </a>

                {{-- Conditional buttons based on loan status and actions --}}
                @if($loan->loan_status === 'active')
                    <a href="#" class="btn btn-warning btn-md rounded-0 me-2 mb-2"
                       onclick="event.preventDefault(); document.getElementById('deactivate-form-{{$loan->id}}').submit();">
                        <i class="fa fa-power-off"></i> Deactivate Loan
                    </a>
                @elseif($loan->loan_status === 'deactivated' || $loan->loan_status === 'rejected') {{-- Allow reactivation from deactivated or rejected --}}
                <a href="#" class="btn btn-success btn-md rounded-0 me-2 mb-2"
                   onclick="event.preventDefault(); document.getElementById('activate-form-{{$loan->id}}').submit();">
                    <i class="fa fa-play-circle"></i> Activate Loan
                </a>
                @endif
            </div>

            {{-- Edit Monthly Repayment Modal --}}
            <div class="modal fade" id="editRepaymentModal" tabindex="-1" aria-labelledby="editRepaymentModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editRepaymentModalLabel">Edit Monthly Repayment</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('loans.updateRepayment', $loan->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="monthly_repayment" class="form-label">New Monthly Repayment</label>
                                    <input type="number" class="form-control" id="monthly_repayment" name="monthly_repayment" value="{{ old('monthly_repayment', $loan->monthly_repayment) }}" step="0.01" required>
                                    @error('monthly_repayment')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <div class="row">
                    <div class="col-md-8"> {{-- Increased width for info card --}}
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Loan Information</h5>
                                <dl class="row mb-0"> {{-- Removed bottom margin for dl --}}
                                    <dt class="col-sm-4">Staff Name:</dt>
                                    <dd class="col-sm-8">{{ $loan->labourer->name ?? 'N/A' }}</dd>
                                    <dt class="col-sm-4">Loan Amount:</dt>
                                    <dd class="col-sm-8">{{ number_format($loan->loan_amount, 2) }}</dd>
                                    <dt class="col-sm-4">Interest Rate:</dt>
                                    <dd class="col-sm-8">{{ number_format($loan->interest_rate, 2) }}%</dd>
                                    <dt class="col-sm-4">Start Date:</dt>
                                    <dd class="col-sm-8">{{ \Carbon\Carbon::parse($loan->loan_start_date)->format('d F Y') }}</dd>
                                    <dt class="col-sm-4">Duration (Months):</dt>
                                    <dd class="col-sm-8">{{ $loan->loan_duration_months }}</dd>
                                    <dt class="col-sm-4">Monthly Repayment:</dt>
                                    <dd class="col-sm-8">{{ number_format($loan->monthly_repayment, 2) }}</dd>
                                    <dt class="col-sm-4">Remaining Balance:</dt>
                                    <dd class="col-sm-8">{{ number_format($loan->remaining_balance, 2) }}</dd>
                                    <dt class="col-sm-4">Loan Status:</dt>
                                    <dd class="col-sm-8" style="color:red;">{{ ucwords(str_replace('_', ' ', $loan->loan_status)) }}</dd>
                                    <dt class="col-sm-4">Account:</dt>
                                    <dd class="col-sm-8">{{ $loan->account->name ?? 'N/A' }}</dd>
                                    <dt class="col-sm-4">Reason:</dt>
                                    <dd class="col-sm-8">{{ $loan->reason ?? 'N/A' }}</dd>
                                    @if($loan->approved_at)
                                        <dt class="col-sm-4">Approved At:</dt>
                                        <dd class="col-sm-8">{{ \Carbon\Carbon::parse($loan->approved_at)->format('d F Y H:i:s') }}</dd>
                                    @endif
                                    @if($loan->loan_end_date)
                                        <dt class="col-sm-4">Projected End Date:</dt>
                                        <dd class="col-sm-8">{{ \Carbon\Carbon::parse($loan->loan_end_date)->format('d F Y') }}</dd>
                                    @endif
                                    <dt class="col-sm-4">Created By:</dt>
                                    <dd class="col-sm-8">{{ $loan->creator->name ?? 'N/A' }}</dd>
                                    <dt class="col-sm-4">Created At:</dt>
                                    <dd class="col-sm-8">{{ \Carbon\Carbon::parse($loan->created_at)->format('d F Y H:i:s') }}</dd>
                                    <dt class="col-sm-4">Last Updated By:</dt>
                                    <dd class="col-sm-8">{{ $loan->updater->name ?? 'N/A' }}</dd>
                                    <dt class="col-sm-4">Last Updated At:</dt>
                                    <dd class="col-sm-8">{{ \Carbon\Carbon::parse($loan->updated_at)->format('d F Y H:i:s') }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-4">
            <h5>Other Loans for This Staff Member</h5>
            <div style="overflow-x: auto;">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Loan Amount</th>
                        <th>Interest Rate</th>
                        <th>Start Date</th>
                        <th>Duration (Months)</th>
                        <th>Monthly Repayment</th>
                        <th>Remaining Balance</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($employeeLoans as $employeeLoan)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ number_format($employeeLoan->loan_amount, 2) }}</td>
                            <td>{{ number_format($employeeLoan->interest_rate, 2) }}%</td>
                            <td>{{ \Carbon\Carbon::parse($employeeLoan->loan_start_date)->format('d F Y') }}</td>
                            <td>{{ $employeeLoan->loan_duration_months }}</td>
                            <td>{{ number_format($employeeLoan->monthly_repayment, 2) }}</td>
                            <td>{{ number_format($employeeLoan->remaining_balance, 2) }}</td>
                            <td>{{ ucwords(str_replace('_', ' ', $employeeLoan->loan_status)) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No other loan history found for this staff member.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">
            <h5>Repayment History for This Loan</h5> {{-- More specific title --}}
            <div style="overflow-x: auto;">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Date</th>
                        <th>Amount Repaid</th>
                        <th>Description</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($repayments as $repayment)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ \Carbon\Carbon::parse($repayment->date)->format('d F Y') }}</td>
                            <td>{{ number_format($repayment->amount, 2) }}</td> {{-- Assuming 'amount' is positive for repayments --}}
                            <td>{{ $repayment->description ?? 'Loan Repayment' }}</td> {{-- Fallback if description is null --}}
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No repayment history found for this loan.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

{{-- Hidden forms for status updates --}}
<form id="activate-form-{{$loan->id}}" action="{{ route('loans.activate', $loan->id) }}" method="POST" class="d-none">
    @csrf
    @method('PUT')
</form>

<form id="deactivate-form-{{$loan->id}}" action="{{ route('loans.deactivate', $loan->id) }}" method="POST" class="d-none">
    @csrf
    @method('PUT')
</form>
