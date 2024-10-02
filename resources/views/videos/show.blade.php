@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-video"></i>&nbsp; Videos
        </h4>
        <p>
            Manage video details
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('attendances.index') }}">Videos</a></li>
                <li class="breadcrumb-item"><a href="{{ route('videos.index') }}">Videos</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $video->title }}</li>
            </ol>
        </nav>

        <div class="mb-5">
            <hr>
        </div>

        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 col-md-6 col-lg-6">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped" id="data-table">
                                    <caption style="caption-side: top; text-align: center">{{ $video->title }} INFORMATION</caption>
                                    <tbody>
                                    <tr>
                                        <td>Video Title</td>
                                        <td>{{ $video->title }}</td>
                                    </tr>
                                    <tr>
                                        <td>YouTube URL</td>
                                        <td><a href="{{ $video->url }}" target="_blank">{{ $video->url }}</a></td>
                                    </tr>
                                    <tr>
                                        <td>Uploaded On</td>
                                        <td>{{ $video->created_at->format('d M, Y') }}</td>
                                    </tr>
                                    </tbody>
                                </table>

                                <div class="mt-3">
                                    <a href="{{ route('videos.edit', $video->id) }}"
                                       class="btn btn-primary rounded-0" style="margin: 2px">
                                        <i class="fa fa-edit"></i> Update
                                    </a>

                                    @if(request()->user()->designation === 'administrator')
                                        <form action="{{ route('videos.destroy', $video->id) }}" method="POST" id="delete-form">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <button class="btn btn-danger rounded-0" style="margin: 2px" id="delete-btn">
                                            <i class="fa fa-trash"></i> Delete
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script>
        $(document).ready(function () {
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
                });
            }

            $("#delete-btn").on('click', function () {
                confirmationWindow("Confirm Deletion", "Are you sure you want to delete this video?", "Yes, Delete", function () {
                    $("#delete-form").submit();
                });
            });
        });
    </script>
@stop
