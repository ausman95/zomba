@extends('layouts.app')


@section('content')
    <div class="container-fluid ps-1 pt-4">

        <h4>
            <i class="fa fa-list-ol"></i>Home Cells
        </h4>
        <p>
            Update Home Cells Details
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('churches.index')}}">Home Cells</a></li>
                <li class="breadcrumb-item"><a href="{{route('churches.show',$church->id)}}">{{$church->name}}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Update</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-4">
                    <form action="{{route('churches.update',$church->id)}}" method="POST" autocomplete="off">
                        @csrf
                        <input type="hidden" name="_method" value="PATCH">
                        <input type="hidden"  name="updated_by" value="{{request()->user()->id}}" required>
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{old('name') ?? $church->name}}"
                                   placeholder="Home Cell Name">
                            @error('name')
                            <span class="invalid-feedback">
                               {{$message}}
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Zone</label>
                            <select name="zone_id" required
                                    class="form-select select-relation @error('zone_id') is-invalid @enderror" style="width: 100%">
                                <option value="{{@$church->zone->id}}">{{@$church->zone->name}}</option>
                                @foreach($zones as $zone)
                                    <option value="{{$zone->id}}"
                                        {{old('zone_id')===$zone->id ? 'selected' : ''}}>{{$zone->name}}</option>
                                @endforeach
                            </select>
                            @error('zone_id')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Leader</label>
                            <select name="member_id"
                                    class="form-select select-relation @error('member_id') is-invalid @enderror" style="width: 100%">
                                <option value="{{@$church->member->id}}">{{@$church->member->name}}</option>
                                @foreach($members as $member)
                                    <option value="{{$member->id}}"
                                        {{old('member_id')===$member->id ? 'selected' : ''}}>{{$member->name.' - '.$member->phone_number}}</option>
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
