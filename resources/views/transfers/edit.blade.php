@extends('layouts.app')


@section('content')
    <div class="container-fluid ps-1 pt-4">

        <h4>
            <i class="fa fa-users"></i>Bank Transfers
        </h4>
        <p>
            Update Bank Transfers Details
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('finances.index')}}">Finances</a></li>
                <li class="breadcrumb-item"><a href="{{route('banks.index')}}">Banks</a></li>
                <li class="breadcrumb-item"><a href="{{route('transfers.index')}}">Bank Transfers</a></li>
                <li class="breadcrumb-item"><a href="{{route('transfers.show',$transfer->id)}}">{{$transfer->id}}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Update</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-4">
                    <form action="{{route('transfers.update',$transfer->id)}}" method="POST" autocomplete="off">
                        @csrf
                        <input type="hidden" name="_method" value="PATCH">
                        <div class="form-group">
                            <label>Date</label>
                            <input type="datetime-local" name="t_date"
                                   class="form-control @error('t_date') is-invalid @enderror"
                                   value="{{$transfer->t_date}}"
                                   placeholder="Transaction Date" >
                            @error('t_date')
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
