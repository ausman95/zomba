@extends('layouts.app')
@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-cash-register"></i> Bank Reconciliations
        </h4>
        <p>
            Manage Bank Transactions
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('finances.index') }}">Finances</a></li>
                <li class="breadcrumb-item active" aria-current="page">Bank Reconciliations</li>
            </ol>
        </nav>
        <hr>
        <div class="mt-3">
            <a href="{{ route('payments.index') }}" class="btn btn-primary btn-md rounded-0">
                <i class="fa fa-wallet"></i> Payments
            </a>

            <a href="{{ route('receipts.index') }}" class="btn btn-primary btn-md rounded-0">
                <i class="fa fa-receipt"></i> Receipts
            </a>
        <div class="mt-3">

            <div class="card container-fluid" style="min-height: 30em;">
                <div class="row g-3"> {{-- Use g-3 for consistent gutter spacing --}}
                    <div class="col-sm-12 col-md-3 col-lg-2 p-3 border-end"> {{-- Adjusted column for filters --}}
                        <h6 class="mb-3">Filter Transactions</h6>
                        {{-- Changed to GET for filters --}}
                        <form action="{{ route('bank-reconciliations') }}" method="GET" id="bankReconciliationFilterForm">
                            <div class="mb-3"> {{-- Use mb-3 for consistent spacing --}}
                                <label for="bank_id" class="form-label">Bank Account</label>
                                <select name="bank_id" id="bank_id" class="form-select select-relation @error('bank_id') is-invalid @enderror" style="width: 100%">
                                    <option value="">Select Bank Account</option> {{-- More specific placeholder --}}
                                    @foreach($banks as $bankOption) {{-- Renamed to avoid conflict with $bank variable used for caption --}}
                                    <option value="{{ $bankOption->id }}" {{ old('bank_id', request('bank_id')) == $bankOption->id ? 'selected' : '' }}>
                                        {{ $bankOption->account_name.' - '.$bankOption->account_number }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('bank_id')
                                <span class="invalid-feedback d-block">{{ $message }}</span> {{-- Added d-block for proper display --}}
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', request('start_date')) }}">
                                @error('start_date')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', request('end_date')) }}">
                                @error('end_date')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group mt-3">
                                <button class="btn btn-primary rounded-0" type="submit">
                                    <i class="fa fa-filter"></i> Apply Filters
                                </button>
                                {{-- Clear Filters Button - Show only if filters are applied --}}
                                @if(request('bank_id') || request('start_date') || request('end_date'))
                                    <button type="button" class="btn btn-secondary rounded-0" id="clearFiltersBtn">
                                        Clear Filters
                                    </button>
                                @endif
                            </div>
                        </form>
                    </div>
                    <div class="col-sm-12 col-md-9 col-lg-10 p-3"> {{-- Adjusted column for table --}}
                        <div class="card">
                            <div class="card-body px-1">
                                <h5 class="card-title text-center">
                                    @if($bank) {{-- Check if $bank object exists before accessing its properties --}}
                                    {{ $bank->bank_name ?? 'N/A' }} Account Reconciliations
                                    @else
                                        All Bank Account Reconciliations
                                    @endif

                                    @if(request('start_date') && request('end_date'))
                                        from {{ \Carbon\Carbon::parse(request('start_date'))->format('d F Y') }}
                                        to {{ \Carbon\Carbon::parse(request('end_date'))->format('d F Y') }}
                                    @elseif(request('start_date'))
                                        from {{ \Carbon\Carbon::parse(request('start_date'))->format('d F Y') }} onwards
                                    @elseif(request('end_date'))
                                        up to {{ \Carbon\Carbon::parse(request('end_date'))->format('d F Y') }}
                                    @endif
                                </h5>

                                {{-- Move the table structure outside the @forelse or ensure proper handling --}}
                                {{-- The @forelse/@foreach logic below was flawed and would duplicate the table --}}

                                @forelse($payments as $payment)
                                    {{-- This block will only run if $payments is NOT empty --}}
                                    {{-- The table structure should wrap the @foreach, not be inside it --}}

                                    {{-- Check if this is the first item in the paginated collection to draw the table once --}}
                                    @if ($loop->first)
                                        <div style="overflow-x:auto;">
                                            <table class="table table-bordered table-hover table-striped">
                                                <thead>
                                                <tr>
                                                    <th>NO</th>
                                                    <th>DATE</th>
                                                    <th>FOR</th>
                                                    <th>DESCRIPTION</th>
                                                    <th>AMOUNT (MK)</th>
                                                    <th>ACCOUNT</th>
                                                    <th>BANK</th>
                                                    <th>TYPE</th>
                                                    <th>ACTION</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @endif

                                                <tr>
                                                    <td>{{ $loop->iteration + $payments->firstItem() - 1 }}</td> {{-- Corrected for pagination --}}
                                                    <td>{{ \Carbon\Carbon::parse($payment->t_date)->format('d F Y') }}</td>
                                                    <td>{{ ucwords(substr($payment->name,0,20)) }}</td>
                                                    <td>{{ $payment->specification ?? 'N/A' }}</td>
                                                    <th>{{ number_format($payment->amount, 2) }}</th>
                                                    <td>{{ ucwords($payment->account->name ?? 'N/A') }}</td>
                                                    <td>
                                                        @if(!empty($payment->bank)) {{-- Check if bank relationship exists --}}
                                                        {{ $payment->bank->bank_name.' - '.$payment->bank->account_number.' - '.$payment->bank->account_name }}
                                                        @else
                                                            N/A
                                                        @endif
                                                    </td>
                                                    {{-- Assuming account type 2 is expense and others are revenue --}}
                                                    <td>{{ ucwords(($payment->account->type ?? null) == 2 ? "Expense" : "Revenue") }}</td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-danger btn-sm rounded-0" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $payment->id }}">
                                                            <i class="fa fa-trash"></i> Delete
                                                        </button>
                                                    </td>
                                                </tr>

                                                {{-- Delete Modal for each payment --}}
                                                <div class="modal" id="deleteModal{{ $payment->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $payment->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="deleteModalLabel{{ $payment->id }}">Delete Transaction</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form action="{{ route('payments.destroy', $payment->id) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <div class="modal-body">
                                                                    <p>Are you sure you want to delete this transaction for ( {{ $payment->name.' Amounting to : MK'. number_format($payment->amount,2) }})?</p>
                                                                    <div class="mb-3">
                                                                        <label for="delete_notes{{ $payment->id }}" class="form-label">Reason for Deletion</label>
                                                                        <textarea class="form-control" id="delete_notes{{ $payment->id }}" name="delete_notes" rows="3" required></textarea> {{-- Added required --}}
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary rounded-0" data-bs-dismiss="modal">Cancel</button>
                                                                    <button type="submit" class="btn btn-danger rounded-0">Delete Transaction</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>

                                                @if ($loop->last)
                                                    {{-- Table Footer for total sum --}}
                                                    <tfoot>
                                                    <tr>
                                                        <th colspan="4" class="text-end">Total Sum:</th>
                                                        <th>{{ number_format($payments->sum('amount'), 2) }}</th>
                                                        <th colspan="4"></th> {{-- Span remaining columns --}}
                                                    </tr>
                                                    </tfoot>
                                                    </tbody>
                                            </table>
                                        </div>
                                        {{-- Pagination Links --}}
                                        <div class="d-flex justify-content-center mt-3">
                                            {{ $payments->appends(request()->input())->links() }} {{-- Keep filters when paginating --}}
                                        </div>
                                    @endif

                                @empty
                                    {{-- This block runs if $payments is empty --}}
                                    <div class="alert alert-info" role="alert">
                                        <i class="fa fa-info-circle"></i> No transactions found for the selected filters.
                                    </div>
                                @endforelse
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
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize select2 for filter dropdowns
            $('.select-relation').select2({
                placeholder: 'Select an option',
                allowClear: true // Allows clearing the selected option
            });

            // Clear Filters button functionality
            const clearFiltersBtn = document.getElementById('clearFiltersBtn');
            if (clearFiltersBtn) {
                clearFiltersBtn.addEventListener('click', function() {
                    // Reset all form fields
                    document.getElementById('bank_id').value = "";
                    document.getElementById('start_date').value = "";
                    document.getElementById('end_date').value = "";

                    // Trigger select2 update if it's initialized on bank_id
                    $('#bank_id').val('').trigger('change');

                    // Submit the form to apply cleared filters
                    document.getElementById('bankReconciliationFilterForm').submit();
                });
            }

            // Fix for Bootstrap 5 Modals (replace data-dismiss with data-bs-dismiss if using BS5)
            // Ensure you are using Bootstrap 5's modal JS for data-bs-toggle and data-bs-dismiss
            // If you are still on Bootstrap 4, then data-dismiss is correct.
            // Assuming Bootstrap 5 from your classes (btn-close)
        });
    </script>
@endsection
