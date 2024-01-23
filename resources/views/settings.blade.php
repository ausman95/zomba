@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-cog"></i>&nbsp;System Settings
        </h4>
        <p>
            Settings
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Settings</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-3">
            <div class="" style="margin-bottom: 10px">
                <a href="{{route('months.index')}}" class="btn btn-primary rounded-0" >
                    <i class="fa fa-list-ol"></i>  Months
                </a>
                <a href="{{route('ministries.index')}}" class="btn btn-primary rounded-0" >
                    <i class="fa fa-folder-plus"></i>  Ministries
                </a>
                <a href="{{route('services.index')}}" class="btn btn-primary rounded-0" >
                    <i class="fa fa-folder-plus"></i>  Services
                </a>
            </div>
            <div class="mt-3">
                <div class="row">
                    <div class="col-sm-12 mb-2 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-body px-1">
                                <div class="row">
{{--                                    <div class="col-sm-12">--}}
{{--                                        <div class="card shadow-sm my-2">--}}
{{--                                            <div class="card-body">--}}
{{--                                                <h5>Financial Reports</h5>--}}
{{--                                                <div>--}}
{{--                                                    Total Reports Available--}}
{{--                                                </div>--}}
{{--                                                <div class="d-flex my-2 justify-content-between">--}}
{{--                                                    <h3 class="text-primary"> 2--}}
{{--                                                        <a href="{{route('financial.statements')}}"--}}
{{--                                                           class="btn btn-md btn-primary rounded-0">--}}
{{--                                                            Go &rarr;--}}
{{--                                                        </a></h3>--}}
{{--                                                    <div>--}}
{{--                                                        <i class="fa fa-file-archive fa-2x"></i>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div><!-- ./ analytic -->--}}
{{--                                    </div>--}}
{{--                                    <div class="col-sm-12">--}}
{{--                                        <div class="card shadow-sm my-2">--}}
{{--                                            <div class="card-body">--}}
{{--                                                <h5>Other Accounts Reports</h5>--}}
{{--                                                <div>--}}
{{--                                                    Total Reports Available--}}
{{--                                                </div>--}}
{{--                                                <div class="d-flex my-2 justify-content-between">--}}
{{--                                                    <h3 class="text-primary"> 1--}}
{{--                                                        <a href="{{route('church.reports')}}"--}}
{{--                                                           class="btn btn-md btn-primary rounded-0">--}}
{{--                                                            Go &rarr;--}}
{{--                                                        </a></h3>--}}
{{--                                                    <div>--}}
{{--                                                        <i class="fa fa-list-ol fa-2x"></i>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div><!-- ./ analytic -->--}}
{{--                                    </div>--}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@stop
