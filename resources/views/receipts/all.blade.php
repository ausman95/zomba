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
                                <label for="bank_id">Banks</label>
                                <select name="bank_id"
                                        class="form-select select-relation
                                            @error('bank_id') is-invalid @enderror" style="width: 100%">
                                    <option value="">Select Bank (Optional)</option>
                                    @foreach($banks as $bank)
                                        <option value="{{$bank->id}}"
                                            {{old('bank_id')===$bank->id ? 'selected' : ''}}>{{$bank->account_name.' - '.$bank->account_number}}</option>
                                    @endforeach
                                </select>
                                @error('bank_id')
                                <span class="invalid-feedback">
                                    {{$message}}
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="month_id">Months</label>
                                <select name="month_id" class="form-select select-relation @error('month_id') is-invalid @enderror"
                                        style="width: 100%">
                                    <option value="">Select Month (Optional)</option>
                                    @foreach($months as $month)
                                        <option value="{{ $month->id }}"
                                            {{ old('month_id') == $month->id ? 'selected' : '' }}>{{ $month->name }}</option>
                                    @endforeach
                                </select>
                                @error('month_id')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="start_date">Start Date</label>
                                <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror"
                                       value="{{ old('start_date') }}">
                                @error('start_date')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="end_date">End Date</label>
                                <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror"
                                       value="{{ old('end_date') }}">
                                @error('end_date')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <button class="btn btn-primary rounded-0" type="submit">
                                    View &rarr;
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Transactions display remains unchanged -->
                    <div class="col-sm-12 mb-2 col-md-10 col-lg-10">
                        <br>
                        <div class="card container-fluid" style="min-height: 30em;">
                            <!-- Transactions table here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
