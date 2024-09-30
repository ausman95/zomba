@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-folder-open"></i> &nbsp; Current Assets
        </h4>
        <p>
            Current Assets
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('assets.index')}}">Assets</a></li>
                <li class="breadcrumb-item active" aria-current="page">Current Assets</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
            <div class="mt-3">
                <div class="row">
                    <div class="col-sm-12 mb-2 col-md-12 col-lg-8">
                        <div class="card " style="min-height: 30em;">
                            <div class="card-body px-1">
                                @php
                                    $payments = \App\Models\Payment::join('accounts', 'payments.account_id', '=', 'accounts.id')
                                        ->join('categories', 'accounts.category_id', '=', 'categories.id')
                                        ->select('accounts.*', \Illuminate\Support\Facades\DB::raw('SUM(payments.amount) as total_amount'))
                                        ->where('categories.status', 1)
                                        ->where('accounts.id','!=', 134)
                                         ->where('accounts.id','!=', 2)
                                        ->groupBy('accounts.id')
                                        ->get();
                                @endphp

                                @if($banks->isEmpty() && $suppliers->isEmpty() && $payments->isEmpty())
                                    <i class="fa fa-info-circle"></i>There are no Bank Accounts, Suppliers, or Payments!
                                @else
                                    <div style="overflow-x:auto;">
                                        <table class="table table-bordered table-hover table-striped" id="data-table">
                                            <caption style="caption-side: top; text-align: center">CURRENT ASSETS</caption>
                                            <thead>
                                            <tr>
                                                <th>NO</th>
                                                <th>NAME</th>
                                                <th>AMOUNT (MK)</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php $c = 1; @endphp

                                            {{-- Display Bank Accounts --}}
                                            @foreach($banks as $bank)
                                                @if($bank->getBalance() > 0)
                                                    <tr>
                                                        <td>{{ $c++ }}</td>
                                                        <td>{{ ucwords($bank->account_name) }}</td>
                                                        <td>{{ number_format($bank->getBalance(), 2) }}</td>
                                                    </tr>
                                                @endif
                                            @endforeach

                                            {{-- Display Suppliers --}}
                                            @foreach($suppliers as $supplier)
                                                @if($supplier->getBalance($supplier->id) > 0)
                                                    <tr>
                                                        <td>{{ $c++ }}</td>
                                                        <td>{{ $supplier->name }}</td>
                                                        <td>{{ number_format($supplier->getBalance($supplier->id), 2) }}</td>
                                                    </tr>
                                                @endif
                                            @endforeach

                                            {{-- Display Payments --}}
                                            @foreach($payments as $payment)
                                                @if($payment->total_amount > 0)
                                                    <tr>
                                                        <td>{{ $c++ }}</td>
                                                        <td>{{ ucwords($payment->name) }}</td>
                                                        <td>{{ number_format($payment->total_amount, 2) }}</td>
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

@stop
