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
            @if($loan->loan_status=='active')
                <button type="button" class="btn btn-primary btn-md rounded-0" data-bs-toggle="modal" data-bs-target="#editRepaymentModal">
                    <i class="fa fa-edit"></i> Edit Monthly Repayment
                </button>
            @endif
            <div class="modal" id="editRepaymentModal" tabindex="-1" aria-labelledby="editRepaymentModalLabel" aria-hidden="true">
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
                                    <input type="number" class="form-control" id="monthly_repayment" name="monthly_repayment" value="{{ $loan->monthly_repayment }}" required>
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
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Loan Information</h5>
                                <dl class="row">
                                    <dt class="col-sm-4">Staff Name:</dt>
                                    <dd class="col-sm-8">{{ @$loan->labourer->name }}</dd>

                                    <dt class="col-sm-4">Loan Amount:</dt>
                                    <dd class="col-sm-8">{{ number_format($loan->loan_amount, 2) }}</dd>

                                    <dt class="col-sm-4">Start Date:</dt>
                                    <dd class="col-sm-8">{{ date('d F Y', strtotime($loan->loan_start_date)) }}</dd>

                                    <dt class="col-sm-4">Duration (Months):</dt>
                                    <dd class="col-sm-8">{{ $loan->loan_duration_months }}</dd>

                                    <dt class="col-sm-4">Monthly Repayment:</dt>
                                    <dd class="col-sm-8">{{ number_format($loan->monthly_repayment, 2) }}</dd>

                                    <dt class="col-sm-4">Remaining Balance:</dt>
                                    <dd class="col-sm-8">{{ number_format($loan->remaining_balance, 2) }}</dd>

                                    <dt class="col-sm-4">Loan Status:</dt>
                                    <dd class="col-sm-8">{{ $loan->loan_status }}</dd>

                                    <dt class="col-sm-4">Account:</dt>
                                    <dd class="col-sm-8">{{ @$loan->account->name }}</dd>

                                    <dt class="col-sm-4">Created By:</dt>
                                    <dd class="col-sm-8">
                                        @if ($loan->created_by)
                                            {{ optional(\App\Models\User::find($loan->created_by))->name }}
                                        @else
                                            N/A
                                        @endif
                                    </dd>

                                    <dt class="col-sm-4">Created At:</dt>
                                    <dd class="col-sm-8">{{ date('d F Y H:i:s', strtotime($loan->created_at)) }}</dd>

                                    <dt class="col-sm-4">Updated By:</dt>
                                    <dd class="col-sm-8">
                                        @if ($loan->updated_by)
                                            {{ optional(\App\Models\User::find($loan->updated_by))->name }}
                                        @else
                                            N/A
                                        @endif
                                    </dd>

                                    <dt class="col-sm-4">Updated At:</dt>
                                    <dd class="col-sm-8">{{ date('d F Y H:i:s', strtotime($loan->updated_at)) }}</dd>
                                </dl>
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
@stop
