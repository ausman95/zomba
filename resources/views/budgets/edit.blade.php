@extends('layouts.app')
@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-list-ul"></i>Budgets
        </h4>
        <p>
            Update Budgets Details
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('finances.index')}}">Finances</a></li>
                <li class="breadcrumb-item"><a href="{{route('budgets.index')}}">Budgets</a></li>
                <li class="breadcrumb-item"><a href="{{route('budgets.show',$budget->id)}}">{{$budget->name}}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Update</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-4">
                    <form action="{{route('budgets.update',$budget->id)}}" method="POST" autocomplete="off">
                        @csrf
                        <input type="hidden" name="_method" value="PATCH">
                        <div class="form-group">
                            <label> Account </label>
                            <select name="account_id"
                                    class="form-select select-relation @error('account_id') is-invalid @enderror" style="width: 100%">
                                <option value="{{$budget->account_id}}">{{$budget->account->name}}</option>
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
                            <label>Amount</label>
                            <input type="number" name="amount"
                                   class="form-control @error('amount') is-invalid @enderror"
                                   value="{{old('amount') ?? $budget->amount}}"
                                   placeholder="Amount Allocated" >
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
                            <label>Financial Year</label>
                            <select name="financial_year_id"
                                    class="form-select select-relation @error('financial_year_id') is-invalid @enderror" style="width: 100%">
                                <option value="{{$budget->financial_year_id}}">{{$budget->financial->name}}</option>
                                @foreach($financial_years as $financial_year)
                                    <option value="{{$financial_year->id}}"
                                        {{old('financial_year_id')===$financial_year->id ? 'selected' : ''}}>{{$financial_year->name.' From '.$financial_year->start_date}}</option>
                                @endforeach
                            </select>
                            @error('financial_year_id')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group ">
                            <button class="btn btn-md btn-primary rounded-0">
                                <i class="fa fa-edit"></i>Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
