@extends('layouts.app')
@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-list-ul"></i>Budgets
        </h4>
        <p>
            Budgets
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('projects.index')}}">Projects</a></li>
                <li class="breadcrumb-item"><a href="{{route('material-budgets.index')}}">Material Budgets</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create Material Budget</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-4">
                    <form action="{{route('material-budgets.store')}}" method="POST" autocomplete="off">
                        @csrf
                        <div class="form-group">
                            <label> Material </label>
                            <select name="material_id"
                                    class="form-select select-relation @error('material_id') is-invalid @enderror" style="width: 100%">
                                <option value="">-- Select ---</option>
                                @foreach($materials as $material)
                                    <option value="{{$material->id}}"
                                        {{old('material_id')===$material->id ? 'selected' : ''}}>{{$material->name.' '.$material->specifications}}</option>
                                @endforeach
                            </select>
                            @error('material_id')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Department</label>
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
                        <div class="form-group">
                            <label>Quantity</label>
                            <input type="number" name="quantity"
                                   class="form-control @error('quantity') is-invalid @enderror"
                                   value="{{old('quantity')}}"
                                   placeholder="Quantity Allocated" >
                            @error('quantity')
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
