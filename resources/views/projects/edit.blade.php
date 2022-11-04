@extends('layouts.app')


@section('content')
    <div class="container-fluid ps-1 pt-4">

        <h4>
            <i class="bx bxs-city "></i>&nbsp; Projects
        </h4>
        <p>
            Update project details
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('projects.index')}}">Projects</a></li>
                <li class="breadcrumb-item"><a href="{{route('projects.show',$project->id)}}">{{$project->name}}</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Update</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <form action="{{route('projects.update',$project->id)}}" method="POST" autocomplete="off">
                @csrf
                <input type="hidden" name="_method" value="PATCH">
                <div class="row">
                    <div class="col-sm-12 col-md-8 col-lg-4">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{old('name') ?? $project->name}}"
                                   placeholder="Project name"
                            >
                            @error('name')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Amount</label>
                            <input type="text" name="amount"
                                   class="form-control @error('amount') is-invalid @enderror"
                                   value="{{old('amount') ?? $project->amount}}"
                                   placeholder="Project amount"
                            >
                            @error('name')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Start Date</label>
                            <input type="date" name="start_date"
                                   class="form-control @error('start_date') is-invalid @enderror"
                                   value="{{old('start_date') ?? $project->start_date}}"
                                   placeholder="Enter project start date"
                            >
                            @error('start_date')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>End Date</label>
                            <input type="date" name="end_date"
                                   class="form-control @error('end_date') is-invalid @enderror"
                                   value="{{old('end_date') ?? $project->end_date}}"
                                   placeholder="Enter project end date"
                            >
                            @error('end_date')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Location</label>
                            <input type="text" name="location"
                                   class="form-control @error('location') is-invalid @enderror"
                                   value="{{old('location') ?? $project->location}}"
                                   placeholder="Enter location i.e Lilongwe"
                            >
                            @error('location')
                            <span class="invalid-feedback">
                               {{$message}}
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status"
                                    class="form-select select-relation @error('status') is-invalid @enderror" style="width: 100%">
                                <option value="{{$project->status}}">
                                    @if($project->status==0)
                                        {{'Pending'}}
                                        @elseif($project->status==1)
                                    {{'In-Progress'}}
                                    @elseif($project->status==2)
                                    {{'Finished'}}
                                    @elseif($project->status==3)
                                    {{'Abandoned'}}
                                        @endif
                                </option>
                                <option value="0">Pending</option>
                                <option value="1">In-Progress</option>
                                <option value="2">Finished</option>
                                <option value="3">Abandoned</option>
                            </select>
                            @error('status')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-4 col-lg-3">
                        <div class="form-group">
                            <label>Client</label>
                            <select name="client_id"
                                    class="form-select select-relation @error('client_id') is-invalid @enderror" style="width: 100%">
                                <option value="">-- Select Client ---</option>
                                @foreach($clients as $client)
                                    <option value="{{$client->id}}"
                                        {{(old('client_id')==$client->id || $project->client->id==$client->id) ? 'selected' : ''}}>{{$client->name}}</option>
                                @endforeach
                            </select>
                            @error('client_id')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Supervisor</label>
                            <select name="supervisor_id"
                                    class="form-select select-relation @error('supervisor_id') is-invalid @enderror" style="width: 100%">
                                <option value="">-- Select User ---</option>
                                @foreach($labourers as $labourer)
                                    <option value="{{$labourer->id}}"
                                        {{(old('supervisor_id')===$labourer->id || $project->supervisor_id == $labourer->id) ? 'selected' : ''}}>{{$labourer->name}}</option>
                                @endforeach
                            </select>
                            @error('supervisor_id')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <hr style="height: .3em;" class="border-theme">
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" rows="5"
                                      class="form-control @error('description') is-invalid @enderror">{{old('description')??$project->description}}</textarea>
                            @error('description')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                    </div>
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
@stop
