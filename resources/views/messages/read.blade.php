@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-bell"></i>Notifications
        </h4>
        <p>
            Your notifications
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('notifications.index')}}">Notifications</a></li>
                <li class="breadcrumb-item active" aria-current="page">View</li>
                <li class="breadcrumb-item active" aria-current="page">{{$notification->data['subject']}}</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
            <div class="mt-2 row">
                <div class="col-sm-12 mb-2 col-md-4">
                    <div>
                        {{$notification->data['notification_type']}}
                    </div>
                    <div>
                        {{$notification->created_at->diffForHumans()}}
                    </div>
                    <div class="mt-4">
                        <div class="card">
                            <div class="card-body">
                                {{$notification->data['content']}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@stop

