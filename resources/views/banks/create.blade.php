@extends('layouts.app')


@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-building"></i>Bank Accounts
        </h4>
        <p>
            Bank Account
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('finances.index')}}">Finances</a></li>
                <li class="breadcrumb-item"><a href="{{route('banks.index')}}">Bank Accounts</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create Bank Account</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-4">
                    <form action="{{route('banks.store')}}" method="POST" autocomplete="off">
                        @csrf
                        <div class="form-group">
                            <label>Account Name</label>
                            <input type="text" name="account_name"
                                   class="form-control @error('account_name') is-invalid @enderror"
                                   value="{{old('account_name')}}"
                                   placeholder="Bank Account's name" >
                            @error('account_name')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <hr style="height: .3em;" class="border-theme">
                        <div class="form-group">
                            <label>Account Number</label>
                            <input type="text" name="account_number"
                                   class="form-control @error('account_number') is-invalid @enderror"
                                   value="{{old('account_number')}}"
                                   placeholder="Bank Account's number" >
                            @error('account_number')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Service Centre</label>
                            <input type="text" name="service_centre"
                                   class="form-control @error('service_centre') is-invalid @enderror"
                                   value="{{old('service_centre')}}"
                                   placeholder="Service Centre" >
                            @error('service_centre')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Account Type</label>
                            <input type="text" name="account_type"
                                   class="form-control @error('account_type') is-invalid @enderror"
                                   value="{{old('account_type')}}"
                                   placeholder="Account Type" >
                            @error('account_type')
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
