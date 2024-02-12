@extends('layouts.app')


@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-list-ol"></i>Church Attendances
        </h4>
        <p>
            Manage Church Attendances
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('attendances.index')}}">Attendances</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create Church Attendance</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-4">
                    <form action="{{route('attendances.store')}}" method="POST" autocomplete="off">
                        @csrf
                        <div class="form-group">
                            <label>Service</label>
                            <input type="hidden"  name="updated_by" value="{{request()->user()->id}}" required>
                            <input type="hidden"  name="created_by" value="{{request()->user()->id}}" required>
                            <select name="service_id"
                                    class="form-select select-relation @error('service_id') is-invalid @enderror" style="width: 100%" required>
                                @foreach($services as $service)
                                    <option value="{{$service->id}}"
                                        {{old('service_id')===$service->id ? 'selected' : ''}}>{{$service->name}}</option>
                                @endforeach
                            </select>
                            @error('service_id')
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
                        <div class="form-group">
                            <label>Male Attendance</label>
                            <input type="number" name="male"
                                   class="form-control @error('male') is-invalid @enderror"
                                   value="{{old('male')}}" placeholder="Male Attendance"
                                   required>
                            @error('male')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Female Attendance</label>
                            <input type="number" name="female"
                                   class="form-control @error('female') is-invalid @enderror"
                                   value="{{old('female')}}" placeholder="Female Attendance"
                                   required>
                            @error('female')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Visitors Attendance</label>
                            <input type="number" name="visitors"
                                   class="form-control @error('visitors') is-invalid @enderror"
                                   value="{{old('visitors')}}" placeholder="Visitors Attendance"
                                   required>
                            @error('visitors')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Date</label>
                            <input type="date" name="date"
                                   class="form-control @error('date') is-invalid @enderror"
                                   value="{{old('date')}}"
                                   required>
                            @error('church_id')
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
@section('scripts')
@endsection
