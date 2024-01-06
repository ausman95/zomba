@extends('layouts.app')


@section('content')
    <div class="container-fluid ps-1 pt-4">

        <h4>
            <i class="fa fa-users"></i>Employees
        </h4>
        <p>
            Update Employees account
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('human-resources.index')}}">Human Resources</a></li>
                <li class="breadcrumb-item"><a href="{{route('labourers.employees')}}">Employees</a></li>
                <li class="breadcrumb-item"><a href="{{route('labourers.show',$labourer->id)}}">{{$labourer->name}}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Update</li>

            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <form action="{{route('labourers.update',$labourer->id)}}" method="POST" autocomplete="off">
                    @csrf
                    <input type="hidden" name="_method" value="PATCH">
                    <input type="hidden" name="labourer_id" value="{{$labourer->id}}">
                    <div class="row">
                        <div class="col-sm-12 col-md-8 col-lg-4">
                            @csrf
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{$labourer->name}}"
                                       placeholder="labourer's name"
                                >
                                @error('name')
                                <span class="invalid-feedback">
                               {{$message}}
                        </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Gender</label>
                                <select name="gender"
                                        class="form-select select-relation @error('gender') is-invalid @enderror"
                                        style="width: 100%">{{old('gender')}}>
                                    <option value="{{$labourer->gender}}">{{$labourer->gender}}</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Customised">Customised</option>
                                </select>
                                @error('gender')
                                <span class="invalid-feedback">
                               {{$message}}
                        </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Phone Number</label>
                                <input type="text" name="phone_number"
                                       class="form-control @error('phone_number') is-invalid @enderror"
                                       value="{{$labourer->phone_number}}"
                                       placeholder="Phone number"
                                >
                                @error('phone_number')
                                <span class="invalid-feedback">
                               {{$message}}
                        </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Professional/Labour</label>
                                <select name="labour_id"
                                        class="form-select select-relation @error('labour_id') is-invalid @enderror"
                                        style="width: 100%">
                                    <option value="{{$labourer->labour_id}}">{{$labourer->labour->name}}</option>
                                    @foreach($labours as $labour)
                                        <option value="{{$labour->id}}"
                                            {{old('labour_id')===$labour->id ? 'selected' : ''}}>{{$labour->name}}</option>
                                    @endforeach
                                </select>
                                @error('client_id')
                                <span class="invalid-feedback">
                               {{$message}}
                        </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Department</label>
                                <select name="department_id"
                                        class="form-select select-relation @error('department_id') is-invalid @enderror"
                                        style="width: 100%">
                                    <option
                                        value="{{$labourer->department_id}}">{{$labourer->department->name}}</option>
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
                                <label>Labour Type</label>
                                <select name="type"
                                        class="form-select select-relation @error('type') is-invalid @enderror"
                                        style="width: 100%">{{old('type')}}>
                                    <option value="{{$labourer->type}}">
                                        @if($labourer->type==1)
                                            {{'Employed'}}
                                        @elseif($labourer->type==2)
                                            {{'Sub-Contractor'}}
                                        @elseif($labourer->type==3)
                                            {{'Temporary Workers'}}
                                        @endif
                                    </option>
                                    <option value="1">Employed</option>
                                    <option value="2">Sub-Contractor</option>
                                    <option value="3">Temporary Workers</option>
                                </select>
                                @error('type')
                                <span class="invalid-feedback">
                               {{$message}}
                        </span>
                                @enderror
                            </div>
                        </div>
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
@stop
