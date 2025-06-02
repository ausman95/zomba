@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-sitemap"></i> Positions {{-- Consistent icon for positions --}}
        </h4>
        <p>
            Update Position Details
        </p>
        <nav aria-label="breadcrumb"> {{-- Added aria-label for accessibility --}}
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                {{-- FIX: Changed 'labourers.index' to 'positions.index' for consistency --}}
                <li class="breadcrumb-item"><a href="{{route('members.index')}}">Members</a></li>
                <li class="breadcrumb-item"><a href="{{route('positions.index')}}">Positions</a></li>
                {{-- FIX: Changed 'labours.show' and $labour to 'positions.show' and $position --}}
                <li class="breadcrumb-item"><a href="{{route('positions.show',$position->id)}}">{{ucwords($position->name)}}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Update</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-4">
                    {{-- FIX: Changed 'labours.update' and $labour to 'positions.update' and $position --}}
                    <form action="{{route('positions.update',$position->id)}}" method="POST" autocomplete="off">
                        @csrf
                        @method('PATCH') {{-- Using @method directive for PATCH --}}
                        <div class="form-group mb-3"> {{-- Added mb-3 for consistent spacing --}}
                            <label for="name" class="form-label">Position Name<span class="text-danger">*</span></label> {{-- Added for, id, and required indicator --}}
                            <input type="text" name="name" id="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{old('name') ?? $position->name}}" {{-- old() ?? $position->name for sticky form --}}
                                   placeholder="e.g., Accountant, Senior Pastor, IT Manager"> {{-- Improved placeholder --}}
                            @error('name')
                            <span class="invalid-feedback" role="alert"> {{-- Added role="alert" for accessibility --}}
                                    <strong>{{$message}}</strong>
                                </span>
                            @enderror
                        </div>
                        <hr class="border-theme"> {{-- Use Bootstrap hr classes or ensure style is consistent --}}

                        <div class="form-group d-flex justify-content-between"> {{-- Use flex for button alignment --}}
                            <button type="submit" class="btn btn-primary btn-md rounded-0">
                                <i class="fa fa-edit"></i> Update Position {{-- More specific button text --}}
                            </button>
                            <a href="{{ route('positions.show', $position->id) }}" class="btn btn-secondary btn-md rounded-0"> {{-- Cancel button --}}
                                <i class="fa fa-times-circle"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
