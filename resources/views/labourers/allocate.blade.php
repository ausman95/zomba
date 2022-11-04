@extends('layouts.app')


@section('content')
    <div class="container-fluid ps-1 pt-4">

        <h4>
            <i class="fa fa-users"></i>Labourers
        </h4>
        <p>
            Update Labourers account
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('human-resources.index')}}">Human Resources</a></li>
                <li class="breadcrumb-item"><a href="{{route('labourers.show',$_GET['id'])}}">{{$labourer->getName($_GET['id'])}}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Site Allocation</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-4">
                    <h4 style="color: red">Allocating {{$labourer->getName($_GET['id'])}}</h4>
                    <hr>
                    <form action="{{route('allocations.store')}}" method="POST" autocomplete="off">
                        @csrf
                        <input type="hidden" name="labourer_id" value="{{$_GET['id']}}">
                        <div class="form-group">
                            <label>Project</label>
                            <select name="project_id"
                                    class="form-select select-relation @error('project_id') is-invalid @enderror" style="width: 100%">
                                <option value="">-- Select ---</option>
                                @foreach($projects as $project)
                                    <option value="{{$project->id}}"
                                        {{old('project_id')===$project->id ? 'selected' : ''}}>{{$project->name}}</option>
                                @endforeach
                            </select>
                            @error('project_id')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        @if($labourer->getType($_GET['id'])==2)
                        <div class="form-group">
                            <label>Amount Agreed</label>
                            <input type="number" name="amount"
                                   class="form-control @error('amount') is-invalid @enderror"
                                   value="{{old('amount')}}"
                                   placeholder="Labour Cost">
                            @error('amount')
                            <span class="invalid-feedback">
                               {{$message}}
                            </span>
                            @enderror
                        </div>
                        @endif
                        <hr>
                        <div class="form-group ">
                            <button class="btn btn-md btn-primary rounded-0">
                                <i class="fa fa-edit"></i>Allocate
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
