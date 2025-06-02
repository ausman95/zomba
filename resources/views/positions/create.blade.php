@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-sitemap"></i> Positions {{-- Changed icon for better semantic meaning related to hierarchy/structure --}}
        </h4>
        <p>
            Create a new position.
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('members.index')}}">Members</a></li>
                {{-- FIX: Changed 'labours.index' to 'positions.index' for consistency --}}
                <li class="breadcrumb-item"><a href="{{route('positions.index')}}">Positions</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create Position</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-4">
                    {{-- FIX: Changed 'labours.store' to 'positions.store' for consistency --}}
                    <form action="{{route('positions.store')}}" method="POST" autocomplete="off">
                        @csrf
                        <div class="form-group mb-3"> {{-- Added mb-3 for consistent spacing below the input --}}
                            <label for="name" class="form-label">Position Name<span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{old('name')}}"
                                   placeholder="e.g., Church Elder, Home Cell Leaders, Ministry Leaders, etc" >
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{$message}}</strong>
                                </span>
                            @enderror
                        </div>
                        <hr class="border-theme" style="height: .3em;"> {{-- Use Bootstrap hr classes or ensure style is consistent --}}

                        <div class="form-group d-flex justify-content-between"> {{-- Use flex for button alignment --}}
                            <button type="submit" class="btn btn-primary btn-md rounded-0">
                                <i class="fa fa-paper-plane"></i> Save Position
                            </button>
                            <a href="{{ url()->previous() }}" class="btn btn-secondary btn-md rounded-0"> {{-- Go back button --}}
                                <i class="fa fa-times-circle"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
