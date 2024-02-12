@extends('layouts.app')


@section('content')
    <div class="container-fluid ps-1 pt-4">

        <h4>
            <i class="fa fa-list-ol"></i>Zones
        </h4>
        <p>
            Update Zones Details
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('zones.index')}}">Zones</a></li>
                <li class="breadcrumb-item"><a href="{{route('zones.show',$zone->id)}}">{{$zone->name}}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Update</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-4">
                    <form action="{{route('zones.update',$zone->id)}}" method="POST" autocomplete="off">
                        @csrf
                        <input type="hidden" name="_method" value="PATCH">
                        <input type="hidden"  name="updated_by" value="{{request()->user()->id}}" required>
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{old('name') ?? $zone->name}}"
                                   placeholder="Zone Name">
                            @error('name')
                            <span class="invalid-feedback">
                               {{$message}}
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Leader</label>
                            <select name="member_id"
                                    class="form-select select-relation @error('member_id') is-invalid @enderror" style="width: 100%">
                                <option value="{{@$zone->member->id}}">{{@$zone->member->name}}</option>
                                @foreach($members as $member)
                                    <option value="{{$member->id}}"
                                        {{old('member_id')===$member->id ? 'selected' : ''}}>{{$member->name.' OF '.$member->phone_number}}</option>
                                @endforeach
                            </select>
                            @error('member_id')
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
