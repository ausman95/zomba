@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-speaker-deck"></i>Home Cell Programs
        </h4>
        <p>
            Manage Programs
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Programs</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-3">
            @if(request()->user()->designation=='church')
            <a href="{{route('programs.create')}}" class="btn btn-primary btn-md rounded-0">
                <i class="fa fa-plus-circle"></i> New Program
            </a>
            @endif
            <div class="mt-3">
                <div class="row">
                    <div class="col-sm-12 col-md-2 mb-2">
                        <form action="{{route('program.generate')}}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Month</label>
                                <select name="month_id"
                                        class="form-select select-relation @error('month_id') is-invalid @enderror" style="width: 100%">
                                    @foreach($months as $month)
                                        <option value="{{$month->id}}"
                                            {{old('month')===$month->id ? 'selected' : ''}}>{{$month->name}}</option>
                                    @endforeach
                                </select>
                                @error('month_id')
                                <span class="invalid-feedback">
                               {{$message}}
                        </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary rounded-0" type="submit">
                                    <i class="fa fa-print"></i>  Generate
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="col-sm-12 mb-2 col-md-10">
                        <div class="card " style="min-height: 30em;">
                            <div class="card-body px-1">
                                @if($programs->count() === 0)
                                    <i class="fa fa-info-circle"></i>There are no programs!
                                @else
                                    <div style="overflow-x:auto;">
                                        <table class="table table-bordered table-hover table-striped">
                                            <caption style=" caption-side: top; text-align: center">Programs</caption>
                                            <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>DATE</th>
                                            <th>HOME CELL</th>
                                            <th>VENUE</th>
                                            <th>FACILITATOR</th>
                                            <th>PREACHER</th>
                                            <th>ACTION</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php  $c= 1;?>
                                        @foreach($programs as $program)
                                            <tr>
                                                <td>{{$c++}}</td>
                                                <td>{{date('d F Y', strtotime($program->t_date))}}</td>
                                                <td>{{ucwords($program->church->name) }}</td>
                                                <td>{{ucwords($program->venue) }}</td>
                                                <td>{{ucwords($program->member->name) }}</td>
                                                <td>{{ucwords($program->members->name) }}</td>
                                                <td class="pt-1">
                                                    <a href="{{route('programs.show',$program->id)}}"
                                                       class="btn btn-primary btn-md rounded-0">
                                                       <i class="fa fa-list-ol"></i> Manage
                                                    </a>
                                                </td>
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

