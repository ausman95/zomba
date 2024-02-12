@extends('layouts.app')


@section('content')
    <div class="container-fluid ps-1 pt-4">

        <h4>
            <i class="fa fa-users"></i>Users
        </h4>
        <p>
            Update user account
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                @if(request()->user()->designation=='administrator')
                <li class="breadcrumb-item"><a href="{{route('users.index')}}">Users</a></li>
                @endif
                <li class="breadcrumb-item"><a href="{{route('users.show',$user->id)}}">{{$user->first_name.' '.$user->last_name}}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Update</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-4">
                    <form action="{{route('users.update',$user->id)}}" method="POST" autocomplete="off">
                        @csrf
                        <input type="hidden" name="_method" value="PATCH">
                        <input type="hidden"  name="updated_by" value="{{request()->user()->id}}" required>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                   value="{{old('email') ?? $user->email}}"
                                   placeholder="Enter firstname"
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
                                   value="{{old('first_name') ?? $user->first_name}}"
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
                                   value="{{old('last_name') ?? $user->last_name}}"
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
                                   value="{{old('phone_number') ?? $user->phone_number}}"
                                   placeholder="Enter phone number"
                            >
                            @error('phone_number')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        @if(request()->user()->designation==='administrator')
                        <div class="form-group">
                            <label> Role </label>
                            <select name="designation"
                                    class="select-relation designation form-select @error('designation') is-invalid @enderror" style="width: 100%">
                                <option value="{{old('designation') ?? $user->designation}}">{{ucwords($user->designation)}}</option>
                                <option value="administrator">Administrator</option>
                                <option value="member">Member</option>
                                <option value="church">Home Church</option>
                            </select>
                            @error('designation')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                            <div class="members  d-none">
                                <div class="form-group">
                                    <label>Member</label>
                                    <select name="member_id"
                                            class="form-select select-relation @error('member_id') is-invalid @enderror" style="width: 100%">
                                        <option value="">-- Select ---</option>
                                        @foreach($members as $member)
                                            <option value="{{$member->id}}"
                                                {{old('member_id')===$member->id ? 'selected' : ''}}>{{$member->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('member_id')
                                    <span class="invalid-feedback">
                                   {{$message}}
                            </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="churches  d-none">
                                <div class="form-group">
                                    <label>Home Church</label>
                                    <select name="church_id"
                                            class="form-select select-relation @error('church_id') is-invalid @enderror" style="width: 100%">
                                        <option value="">-- Select ---</option>
                                        @foreach($churches as $church)
                                            <option value="{{$church->id}}"
                                                {{old('church_id')===$church->id ? 'selected' : ''}}>{{$church->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('church_id')
                                    <span class="invalid-feedback">
                                   {{$message}}
                            </span>
                                    @enderror
                                </div>
                            </div>
                            @if(request()->user()->level>2)
                            <div class="form-group">
                                <label> Level </label>
                                <select name="level"
                                        class="select-relation form-select @error('level') is-invalid @enderror" style="width: 100%">
                                    <option value="{{old('level') ?? $user->level}}">{{ucwords($user->level)}}</option>
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
                        @endif
                        <hr>
                        <div class="form-group ">
                            <button class="btn btn-md btn-primary rounded-0">
                                <i class="fa fa-user-edit"></i> Update
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
                if(designation==='member'){
                    $('.members').addClass('show').removeClass('d-none');
                    $('.churches').addClass('d-none').removeClass('show');
                }
                else if(designation==='church'){
                    $('.members').addClass('d-none').removeClass('show');
                    $('.churches').addClass('show').removeClass('d-none');
                }
                else{
                    $('.churches').addClass('d-none').removeClass('show');
                    $('.members').addClass('d-none').removeClass('show');
                }
            });
        });
    </script>
@endsection
