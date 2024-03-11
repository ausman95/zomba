@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">

        <h4>
            <i class="fa fa-list-ol"></i> Member Home Cell Attendances
        </h4>
        <p>
            Attendances
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('home-attendances.index')}}">Attendances</a></li>
                <li class="breadcrumb-item"><a href="{{route('home-attendances.index',$attendance->id)}}">{{$attendance->member->name}}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Attendance</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <h3>
            {{$attendance->member->name}}
        </h3>
        <div class="row">
            <form action="{{route('home-attendances.update',$attendance->id)}}" method="POST">
                @csrf
                <input type="hidden" name="_method" value="PATCH">
                <div class="col-sm-12 col-md-4">
                    <div class="form-group">
                        <input type="hidden"  name="church_id" value="{{$church_id}}" required>
                        <input type="hidden" name="id" value="{{$attendance->id}}">
                        <input type="hidden"  name="updated_by" value="{{request()->user()->id}}" required>
                        <label for="date">Status</label>
                        <select name="status" class="form-select select-relation">
                            <option value="{{$attendance->status}}">
                                @if($attendance->status ==0)
                                    0-Absent
                                @else
                                    1-Present
                                @endif
                            </option>
                            <option value="0">0 -
                                Absent
                            </option>
                            <option value="1"> 1- Present
                            </option>
                        </select>
                        @error('date')
                        <span class="invalid-feedback">
                            {{$message}}
                        </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary rounded-0">
                         <i class="fa fa-edit"></i>   Update
                        </button>
                    </div>
                    <hr>
                </div>
            </form>
        </div>

    </div>
@stop

