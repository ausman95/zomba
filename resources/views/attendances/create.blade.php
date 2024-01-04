@extends('layouts.app')


@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-list-ol"></i>Home Church Attendances
        </h4>
        <p>
            Manage Home Church Attendances
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('attendances.index')}}">Attendances</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create Home Church Attendance</li>
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
                            <label>Attendance</label>
                            <input type="number" name="attendance"
                                   class="form-control @error('attendance') is-invalid @enderror"
                                   value="{{old('attendance')}}" placeholder="Attendance"
                                   required>
                            @error('attendance')
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
                        @if(!request()->user()->church_id)
                            <div class="form-group">
                                <label>Church</label>
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
                        @else
                            <input type="hidden" name="church_id"
                                   class="form-control @error('church_id') is-invalid @enderror"
                                   value="{{request()->user()->church_id}}"
                                   required>
                        @endif
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
