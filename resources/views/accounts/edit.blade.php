@extends('layouts.app')


@section('content')
    <div class="container-fluid ps-1 pt-4">

        <h4>
            <i class="fa fa-money-bill-alt"></i>Charts Of Accounts
        </h4>
        <p>
            Update Account Details
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('finances.index')}}">Finances</a></li>
                <li class="breadcrumb-item"><a href="{{route('accounts.index')}}">Accounts</a></li>
                <li class="breadcrumb-item"><a href="{{route('accounts.show',$account->id)}}">{{$account->name}}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Update</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-4">
                    <form action="{{route('accounts.update',$account->id)}}" method="POST" autocomplete="off">
                        @csrf
                        <input type="hidden" name="_method" value="PATCH">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{old('name') ?? $account->name}}"
                                   placeholder="Account's name">
                            @error('name')
                            <span class="invalid-feedback">
                               {{$message}}
                            </span>
                            @enderror
                        </div>
                        <hr style="height: .3em;" class="border-theme">
                        <div class="form-group">
                            <label>Account Type</label>
                            <select name="type" class="form-control select-relation @error('type') is-invalid @enderror" style="width: 100%">{{old('type')}} >
                                <option value="{{$account->type}}">@if($account->type==1)
                                        {{"CR"}}
                                    @else
                                        {{"DR"}}
                                    @endif</option>
                                <option value="2">Dr</option>
                                <option value="1">Cr</option>
                            </select>
                            @error('type')
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
