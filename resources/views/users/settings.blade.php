@extends('layouts.app')


@section('content')
    <div class="container ps-1 pt-4">
        <h4>
            <i class="fa fa-user-cog"></i>Settings
        </h4>
        <p>
            Update account settings
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                @if(request()->user()->designation==='administrator')
                    <li class="breadcrumb-item"><a href="{{route('users.index')}}">Users</a></li>
                @endif
                <li class="breadcrumb-item"><a href="{{route('users.show',$user->id)}}">{{$user->first_name.' '.$user->last_name}}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Settings</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="my-3">
                <p>
                    Hi, <strong> {{$user->name}} &lt;{{$user->email}}&gt;</strong>
                </p>
                <p>
                    You can update users passwords in the form below.
                </p>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-4">
                    <form action="{{route('settings.update')}}" method="POST" autocomplete="off">
                        @csrf
                        <input type="hidden" name="_method" value="PATCH">
                        <div class="form-group">
                            <input type="hidden" name="id"
                                   class="form-control @error('password') is-invalid @enderror"
                                   value="{{request()->user()->id}}">
                            <label> User </label>
                            @error('id')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password"
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
                        <div class="form-group">
                            <label>Confirm Password</label>
                            <input type="password" name="password_confirmation"
                                   class="form-control @error('password_confirmation') is-invalid @enderror"
                                   value="{{old('password_confirmation')}}"
                                   placeholder="Confirm secret password"
                            >
                            @error('password_confirmation')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <hr style="height: .3em;" class="border-theme">

                        <div class="form-group d-flex  ">
                            <button class="btn btn-md btn-primary rounded-0">
                                <i class="fa fa-user-edit"></i>Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@stop
