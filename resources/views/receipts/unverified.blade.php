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
                                    <h5 class="card-title text-center mb-3">
                                        @if($bank)
                                            <strong>{{ $bank->bank_name.' - '.$bank->account_name.' -'.$bank->account_number ?? 'N/A' }}</strong> Account Reconciliations
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

                                    @forelse($transactions as $transaction)
                                    @empty
                                        <div class="alert alert-info text-center" role="alert">
                                            <i class="fa fa-info-circle me-2"></i> No transactions found for the selected filters.
                                        </div>
                                    @endforelse

                                    @if ($transactions->isNotEmpty() || (isset($openingBalance) && $openingBalance != 0)) {{-- Show table if transactions exist OR if there's an opening balance --}}
                                    <div style="overflow-x:auto;">
                                        <table class="table table-bordered table-hover table-striped">
                                            <thead>
                                            <tr>
                                                <th>NO</th>
                                                <th>DATE</th>
                                                <th>NAME/FOR</th>
                                                <th>DESCRIPTION</th>
                                                <th class="text-end">REVENUE (MK)</th> {{-- Specific header for Revenue --}}
                                                <th class="text-end">EXPENDITURE (MK)</th> {{-- Specific header for Expenditure --}}
                                                <th class="text-end">BALANCE (MK)</th> {{-- New header for running balance --}}
                                                <th>ACCOUNT</th>
                                                <th>CHEQUE</th>
                                                <th class="text-center">ACTION</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php
                                                // Initialize running balance
                                                $runningBalance = $openingBalance;
                                                $counter = 0; // For overall numbering including opening balance
                                            @endphp

                                            {{-- Display Opening Balance as the first row --}}
                                            <tr>
                                                <td class="text-nowrap">{{ ++$counter }}</td>
                                                <td class="text-nowrap">N/A</td> {{-- Date for opening balance --}}
                                                <td class="text-nowrap">Opening Balance</td>
                                                <td>Balance from previous transactions</td>
                                                <td class="text-end text-nowrap">
                                                    @if($openingBalance > 0)
                                                        {{ number_format($openingBalance, 2) }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td class="text-end text-nowrap">
                                                    @if($openingBalance < 0)
                                                        ({{ number_format(abs($openingBalance), 2) }})
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td class="text-end fw-bold text-nowrap">{{ number_format($runningBalance, 2) }}</td>
                                                <td class="text-nowrap">N/A</td>
                                                <td class="text-nowrap">-</td> {{-- No cheque/reference for opening balance --}}
                                                <td class="text-center text-nowrap">-</td> {{-- No action for opening balance --}}
                                            </tr>

                                            @foreach($transactions as $transaction)
                                                @php
                                                    $displayAmount = $transaction->amount;

                                                    if ($transaction->type == 1) {
                                                        $runningBalance += $displayAmount;
                                                        }
                                                    elseif($transaction->name=='Loan Monthly Repayment'){
                                                            $runningBalance += $displayAmount;
                                                        }
                                                    else {
                                                        $runningBalance -= $displayAmount;
                                                    }
                                                    $counter++; // Increment for the current transaction
                                                @endphp
                                                <tr>
                                                    <td>{{ $counter }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($transaction->t_date)->format('d F Y') }}</td>
                                                    <td>{{ ucwords(substr($transaction->name, 0, 20)) }}</td>
                                                    <td>{{ $transaction->specification ?? 'N/A' }}</td>
                                                    <td class="text-end">
                                                        @if($isRevenue)
                                                            @if($transaction->name=='Loan Monthly Repayment')
                                                                {{ number_format(abs($displayAmount), 2) }}
                                                            @else
                                                                {{ number_format($displayAmount, 2) }}
                                                            @endif

                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td class="text-end">
                                                        @if(!$isRevenue)
                                                            ({{ number_format($displayAmount, 2) }})
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td class="text-end fw-bold">{{ number_format($runningBalance, 2) }}</td>
                                                    <td>{{ ucwords($transaction->account->name ?? 'N/A') }}</td>
                                                    <td>{{ ucwords($transaction->reference ?? 'N/A') }}</td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-info btn-sm rounded-0 text-white"
                                                                data-bs-toggle="modal" data-bs-target="#editTransactionModal"
                                                                data-transaction-id="{{ $transaction->id }}"
                                                                data-transaction-bank-id="{{ $transaction->bank_id }}"
                                                                data-transaction-date="{{ $transaction->t_date }}"
                                                                data-transaction-name="{{ $transaction->name }}"
                                                                data-transaction-description="{{ $transaction->specification ?? '' }}"
                                                                data-transaction-account-id="{{ $transaction->account_id }}"  {{-- NEW --}}
                                                                data-transaction-reference="{{ $transaction->reference ?? '' }}"> {{-- NEW --}}
                                                            <i class="fa fa-edit"></i> Edit
                                                        </button>
                                                        {{-- Existing Delete Button --}}
                                                        <button type="button" class="btn btn-danger btn-sm rounded-0"
                                                                data-bs-toggle="modal" data-bs-target="#deleteTransactionModal"
                                                                data-transaction-id="{{ $transaction->id }}"
                                                                data-transaction-name="{{ $transaction->name }}"
                                                                data-transaction-amount="{{ number_format($transaction->amount, 2) }}">
                                                            <i class="fa fa-trash"></i> Delete
                                                        </button>

                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th colspan="4" class="text-end">Closing Balance:</th>
                                                <th colspan="2" class="text-end"></th>
                                                <th class="text-end fw-bold text-nowrap">{{ number_format($runningBalance, 2) }}</th>
                                                <th colspan="3"></th> {{-- Adjusted colspan for the remaining 3 columns (Account, Cheque/Reference, Action) --}}
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>

                                    {{-- Pagination Links --}}
                                    <div class="d-flex justify-content-center mt-3">
                                        {{ $transactions->appends(request()->input())->links() }}
                                    </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Universal Delete Modal (Outside the loop, updated with JS for dynamic content) --}}
                            <div class="modal" id="deleteTransactionModal" tabindex="-1" aria-labelledby="deleteTransactionModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteTransactionModalLabel">Confirm Deletion</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form id="deleteTransactionForm" action="" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="modal-body">
                                                <p>Are you sure you want to delete this transaction for <strong id="transactionName"></strong> amounting to: <strong>MK<span id="transactionAmount"></span></strong>?</p>
                                                <div class="mb-3">
                                                    <label for="delete_notes" class="form-label">Reason for Deletion</label>
                                                    <textarea class="form-control" id="delete_notes" name="delete_notes" rows="3" required></textarea>
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
                            <!-- Edit Transaction Modal -->
                            <div class="modal" id="editTransactionModal" tabindex="-1" aria-labelledby="editTransactionModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editTransactionModalLabel">Edit Transaction</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form id="editTransactionForm" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <input type="hidden" name="transaction_id" id="editTransactionId">

                                                <div class="mb-3">
                                                    <label for="editBankId" class="form-label">Bank Account</label>
                                                    <select name="bank_id" id="editBankId" class="form-select select-relation" style="width: 100%" required>
                                                        {{-- This option will be dynamically set by JS --}}
                                                        <option value="{{@$transaction->bank->id}}"
                                                            {{old('bank_id')===@$transaction->bank->id ? 'selected' : ''}}>{{@$transaction->bank->account_name.' '.@$transaction->bank->account_number}}</option>                                                        @foreach($banks as $bankOption)
                                                            <option value="{{ $bankOption->id }}">
                                                                {{ $bankOption->account_name.' - '.$bankOption->account_number }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <span class="invalid-feedback d-block" id="editBankIdError"></span>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="editAccountId" class="form-label">Account</label>
                                                    <select name="account_id" id="editAccountId" class="form-select select-relation" style="width: 100%" required>
                                                        {{-- This option will be dynamically set by JS --}}
                                                        <option value="{{@$transaction->account_id}}">{{@$transaction->account->name}}</option>
                                                        @foreach($accounts as $accountOption)
                                                            <option value="{{ $accountOption->id }}">
                                                                {{ $accountOption->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <span class="invalid-feedback d-block" id="editAccountIdError"></span>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="editTransactionDate" class="form-label">Date</label>
                                                    <input type="date" name="t_date" id="editTransactionDate" class="form-control" required>
                                                    <span class="invalid-feedback d-block" id="editTransactionDateError"></span>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="editTransactionName" class="form-label">Name/For</label>
                                                    <input type="text" name="name" id="editTransactionName" class="form-control" required>
                                                    <span class="invalid-feedback d-block" id="editTransactionNameError"></span>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="editTransactionDescription" class="form-label">Description (Optional)</label>
                                                    <textarea name="description" id="editTransactionDescription" class="form-control" rows="3"></textarea>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="editTransactionReference" class="form-label">Cheque / Reference (Optional)</label>
                                                    <input type="text" name="reference" id="editTransactionReference" class="form-control">
                                                    <span class="invalid-feedback d-block" id="editTransactionReferenceError"></span>
                                                </div>

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary rounded-0" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary rounded-0">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<script src="{{asset('vendor/simple-datatable/simple-datatable.js')}}"></script>
@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteTransactionModal = document.getElementById('deleteTransactionModal');
            deleteTransactionModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const transactionId = button.getAttribute('data-transaction-id');
                const transactionName = button.getAttribute('data-transaction-name');
                const transactionAmount = button.getAttribute('data-transaction-amount');

                const modalTitle = deleteTransactionModal.querySelector('.modal-title');
                const transactionNameSpan = deleteTransactionModal.querySelector('#transactionName');
                const transactionAmountSpan = deleteTransactionModal.querySelector('#transactionAmount');
                const deleteForm = deleteTransactionModal.querySelector('#deleteTransactionForm');

                modalTitle.textContent = 'Delete Transaction (ID: ' + transactionId + ')';
                transactionNameSpan.textContent = transactionName;
                transactionAmountSpan.textContent = transactionAmount;

                deleteForm.action = `/payments/${transactionId}`; // Assuming your delete route is like /payments/{id}
            });

            deleteTransactionModal.addEventListener('hidden.bs.modal', function () {
                const deleteNotes = deleteTransactionModal.querySelector('#delete_notes');
                if (deleteNotes) {
                    deleteNotes.value = '';
                }
            });
            // --- NEW: Edit Transaction Modal Logic ---
            const editTransactionModal = document.getElementById('editTransactionModal');
            editTransactionModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget; // Button that triggered the modal

                // Extract data from data-bs-* attributes
                const transactionId = button.getAttribute('data-transaction-id');
                const transactionBankId = button.getAttribute('data-transaction-bank-id');
                const transactionDate = button.getAttribute('data-transaction-date');
                const transactionName = button.getAttribute('data-transaction-name');
                const transactionDescription = button.getAttribute('data-transaction-description');
                const transactionAccountId = button.getAttribute('data-transaction-account-id');
                const transactionReference = button.getAttribute('data-transaction-reference');

                // Get modal elements
                const editForm = editTransactionModal.querySelector('#editTransactionForm');
                const idInput = editTransactionModal.querySelector('#editTransactionId');
                const bankSelect = editTransactionModal.querySelector('#editBankId');
                const accountSelect = editTransactionModal.querySelector('#editAccountId');
                const dateInput = editTransactionModal.querySelector('#editTransactionDate');
                const nameInput = editTransactionModal.querySelector('#editTransactionName');
                const descriptionInput = editTransactionModal.querySelector('#editTransactionDescription');
                const referenceInput = editTransactionModal.querySelector('#editTransactionReference');
                const modalTitle = editTransactionModal.querySelector('.modal-title');

                // Populate basic fields
                idInput.value = transactionId;
                dateInput.value = transactionDate;
                nameInput.value = transactionName;
                descriptionInput.value = transactionDescription;
                referenceInput.value = transactionReference;
                modalTitle.textContent = `Edit Transaction (ID: ${transactionId})`;

                // --- Handle Bank Select Pre-selection ---
                // Find the option by its value (ID).
                let bankOptionFound = false;
                for (let i = 0; i < bankSelect.options.length; i++) {
                    if (bankSelect.options[i].value == transactionBankId) {
                        bankSelect.value = transactionBankId;
                        bankOptionFound = true;
                        break;
                    }
                }
                // If the specific bank ID isn't in the pre-loaded list (e.g., it's deactivated)
                // we prepend it so the user still sees the original.
                if (!bankOptionFound && transactionBankId) {
                    // Fetch the text for the original bank from the button's data attribute
                    const originalBankName = button.getAttribute('data-transaction-bank-name'); // You'll need to add this data attribute to the button
                    const newBankOption = document.createElement('option');
                    newBankOption.value = transactionBankId;
                    newBankOption.textContent = originalBankName || 'Unknown Bank (ID: ' + transactionBankId + ')'; // Fallback text
                    bankSelect.prepend(newBankOption); // Add it to the top
                    bankSelect.value = transactionBankId; // Select it
                } else if (!transactionBankId) {
                    bankSelect.value = ''; // Ensure placeholder is selected if no bank ID
                }


                // --- Handle Account Select Pre-selection ---
                // Find the option by its value (ID).
                let accountOptionFound = false;
                for (let i = 0; i < accountSelect.options.length; i++) {
                    if (accountSelect.options[i].value == transactionAccountId) {
                        accountSelect.value = transactionAccountId;
                        accountOptionFound = true;
                        break;
                    }
                }
                // If the specific account ID isn't in the pre-loaded list
                if (!accountOptionFound && transactionAccountId) {
                    // Fetch the text for the original account from the button's data attribute
                    const originalAccountName = button.getAttribute('data-transaction-account-name'); // You'll need to add this data attribute to the button
                    const newAccountOption = document.createElement('option');
                    newAccountOption.value = transactionAccountId;
                    newAccountOption.textContent = originalAccountName || 'Unknown Account (ID: ' + transactionAccountId + ')'; // Fallback text
                    accountSelect.prepend(newAccountOption); // Add it to the top
                    accountSelect.value = transactionAccountId; // Select it
                } else if (!transactionAccountId) {
                    accountSelect.value = ''; // Ensure placeholder is selected if no account ID
                }

                // Set the form action URL dynamically
                editForm.action = `/payments/${transactionId}`; // Adjust route if needed

                // Clear previous validation errors if any
                clearEditModalErrors();

                // If using Select2 or similar, trigger change after setting value
                // $(bankSelect).trigger('change.select2');
                // $(accountSelect).trigger('change.select2');
            });

            // Clear form fields and added options when the modal is closed
            editTransactionModal.addEventListener('hidden.bs.modal', function () {
                const editForm = editTransactionModal.querySelector('#editTransactionForm');
                editForm.reset(); // Resets all form fields
                clearEditModalErrors(); // Clear any validation feedback

                // Remove any dynamically added options from previous openings
                const bankSelect = editTransactionModal.querySelector('#editBankId');
                const accountSelect = editTransactionModal.querySelector('#editAccountId');

                // Remove options that are NOT part of the initial list ($banks, $accounts)
                // A simple way is to check if its value matches any of the *original* $bank/$account IDs.
                // For simplicity, we can remove any dynamically added options if they are not system-defined.
                // A more robust solution might mark initial options with a class.
                // For now, let's just reset the value to the placeholder.
                bankSelect.value = '';
                accountSelect.value = '';
            });

            // Function to clear validation errors in the edit modal
            function clearEditModalErrors() {
                document.querySelectorAll('#editTransactionForm .is-invalid').forEach(element => {
                    element.classList.remove('is-invalid');
                });
                document.querySelectorAll('#editTransactionForm .invalid-feedback').forEach(element => {
                    element.textContent = '';
                });
            }
        });
    </script>
@stop
