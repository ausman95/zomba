@extends('layouts.app')


@section('content')
    <div class="container-fluid ps-1 pt-4">

        <h4>
            <i class="fa fa-car"></i>Driver Allowances
        </h4>
        <p>
            Manage Driver Allowances
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('drivers.index')}}">Driver Allowances</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$driver->labourer->name}}  {{$driver->account->name}}</li>
                <li class="breadcrumb-item active" aria-current="page">Update</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-4">
                    <form action="{{route('drivers.update',$driver->id)}}" method="POST" autocomplete="off">
                        @csrf
                        <input type="hidden" name="_method" value="PATCH">
                        <div class="form-group">
                            <label> Account </label>
                            <select name="account_id"
                                    class="form-select select-relation @error('account_id') is-invalid @enderror" style="width: 100%">
                                <option value="{{$driver->account_id}}">{{$driver->account->name}}</option>
                                @foreach($accounts as $account)
                                    <option value="{{$account->id}}"
                                        {{old('account_id')===$account->id ? 'selected' : ''}}>{{$account->name}}</option>
                                @endforeach

                            </select>

                        </div>
                        <div class="form-group">
                            <label> Driver </label>
                            <select name="labourer_id"
                                    class="form-select select-relation @error('labourer_id') is-invalid @enderror" style="width: 100%">
                                <option value="{{$driver->labourer_id}}">{{$driver->labourer->name}}</option>
                                @foreach($labourers as $labourer)
                                    <option value="{{$labourer->id}}"
                                        {{old('labourer_id')===$labourer->id ? 'selected' : ''}}>{{$labourer->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Amount</label>
                            <input type="number" name="amount"
                                   class="form-control @error('amount') is-invalid @enderror"
                                   value="{{$driver->amount}}" placeholder="Amount" >
                            @error('amount')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Start Date</label>
                            <input type="date" name="start_date"
                                   class="form-control @error('start_date') is-invalid @enderror"
                                   value="{{$driver->start_date}}" placeholder="Start Date" >
                            @error('start_date')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>{{$driver->end_date}}</label>
                            <input type="date" name="end_date"
                                   class="form-control @error('end_date') is-invalid @enderror"
                                   value="{{$driver->end_date}}"
                                   placeholder="End Date" >
                            @error('end_date')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <hr style="height: .3em;" class="border-theme">
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
