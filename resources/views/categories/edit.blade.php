@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-list-ol"></i>Accounts Categories
        </h4>
        <p>
            Manage Accounts Categories
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('finances.index')}}">Finances</a></li>
                <li class="breadcrumb-item"><a href="{{route('accounts.index')}}">Chart of Accounts</a></li>
                <li class="breadcrumb-item"><a href="{{route('categories.index')}}">Accounts Categories</a></li>
                <li class="breadcrumb-item"><a href="{{route('categories.show',$category->id)}}">{{$category->name}}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Update</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-4">
                    <form action="{{route('categories.update',$category->id)}}" method="POST" autocomplete="off">
                        @csrf
                        <input type="hidden" name="_method" value="PATCH">
                        <input type="hidden" name="updated_by" value="{{request()->user()->id}}">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="hidden" name="updated_by" value="{{request()->user()->id}}">
                            <input type="text" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{old('name') ?? $category->name}}"
                                   placeholder="Category name">
                            @error('name')
                            <span class="invalid-feedback">
                               {{$message}}
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Type</label>
                            <select name="status" required class="form-control select-relation @error('type') is-invalid @enderror" style="width: 100%">{{old('status')}}>
                                <option value="{{$category->status}}">@if($category->status==1)
                                        BALANCE SHEET ITEM
                                    @else
                                        P&L ITEM
                                    @endif
                                </option>
                                <option value="2">Income Statement Item</option>
                                <option value="1">Balance Sheet Item</option>
                            </select>
                            @error('type')
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
