@extends('layouts.app')


@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-users"></i>Users
        </h4>
        <p>
            Create user account
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('users.index')}}">Users</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create user</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-4">
                    <form action="{{route('users.store')}}" method="POST" autocomplete="off">
                        @csrf
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                   value="{{old('email')}}"
                                   placeholder="Enter Email"
                            >
                            @error('email')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>First Name</label>
                            <input type="text" name="first_name"
                                   class="form-control @error('first_name') is-invalid @enderror"
                                   value="{{old('first_name')}}"
                                   placeholder="Enter firstname"
                            >
                            @error('first_name')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Last Name</label>
                            <input type="text" name="last_name"
                                   class="form-control @error('last_name') is-invalid @enderror"
                                   value="{{old('last_name')}}"
                                   placeholder="Enter lastname"
                            >
                            @error('last_name')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="number" name="phone_number"
                                   class="form-control @error('phone_number') is-invalid @enderror"
                                   value="{{old('phone_number')}}"
                                   placeholder="Enter phone number"
                            >
                            @error('phone_number')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Company Position</label>
                            <input type="text" name="position"
                                   class="form-control @error('position') is-invalid @enderror"
                                   value="{{old('position')}}"
                                   placeholder="Enter Position"
                            >
                            @error('position')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label> Role </label>
                            <select name="designation"
                                    class="select-relation designation form-select @error('designation') is-invalid @enderror" style="width: 100%">
                                <option></option>
                                <option value="accountant">Accountant</option>
                                <option value="administrator">Administrator</option>
                                <option value="project">Project</option>
                                <option value="stores">Stores</option>
                                <option value="clerk">Clerk</option>
                                <option value="hr">Human Resource</option>
                                <option value="other">Other</option>
                            </select>
                            @error('designation')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        @if(request()->user()->level>2)
                        <div class="form-group">
                            <label> Level </label>
                            <select name="level"
                                    class="select-relation form-select @error('level') is-invalid @enderror" style="width: 100%">
                                <option value="0">Default</option>
                                <option value="1">Check</option>
                                <option value="2">Authorize</option>
                                <option value="3">FINAL (APPROVAL)</option>
                            </select>
                            @error('designation')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        @endif
                        <div class="departments  d-none">
                            <div class="form-group">
                                <label>Departments</label>
                                <select name="department_id"
                                        class="form-select select-relation @error('project_id') is-invalid @enderror" style="width: 100%">
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
                        </div>
                        <hr style="height: .3em;" class="border-theme">
                        <div class="form-group">
                            <label>Password</label>
                            <input type="text" name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   value="{{old('password')}}"
                                   placeholder="Enter secret password"
                            >
                            @error('password')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <hr>
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
@section('scripts')
    <script>
        $(document).ready(function () {
            $('.designation').on('change', function () {
                let designation = $(this).val();
                if(designation==='clerk'){
                    $('.departments').addClass('show').removeClass('d-none');
                }
                else{
                    $('.departments').addClass('d-none').removeClass('show');
                }
            });
        });
    </script>
@endsection
