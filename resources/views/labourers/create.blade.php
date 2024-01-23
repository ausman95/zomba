@extends('layouts.app')


@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-users"></i>Employees
        </h4>
        <p>
            Create Employee
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('human-resources.index')}}">Human Resources</a></li>
                <li class="breadcrumb-item"><a href="{{route('labourers.index')}}">Employees</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create Employee</li>
            </ol>
        </nav>
        <hr>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-4">
            <form action="{{route('labourers.store')}}" method="POST" autocomplete="off">
                @csrf
                <div class="form-group">
                    <label>Name</label>
                    <input type="hidden" name="check" value="1">
                    <input type="text" name="name" required
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{old('name')}}"
                           placeholder="Employee name"
                    >
                    @error('name')
                    <span class="invalid-feedback">
                               {{$message}}
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Gender</label>
                    <select name="gender" required class="form-select select-relation @error('gender') is-invalid @enderror" style="width: 100%">{{old('gender')}}>
                        <option value="">-- Select ---</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                    @error('gender')
                    <span class="invalid-feedback">
                               {{$message}}
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" name="phone_number" required
                           class="form-control @error('phone_number') is-invalid @enderror"
                           value="{{old('phone_number')}}"
                           placeholder="Phone number"
                    >
                    @error('phone_number')
                    <span class="invalid-feedback">
                               {{$message}}
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Position</label>
                    <select name="labour_id" required
                            class="form-select select-relation
                            @error('labour_id') is-invalid @enderror" style="width: 100%">
                        <option value="">-- Select ---</option>
                        @foreach($labours as $labour)
                            <option value="{{$labour->id}}"
                                {{old('labour_id')===$labour->id ? 'selected' : ''}}>{{$labour->name}}</option>
                        @endforeach
                    </select>
                    @error('labour_id')
                    <span class="invalid-feedback">
                               {{$message}}
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Department</label>
                    <select name="department_id" required
                            class="form-select select-relation @error('department_id') is-invalid @enderror" style="width: 100%">
                        <option value="">-- Select ---</option>
                        @foreach($departments as $department)
                            <option value="{{$department->id}}"
                                {{old('department_id')===$department->id ? 'selected' : ''}}>{{$department->name}}</option>
                        @endforeach
                    </select>
                    <input type="hidden" name="type" value="1">
                    @error('department_id')
                    <span class="invalid-feedback">
                               {{$message}}
                        </span>
                    @enderror
                </div>
                <hr style="height: .3em;" class="border-theme">
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
