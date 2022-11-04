@extends('layouts.app')


@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="bx bxs-city "></i>&nbsp; Clients
        </h4>
        <p>
            Create client account
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('projects.index')}}">Projects</a></li>
                <li class="breadcrumb-item"><a href="{{route('clients.index')}}">Clients</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create client</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-4">
                    <form action="{{route('clients.store')}}" method="POST" autocomplete="off">
                        @csrf
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{old('name')}}"
                                   placeholder="Client's name"
                            >
                            @error('name')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                   value="{{old('email')}}"
                                   placeholder="Enter Email"
                            >
                            @error('email')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="text" name="phone_number"
                                   class="form-control @error('phone_number') is-invalid @enderror"
                                   value="{{old('phone_number')}}"
                                   placeholder="Client's phone number"
                            >
                            @error('phone_number')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Location</label>
                            <input type="text" name="location"
                                   class="form-control @error('location') is-invalid @enderror"
                                   value="{{old('location')}}"
                                   placeholder="Enter location i.e Lilongwe"
                            >
                            @error('location')
                            <span class="invalid-feedback">
                               {{$message}}
                            </span>
                            @enderror
                        </div>
                        <hr style="height: .3em;" class="border-theme">
                        <div class="form-group">
                            <label>Address</label>
                            <textarea name="address" rows="5"
                                      class="form-control @error('address') is-invalid @enderror">{{old('address')}}</textarea>
                            @error('address')
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
