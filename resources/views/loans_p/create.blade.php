@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-hand-holding-usd"></i> Employee Loan Application
        </h4>
        <p>
            Apply for a New Employee Loan
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('human-resources.index') }}">Human Resources</a></li>
                <li class="breadcrumb-item"><a href="{{route('loans.index')}}">Employee Loans</a></li>
                <li class="breadcrumb-item active" aria-current="page">Apply Loan</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-6">
                    <form action="{{route('loans.store')}}" method="POST" autocomplete="off" onsubmit="return disableSubmitButton(this);">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="employee_id">Employee (Staff)</label>
                            <select name="labourer_id" id="labourer_id"
                                    class="form-select select-relation @error('labourer_id') is-invalid @enderror" style="width: 100%" required>
                                <option value="">-- Select Employee ---</option>
                                @foreach($labourers as $labourer)
                                    <option value="{{$labourer->id}}"
                                        {{ (old('labourer_id') == $labourer->id) ? 'selected' : '' }}>
                                        {{$labourer->name}}
                                    </option>
                                @endforeach
                            </select>
                            @error('labourer_id')
                            <span class="invalid-feedback">
                                {{$message}}
                            </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="loan_amount">Loan Amount (MWK)</label>
                            <input type="number" step="0.01" name="loan_amount" id="loan_amount"
                                   class="form-control @error('loan_amount') is-invalid @enderror"
                                   value="{{old('loan_amount')}}"
                                   placeholder="e.g., 150000.00" required>
                            @error('loan_amount')
                            <span class="invalid-feedback">
                                {{$message}}
                            </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="interest_rate">Interest Rate (%) (Optional)</label>
                            <input type="number" step="0.01" name="interest_rate" id="interest_rate"
                                   class="form-control @error('interest_rate') is-invalid @enderror"
                                   value="{{old('interest_rate')}}"
                                   placeholder="e.g., 5.00 for 5%">
                            @error('interest_rate')
                            <span class="invalid-feedback">
                                {{$message}}
                            </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="loan_duration_months">Loan Term (Months)</label>
                            <input type="number" name="loan_duration_months" id="loan_duration_months"
                                   class="form-control @error('loan_duration_months') is-invalid @enderror"
                                   value="{{old('loan_duration_months')}}"
                                   placeholder="e.g., 12 for 12 months" required>
                            @error('loan_duration_months')
                            <span class="invalid-feedback">
                                {{$message}}
                            </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="loan_start_date">Loan Start Date</label>
                            <input type="date" name="loan_start_date" id="loan_start_date"
                                   class="form-control @error('loan_start_date') is-invalid @enderror"
                                   value="{{old('loan_start_date')}}"
                                   required>
                            @error('loan_start_date')
                            <span class="invalid-feedback">
                                {{$message}}
                            </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="reason">Reason for Loan</label>
                            <textarea name="reason" id="reason"
                                      class="form-control @error('reason') is-invalid @enderror"
                                      rows="3" placeholder="Briefly explain the reason for the loan application" required>{{old('reason')}}</textarea>
                            @error('reason')
                            <span class="invalid-feedback">
                                {{$message}}
                            </span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="account_id" class="form-label">Account</label>
                            <select class="form-select select-relation" id="account_id" name="account_id" required style="width: 100%;">
                                <option value="">Select an Account</option>
                                @foreach ($accounts as $account)
                                    <option value="{{ $account->id }}" {{ old('account_id') == $account->id ? 'selected' : '' }}>
                                        {{ $account->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('account_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="bank_id" class="form-label">Bank Account</label>
                            <select class="form-select select-relation" id="bank_id" name="bank_id" required style="width: 100%;">
                                <option value="">Select a Bank Account</option>
                                @foreach ($banks as $bank)
                                    <option value="{{ $bank->id }}" {{ old('bank_id') == $bank->id ? 'selected' : '' }}>
                                        {{ $bank->account_name.' - '.$bank->account_number }}
                                    </option>
                                @endforeach
                            </select>
                             @error('bank_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-md btn-primary rounded-0 submit-button">
                                <i class="fa fa-paper-plane"></i> Submit Application
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
        // Function to disable submit button to prevent double submission
        function disableSubmitButton(form) {
            const submitButton = form.querySelector('.submit-button');
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Submitting...';
            }
            return true; // Allow form submission
        }

        $(document).ready(function () {
            // Initialize select2 for dropdowns if you are using it
            // $('.form-select').select2();
        });
    </script>
@endsection
