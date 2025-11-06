@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('vendor/simple-datatable/simple-datatable.css') }}">
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #printable-payroll, #printable-payroll * {
                visibility: visible;
            }
            #printable-payroll {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                padding: 20px;
                font-size: 12px;
            }
            .no-print {
                display: none;
            }
            #printable-payroll img {
                max-width: 150px; /* Adjust logo size */
                margin-bottom: 20px;
            }
            #printable-payroll h2, #printable-payroll h3 {
                margin-bottom: 10px;
            }
            #printable-payroll ul {
                list-style-type: none;
                padding: 0;
            }
            #printable-payroll li {
                margin-bottom: 5px;
            }
        }
    </style>
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4 no-print">
        <h4><i class="fa fa-file-contract"></i> Payroll Details</h4>
        <p>Detailed information about the selected payroll.</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('human-resources.index') }}">Human Resources</a></li>
                <li class="breadcrumb-item"><a href="{{ route('payrolls.index') }}">Payrolls</a></li>
                <li class="breadcrumb-item active" aria-current="page">Payroll Details</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-3">
            <div class="row">
                <div class="col-sm-12 mb-2 col-md-12 col-lg-12">
                        <div class="card-body px-1">
                            <h2 class="mb-4">Payroll Actions</h2>
                            <div class="mt-3">
                                @if ($payroll->status == 'Pending')
                                    <button class="btn btn-success rounded-0" data-bs-toggle="modal" data-bs-target="#approvePayrollModal">
                                        <i class="fas fa-check-circle"></i> Approve
                                    </button>
                                    <form action="{{ route('payrolls.destroy', $payroll->id) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-md rounded-0" onclick="return confirm('Are you sure you want to delete this payroll record?')">
                                            <i class="fa fa-trash"></i> Delete
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('payrolls.index') }}" class="btn btn-secondary rounded-0">
                                    <i class="fas fa-arrow-left"></i> Back to Payrolls
                                </a>
                                <button class="btn btn-primary rounded-0" onclick="window.print()">
                                    <i class="fas fa-print"></i> Print Payroll
                                </button>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>

    <div id="printable-payroll">
        <div style="text-align: center;">
            <img class="mb-2" src="{{asset('vendor/logo.png')}}" alt="" width="72" height="57">
            <p><strong>VICTORY TEMPLE </strong><br>
                Area 25, Lilongwe Malawi<br></p>
        </div>
        <div class="card" style="min-height: 30em;">
            <div class="card-body px-1">
                <h2 class="mb-4">Payroll Information</h2>
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">General Details</h5>
                        <p class="card-text"><strong>Employee:</strong> {{ $payroll->labourer->name }}</p>
                        <p class="card-text"><strong>Job Position:</strong> {{ $payroll->labourer->labour->name }}</p>
                        <p class="card-text"><strong>Month:</strong> {{ $payroll->month->name }}</p>
                        <p class="card-text"><strong>Total Amount (MK):</strong> {{ number_format($payroll->total_amount, 2) }}</p>
                        <p class="card-text"><strong>Status:</strong> {{ $payroll->status }}</p>
                        <p class="card-text"><strong>Payroll Date:</strong> {{date('d F Y', strtotime($payroll->payroll_date)) }}</p>
                    </div>
                </div>

                <div class="card-body">
                    <h5 class="card-title">Payroll Items</h5>
                    <ol class="list-group list-group-flush">
                        @php
                            $c = 1;
                            $totalBalance = 0; // Initialize total balance
                            $totalTax = 0; // Initialize total tax
                        @endphp

                        @forelse ($payroll->payrollItems as $item)
                            @php
                                $amount = abs($item->amount);
                                $totalBalance += $amount;

                                // Calculate tax if the department is ADMIN
                                $tax = 0;
                                if ($payroll->labourer->department->name == 'ADMIN') {
                                    // Tax calculation logic
                                    $taxFree = 150000;
                                    if ($amount <= 350000) {
                                        // Entire amount taxed at 25%
                                        $tax += $amount * 0.25;
                                    } else {
                                        // First 350,000 at 25%
                                        $tax += 350000 * 0.25;
                                        // Remaining amount at 30%
                                        $remaining = $amount - 350000;
                                        $tax += $remaining * 0.30;
                                    }
                                    $totalTax += $tax;
                                }
                            @endphp
                            <li class="list-group-item">
                                {{ $c++ }} - {{ $item->account->name ?? $item->description }} - <strong>(MK) {{ number_format($amount, 2) }}</strong> ({{ $item->type }})
                                @if($item->amount < 0)
                                    <span class="text-danger"> (Deduction)</span>
                                @endif
                                @if($payroll->labourer->department->name=='ADMIN')
                                     <br>
                                    Total Tax : <strong>(MK) {{ number_format($tax, 2) }}</strong>
                                @endif
                            </li>
                        @empty
                            <li class="list-group-item">No payroll items found.</li>
                        @endforelse
                        @if($payroll->labourer->department->name=='ADMIN')
                            <li class="list-group-item">
                                <strong>Total Net Tax: (MK) {{ number_format($totalTax, 2) }}</strong>
                            </li>
                        @endif
                    </ol>
                </div>


                <div class="mt-4">
                    <div class="row">
                        <div class="col-md-4">
                            <p>Prepared By: {{ $payroll->creator->name }}</p>
                        </div>
                        <div class="col-md-4">
                            <p>Approved By: {{ Auth::user()->name }}</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <p>_________________________<br>
                                Employee Signature</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

            <div class="modal" id="approvePayrollModal" tabindex="-1" aria-labelledby="approvePayrollModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="approvePayrollModalLabel">Approve Payroll</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('payrolls.update', $payroll->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="_method" value="PATCH">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="reference" class="form-label">Reference</label>
                                    <input type="text" class="form-control" id="reference" name="reference" placeholder="Cheque Number" required>
                                </div>
                                <div class="mb-3">
                                    <label for="bank_id" class="form-label">Bank</label>
                                    <select class="form-select select-relation" id="bank_id" name="bank_id" required style="width: 100%;">
                                        <option value="">Select Bank</option>
                                        @foreach ($banks as $bank)
                                            <option value="{{ $bank->id }}">{{ $bank->account_name.' - '.$bank->account_number }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Approve</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
@stop
