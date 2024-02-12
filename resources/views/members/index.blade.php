@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-users"></i>Church Members
        </h4>
        <p>
            Manage Members
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Members</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-3">
            @if(request()->user()->designation=='administrator')
                <a href="{{route('members.create')}}" class="btn btn-primary btn-md rounded-0">
                    <i class="fa fa-plus-circle"></i>New Member
                </a>
                <a href="{{route('member.merge')}}" class="btn btn-primary btn-md rounded-0">
                    <i class="fa fa-list-ol"></i>Merge Members
                </a>
            @endif
            <div class="card container-fluid" style="min-height: 30em;">
                <div class="row">
                    <div class="col-sm-12 mb-2 col-md-2 col-lg-2">
                        <hr>
                        <form action="{{route('member.reports')}}" method="POST">
                            @csrf
                            <div class="form-group">
                                <select name="ministry_id"
                                        class="form-select select-relation @error('ministry_id') is-invalid @enderror" style="width: 100%">
                                    <option value=""></option>
                                @foreach($ministries as $ministry)
                                        <option value="{{$ministry->id}}"
                                            {{old('ministry_id')===$ministry->id ? 'selected' : ''}}>{{$ministry->name}}</option>
                                    @endforeach
                                </select>
                                @error('ministry_id')
                                <span class="invalid-feedback">
                               {{$message}}
                        </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <select name="church_id"
                                        class="form-select select-relation @error('church_id') is-invalid @enderror" style="width: 100%">
                                    <option value=""></option>
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
                            <div class="form-group">
                                <select name="gender"
                                        class="form-select select-relation @error('gender') is-invalid @enderror" style="width: 100%">
                                    <option value=""></option>
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
                                <button class="btn btn-primary rounded-0" type="submit">
                                    View &rarr;
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="col-sm-12 mb-2 col-md-11 col-lg-10">
                        <br>
                        <div class="card " style="min-height: 30em;">
                            <div class="card-body px-1">
                                @if($members->count() === 0)
                                    <i class="fa fa-info-circle"></i>There are no  Member!
                                @else
                                    <div style="overflow-x:auto;">
                                        <table class="table  table-bordered table-hover table-striped" id="data-table">
                                            <caption style=" caption-side: top; text-align: center">Members</caption>
                                            <thead>
                                            <tr>
                                                <th>NO</th>
                                                <th>NAME</th>
                                                <th>GENDER</th>
                                                <th>HOME CHURCH</th>
                                                <th>PHONE</th>
                                                <th>STATUS</th>
{{--                                                @if(@!$report)--}}
                                                <th>CREATED BY</th>
                                                <th>UPDATED BY</th>
                                                <th>ACTION</th>
{{--                                                @endif--}}
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <?php $c = 1;?>
                                            @foreach($members as $member)
                                                <tr>
                                                    <td>{{$c++}}</td>
                                                    <td>{{$member->name}}</td>
                                                    <td>{{$member->gender}}</td>
                                                    <td>{{$member->church->name}}</td>
                                                    <td>{{$member->phone_number}}</td>
                                                    <td>
                                                        @if($member->status==1)
                                                            {{'ACTIVE'}}
                                                        @elseif($member->status==2)
                                                            {{'MOVED'}}
                                                        @else
                                                            {{'DECEASED'}}
                                                        @endif
                                                    </td>
                                                    <td>{{\App\Models\Budget::userName($member->created_by)}}</td>
                                                    <td>{{\App\Models\Budget::userName($member->updated_by)}}</td>
{{--                                                    @if(@!$report)--}}
                                                    <td class="pt-1">
                                                        {{--                                                    @if(request()->user()->member_id==$member->id)--}}
                                                        <a href="{{route('members.show',$member->id)}}"
                                                           class="btn btn-primary btn-md rounded-0">
                                                            <i class="fa fa-list-ol"></i>  Manage
                                                        </a>
                                                        {{--                                                    @endif--}}
                                                    </td>
{{--                                                    @endif--}}
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@section('scripts')
    <script src="{{asset('vendor/simple-datatable/simple-datatable.js')}}"></script>
    <script>
        function confirmationWindow(title, message, primaryLabel, callback) {
            Swal.fire({
                title: title,
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: primaryLabel
            }).then((result) => {
                if (result.isConfirmed) {
                    callback();
                }
            })
        }


        $(document).ready(function () {
            $(".delete-btn").on('click', function () {
                $url = $(this).attr('data-target-url');

                $("#delete-form").attr('action', $url);
                confirmationWindow("Confirm Deletion", "Are you sure you want to delete this position?", "Yes,Delete", function () {
                    $("#delete-form").submit();
                })
            });
        })
    </script>
@stop

