@extends('layouts.app')


@section('content')
    <div class="container-fluid ps-1 pt-4">

        <h4>
            <i class="fa fa-users"></i>Labourer allocation
        </h4>
        <p>
            Labourer allocation Details
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('hr.index')}}">Human Resources</a></li>
                <li class="breadcrumb-item"><a href="{{route('members.index')}}">Labourers</a></li>
                <li class="breadcrumb-item active" aria-current="page">Project Allocations</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-4">
                    <form action="{{route('allocations.update',$allocate->id)}}" method="POST" autocomplete="off">
                        @csrf
                        <input type="hidden" name="_method" value="PATCH">
                        <input type="hidden" name="labourer_id" value="{{$allocate->id}}">
                        <div class="form-group">
                            <label>Project</label>
                            <select name="project_id"
                                    class="form-select @error('project_id') is-invalid @enderror">
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
                        <div class="form-group">
                            <label>Amount Agreed</label>
                            <input type="number" name="amount"
                                   class="form-control @error('amount') is-invalid @enderror"
                                   value="{{old('name') ?? $labour->name}}"
                                   placeholder="Labour Cost">
                            @error('amount')
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
