@extends('layouts.app')


@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-list-ol"></i>Supplier Material Allocations
         </h4>
        <p>
            Create Supplier Material Allocation
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('suppliers.index')}}">Suppliers</a></li>
                <li class="breadcrumb-item"><a href="{{route('selections.index')}}">Supplier Material Allocations</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create Supplier Material</li>
            </ol>
        </nav>
        <hr>

        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-4">
                    <form action="{{route('selections.store')}}" method="POST" autocomplete="off">
                        @csrf
                        <div class="form-group">
                            <label>Supplier</label>
                            <select name="supplier_id"
                                    class="form-select select-relation @error('supplier_id') is-invalid @enderror" style="width: 100%">
                                <option value="">-- Select ---</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{$supplier->id}}"
                                        {{old('supplier_id')===$supplier->id ? 'selected' : ''}}>{{$supplier->name}}</option>
                                @endforeach
                            </select>
                            @error('supplier_id')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Materials</label>
                            <select name="material_id"
                                    class="form-select select-relation @error('material_id') is-invalid @enderror" style="width: 100%">
                                <option value="">-- Select ---</option>
                                @foreach($materials as $material)
                                    <option value="{{$material->id}}"
                                        {{old('material_id')===$material->id ? 'selected' : ''}}>{{$material->name.' OF '.$material->specifications}}</option>
                                @endforeach
                            </select>
                            @error('material_id')
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
