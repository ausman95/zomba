@extends('layouts.app')


@section('content')
    <div class="container-fluid ps-1 pt-4">

        <h4>
            <i class="bx bx-abacus"></i>Materials
        </h4>
        <p>
            Update Material Details
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('materials.index')}}">Materials</a></li>
                <li class="breadcrumb-item"><a href="{{route('materials.show',$material->id)}}">{{$material->name}}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Update</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-4">
                    <form action="{{route('materials.update',$material->id)}}" method="POST" autocomplete="off">
                        @csrf
                        <input type="hidden" name="_method" value="PATCH">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{old('name') ?? $material->name}}"
                                   placeholder="Supplier's name"
                            >
                            @error('name')
                            <span class="invalid-feedback">
                               {{$message}}
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Units of Measurement</label>
                            <input type="text" name="units"
                                   class="form-control @error('units') is-invalid @enderror"
                                   value="{{old('units') ?? $material->units}}"
                                   placeholder="Units of Measurement"
                            >
                            @error('units')
                            <span class="invalid-feedback">
                               {{$message}}
                            </span>
                            @enderror
                        </div>
                        <hr style="height: .3em;" class="border-theme">
                        <div class="form-group">
                            <label>Specifications</label>
                            <textarea name="specifications" rows="5"
                                      class="form-control @error('specifications') is-invalid @enderror">{{old('specifications') ?? $material->specifications}}</textarea>
                            @error('address')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <hr>
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
