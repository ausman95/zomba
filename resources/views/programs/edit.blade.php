@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-speaker-deck"></i>Home Cell Programs
        </h4>
        <p>
            Manage Programs
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('programs.index')}}">Programs</a></li>
                <li class="breadcrumb-item"><a href="{{route('programs.show',$program->id)}}">{{date('d F Y', strtotime($program->t_date))}}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Update</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-4">
                    <form action="{{route('programs.update',$program->id)}}" method="POST" autocomplete="off">
                        @csrf
                        <input type="hidden" name="_method" value="PATCH">
                        <input type="hidden"  name="created_by" value="{{$program->church_id}}" required>
                        <input type="hidden"  name="updated_by" value="{{request()->user()->id}}" required>
                        <div class="form-group">
                            <label>Date</label>
                            <input type="date" name="t_date"
                                   class="form-control @error('t_date') is-invalid @enderror"
                                   value="{{old('t_date') ?? $program->t_date}}" placeholder="Date"
                                   required>
                            @error('t_date')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Vanue</label>
                            <input type="text" name="venue"
                                   class="form-control @error('venue') is-invalid @enderror"
                                   value="{{old('venue') ?? $program->venue}}" placeholder="from"
                                   required>
                            @error('venue')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Facilitator</label>
                            <select name="facilitator_id"
                                    class="form-select select-relation @error('facilitator_id') is-invalid @enderror" style="width: 100%">
                                <option value="{{$program->facilitator_id}}">{{$program->member->name}}</option>
                                @foreach($members as $member)
                                    <option value="{{$member->id}}"
                                        {{old('facilitator_id')===$member->id ? 'selected' : ''}}>{{$member->name}}</option>
                                @endforeach
                            </select>
                            @error('facilitator_id')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Preacher</label>
                            <select name="preacher_id"
                                    class="form-select select-relation @error('preacher_id') is-invalid @enderror" style="width: 100%">
                                <option value="{{$program->preacher_id}}">{{$program->members->name}}</option>
                            @foreach($members as $member)
                                    <option value="{{$member->id}}"
                                        {{old('facilitator_id')===$member->id ? 'selected' : ''}}>{{$member->name}}</option>
                                @endforeach
                            </select>
                            @error('preacher_id')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
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
