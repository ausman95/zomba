@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-bell"></i>Messages
        </h4>
        <p>Your messages</p>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('messages.index')}}">Messages</a></li>
                <li class="breadcrumb-item active" aria-current="page">Unread</li>
            </ol>
        </nav>

        <!-- Create New Bulk SMS Button -->
        <div>
            <a href="{{route('messages.create')}}" class="btn btn-primary mb-3">
                <i class="fa fa-plus"></i> Create New Bulk SMS
            </a>
        </div>

        {{--        @if($messages->count() !== 0)--}}
        {{--            <a href="{{route('messages.mark-all-read')}}" class="btn btn-outline-danger">--}}
        {{--                Mark All as Read--}}
        {{--            </a>--}}
        {{--        @endif--}}

        <div class="mb-5">
            <hr>
            <div class="mt-2">
                @if($messages->count() === 0 )
                    Hooray! You do not have any notifications.
                @else
                    <div class="card">
                        <div class="list-group list-group-flush">
                            @foreach($messages as $notification)
                                <div class="list-group-item d-flex justify-content-between">
                                    <div class="d-flex">
                                        <div><i class="fa fa-bell"></i></div>
                                        <div>
                                            {{substr($notification->body,0,20)}}
                                            <a href="{{route('messages.read',$notification->id)}}" class="d-block text-black-50">
                                                {{$notification->body}}
                                            </a>
                                            <div class="text-black-50 mt-1">
                                                <i>{{$notification->created_at->diffForHumans()}}</i>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <a href="{{route('messages.read',$notification->id)}}">
                                            <i class="fa fa-envelope-open fa-2x"></i>
                                        </a>
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
