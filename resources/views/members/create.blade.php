@extends('layouts.app')


@section('content')
    <style>
        :focus{
            outline:none;
        }
        .radio{
            -webkit-appearance:button;
            -moz-appearance:button;
            appearance:button;
            border:4px solid #ccc;
            border-top-color:#bbb;
            border-left-color:#bbb;
            background:#fff;
            width:50px;
            height:50px;
            border-radius:50%;
        }
        .radio:checked{
            border:20px solid #4099ff;
        }

    </style>
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-users"></i>Church Members
        </h4>
        <p>
            Create Member
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('members.index')}}">Members</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create Member</li>
            </ol>
        </nav>
        <hr>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-4">
            <form action="{{route('members.store')}}" method="POST" autocomplete="off">
                @csrf
                <div class="form-group">
                    <label>Name</label>
                    <input type="hidden" name="check" value="1">
                    <input type="text" name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{old('name')}}" required
                           placeholder="Member name"
                    >
                    @error('name')
                    <span class="invalid-feedback">
                               {{$message}}
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Gender</label>
                    <select name="gender" required
                            class="form-select
                            select-relation @error('gender') is-invalid @enderror" style="width: 100%">{{old('gender')}}>
                        <option value="">-- Select ---</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                    @error('gender')
                    <span class="invalid-feedback">
                               {{$message}}
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="number" name="phone_number"
                           class="form-control @error('phone_number') is-invalid @enderror"
                           value="{{old('phone_number')}}"
                           placeholder="Phone number"
                    >
                    @error('phone_number')
                    <span class="invalid-feedback">
                               {{$message}}
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Home Cell</label>
                    <select name="church_id"
                            class="form-select select-relation @error('church_id') is-invalid @enderror" style="width: 100%">
                        <option value="">-- Select ---</option>
                        @foreach($churches as $church)
                            <option value="{{$church->id}}"
                                {{old('church_id')===$church->id ? 'selected' : ''}}>{{$church->name}}</option>
                        @endforeach
                    </select>
                    @error('church_id')
                    <span class="invalid-feedback">
                               {{$message}}
                        </span>
                    @enderror
                </div>
                <p>Ministries</p>
                <hr>
                @foreach($ministries as $ministry)
                    <div class="form-group">
                        <div class="row">
                            <div class="col-7">
                                <label>{{$ministry->name}}</label>
                            </div>
                            <div class="col-5">
                                <select name="min-{{$ministry->id}}" class="form-select">
                                    <option value="0" {{old('min-'.$ministry->id) == '0' ? 'selected' : ''}}>Not Allocated </option>
                                    <option value="1" {{old('min-'.$ministry->id) == '1' ? 'selected' : ''}}>Allocate</option>
                                </select>
                            </div>
                        </div>
                    </div>
                @endforeach
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
