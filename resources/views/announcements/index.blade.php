@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-speaker-deck"></i>Announcements
        </h4>
        <p>
            Manage Announcements
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Announcements</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-3">
            @if(request()->user()->designation=='administrator')
            <a href="{{route('announcements.create')}}" class="btn btn-primary btn-md rounded-0">
                <i class="fa fa-plus-circle"></i> New Announcement
            </a>
            @endif
            <div class="mt-3">
                <div class="row">
                    <div class="col-sm-12 mb-2 col-md-12 col-lg-12">
                        <div class="card " style="min-height: 30em;">
                            <div class="card-body px-1">
                                @if($announcements->count() === 0)
                                    <i class="fa fa-info-circle"></i>There are no announcements!
                                @else
                                    <div style="overflow-x:auto;">
                                        <table class="table table-bordered table-hover table-striped">
                                            <caption style=" caption-side: top; text-align: center">Announcements</caption>
                                            <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>FROM</th>
                                            <th>TITLE</th>
                                            <th>START DATE</th>
                                            <th>END DATE</th>
                                            <th>TITLE</th>
                                            <th>IMAGE</th>
                                            <th>ACTION</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php  $c= 1;?>
                                        @foreach($announcements as $announcement)
                                            <tr>
                                                <td>{{$c++}}</td>
                                                <td>{{ucwords($announcement->from) }}</td>
                                                <td>{{ucwords($announcement->title) }}</td>
                                                <td>{{date('d F Y', strtotime($announcement->start_date)) }}</td>
                                                <td>{{date('d F Y', strtotime($announcement->end_date)) }}</td>
                                                <td>{{ucwords($announcement->title) }}</td>
                                                <td>
                                                    <img id="preview" src="img/blog/{{$announcement->url}}" alt="" style="max-width: 100%; max-height: 200px;">
                                                </td>
                                                <td class="pt-1">
                                                    <a href="{{route('announcements.show',$announcement->id)}}"
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

