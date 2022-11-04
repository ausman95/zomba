@extends('layouts.app')


@section('content')
    <div class="container-fluid ps-1 pt-4">

        <h4>
            <i class="fa fa-car"></i>Asset Services
        </h4>
        <p>
            Update Asset Services
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('assets.index')}}">Assets</a></li>
                <li class="breadcrumb-item"><a href="{{route('asset-services.index')}}">Asset Service</a></li>
                <li class="breadcrumb-item"><a href="{{route('asset-services.show',$service->id)}}">{{$service->asset->name}}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Update</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-4">
                    <form action="{{route('asset-services.update',$service->id)}}" method="POST" autocomplete="off">
                        @csrf
                        <input type="hidden" name="_method" value="PATCH">
                        <div class="form-group">
                            <label>Asset Name</label>
                            <select name="asset_id"
                                    class="form-select select-relation @error('asset_id') is-invalid @enderror" style="width: 100%">
                                <option value="{{$service->asset_id}}">{{$service->asset->name.' of Serial # '.$service->asset->serial_number}}</option>
                                @foreach($assets as $asset)
                                    <option value="{{$asset->id}}"
                                        {{(old('asset_id')==$asset->id) ? 'selected' : ''}}>{{$asset->name.' of Serial # '.$asset->serial_number}}</option>
                                @endforeach
                            </select>
                            @error('asset_id')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Service Provider</label>
                            <select name="provider_id"
                                    class="form-select select-relation @error('provider_id') is-invalid @enderror" style="width: 100%">
                                <option value="{{$service->provider_id}}">{{$service->provider->name.' '.$service->provider->service}}</option>
                                @foreach($providers as $provider)
                                    <option value="{{$provider->id}}"
                                        {{old('provider_id')===$provider->id ? 'selected' : ''}}>{{$provider->name.' '.$provider->service}}</option>
                                @endforeach
                            </select>
                            @error('provider_id')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Service Due Date</label>
                            <input type="date" name="service_due"
                                   class="form-control @error('service_due') is-invalid @enderror"
                                   value="{{old('service_due') ?? $service->service_due}}"
                                   placeholder="">
                            @error('service_due')
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
