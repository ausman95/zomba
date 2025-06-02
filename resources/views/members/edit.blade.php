@extends('layouts.app')


@section('content')
    <div class="container-fluid ps-1 pt-4">

        <h4>
            <i class="bx bxs-city "></i>&nbsp; Church Members
        </h4>
        <p>
            Update Member details
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('members.index')}}">Members</a></li>
                <li class="breadcrumb-item"><a href="{{route('members.show',$member->id)}}">{{$member->name}}</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Update</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <form action="{{route('members.update',$member->id)}}" method="POST" autocomplete="off">
                @csrf
                <input type="hidden" name="_method" value="PATCH">
                <div class="row">
                    <div class="col-sm-12 col-md-8 col-lg-4">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="hidden"  name="updated_by" value="{{request()->user()->id}}" required>
                            <input type="text" name="name" required
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{old('name') ?? $member->name}}"
                                   placeholder="Member name"
                            >
                            @error('name')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Phone Number 1</label>
                            <input type="number" name="phone_number"
                                   class="form-control @error('phone_number') is-invalid @enderror"
                                   value="{{old('phone_number') ?? $member->phone_number}}"
                                   placeholder="Phone Number 1"
                            >
                            @error('phone_number')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Phone Number 2</label>
                            <input type="number" name="phone"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   value="{{old('phone') ?? $member->phone}}"
                                   placeholder="Phone Number 2"
                            >
                            @error('phone')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" required
                                    class="form-select select-relation @error('status') is-invalid @enderror" style="width: 100%">
                                <option value="{{$member->status}}">
                                    @if($member->status==1)
                                        {{'Active'}}
                                    @elseif($member->status==1)
                                        {{'Moved'}}
                                    @elseif($member->status==3)
                                        {{'Deceased'}}
                                    @endif
                                </option>
                                <option value="1">Active</option>
                                <option value="2">Moved</option>
                                <option value="3">Deceased</option>
                            </select>
                            @error('status')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Gender</label>
                            <select name="gender" required class="form-select select-relation @error('gender') is-invalid @enderror" style="width: 100%">{{old('gender')}}>
                                <option value="{{$member->gender}}">{{$member->gender}}</option>
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
                            <label>Home Cell</label>
                            <select name="church_id" required
                                    class="form-select select-relation @error('church_id') is-invalid @enderror" style="width: 100%">
                                <option value="">-- Select ---</option>
                                @foreach($churches as $church)
                                    <option value="{{$church->id}}"
                                        {{(old('church_id')===$church->id || $member->church_id == $church->id) ? 'selected' : ''}}>{{$church->name}}</option>
                                @endforeach
                            </select>
                            @error('church_id')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group mb-3"> {{-- Added mb-3 for consistent spacing below the input --}}
                            <label for="position_id" class="form-label">Church Position<span class="text-danger">*</span></label> {{-- Added 'for' attribute and 'form-label' class, plus a required indicator --}}
                            <select name="position_id" id="position_id" required {{-- Added 'id' for the label's 'for' attribute --}}
                            class="form-select select-relation @error('position_id') is-invalid @enderror" style="width: 100%">
                                <option value="">-- Select Church Position --</option> {{-- More descriptive default option --}}
                                @foreach($positions as $position)
                                    <option value="{{ $position->id }}"
                                        {{-- FIX: Corrected "selected" logic. Checks old input first, then existing model's position_id --}}
                                        {{ (old('position_id') == $position->id || (isset($member) && $member->position_id == $position->id)) ? 'selected' : '' }}>
                                        {{ ucwords($position->name) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('position_id')
                            <span class="invalid-feedback" role="alert"> {{-- Added role="alert" for accessibility --}}
            <strong>{{ $message }}</strong>
        </span>
                            @enderror
                        </div>
                        <hr style="height: .3em;" class="border-theme">
                        <div class="form-group ">
                            <button class="btn btn-md btn-primary rounded-0">
                                <i class="fa fa-edit"></i>Update
                            </button>
                        </div>
                    </div>

                </div>
                <hr>

            </form>
        </div>
    </div>
@stop
