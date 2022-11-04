@extends('layouts.app')


@section('content')
    <div class="container-fluid ps-1 pt-4">

        <h4>
            <i class="fa fa-car"></i>Service Providers
        </h4>
        <p>
            Update Service Provider
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('assets.index')}}">Assets</a></li>
                <li class="breadcrumb-item"><a href="{{route('services.index')}}">Service Providers</a></li>
                <li class="breadcrumb-item"><a href="{{route('services.show',$service->id)}}">{{$service->name}}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Update</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-4">
                    <form action="{{route('services.update',$service->id)}}" method="POST" autocomplete="off">
                        @csrf
                        <input type="hidden" name="_method" value="PATCH">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{old('name') ?? $service->name}}"
                                   placeholder="Client's name"
                            >
                            @error('name')
                            <span class="invalid-feedback">
                               {{$message}}
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Service</label>
                            <input type="text" name="service"
                                   class="form-control @error('service') is-invalid @enderror"
                                   value="{{old('service') ?? $service->name}}"
                                   placeholder="Service Name"
                            >
                            @error('service')
                            <span class="invalid-feedback">
                               {{$message}}
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="text" name="phone"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   value="{{old('phone') ?? $service->phone}}"
                                   placeholder="phone number"
                            >
                            @error('phone_number')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                   value="{{old('email') ?? $service->email}}"
                                   placeholder="Enter Email">
                            @error('email')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <hr style="height: .3em;" class="border-theme">
                        <div class="form-group">
                            <label>Address</label>
                            <textarea name="address" rows="5"
                                      class="form-control @error('address') is-invalid @enderror">{{old('address') ?? $service->address}}</textarea>
                            @error('address')
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
