@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-chart-bar"></i>&nbsp;Analytics
        </h4>
        <p>
            Site Analytics
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Analytics</li>
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
            </div>
            <div class="mt-2">
                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <div class="card shadow-sm" style="min-height:30em;">
                            <div class="card-header bg-white">
                                <i class="fa fa-chart-pie"></i>&nbsp;Summary of Supplier's Statement
                            </div>
                            <div class="card-body bg-light">
                                @if($suppliers->count() === 0)
                                    <i class="fa fa-info-circle"></i>There are no  Suppliers!
                                @else
                                    <div style="overflow-x:auto;">
                                        <table class="table table-bordered table-primary table-hover table-striped" id="data-table">
                                            <thead>
                                            <tr>
                                                <th>NO</th>
                                                <th>NAME</th>
                                                <th>EMAIL</th>
                                                <th>AMOUNT(MK)</th>
                                                <th>ACTION</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php $c = 1;?>
                                            @foreach($suppliers as $supplier)
                                                <tr>
                                                    <td>{{$c++}}</td>
                                                    <td>{{$supplier->name}}</td>
                                                    <td>{{$supplier->email}}</td>
                                                    <td>
                                                        @if($supplier->getBalance($supplier->id)<0)
                                                       ( {{number_format($supplier->getBalance($supplier->id)*(-1))}})
                                                        @else
                                                         {{number_format($supplier->getBalance($supplier->id))}}
                                                        @endif
                                                    </td>
                                                    <td class="pt-1">
                                                        @if(request()->user()->designation==='accountant' || request()->user()->designation==='administrator')
                                                            <a href="{{route('suppliers.show',$supplier->id)}}"
                                                               class="btn btn-link btn-md rounded-0">
                                                                <i class="fa fa-list-ol"></i>   Details
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
                    <div class="col-sm-12 col-md-6">
                        <div class="card shadow-sm" style="min-height:30em;">
                            <div class="card-header bg-white">
                                <i class="fa fa-chart-line"></i>&nbsp;Summary of Material's Statement
                            </div>
                            <div class="card-body bg-light">
                                @if($materials->count() === 0)
                                    <i class="fa fa-info-circle"></i>There are no Materials!
                                @else
                                    <div style="overflow-x:auto;">
                                        <table class="table table-primary table-bordered table-hover table-striped" id="data-table">
                                            <thead>
                                            <tr>
                                                <th>NO</th>
                                                <th>NAME</th>
                                                <th>SPECIFICATIONS</th>
                                                <th>BALANCE (IN STORES)</th>
                                                <th>ACTION</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php $c = 1;?>
                                            @foreach($materials as $material)
                                                @if($material->getBalance2($material->id)<11)
                                                    <tr>
                                                        <td>{{$c++}}</td>
                                                        <td>{{ucwords($material->name) }}</td>
                                                        <td>{{ucwords($material->specifications)}}</td>
                                                        <td>{{$material->getBalance2($material->id)}}</td>
                                                        <td class="pt-1">
                                                            <a href="{{route('materials.show',$material->id)}}"
                                                               class="btn btn-link btn-md rounded-0">
                                                                <i class="fa fa-list-ol"></i>   Details
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endif
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
