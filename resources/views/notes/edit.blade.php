@extends('layouts.app')
@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-list-ul"></i>Project Notes
        </h4>
        <p>
            Update Project Note Details
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('projects.index')}}">Projects</a></li>
                <li class="breadcrumb-item"><a href="{{route('notes.index')}}">Project Notes</a></li>
                <li class="breadcrumb-item"><a href="{{route('notes.show',$note->id)}}">{{$note->department->name}}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Update</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-4">
                    <form action="{{route('notes.update',$note->id)}}" method="POST" autocomplete="off">
                        @csrf
                        <input type="hidden" name="_method" value="PATCH">
                        <div class="form-group">
                            <label>Department</label>
                            <input type="text" name="department_id"
                                   class="form-control @error('department_id') is-invalid @enderror"
                                   value="{{$note->department->name}}"
                                   disabled>
                            @error('department_id')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Notes</label>
                            <textarea name="note" rows="5"
                                      class="form-control @error('note') is-invalid @enderror">{{$note->note}}</textarea>
                            @error('note')
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
