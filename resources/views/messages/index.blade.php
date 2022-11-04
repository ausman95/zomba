@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-bell"></i>Messages
        </h4>
        <p>
            Your Messages
        </p>
        <div>
            <a href="{{route('messages.unread')}}" class="btn btn-primary">
                Unread
            </a>
        </div>
        <div class="mb-5">
            <hr>
            <div class="mt-2">
                @if($messages->count() === 0 )
                    Hooray! You do not have any messages.
                @else
                    <div class="card">
                        <div class="list-group list-group-flush">
                            @foreach($messages as $notification)
                                <div class="list-group-item d-flex justify-content-between">
                                    <div class="d-flex">
                                        <div>
                                            <i class="fa fa-bell"></i>
                                        </div>
                                        <div>
                                            {{substr($notification->body,0,20)}}
                                            <a href="{{route('messages.read',$notification->id)}}"
                                               class="d-block text-black-50">
                                                {{$notification->body}}
                                            </a>
                                            <div class="text-black-50 mt-1 ">
                                                <i>
                                                    {{$notification->created_at->diffForHumans()}}
                                                </i>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="">
                                            <form action="{{route('messages.delete',$notification->id)}}" method="POST" id="delete-form">
                                                @csrf
                                                <input type="hidden" name="_method" value="DELETE">
                                            </form>
                                            <button class="btn btn-danger rounded-0" style="margin: 2px" id="delete-btn">
                                                <i class="fa fa-trash-alt fa-2x"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
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
                })
            }
            $("#delete-btn").on('click', function () {
                confirmationWindow("Confirm Deletion", "Are you sure you want to delete this record?", "Yes,Delete", function () {
                    $("#delete-form").submit();
                });
            });
        })
    </script>
@stop
