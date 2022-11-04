@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <h4>
            <i class="fa fa-wrench"></i>&nbsp;Settings
        </h4>
        <p>
            Manage credentials and security settings
        </p>
        <div class="mt-4">
            <div class="row">
                <div class="col-sm-12 col-md-6 col-lg-5 mb-2">
                    <i class="fa fa-user-tag"></i>&nbsp;Credentials
                    <form action="{{route('settings.credentials')}}" method="POST">
                        @csrf
                        <input type="hidden" name="_method" value="PATCH">
                        <div class="form-group mt-3">
                            <label for="username" class="control-label">Username</label>
                            <input type="text" name="username" value="{{old('username') ?? $user->username}}"
                                   class="form-control @error('username') is-invalid @enderror">
                            @error('username')
                            <span class="invalid-feedback">
                                {{$message}}
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="email" class="control-label">Email</label>
                            <input type="email" name="email" value="{{old('email') ?? $user->email}}"
                                   class="form-control @error('email') is-invalid @enderror">
                            @error('email')
                            <span class="invalid-feedback">
                                {{$message}}
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="first_name" class="control-label">First name</label>
                            <input type="text" name="first_name" value="{{old('first_name') ?? $user->first_name}}"
                                   class="form-control @error('first_name') is-invalid @enderror">
                            @error('first_name')
                            <span class="invalid-feedback">
                                {{$message}}
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="last_name" class="control-label">Last name</label>
                            <input type="text" name="last_name" value="{{old('last_name') ?? $user->last_name}}"
                                   class="form-control @error('last_name') is-invalid @enderror">
                            @error('last_name')
                            <span class="invalid-feedback">
                                {{$message}}
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <button class="btn btn-success">
                                <i class="fa fa-save"></i>&nbsp;Save
                            </button>
                        </div>
                    </form>

                    <div class="mt-3">
                        <hr>
                        <i class="fa fa-key"></i>&nbsp;Security
                        <form action="{{route('settings.password')}}" method="POST">
                            @csrf
                            <input type="hidden" name="_method" value="PATCH">
                            <div class="form-group mt-2">
                                <label for="password" class="control-label">Password</label>
                                <input type="password" name="password" value=""
                                       class="form-control @error('password') is-invalid @enderror">
                                @error('password')
                                <span class="invalid-feedback">
                                {{$message}}
                            </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation" class="control-label">Password</label>
                                <input type="password" name="password_confirmation" value=""
                                       class="form-control @error('password_confirmation') is-invalid @enderror">
                                @error('password_confirmation')
                                <span class="invalid-feedback">
                                {{$message}}
                            </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <button class="btn btn-success">
                                    <i class="fa fa-save"></i>&nbsp;Update
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
