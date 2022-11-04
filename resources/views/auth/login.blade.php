@extends('layouts.auth')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-12 col-md-6 col-lg-5 mt-5 px-lg-4 px-xl-5">
                <div class="text-center">
                <a href="{{url('/')}}" class=" text-decoration-none" style="font-size:20pt;color:orange">
                   Devine OASIS Church
                </a>
                    <p>
                        <i class="mt-2 text-blue" style="color:blue;font-size:14pt;">
                            Malawi Assemblies of God.
                        </i>
                    </p>

                </div>
                <div class="card auth-card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="form-group my-2">
                                <label for="email">{{ __('Email Address') }}</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                       name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email Address" autofocus>
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group my-2">
                                <label for="password">{{ __('Password') }}</label>
                                <input id="password" type="password" placeholder="Password"
                                       class="form-control @error('password') is-invalid @enderror" name="password"
                                       required autocomplete="current-password">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group my-2 mt-5 mb-0">
                                <button type="submit" class="btn btn-primary rounded-0">
                                    <i class="fa fa-sign-in-alt"></i>&nbsp;{{ __('Login') }}
                                </button>
                            </div>
                            <div class="form-group my-4">
                                @if (Route::has('password.request'))
                                    <a class="" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
