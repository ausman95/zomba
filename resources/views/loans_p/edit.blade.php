@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-hand-holding-usd"></i> Edit Employee Loan Application
        </h4>
        <p>
            Edit Loan Application for {{ $loan->employee->name ?? 'N/A' }}
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('human-resources.index') }}">Human Resources</a></li>
                <li class="breadcrumb-item"><a href="{{route('loans.index')}}">Employee Loans</a></li>
                <li class="breadcrumb-item"><a href="{{route('loans.show', $loan->id)}}">Loan Details</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Loan</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-6">
                    <form action="{{route('loans.update', $loan->id)}}" method="POST" autocomplete="off" onsubmit="return disableSubmitButton(this);">
                        @csrf
                        @method('PATCH') {{-- Use PATCH method for updates --}}

                        <div class="form-group mb-3">
                            <label for="employee_id">Employee (Staff)</label>
                            <select name="employee_id" id="employee_id"
                                    class="form-select @error('employee_id') is-invalid @enderror" style="width: 100%" required>
                                <option value="">-- Select Employee ---</option>
                                @foreach($labourers as $labourer)
                                    <option value="{{$labourer->id}}"
                                        {{ (old('employee_id', $loan->employee_id) == $labourer->id) ? 'selected' : '' }}>
                                        {{$labourer->name}}
                                    </option>
                                @endforeach
                            </select>
                            @error('employee_id')
                            <span class="invalid-feedback">
                                {{$message}}
                            </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="loan_amount">Loan Amount (MWK)</label>
                            <input type="number" step="0.01" name="loan_amount" id="loan_amount"
                                   class="form-control @error('loan_amount') is-invalid @enderror"
                                   value="{{old('loan_amount', $loan->loan_amount)}}"
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
                                   value="{{old('interest_rate', $loan->interest_rate)}}"
                                   placeholder="e.g., 5.00 for 5%">
                            @error('interest_rate')
                            <span class="invalid-feedback">
                                {{$message}}
                            </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="loan_term_months">Loan Term (Months) (Optional)</label>
                            <input type="number" name="loan_term_months" id="loan_term_months"
                                   class="form-control @error('loan_term_months') is-invalid @enderror"
                                   value="{{old('loan_term_months', $loan->loan_term_months)}}"
                                   placeholder="e.g., 12 for 12 months">
                            @error('loan_term_months')
                            <span class="invalid-feedback">
                                {{$message}}
                            </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="start_date">Loan Start Date</label>
                            <input type="date" name="start_date" id="start_date"
                                   class="form-control @error('start_date') is-invalid @enderror"
                                   value="{{old('start_date', \Carbon\Carbon::parse($loan->start_date)->format('Y-m-d'))}}"
                                   required>
                            @error('start_date')
                            <span class="invalid-feedback">
                                {{$message}}
                            </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="reason">Reason for Loan</label>
                            <textarea name="reason" id="reason"
                                      class="form-control @error('reason') is-invalid @enderror"
                                      rows="3" placeholder="Briefly explain the reason for the loan application" required>{{old('reason', $loan->reason)}}</textarea>
                            @error('reason')
                            <span class="invalid-feedback">
                                {{$message}}
                            </span>
                            @enderror
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-md btn-primary rounded-0 submit-button">
                                <i class="fa fa-save"></i> Update Application
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
                submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...';
            }
            return true; // Allow form submission
        }

        $(document).ready(function () {
            // Initialize select2 for dropdowns if you are using it
            // $('.form-select').select2();
        });
    </script>
@endsection
