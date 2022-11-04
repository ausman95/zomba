@extends('layouts.app')


@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-list-ul"></i>Contract Types
        </h4>
        <p>
            Contract Types
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('human-resources.index')}}">Human Resources</a></li>
                <li class="breadcrumb-item"><a href="{{route('contracts.index')}}">Contracts</a></li>
                <li class="breadcrumb-item"><a href="{{route('contract-types.index')}}">Contract Types</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create Contract Type</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-4">
                    <form action="{{route('contract-types.store')}}" method="POST" autocomplete="off">
                        @csrf
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{old('name')}}"
                                   placeholder="Contract Type name" >
                            @error('name')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" rows="3" placeholder="Provide a short description of the contract type"
                                      class="form-control @error('description') is-invalid @enderror">{{old('description')}}</textarea>
                            @error('description')
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
