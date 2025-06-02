@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
    {{-- You might want to add specific styles here if needed for this page --}}
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-cash-register"></i> Church Receipts
        </h4>
        <p>
            Manage Church Receipts
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('finances.index')}}">Finances</a></li>
                <li class="breadcrumb-item active" aria-current="page">Church Receipts</li>
            </ol>
        </nav>
        <hr>
        <div class="mt-3">
            <a href="{{route('receipts.create')}}" class="btn btn-primary btn-md rounded-0 mb-3"> {{-- Added mb-3 for spacing below button --}}
                <i class="fa fa-plus-circle"></i> New Receipt
            </a>
            <a href="{{ route('bank-reconciliations') }}" class="btn btn-primary btn-md rounded-0 mb-3">
                <i class="fa fa-money-check-alt"></i> Bank Reconciliations
            </a>
            <div class="card container-fluid" style="min-height: 30em;">
                <div class="row">
                    <div class="col-sm-12 mb-2 col-md-3 col-lg-2"> {{-- Adjusted col-md to 3, col-lg to 2 for filters --}}
                        <hr>
                        <form action="{{ route('receipt.produce') }}" method="POST" id="receiptsFilterForm">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="bank_id">Banks</label>
                                <select name="bank_id" class="form-select select-relation @error('bank_id') is-invalid @enderror" style="width: 100%">
                                    <option value="">Select Bank</option>
                                    @foreach($banks as $bank)
                                        <option value="{{ $bank->id }}" {{ ((int)$selectedBankId === $bank->id) ? 'selected' : '' }}>
                                            {{ $bank->account_name.' - '.$bank->account_number }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('bank_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
{{--                            <div class="form-group mb-3">--}}
{{--                                <label for="month_id">Months</label>--}}
{{--                                <select name="month_id" class="form-select select-relation @error('month_id') is-invalid @enderror" style="width: 100%">--}}
{{--                                    <option value="">Select Month</option>--}}
{{--                                    @foreach($months as $month)--}}
{{--                                        <option value="{{ $month->id }}" {{ ((int)$selectedMonthId === $month->id) ? 'selected' : '' }}>--}}
{{--                                            {{ $month->name }}--}}
{{--                                        </option>--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}
{{--                                @error('month_id')--}}
{{--                                <span class="invalid-feedback">{{ $message }}</span>--}}
{{--                                @enderror--}}
{{--                            </div>--}}
                            <div class="form-group mb-3">
                                <label for="start_date">Start Date</label>
                                <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ $startDate ?? '' }}">
                                @error('start_date')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="end_date">End Date</label>
                                <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ $endDate ?? '' }}">
                                @error('end_date')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group mt-3">
                                <button class="btn btn-primary rounded-0" type="submit">
                                    Apply Date Filter &rarr;
                                </button>
                                @if(request('start_date') || request('end_date') || request('month_id'))
                                    <a href="{{ route('receipts.index') }}" class="btn btn-default rounded-0 mt-2">Clear Filters</a>
                                @endif
                            </div>

                        </form>
                    </div>
                    <div class="col-sm-12 mb-2 col-md-9 col-lg-10"> {{-- Adjusted col-md/lg to complement filter column --}}
                        <br>
                        <div class="card container-fluid" style="min-height: 30em;">
                            <div class="card-body px-1">
                                @if($payments->count() === 0)
                                    <i class="fa fa-info-circle"></i> There are no Receipts matching your criteria!
                                @else
                                    <div style="overflow-x:auto;">
                                        <table class="table table-bordered table-hover table-striped">
                                            {{-- Dynamic Table Caption for Receipts --}}
                                            <caption style="caption-side: top; text-align: center; font-weight: bold; font-size: 1.2em;">
                                                @php
                                                    $receiptTitlePart = 'Verified Receipts';
                                                    $receiptPeriod = '';

                                                    if (!empty($selectedBank)) { // Check if $selectedBank is not null/empty
                                                        $receiptTitlePart = $selectedBank->account_name . ' Verified Receipts';
                                                    }

                                                    if (!empty($currentMonth)) { // Check if $currentMonth is not null/empty
                                                        $receiptPeriod = ' for ' . $currentMonth;
                                                    } elseif (!empty($startDate) && !empty($endDate)) {
                                                        $receiptPeriod = ' from ' . \Carbon\Carbon::parse($startDate)->format('d F Y') . ' to ' . \Carbon\Carbon::parse($endDate)->format('d F Y');
                                                    } elseif (!empty($startDate)) {
                                                        $receiptPeriod = ' from ' . \Carbon\Carbon::parse($startDate)->format('d F Y') . ' onwards';
                                                    } elseif (!empty($endDate)) {
                                                        $receiptPeriod = ' up to ' . \Carbon\Carbon::parse($endDate)->format('d F Y');
                                                    }

                                                    echo $receiptTitlePart . $receiptPeriod;
                                                @endphp
                                            </caption>
                                            <thead>
                                            <tr>
                                                <th>NO</th>
                                                <th>DATE</th>
                                                <th>FOR</th>
                                                <th>AMOUNT (MK)</th>
                                                <th>ACCOUNT</th>
                                                <th>BANK</th>
                                                <th>-</th>
                                                <th>ACTION</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php $c = $payments->firstItem() @endphp {{-- Start counter from the first item of the current page --}}
                                            @foreach($payments as $payment)
                                                <tr>
                                                    <td>{{ $c++ }}</td>
                                                    <td>{{ date('d F Y', strtotime($payment->t_date)) }}</td>
                                                    <td>{{ ucwords(substr($payment->name,0,20)) }}</td>
                                                    <th>{{ number_format($payment->amount, 2) }}</th>
                                                    <td>{{ ucwords($payment->account->name) }}</td>
                                                    <td>
                                                        @if(!empty($payment->bank->account_name)) {{-- Changed @ to !empty() for robustness --}}
                                                        {{ $payment->bank->bank_name.' - '.$payment->bank->account_name }}
                                                        @else
                                                            OPENING TRANSACTION {{-- This might need re-evaluation based on your data --}}
                                                        @endif
                                                    </td>
                                                    <td>{{ ucwords(substr($payment->specification,0,20)) }}</td>
                                                    <td class="pt-1">
                                                        <a href="{{ route('payments.show', $payment->id) . "?verified=0" }}"
                                                           class="btn btn-primary btn-md rounded-0">
                                                            <i class="fa fa-list-ol"></i> Details
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th colspan="3" class="text-end">Total Sum:</th>
                                                <th>{{ number_format($payments->sum('amount'), 2) }}</th> {{-- Sums amounts on the current page --}}
                                                <th colspan="5"></th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    {{-- Pagination Links --}}
                                    <div class="d-flex justify-content-center mt-3">
                                        {{ $payments->links() }}
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
        // Ensure the script runs after the DOM is fully loaded
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize Simple-DataTables if you intend to use it.
            // If the table is dynamically generated/filtered by Laravel, Simple-DataTables might interfere with pagination.
            // Consider if you really need Simple-DataTables for a table that is already being filtered/paginated server-side.
            // const dataTable = new simpleDatatables.DataTable("#paymentsTable"); // Example usage, replace #paymentsTable with your table ID

            // Script for Clear Filters button
            const clearFiltersBtn = document.getElementById('clearFiltersBtn');
            if (clearFiltersBtn) {
                clearFiltersBtn.addEventListener('click', function() {
                    // Reset all select elements to their first option (value="")
                    document.querySelector('select[name="bank_id"]').value = "";
                    document.querySelector('select[name="month_id"]').value = "";

                    // Clear date input fields
                    document.querySelector('input[name="start_date"]').value = "";
                    document.querySelector('input[name="end_date"]').value = "";

                    // Submit the form to apply cleared filters
                    document.getElementById('receiptsFilterForm').submit();
                });
            }

            // Initialize select2 for filter dropdowns
            $('.select-relation').select2({
                placeholder: 'Select an option',
                allowClear: true // Allows clearing the selected option
            });
        });
    </script>
@stop
