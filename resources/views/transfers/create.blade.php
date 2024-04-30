@extends('layouts.app')


@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-users"></i>Bank Transfers
        </h4>
        <p>
            Bank Transfers
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('finances.index')}}">Finances</a></li>
                <li class="breadcrumb-item"><a href="{{route('banks.index')}}">Banks</a></li>
                <li class="breadcrumb-item"><a href="{{route('transfers.index')}}">Bank Transfers</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create Bank Transfers</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-4">
                    <form action="{{route('transfers.store')}}" method="POST" autocomplete="off">
                        @csrf
                        <div class="form-group">
                            <label>Bank Account From</label>
                            <input type="hidden"  name="updated_by" value="{{request()->user()->id}}" required>
                            <input type="hidden"  name="created_by" value="{{request()->user()->id}}" required>
                            <select name="from_account_id"
                                    class="form-select select-relation @error('from_account_id') is-invalid @enderror" style="width: 100%">
                                <option value="">-- Select ---</option>
                                @foreach($banks as $bank)
                                    <option value="{{$bank->id}}"
                                        {{old('from_account_id')===$bank->id ? 'selected' : ''}}>{{$bank->account_name.' '.$bank->account_number}}</option>
                                @endforeach
                            </select>
                            @error('from_account_id')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Bank Account To</label>
                            <select name="to_account_id"
                                    class="form-select select-relation @error('to_account_id') is-invalid @enderror" style="width: 100%">
                                <option value="">-- Select ---</option>
                                @foreach($banks as $bank)
                                    <option value="{{$bank->id}}"
                                        {{old('to_account_id')===$bank->id ? 'selected' : ''}}>{{$bank->account_name.' '.$bank->account_number}}</option>
                                @endforeach
                            </select>
                            @error('from_account_id')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Amount</label>
                            <input type="number" name="amount"
                                   class="form-control @error('amount') is-invalid @enderror"
                                   value="{{old('amount')}}"
                                   placeholder="Amount Paid" >
                            <input type="hidden" name="transaction_type" value="1"
                                   class="form-control @error('transaction_type') is-invalid @enderror"
                                  >
                            @error('amount')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Reference (is Optional)</label>
                            <input type="text" name="cheque_number"
                                   class="form-control @error('cheque_number') is-invalid @enderror"
                                   placeholder="Cheque Number" >
                            @error('cheque_number')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Date</label>
                            <input type="date" name="t_date"
                                   class="form-control @error('t_date') is-invalid @enderror"
                                   placeholder="Transaction Date" >
                            @error('t_date')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <button class="btn btn-md btn-primary rounded-0">
                                <i class="fa fa-paper-plane"></i>Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
