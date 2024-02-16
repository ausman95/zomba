@extends('layouts.app')


@section('content')
    <div class="container-fluid ps-1 pt-4">

        <h4>
            <i class="fa fa-calendar-check"></i>Financial Years
        </h4>
        <p>
            Update Financial Year Details
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('setting.index')}}">Settings</a></li>
                <li class="breadcrumb-item"><a href="{{route('financial-years.index')}}">Financial Years</a></li>
                <li class="breadcrumb-item"><a href="{{route('financial-years.show',$financial_year->id)}}">{{$financial_year->name}}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Update</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-4">
                    <form action="{{route('financial-years.update',$financial_year->id)}}" method="POST" autocomplete="off">
                        @csrf
                        <input type="hidden" name="_method" value="PATCH">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{old('name') ?? $financial_year->name}}"
                                   placeholder="Account's name">
                            @error('name')
                            <span class="invalid-feedback">
                               {{$message}}
                            </span>
                            @enderror
                        </div>
                        <hr style="height: .3em;" class="border-theme">
                        <div class="form-group">
                            <label>Start Date</label>
                            <input type="date" name="start_date"
                                   class="form-control @error('start_date') is-invalid @enderror"
                                   value="{{old('start_date') ?? $financial_year->start_date}}"
                                   placeholder="Start Date">
                            @error('start_date')
                            <span class="invalid-feedback">
                               {{$message}}
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>End Date</label>
                            <input type="date" name="end_date"
                                   class="form-control @error('end_date') is-invalid @enderror"
                                   value="{{old('end_date') ?? $financial_year->end_date}}"
                                   placeholder="End Date">
                            @error('end_date')
                            <span class="invalid-feedback">
                               {{$message}}
                            </span>
                            @enderror
                        </div>
                        <hr style="height: .3em;" class="border-theme">
                        <div class="form-group">
                            <label>Financial Year Status</label>
                            <select name="status" class="form-control @error('status') is-invalid @enderror">{{old('status')}}>
                                <option value="{{$financial_year->status}}">{{$financial_year->status === 1 ? 'Active' : 'Previous'}}
                                  </option>
                                <option value="1">Active</option>
                                <option value="2">Previous</option>
                            </select>
                            @error('status')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <hr>
                        <div class="form-group">
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
