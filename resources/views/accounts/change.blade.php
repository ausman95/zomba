@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-money-bill-alt"></i> Charts Of Accounts
        </h4>
        <p>
            Update Account Details
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('finances.index') }}">Finances</a></li>
                <li class="breadcrumb-item"><a href="{{ route('accounts.index') }}">Accounts</a></li>
                <li class="breadcrumb-item"><a href="{{ route('accounts.show', $transaction->account->id) }}">{{ $transaction->account->name }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Update</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-4">
                    <form action="{{ route('account.modify', $transaction->id) }}" method="POST" autocomplete="off">
                        @csrf
                        <input type="hidden" name="_method" value="PATCH">
                        <input type="hidden" name="updated_by" value="{{ request()->user()->id }}" required>

                        <div class="form-group">
                            <label for="paymentName">Payment Name</label>
                            <input type="text" id="paymentName" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $transaction->name) }}" placeholder="Payment name">
                            @error('name')
                            <span class="invalid-feedback">
                            {{ $message }}
                        </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <input type="number" id="amount" name="amount"
                                   class="form-control @error('amount') is-invalid @enderror"
                                   value="{{ old('amount', $transaction->amount) }}" placeholder="Amount">
                            @error('amount')
                            <span class="invalid-feedback">
                            {{ $message }}
                        </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="accountSelect">Account</label>
                            <select id="accountSelect" name="account_id" required class="form-select select-relation @error('account_id') is-invalid @enderror" style="width: 100%">
                                <option value="{{ @$transaction->account_id }}">{{ @$transaction->account->name }}</option>
                                @foreach($accounts as $account)
                                    <option value="{{ $account->id }}" {{ old('account_id') == $account->id ? 'selected' : '' }}>{{ $account->name }}</option>
                                @endforeach
                            </select>
                            @error('account_id')
                            <span class="invalid-feedback">
                            {{ $message }}
                        </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="bank_id">Bank</label>
                            <select id="bank_id" name="bank_id" required class="form-select select-relation @error('bank_id') is-invalid @enderror" style="width: 100%">
                                <option value="{{ @$transaction->bank_id }}">{{ @$transaction->bank->bank_name.' - '.$transaction->bank->account_number }}</option>
                                @foreach($banks as $bank)
                                    <option value="{{ $bank->id }}" {{ old('account_id') == $bank->id ? 'selected' : '' }}>{{ $bank->bank_name.' '.$bank->account_number }}</option>
                                @endforeach
                            </select>
                            @error('bank_id')
                            <span class="invalid-feedback">
                            {{ $message }}
                        </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="reference">Reference</label>
                            <input type="text" id="reference" name="reference"
                                   class="form-control @error('reference') is-invalid @enderror"
                                   value="{{ old('reference', $transaction->reference) }}" placeholder="Reference">
                            @error('reference')
                            <span class="invalid-feedback">
                            {{ $message }}
                        </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="t_date">Transaction Date</label>
                            <input type="date" id="t_date" name="t_date"
                                   class="form-control @error('t_date') is-invalid @enderror"
                                   value="{{ old('t_date', $transaction->t_date) }}">
                            @error('t_date')
                            <span class="invalid-feedback">
                            {{ $message }}
                        </span>
                            @enderror
                        </div>

                        <hr style="height: .3em;" class="border-theme">
                        <div class="form-group">
                            <button class="btn btn-md btn-primary rounded-0">
                                <i class="fa fa-edit"></i> Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
