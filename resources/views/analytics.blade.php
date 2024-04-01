@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-chart-bar"></i>&nbsp;Statements
        </h4>
        <p>
            Church Statements
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('finances.index')}}">Finances</a></li>
                <li class="breadcrumb-item active" aria-current="page">Statements</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-3">
            <div class="" style="margin-bottom: 10px">
                <a href="{{route('financial.statements')}}" class="btn btn-primary rounded-0" >
                    <i class="fa fa-file-archive"></i>  Financial Statements
                </a>
                <a href="{{route('church.reports')}}" class="btn btn-primary rounded-0" >
                    <i class="fa fa-list-ol"></i>  Accounts Reports
                </a>
                <a href="{{route('bank.statements')}}" class="btn btn-primary rounded-0" >
                    <i class="fa fa-list-ol"></i>  Bank Statements
                </a>
            </div>
            <div class="mt-3">
                <div class="row">
                    <div class="col-sm-12 mb-2 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-body px-1">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="card shadow-sm my-2">
                                            <div class="card-body">
                                                <h5>Financial Reports</h5>
                                                <div>
                                                    Total Reports Available
                                                </div>
                                                <div class="d-flex my-2 justify-content-between">
                                                    <h3 class="text-primary"> 3
                                                        <a href="{{route('financial.statements')}}"
                                                           class="btn btn-md btn-primary rounded-0">
                                                            Go &rarr;
                                                        </a></h3>
                                                    <div>
                                                        <i class="fa fa-file-archive fa-2x"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- ./ analytic -->
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="card shadow-sm my-2">
                                            <div class="card-body">
                                                <h5>Other Accounts Reports</h5>
                                                <div>
                                                    Total Reports Available
                                                </div>
                                                <div class="d-flex my-2 justify-content-between">
                                                    <h3 class="text-primary"> 1
                                                        <a href="{{route('church.reports')}}"
                                                           class="btn btn-md btn-primary rounded-0">
                                                            Go &rarr;
                                                        </a></h3>
                                                    <div>
                                                        <i class="fa fa-list-ol fa-2x"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- ./ analytic -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@stop
