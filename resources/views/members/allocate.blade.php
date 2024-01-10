@extends('layouts.app')


@section('content')
    <div class="container-fluid ps-1 pt-4">

        <h4>
            <i class="fa fa-users"></i>Labourers
        </h4>
        <p>
            Update Labourers account
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('human-resources.index')}}">Members</a></li>
                <li class="breadcrumb-item"><a href="{{route('members.show',$_GET['id'])}}">{{$member->getName($_GET['id'])}}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Ministry Allocation</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-4">
                    <h4 style="color: red">Allocating {{$member->getName($_GET['id'])}}</h4>
                    <hr>
                    <form action="{{route('allocations.store')}}" method="POST" autocomplete="off">
                        @csrf
                        <input type="hidden" name="member_id" value="{{$_GET['id']}}">
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
                        <hr>
                        <div class="form-group ">
                            <button class="btn btn-md btn-primary rounded-0">
                                <i class="fa fa-edit"></i>Allocate
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
