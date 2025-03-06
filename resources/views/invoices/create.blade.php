@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fas fa-file-invoice-plus"></i> Create Invoice
        </h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('finances.index') }}">Finances</a></li>
                <li class="breadcrumb-item"><a href="{{ route('invoices.index') }}">Invoices</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create Invoice</li>
            </ol>
        </nav>
        <hr>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-6">
                    <form action="{{ route('invoices.store') }}" method="POST" autocomplete="off"> {{-- Common route --}}
                        @csrf
                        <input type="hidden" name="created_by" value="{{ auth()->user()->id }}" required>
                        <input type="hidden" name="updated_by" value="{{ auth()->user()->id }}" required>

                        <div class="mb-3">
                            <label for="party_type" class="form-label">Party Type</label>
                            <select name="party" id="party_type" class="form-control" required>
                                <option value="">Select Party Type</option>
                                <option value="creditor" {{ old('party_type') == 'creditor' ? 'selected' : '' }}>Creditor</option>
                                <option value="debtor" {{ old('party_type') == 'debtor' ? 'selected' : '' }}>Debtor</option>
                            </select>
                        </div>
                        <div class="creditor-select d-none">
                            <div class="mb-3">
                                <label for="creditor_id" class="form-label">Creditor</label>
                                <select name="creditor_id" id="creditor_id" class="select-relation form-control @error('creditor_id') is-invalid @enderror" style="width: 100%">
                                    <option value="">Select Creditor</option>
                                    @foreach ($creditors as $creditor)
                                        <option value="{{ $creditor->id }}" {{ old('creditor_id') == $creditor->id ? 'selected' : '' }}>
                                            {{ $creditor->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('creditor_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="invoice_number" class="form-label">Invoice Number</label>
                                <input type="text" name="invoice_number" id="invoice_number" class="form-control @error('invoice_number') is-invalid @enderror" value="{{ old('invoice_number') }}" placeholder="Invoice Number">
                                @error('invoice_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 debtor-select d-none">
                            <label for="member_id" class="form-label">Debtor</label>
                            <select name="member_id" id="member_id" class="select-relation form-control @error('member_id') is-invalid @enderror" style="width: 100%">
                                <option value="">Select Debtor</option>
                                @foreach ($debtors as $debtor)
                                    <option value="{{ $debtor->id }}" {{ old('member_id') == $debtor->id ? 'selected' : '' }}>
                                        {{ $debtor->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('member_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                        </div>




                        <div class="mb-3">
                            <label for="invoice_date" class="form-label">Invoice Date</label>
                            <input type="date" name="invoice_date" id="invoice_date" class="form-control @error('invoice_date') is-invalid @enderror" value="{{ old('invoice_date') }}" required>
                            @error('invoice_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount</label>
                            <input type="number" name="amount" id="amount" step="0.01" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}" required placeholder="Amount">
                            @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="account_id" class="form-label">Account</label>
                            <select name="account_id" id="account_id" class="select-relation form-control @error('account_id') is-invalid @enderror" required>
                                <option value="">Select Account</option>
                                @foreach ($accounts as $account)
                                    <option value="{{ $account->id }}" {{ old('account_id') == $account->id ? 'selected' : '' }}>
                                        {{ $account->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('account_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description (Optional)</label>
                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" placeholder="Description">{{ old('description') }}</textarea>
                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <button type="submit" class="btn btn-md btn-primary rounded-0">
                                <i class="fas fa-paper-plane"></i> Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


@stop
@section('scripts')
    <script>
        $(document).ready(function () {
            $('#party_type').on('change', function () {
                let type = $(this).val();
                if(type==='creditor'){
                    $('.debtor-select').addClass('d-none').removeClass('show');
                    $('.creditor-select').addClass('show').removeClass('d-none');
                }
                if(type==='debtor'){
                    $('.debtor-select').addClass('show').removeClass('d-none');
                    $('.creditor-select').addClass('d-none').removeClass('show');
                }
            });
        });
    </script>
@endsection
