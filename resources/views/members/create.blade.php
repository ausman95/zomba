@extends('layouts.app')


@section('content')
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
                           value="{{old('name')}}"
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
                    <select name="gender" class="form-select select-relation @error('gender') is-invalid @enderror" style="width: 100%">{{old('gender')}}>
                        <option value="">-- Select ---</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Customised">Customised</option>
                    </select>
                    @error('gender')
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
                           placeholder="Phone number"
                    >
                    @error('phone_number')
                    <span class="invalid-feedback">
                               {{$message}}
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Home Church</label>
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
                <div class="form-group">
                    <label>Ministry</label>
                    <select name="ministry_id"
                            class="form-select select-relation @error('ministry_id') is-invalid @enderror" style="width: 100%">
                        <option value="">-- Select ---</option>
                        @foreach($ministries as $ministry)
                            <option value="{{$ministry->id}}"
                                {{old('ministry_id')===$ministry->id ? 'selected' : ''}}>{{$ministry->name}}</option>
                        @endforeach
                    </select>
                    @error('ministry_id')
                    <span class="invalid-feedback">
                               {{$message}}
                        </span>
                    @enderror
                </div>
                <hr style="height: .3em;" class="border-theme">
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
