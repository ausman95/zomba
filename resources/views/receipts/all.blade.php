@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-cash-register"></i> All Transactions
        </h4>
        <p>
            Manage All Transactions
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('finances.index') }}">Finances</a></li>
                <li class="breadcrumb-item active" aria-current="page">All Transactions</li>
            </ol>
        </nav>
        <hr>
        <div class="mt-3">
            <div class="card container-fluid" style="min-height: 30em;">
                <div class="row">
                    <div class="col-sm-12 mb-2 col-md-2 col-lg-2">
                        <hr>
                        <form action="{{ route('all.produce') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="Bank">Banks</label>
                                <select name="bank_id" class="form-select select-relation @error('bank_id') is-invalid @enderror" style="width: 100%">
                                    <option value="">Select Bank</option>
                                    @foreach($banks as $bank)
                                        <option value="{{ $bank->id }}" {{ old('bank_id') == $bank->id ? 'selected' : '' }}>
                                            {{ $bank->account_name.' - '.$bank->account_number }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('bank_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="Month">Months</label>
                                <select name="month_id" class="form-select select-relation @error('month_id') is-invalid @enderror" style="width: 100%">
                                    <option value="">Select Month</option>
                                    @foreach($months as $month)
                                        <option value="{{ $month->id }}" {{ old('month_id') == $month->id ? 'selected' : '' }}>
                                            {{ $month->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('month_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- New filters for Start Date and End Date -->
                            <div class="form-group">
                                <label for="Start Date">Start Date</label>
                                <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}">
                                @error('start_date')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="End Date">End Date</label>
                                <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}">
                                @error('end_date')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary rounded-0" type="submit">
                                    Apply Filters &rarr;
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="col-sm-12 mb-2 col-md-10 col-lg-10">
                        <br>
                        <div class="card container-fluid" style="min-height: 30em;">
                            <div class="card-body px-1">
                                @if(!request()->has('bank_id') && !request()->has('month_id') && !request()->has('start_date') && !request()->has('end_date'))
                                    <!-- Display message if no filters applied -->
                                    <div class="text-center">
                                        <div class="alert alert-danger">
                                            <i class="fa fa-info-circle">
                                            </i> Please apply filters and click 'Apply Filters' to view the data.
                                        </div>
                                    </div>

                                @elseif($payments->isEmpty())
                                    <!-- Display message if no transactions available -->
                                    <i class="fa fa-info-circle"></i> There are no Transactions for the applied filters!
                                @else
                                    <div style="overflow-x:auto;">
                                        <table class="table table-bordered table-hover table-striped">
                                            {{-- Dynamic Table Caption --}}
                                            <caption style="caption-side: top; text-align: center; font-weight: bold; font-size: 1.2em;">
                                                @php
                                                    $titlePart = 'All Transactions';
                                                    $bankName = $selectedBank->account_name ?? null; // Uses selectedBank object from controller
                                                    $datePeriod = '';

                                                    if ($bankName) {
                                                        $titlePart = $bankName . ' Transactions';
                                                    }

                                                    if ($currentMonth) {
                                                        $datePeriod = ' for ' . $currentMonth;
                                                    } elseif ($startDate && $endDate) {
                                                        $datePeriod = ' from ' . \Carbon\Carbon::parse($startDate)->format('d F Y') . ' to ' . \Carbon\Carbon::parse($endDate)->format('d F Y');
                                                    } elseif ($startDate) {
                                                        $datePeriod = ' from ' . \Carbon\Carbon::parse($startDate)->format('d F Y') . ' onwards';
                                                    } elseif ($endDate) {
                                                        $datePeriod = ' up to ' . \Carbon\Carbon::parse($endDate)->format('d F Y');
                                                    }

                                                    echo $titlePart . $datePeriod;
                                                @endphp
                                            </caption>
                                            <thead>
                                            <tr>
                                                <th>NO</th>
                                                <th>DATE</th>
                                                <th>FOR</th> {{-- This column now displays the 'name' field (e.g., recipient) --}}
                                                <th>AMOUNT (MK)</th>
                                                <th>ACCOUNT</th>
                                                <th>BANK</th>
                                                <th>REF #</th> {{-- This column displays the 'reference' field (e.g., cheque number) --}}
                                                <th>TYPE</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php
                                                $totalPeriodSum = 0; // Sum of transactions within the filtered period
                                            @endphp

                                            @if($openingBalance != 0)
                                                <tr>
                                                    <td>1</td>
                                                    <td>
                                                        @if($startDate)
                                                            {{ \Carbon\Carbon::parse($startDate)->format('d F Y') }}
                                                        @else
                                                            N/A {{-- Fallback if startDate is unexpectedly not set --}}
                                                        @endif
                                                        (Opening Balance)
                                                    </td>
                                                    <td>N/A</td>
                                                    <td>{{ number_format($openingBalance, 2) }}</td>
                                                    <td>N/A</td>
                                                    <td>N/A</td>
                                                    <td>N/A</td>
                                                    <td>N/A</td>
                                                </tr>
                                            @endif

                                            @foreach($payments as $payment)
                                                @php
                                                    // Calculate the effective amount for summing based on type and sign
                                                    $effectiveAmount = 0;
                                                    if (($payment->type == 1 && $payment->amount >= 0) || ($payment->type == 2 && $payment->amount < 0)) {
                                                        // This is an income-like transaction (Revenue with positive amount, or Reversed Expense with negative amount)
                                                        $effectiveAmount = abs($payment->amount);
                                                    } else {
                                                        // This is an expense-like transaction (Expense with positive amount, or Reversed Revenue with negative amount)
                                                        $effectiveAmount = -abs($payment->amount);
                                                    }
                                                    $totalPeriodSum += $effectiveAmount;
                                                @endphp
                                                <tr>
                                                    {{-- Dynamic 'NO' column: Adjusts for presence of opening balance row --}}
                                                    <td>{{ ($loop->index + ($openingBalance != 0 ? 2 : 1)) + (($payments->currentPage() - 1) * $payments->perPage()) }}</td>
                                                    <td>{{ date('d F Y', strtotime($payment->t_date)) }}</td>
                                                    <td>
                                                        @if($payment->amount < 0)
                                                            Transaction Reverse: {{ $payment->name }}
                                                        @else
                                                            {{ $payment->name }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{-- Display logic for amount: positive for income-like, (negative) for expense-like --}}
                                                        @if(($payment->type == 1 && $payment->amount > 0) || ($payment->type == 2 && $payment->amount < 0))
                                                            {{ number_format(abs($payment->amount), 2) }}
                                                        @else {{-- ($payment->type == 1 && $payment->amount < 0) || ($payment->type == 2 && $payment->amount > 0) --}}
                                                        ({{ number_format(abs($payment->amount), 2) }})
                                                        @endif
                                                    </td>
                                                    <td>{{ ucwords($payment->account->name) }}</td>
                                                    <td>
                                                        @php
                                                            // Assuming your Bank model is named 'Bank' (singular)
                                                            $bank = \App\Models\Banks::find($payment->bank_id);
                                                        @endphp
                                                        {{ $bank->account_name . ' - ' . $bank->account_number }}
                                                    </td>
                                                    <td>
                                                        {{ $payment->reference }}
                                                    </td>
                                                    <td>
                                                        @if($payment->type == 1)
                                                            Revenue
                                                        @else
                                                            Expense
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th colspan="3" class="text-right">Total for Current Period:</th>
                                                <th>{{ number_format($totalPeriodSum, 2) }}</th>
                                                <th colspan="4"></th> {{-- Span remaining columns --}}
                                            </tr>
                                            <tr>
                                                <th colspan="3" class="text-right">Overall Closing Balance:</th>
                                                <th>{{ number_format($openingBalance + $totalPeriodSum, 2) }}</th>
                                                <th colspan="4"></th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <div class="float-end">
                                        {{ $payments->appends(request()->query())->links() }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
