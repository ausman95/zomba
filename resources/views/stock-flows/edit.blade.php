@extends('layouts.app')


@section('content')
    <div class="container-fluid ps-1 pt-4">

        <h4>
            <i class="fa fa-users"></i>Bank Accounts
        </h4>
        <p>
            Update Bank Account Details
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('finances.index')}}">Finances</a></li>
                <li class="breadcrumb-item"><a href="{{route('banks.index')}}">Bank Account</a></li>
                <li class="breadcrumb-item"><a href="{{route('ministries.show',$bank->id)}}">{{$bank->name}}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Update</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-4">
                    <form action="{{route('banks.update',$bank->id)}}" method="POST">
                        @csrf
                        <input type="hidden" name="_method" value="PATCH">
                        <div class="form-group">
                            <label>Account Name</label>
                            <input type="text" name="account_name"
                                   class="form-control @error('account_name') is-invalid @enderror"
                                   value="{{old('account_name') ?? $bank->account_name}}"
                                   placeholder="Account's name">
                            @error('name')
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
                                   value="{{old('account_number') ?? $bank->account_number}}"
                                   placeholder="Account's Number">
                            @error('name')
                            <span class="invalid-feedback">
                               {{$message}}
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Account Type</label>
                            <input type="text" name="account_type"
                                   class="form-control @error('account_type') is-invalid @enderror"
                                   value="{{old('account_type') ?? $bank->account_type}}"
                                   placeholder="Account's Type">
                            @error('name')
                            <span class="invalid-feedback">
                               {{$message}}
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Service Centre</label>
                            <input type="text" name="service_centre"
                                   class="form-control @error('service_centre') is-invalid @enderror"
                                   value="{{old('service_centre') ?? $bank->service_centre}}"
                                   placeholder="Service Centre">
                            @error('name')
                            <span class="invalid-feedback">
                               {{$message}}
                            </span>
                            @enderror
                        </div>
                        <hr>
                        <div class="form-group ">
                            <button class="btn btn-md btn-success me-2">
                                <i class="fa fa-save"></i>Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
