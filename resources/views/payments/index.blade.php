@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-money-bill-alt"></i>Payments
        </h4>
        <p>
            Manage Payments
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('finances.index')}}">Finances</a></li>
                <li class="breadcrumb-item active" aria-current="page">Payments</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-3">
            <a href="{{route('payments.create')}}" class="btn btn-primary btn-md rounded-0">
                <i class="fa fa-plus-circle"></i>New Payment
            </a>
            <a href="{{ route('bank-reconciliations') }}" class="btn btn-primary btn-md rounded-0">
                <i class="fa fa-money-check-alt"></i> Bank Reconciliations
            </a>
            <div class="mt-3">
                <div class="card container-fluid" style="min-height: 30em;">
                    <div class="row">
                        <div class="col-sm-12 mb-2 col-md-2 col-lg-2">
                            <hr>
                            {{-- EXISTING MONTH FILTER FORM --}}
                            <form action="{{route('receipt.generate')}}" method="POST" class="mb-4">
                                @csrf
                                <label class="form-label" for="month_id">Filter by Month</label>
                                <div class="form-group">
                                    <select name="month_id"
                                            class="form-select select-relation @error('month_id') is-invalid @enderror" style="width: 100%">
                                        <option value="">--Select Month--</option> {{-- Added default option --}}
                                        @foreach($months as $month)
                                            <option value="{{$month->id}}"
                                                {{ old('month_id', request('month_id')) == $month->id ? 'selected' : '' }}>{{$month->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('month_id')
                                    <span class="invalid-feedback">
                                       {{$message}}
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group mt-2">
                                    <button class="btn btn-primary rounded-0" type="submit">
                                        View By Month &rarr;
                                    </button>
                                </div>
                            </form>

                            <hr>

                            {{-- NEW START AND END DATE FILTER FORM --}}
                            <form action="{{ route('payments.index') }}" method="GET" class="mb-4">
                                <label class="form-label">Filter by Date Range</label>
                                <div class="form-group">
                                    <label for="start_date">Start Date</label>
                                    <input type="date" name="start_date" id="start_date"
                                           class="form-control @error('start_date') is-invalid @enderror"
                                           value="{{ old('start_date', request('start_date')) }}">
                                    @error('start_date')
                                    <span class="invalid-feedback">
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group mt-2">
                                    <label for="end_date">End Date</label>
                                    <input type="date" name="end_date" id="end_date"
                                           class="form-control @error('end_date') is-invalid @enderror"
                                           value="{{ old('end_date', request('end_date')) }}">
                                    @error('end_date')
                                    <span class="invalid-feedback">
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group mt-3">
                                    <button class="btn btn-primary rounded-0" type="submit">
                                        Apply Date Filter &rarr;
                                    </button>
                                    @if(request('start_date') || request('end_date') || request('month_id'))
                                        <a href="{{ route('payments.index') }}" class="btn btn-default rounded-0 mt-2">Clear Filters</a>
                                    @endif
                                </div>
                            </form>

                        </div>
                        <div class="col-sm-12 mb-2 col-md-11 col-lg-10">
                            <br>
                            <div class="card container-fluid" style="min-height: 30em;">
                                <div class="card-body px-1">
                                    {{-- DYNAMIC TITLE HERE (USING OPTION 2 from previous answer) --}}
                                    <h5 class="card-title text-center">
                                        Payments
                                        @if(request('month_id'))
                                            for {{ $months->firstWhere('id', request('month_id'))->name ?? '' }}
                                        @endif
                                        @if(request('start_date') && request('end_date'))
                                            (From {{ \Carbon\Carbon::parse(request('start_date'))->format('d F Y') }}
                                            to {{ \Carbon\Carbon::parse(request('end_date'))->format('d F Y') }})
                                        @elseif(request('start_date'))
                                            (From {{ \Carbon\Carbon::parse(request('start_date'))->format('d F Y') }} Onwards)
                                        @elseif(request('end_date'))
                                            (Up to {{ \Carbon\Carbon::parse(request('end_date'))->format('d F Y') }})
                                        @endif
                                    </h5>
                                    @if($payments->count() === 0)
                                        <i class="fa fa-info-circle"></i>There are no Payment!
                                    @else
                                        <div style="overflow-x:auto;">
                                            <table class="table table-bordered table-hover table-striped">
                                                <caption style=" caption-side: top; text-align: center; font-weight: bold; font-size: 1.2em;">
                                                    @php
                                                        $receiptTitlePart = 'Verified Receipts';
                                                        $receiptPeriod = '';

                                                        if (!empty($selectedBank)) {
                                                            $receiptTitlePart = $selectedBank->account_name . ' Verified Receipts';
                                                        }

                                                        if (!empty($currentMonth)) {
                                                            $receiptPeriod = ' for ' . $currentMonth;
                                                        } elseif (!empty($startDate) && !empty($endDate)) {
                                                            $receiptPeriod = ' from ' . \Carbon\Carbon::parse($startDate)->format('d F Y') . ' to ' . \Carbon\Carbon::parse($endDate)->format('d F Y');
                                                        } elseif (!empty($startDate)) {
                                                            $receiptPeriod = ' from ' . \Carbon\Carbon::parse($startDate)->format('d F Y') . ' onwards';
                                                        } elseif (!empty($endDate)) {
                                                            $receiptPeriod = ' up to ' . \Carbon\Carbon::parse($endDate)->format('d F Y');
                                                        }

                                                        echo $receiptTitlePart . $receiptPeriod;
                                                    @endphp
                                                </caption>
                                                <thead>
                                                <tr>
                                                    <th>NO</th>
                                                    <th>DATE</th>
                                                    <th>FOR</th>
                                                    <th>DESC</th>
                                                    <th>AMOUNT (MK)</th>
                                                    <th>ACCOUNT</th>
                                                    <th>BANK</th>
                                                    <th>REF</th>
                                                    <th>ACTION</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @php $c = $payments->firstItem(); @endphp
                                                @foreach($payments as $payment)
                                                    <tr>
                                                        <td>{{$c++}}</td>
                                                        <td>{{date('d F Y', strtotime($payment->t_date)) }}</td>
                                                        <td>{{ucwords(substr($payment->name,0,20)) }}</td>
                                                        <td>{{ucwords($payment->specification) }}</td>
                                                        <th>{{number_format(abs($payment->amount),2) }}</th>
                                                        <td>{{ucwords($payment->account->name) }}</td>
                                                        <td>
                                                            @if(!empty($payment->bank->account_name))
                                                                {{ $payment->bank->bank_name.' - '.$payment->bank->account_name }}
                                                            @else
                                                                OPENING TRANSACTION
                                                            @endif
                                                        </td>
                                                        <td>{{ucwords($payment->reference) }}</td>
                                                        <td class="pt-1">
                                                            <a href="{{route('payments.show',$payment->id)}}"
                                                               class="btn btn-primary btn-md rounded-0">
                                                                <i class="fa fa-list-ol"></i> Details
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                                <tfoot>
                                                <tr>
                                                    {{-- Sum of amount should be aligned under the AMOUNT column.
                                                         You need to span columns before it.
                                                         NO (1) + DATE (1) + FOR (1) + DESC (1) = 4 columns. --}}
                                                    <th colspan="4" class="text-end">Total Sum:</th>
                                                    <th>{{ number_format($payments->sum('amount'), 2) }}</th> {{-- Calculates sum of amounts for the current page --}}
                                                    {{-- Span remaining columns --}}
                                                    <th colspan="4"></th> {{-- ACCOUNT (1) + BANK (1) + REF (1) + ACTION (1) = 4 columns --}}
                                                </tr>
                                                </tfoot>
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
    </div>

@stop
