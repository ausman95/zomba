@extends('layouts.app')
@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-list-ul"></i>Project Notes
        </h4>
        <p>
            Notes
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('projects.index')}}">Projects</a></li>
                <li class="breadcrumb-item"><a href="{{route('notes.index')}}">Notes</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create Project Notes</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-4">
                    <form action="{{route('notes.store')}}" method="POST" autocomplete="off">
                        @csrf
                        @if(request()->user()->designation!='clerk')
                        <div class="form-group">
                            <label>Project</label>
                            <select name="department_id"
                                    class="form-select select-relation @error('department_id') is-invalid @enderror" style="width: 100%">
                                <option value="">-- Select ---</option>
                                @foreach($departments as $department)
                                    <option value="{{$department->id}}"
                                        {{old('department_id')===$department->id ? 'selected' : ''}}>{{$department->name}}</option>
                                @endforeach
                            </select>
                            @error('department_id')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        @else
                            <input type="hidden" name="department_id" value="{{request()->user()->department_id}}">
                        @endif
                        <div class="form-group">
                            <label>Notes</label>
                            <textarea name="note" rows="5"
                                      class="form-control @error('note') is-invalid @enderror">{{old('note')}}</textarea>
                            @error('note')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>

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
