@extends('layouts.app')


@section('content')
    <div class="container-fluid ps-1 pt-4">

        <h4>
            <i class="fa fa-car"></i>Payments
        </h4>
        <p>
            Update Payment
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('finances.index')}}">Finances</a></li>
                @if(request()->has('verified') && request()->input('verified') == 0)
                    <li class="breadcrumb-item"><a href="{{ route('receipts.index') }}">Receipts</a></li>
                @endif

                @if(request()->has('verified') && request()->input('verified') == 2)
                    <li class="breadcrumb-item"><a href="{{ route('payments.index') }}">Payments</a></li>
                @endif

                @if(request()->has('verified') && request()->input('verified') == 1)
                    <li class="breadcrumb-item"><a href="{{ route('receipt.unverified') }}">Un~Verified Transactions</a></li>
                @endif
                <li class="breadcrumb-item"><a href="{{route('payments.show',$transaction->id).'?verified='.$_GET['verified']}}">{{$transaction->id}}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Update</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-4">
                    <form action="{{route('payments.update',$transaction->id)}}" method="POST" autocomplete="off">
                        @csrf
                        <input type="hidden" name="_method" value="PATCH">
                        <input type="hidden" name="verified" value="{{$_GET['verified']}}">
                        <div class="form-group">
                            <label>Amount</label>
                            <input type="number" name="amount"
                                   class="form-control @error('amount') is-invalid @enderror"
                                   value="{{old('amount') ?? $transaction->amount}}" readonly
                            >
                            @error('amount')
                            <span class="invalid-feedback">
                               {{$message}}
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Reference/ Cheque Number</label>
                            <input type="text" name="reference"
                                   class="form-control @error('reference') is-invalid @enderror"
                                   value="{{old('reference') ?? $transaction->reference}}"
                                   placeholder="Transaction Reference"
                            >
                            @error('reference')
                            <span class="invalid-feedback">
                               {{$message}}
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="specification" rows="2"
                                      class="form-control @error('specification')
                                      is-invalid @enderror" placeholder="Optional">{{old('specification') ?? $transaction->specification }}</textarea>
                            @error('specification')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <hr>
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
