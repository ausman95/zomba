@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="bx bxs-city "></i>&nbsp; Projects
        </h4>
        <p>
            Manage projects
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Projects</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-3">
            @if(request()->user()->designation==='project' || request()->user()->designation==='accountant' || request()->user()->designation==='administrator')
            <a href="{{route('projects.create')}}" class="btn btn-primary btn-md rounded-0">
                <i class="fa fa-plus-circle"></i>New Project
            </a>
            <a href="{{route('clients.index')}}" class="btn btn-primary btn-md rounded-0">
                <i class="bx bxs-city "></i>&nbsp; Clients
            </a>
                <a href="{{route('material-budgets.index')}}" class="btn btn-primary btn-md rounded-0">
                    <i class="bx bxs-file-archive "></i>&nbsp; Material Budgets
                </a>
            @endif

                <a href="{{route('notes.index')}}" class="btn btn-primary btn-md rounded-0">
                    <i class="fa fa-paperclip "></i>&nbsp; Notes
                </a>
            <div class="mt-3">
                <div class="row">
                    <div class="col-sm-12 mb-2 col-md-12 col-lg-12">
                        <div class="card " style="min-height: 30em;">
                            <div class="card-body px-1">
                                @if($projects->count() === 0)
                                    <i class="fa fa-info-circle"></i>There are no projects!
                                @else
                                    <div style="overflow-x:auto;">
                                        <table class="table table-bordered table-hover table-striped">
                                            <caption style=" caption-side: top; text-align: center">PROJECTS</caption>
                                            <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>NAME</th>
                                            <th>AMOUNT (MK)</th>
                                            <th>CLIENT</th>
                                            <th>SUPERVISOR</th>
                                            <th>START DATE</th>
                                            <th>END DATE</th>
                                            <th>PERIOD</th>
                                            <th>STATUS</th>
                                            <th>ACTION</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $c = 1;?>
                                        @foreach($projects as $project)
                                            <tr>
                                                <td>{{$c++}}</td>
                                                <td>{{$project->name}}</td>
                                                <td>{{number_format($project->amount)}}</td>
                                                <td>{{$project->client->name}}</td>
                                                <td>
                                                    @if($project->supervisor)
                                                        {{$project->supervisor->name}}
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td>{{$project->start_date}}</td>
                                                <td>{{$project->end_date}}</td>
                                                <td>{{$project->period}}</td>
                                                <td>
                                                    @if($project->status==1)
                                                        In-Progress
                                                    @elseif($project->status==2)
                                                        Finished
                                                    @elseif($project->status==3)
                                                        Abandoned
                                                    @elseif($project->status==0)
                                                        Pending
                                                    @endif
                                                </td>
                                                <td class="pt-1">
                                                    @if(request()->user()->designation==='hr' || request()->user()->designation==='project' || request()->user()->designation==='accountant' || request()->user()->designation==='administrator')
                                                    <a href="{{route('projects.show',$project->id)}}"
                                                       class="btn btn-primary btn-md rounded-0">
                                                        <i class="fa fa-list-ol"></i>   Manage
                                                    </a>
                                                    @endif
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
