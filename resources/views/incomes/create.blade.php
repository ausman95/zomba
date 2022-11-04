@extends('layouts.app')


@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-users"></i>Payments/Incomes
        </h4>
        <p>
            Payments/Incomes
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('finances.index')}}">Finances</a></li>
                <li class="breadcrumb-item"><a href="{{route('incomes.index')}}">Payments</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create Payment</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-4">
                    <form action="{{route('incomes.store')}}" method="POST" autocomplete="off">
                        @csrf
                        <div class="form-group">
                            <label>Project Name</label>
                            <select name="project_id"
                                    class="form-select @error('client_id') is-invalid @enderror">
                                <option value="">-- Select Project ---</option>
                                @foreach($projects as $project)
                                    <option value="{{$project->id}}"
                                        {{old('project_id')===$project->id ? 'selected' : ''}}>{{$project->name}}</option>
                                @endforeach
                            </select>
                            @error('client_id')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Transaction Account</label>
                            <select name="account_id"
                                    class="form-select @error('account_id') is-invalid @enderror">
                                <option value="">-- Select Account ---</option>
                                @foreach($accounts as $account)
                                    <option value="{{$account->id}}"
                                        {{old('account_id')===$account->id ? 'selected' : ''}}>{{$account->name}}</option>
                                @endforeach
                            </select>
                            @error('account_id')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Deposited To (Bank Name)</label>
                            <select name="bank_id"
                                    class="form-select @error('bank_id') is-invalid @enderror">
                                <option value="">-- Select Bank ---</option>
                                @foreach($banks as $bank)
                                    <option value="{{$bank->id}}"
                                        {{old('bank_id')===$bank->id ? 'selected' : ''}}>{{$bank->account_name.' '. $bank->account_number}}</option>
                                @endforeach
                            </select>
                            @error('bank_id')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <hr style="height: .3em;" class="border-theme">
                        <div class="form-group">
                            <label>Amount</label>
                            <input type="number" name="amount"
                                   class="form-control @error('amount') is-invalid @enderror"
                                   value="{{old('amount')}}"
                                   placeholder="Amount Paid" >
                            <input type="hidden" name="transaction_type" value="1"
                                   class="form-control @error('transaction_type') is-invalid @enderror"
                                  >
                            @error('transaction_type')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Cheque Number</label>
                            <input type="number" name="cheque_number"
                                   class="form-control @error('cheque_number') is-invalid @enderror"
                                   value="{{old('cheque_number')}}"
                                   placeholder="Cheque Number" >
                            @error('cheque_number')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" rows="2"
                                      class="form-control @error('description') is-invalid @enderror">{{old('description')}}</textarea>
                            @error('description')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <button class="btn btn-md btn-success me-2">
                                <i class="fa fa-save"></i>Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
