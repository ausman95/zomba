@extends('layouts.app')


@section('content')
    <div class="container-fluid ps-1 pt-4">

        <h4>
            <i class="fa fa-users"></i>Contracts
        </h4>
        <p>
            Update Contracts account
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('human-resources.index')}}">Human Resources</a></li>
                <li class="breadcrumb-item"><a href="{{route('human-resources.index')}}">Human Resources</a></li>
                <li class="breadcrumb-item"><a href="{{route('contracts.index')}}">Contracts</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$contract->name}}</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-4">
                    <form action="{{route('contracts.update',$contract->id)}}" method="POST" autocomplete="off">
                        @csrf
                        <input type="hidden" name="_method" value="PATCH">
                        <div class="form-group">
                            <label> Employee </label>
                            <select name="labourer_id"
                                    class="form-select select-relation @error('labourer_id') is-invalid @enderror"
                                    style="width: 100%">
                                <option value="{{$contract->labourer->id}}">{{$contract->labourer->name}}</option>
                                @foreach($labourers as $labourer)
                                    <option value="{{$labourer->id}}"
                                        {{old('labourer_id')===$labourer->id ? 'selected' : ''}}>{{$labourer->name}}</option>
                                @endforeach
                            </select>
                            @error('labourer_id')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label> Contract Type</label>
                            <select name="contract_type_id"
                                    class="form-select select-relation @error('contract_type_id') is-invalid @enderror"
                                    style="width: 100%">
                                <option
                                    value="{{$contract->contractType->id}}">{{$contract->contractType->name}}</option>
                                @foreach($contract_types as $contract_type)
                                    <option value="{{$contract_type->id}}"
                                        {{old('contract_type_id')===$contract_type->id ? 'selected' : ''}}>{{$contract_type->name}}</option>
                                @endforeach
                            </select>
                            @error('contract_type_id')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Salary / Month</label>
                            <input type="number" name="salary"
                                   class="form-control @error('salary') is-invalid @enderror"
                                   value="{{old('salary')?? $contract->salary}}"
                                   placeholder="Salary">
                            @error('salary')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Start date</label>
                            <input type="date" name="start_date"
                                   class="form-control @error('start_date') is-invalid @enderror"
                                   value="{{old('start_date') ?? $contract->start_date}}"
                                   placeholder="Start Date">
                            @error('start_date')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Contract Due</label>
                            <input type="date" name="end_date"
                                   class="form-control @error('end_date') is-invalid @enderror"
                                   value="{{old('end_date') ?? $contract->end_date}}"
                                   placeholder="Contract Due">
                            @error('end_date')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label> Contract Status</label>
                            <select name="status"
                                    class="form-select select-relation @error('contract_type_id') is-invalid @enderror"
                                    style="width: 100%">
                                <option value="{{$contract->status}}">{{$contract->status}}</option>
                                <option value="TERMINATED">TERMINATED</option>
                                <option value="ACTIVE">ACTIVE</option>
                            </select>
                            @error('contract_type_id')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <hr style="height: .3em;" class="border-theme">
                        <div class="form-group">
                            <button class="btn btn-md btn-primary rounded-0">
                                <i class="fa fa-user-edit"></i>Edit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
