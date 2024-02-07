@extends('layouts.app')


@section('content')
    <div class="container-fluid ps-1 pt-4">

        <h4>
            <i class="fa fa-car"></i> &nbsp; Assets
        </h4>
        <p>
            Update Asset
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('assets.index')}}">Assets</a></li>
                <li class="breadcrumb-item"><a href="{{route('assets.show',$asset->id)}}">{{$asset->name}}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Update</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-4">
                    <form action="{{route('assets.update',$asset->id)}}" method="POST" autocomplete="off">
                        @csrf
                        <input type="hidden" name="_method" value="PATCH">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{old('name') ?? $asset->name}}"
                                   placeholder="Asset name">
                            @error('name')
                            <span class="invalid-feedback">
                               {{$message}}
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Serial / Reg Number</label>
                            <input type="text" name="serial_number"
                                   class="form-control @error('serial_number') is-invalid @enderror"
                                   value="{{old('serial_number') ?? $asset->serial_number}}"
                                   placeholder="Asset Serial Number">
                            @error('serial_number')
                            <span class="invalid-feedback">
                               {{$message}}
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Category</label>
                            <select name="category_id"
                                    class="form-select select-relation @error('category_id') is-invalid @enderror">
                                <option value="{{$asset->category_id}}">{{$asset->category->name}}</option>
                                <option value="">====Select Category====</option>
                                @foreach($categories as $category)
                                    <option value="{{$category->id}}"
                                        {{(old('category_id')==$category->id) ? 'selected' : ''}}>{{$category->name}}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Opening Balance</label>
                            <input type="text" name="cost"
                                   class="form-control @error('cost') is-invalid @enderror"
                                   value="{{old('cost') ?? $asset->cost}}"
                                   placeholder="Asset cost">
                            @error('cost')
                            <span class="invalid-feedback">
                               {{$message}}
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Quantity</label>
                            <input type="number" name="quantity"
                                   class="form-control @error('quantity') is-invalid @enderror"
                                   value="{{old('quantity')?? $asset->quantity}}"
                                   placeholder="Quantity" >
                            @error('quantity')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Depreciation %</label>
                            <input type="number" name="depreciation"
                                   class="form-control @error('life') is-invalid @enderror"
                                   value="{{old('depreciation') ?? $asset->depreciation}}"
                                   placeholder="Depreciation %" >
                            @error('depreciation')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label> Condition </label>
                            <select name="condition"
                                    class="form-select select-relation @error('condition') is-invalid @enderror" style="width: 100%">
                                <option value="{{$asset->condition}}">{{$asset->condition}}</option>
                                <option value="Good">Good</option>
                                <option value="Bad">Bad</option>
                            </select>
                            @error('condition')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Location</label>
                            <input type="text" name="location"
                                   class="form-control @error('life') is-invalid @enderror"
                                   value="{{old('location') ?? $asset->location}}"
                                   placeholder="Location of the Asset" >
                            @error('location')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Expected Life</label>
                            <input type="number" name="life" required
                                   class="form-control @error('life') is-invalid @enderror"
                                   value="{{old('life') ?? $asset->life}}"
                                   placeholder="Expected Life">
                            @error('life')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Date of Acquisition</label>
                            <input type="date" name="t_date" required
                                   class="form-control @error('t_date') is-invalid @enderror"
                                   value="{{old('t_date') ?? $asset->t_date}}"
                                   placeholder="Date of Acquisition">
                            @error('t_date')
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
