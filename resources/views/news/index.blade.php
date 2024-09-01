@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-list-ul"></i>Church News
        </h4>
        <p>
            Manage Church News
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Church News</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-3">
            @if(request()->user()->designation!='other' )
            <a href="{{route('news.determine')}}" class="btn btn-primary btn-md rounded-0">
                <i class="fa fa-plus-circle"></i>New Post
            </a>
            @endif
            <div class="mt-3">
                <div class="row">
                    <div class="col-sm-12 mb-2">
                        <div class="card " style="min-height: 30em;">
                            <div class="card-body px-1">
                                @if($news->count() === 0)
                                    <i class="fa fa-info-circle"></i>There are no News!
                                @else
                                    <div style="overflow-x:auto;">
                                        <table class="table table-bordered table-hover table-striped" id="data-table">
                                            <caption style=" caption-side: top; text-align: center">NEWS</caption>
                                            <thead>
                                            <tr>
                                                <th>NO</th>
                                                <th>TITLE</th>
                                                <th>PARAGRAPHS</th>
                                                <th>CREATED DATE</th>
                                                <th>CREATED BY</th>
                                                <th>UPDATED BY</th>
                                                @if(request()->user()->designation!='other' )
                                                <th>ACTION</th>
                                                @endif
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php  $c= 1;?>
                                            @foreach($news as $new)
                                                <tr>
                                                    <td>{{$c++}}</td>
                                                    <td>{{ucwords($new->title) }}</td>
                                                    <td>{{count($new->paragraph) }}</td>
                                                    <td>{{date('d F Y', strtotime($new->created_at)) }}</td>
                                                    <td>{{\App\Models\Budget::userName($new->created_by)}}</td>
                                                    <td>{{\App\Models\Budget::userName($new->updated_by)}}</td>
                                                    @if(request()->user()->designation!='other' )
                                                    <td class="pt-1">
                                                        <a href="{{route('news.show',$new->id)}}"
                                                           class="btn btn-primary btn-md rounded-0">
                                                            <i class="fa fa-list-ol"></i>  Details
                                                        </a>
                                                    </td>
                                                    @endif
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

