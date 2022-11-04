@extends('layouts.app')


@section('content')
    <div class="container-fluid ps-1 pt-4">

        <h4>
            <i class="fa fa-list-ul"></i>PROJECT / SITE
        </h4>
        <p>
            Update PROJECT / SITE Details
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('human-resources.index')}}">Human Resources</a></li>
                <li class="breadcrumb-item"><a href="{{route('Ministries.index')}}">Project</a></li>
                <li class="breadcrumb-item"><a href="{{route('departments.show',$department->id)}}">{{$department->name}}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Update</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-4">
                    <form action="{{route('departments.update',$department->id)}}" method="POST" autocomplete="off">
                        @csrf
                        <input type="hidden" name="_method" value="PATCH">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{old('name') ?? $department->name}}"
                                   placeholder="Department name">
                            @error('name')
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
