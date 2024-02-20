@extends('layouts.app')
@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-list-ul"></i>Budgets
        </h4>
        <p>
            Budgets
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('finances.index')}}">Finances</a></li>
                <li class="breadcrumb-item"><a href="{{route('budgets.index')}}">Budgets</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create Budget</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-4">
                    <form action="{{route('budgets.store')}}" method="POST" autocomplete="off">
                        @csrf
                        <div class="form-group">
                            <label> Account </label>
                            <input type="hidden"  name="updated_by" value="{{request()->user()->id}}" required>
                            <input type="hidden"  name="created_by" value="{{request()->user()->id}}" required>
                            <select name="account_id" required
                                    class="form-select select-relation @error('account_id') is-invalid @enderror" style="width: 100%">
                                <option value="">-- Select ---</option>
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
                            <input type="number" name="amount" required
                                   class="form-control @error('amount') is-invalid @enderror"
                                   value="{{old('amount')}}"
                                   placeholder="Amount Allocated" >
                            @error('amount')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label> Financial Year </label>
                            <select name="year_id" required
                                    class="form-select select-relation @error('year_id') is-invalid @enderror" style="width: 100%">
                                <option value="">-- Select ---</option>
                                @foreach($years as $year)
                                    <option value="{{$year->id}}"
                                        {{old('year_id')===$year->id ? 'selected' : ''}}>{{$year->name}}</option>
                                @endforeach
                            </select>
                            @error('year_id')
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
