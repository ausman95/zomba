@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('vendor/simple-datatable/simple-datatable.css') }}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="bx bxs-video"></i>&nbsp; Videos
        </h4>
        <p>
            Manage Videos
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('attendances.index') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">All Videos</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-3">
            <a href="{{ route('videos.create') }}" class="btn btn-primary btn-md rounded-0">
                <i class="fa fa-plus-circle"></i> Add New Video
            </a>
            <div class="mt-3">
                <div class="row">
                    <div class="col-sm-12 mb-2 col-md-12 col-lg-12">
                        <div class="card" style="min-height: 30em;">
                            <div class="card-body px-1">
                                @if($videos->count() === 0)
                                    <i class="fa fa-info-circle"></i> There are no videos available!
                                @else
                                    <div style="overflow-x:auto;">
                                        <table class="table table-bordered table-hover table-striped" id="data-table">
                                            <caption style="caption-side: top; text-align: center">VIDEOS</caption>
                                            <thead>
                                            <tr>
                                                <th>NO</th>
                                                <th>VIDEO TITLE</th>
                                                <th>VIDEO URL</th>
                                                <th>CREATED ON</th>
                                                <th>CREATED BY</th>
                                                <th>ACTION</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <?php $count = 1; ?>
                                            @foreach($videos as $video)
                                                <tr>
                                                    <td>{{ $count++ }}</td>
                                                    <td>{{ $video->title }}</td>
                                                    <td>
                                                        <a href="{{ $video->url }}" target="_blank">{{ Str::limit($video->url, 30) }}</a>
                                                    </td>
                                                    <td>{{ $video->created_at->format('d M, Y') }}</td>
                                                    <td>{{ $video->created_by }}</td>
                                                    <td class="pt-1">
                                                        <a href="{{ route('videos.show', $video->id) }}"
                                                           class="btn btn-primary btn-md rounded-0">
                                                            <i class="fa fa-eye"></i> View
                                                        </a>
                                                        <a href="{{ route('videos.edit', $video->id) }}"
                                                           class="btn btn-warning btn-md rounded-0">
                                                            <i class="fa fa-pencil-alt"></i> Edit
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
    <script src="{{ asset('vendor/simple-datatable/simple-datatable.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#data-table').DataTable();
        });
    </script>
@stop
