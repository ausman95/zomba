@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-wallet"></i> Financial Reports
        </h4>
        <p>
            Generate Financial Reports
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('finances.index')}}">Finances</a></li>
                <li class="breadcrumb-item"><a href="{{route('analytics')}}">Analytics</a></li>
                <li class="breadcrumb-item active" aria-current="page">Generate Financial Reports</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
            <p>
                Select report type, input start date and end date then click generate.
            </p>
            <div class="col-sm-12 mb-2 md-4">
                <p class="text-black-50">
                    Reports
                </p>

            </div>
            <div class="mt-4 row">
                <div class="col-sm-12 col-md-3  mb-2">
                    <form action="{{route('financial.generate')}}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Name</label>
                            <select name="statement" class="form-select select-relation @error('statement') is-invalid @enderror" style="width: 100%">
                                <option value="">-- Select report --</option>
                                <option value="1">Trial Balance </option>
                                <option value="2">Detailed Income Statement </option>
                                <option value="3">Summarized Income Statement </option>
                            </select>
                            @error('statement')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="row mb-3">
                            <label for="inputEmail3" class="col-sm-4 col-form-label">FROM</label>
                            <div class="col-sm-8">
                                <select name="start_date"
                                        class="form-select
                                        select-relation @error('start_date') is-invalid @enderror" style="width: 100%">
                                    @foreach($months as $month)
                                        <option value="{{$month->id}}"
                                            {{old('start_date')===$month->id ? 'selected' : ''}}>{{$month->name}}</option>
                                    @endforeach
                                </select>
                                @error('start_date')
                                <span class="invalid-feedback">
                               {{$message}}
                        </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputEmail3" class="col-sm-4 col-form-label">TO</label>
                            <div class="col-sm-8">
                                <select name="end_date"
                                        class="form-select select-relation
                                         @error('end_date') is-invalid @enderror" style="width: 100%">
                                    @foreach($months as $month)
                                        <option value="{{$month->id}}"
                                            {{old('end_date')===$month->id ? 'selected' : ''}}>{{$month->name}}</option>
                                    @endforeach
                                </select>
                                @error('end_date')
                                <span class="invalid-feedback">
                               {{$message}}
                        </span>
                                @enderror
                            </div>
                        </div>


                        <div class="form-group">
                            <button class="btn btn-primary rounded-0" type="submit">
                                <i class="fa fa-print"></i>  Generate
                            </button>
                        </div>
                    </form>
                </div>
                <div class="col-sm-12 mb-2 col-md-9">
                    <div class="card bg-light">
                        <div class="card-header">
                            Statement
                        </div>
                        @if(!@$statement)
                            <div class="card-body p-5" style="min-height: 20em;">
                                <div class="text-center">
                                    <div class="alert alert-danger">
                                        No statement available at the moment!.
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="ul list-group list-group-flush">
                                <div class="card " style="min-height: 30em;">
                                    <div class="card-body px-1">
                                        <div style="overflow-x:auto;">
                                            @if($statement==1)
                                                <table class="table table-bordered table-hover table-striped" id="data-table">

                                                    <caption style="caption-side:top">   Trial Balance Statement from {{date('d F Y', strtotime($start_date))}} To {{date('d F Y', strtotime($end_date))}}
                                                    </caption>
                                                    <thead>

                                                    <tr>
                                                        <th>NO</th>
                                                        <th>ACCOUNT</th>
                                                        <th>DEBIT (MK)</th>
                                                        <th>CREDIT (MK)</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $c = 1;
                                                        $b1 = 0;
                                                        $b2 = 0;
                                                        $b4 = 0;
                                                        $df = 0;
                                                        ?>
                                                    @foreach($accounts as $account)
                                                        <tr>
                                                            <td>{{$c++}}</td>
                                                            <td>{{ucwords($account->name) }}</td>
                                                            @if($account->type==2)
                                                                <td>{{number_format($account->getAccountBalance($start_date,$end_date),2)}}</td>
                                                                <p class="d-none">{{$b1 = $b1+$account->getAccountBalance($start_date,$end_date)}}</p>
                                                                <td>-</td>
                                                            @endif
                                                            @if($account->type==1)
                                                                <td>-</td>
                                                                <td>{{number_format($account->getAccountBalance($start_date,$end_date),2)}}</td>
                                                                <p class="d-none">{{$b2 = $b2+$account->getAccountBalance($start_date,$end_date)}}</p>
                                                            @endif
                                                        </tr>
                                                    @endforeach
                                                    <tr>
                                                        <td>{{$c++}}</td>
                                                        <td>Difference</td>
                                                        @if($b1>$b2)
                                                            <td>-</td>
                                                            <td>{{number_format($b1-$b2,2)}}</td>
                                                        @endif
                                                        @if($b2>$b1)
                                                            <td>{{number_format($b2-$b1,2)}}</td>
                                                            <td>-</td>
                                                        @endif
                                                        @if($b2==$b1)
                                                            <td>-</td>
                                                            <td>-</td>
                                                        @endif
                                                    </tr>
                                                    <tr>
                                                        <td>{{$c++}}</td>
                                                        <th>Totals</th>
                                                        @if($b1>=$b2)
                                                            <th>{{number_format($b1,2)}}</th>
                                                            <th>{{number_format($b1,2)}}</th>
                                                        @else
                                                            <th>{{number_format($b2,2)}}</th>
                                                            <th>{{number_format($b2,2)}}</th>
                                                        @endif
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            @elseif($statement==2)
                                                <table data-page-length='100'  class="table table-bordered table-hover table-striped" id="data-table">
                                                    <caption style="caption-side:top">    Detailed Income Statement from {{date('d F Y', strtotime($start_date))}} To {{date('d F Y', strtotime($end_date))}}
                                                    </caption>
                                                    <thead>
                                                    <tr>
                                                        <th>NO</th>
                                                        <th>ACCOUNT</th>
                                                        <th></th>
                                                        <th></th>
                                                        <th>ACTUAL (MK)</th>
                                                        <th>BUDGETED (MK)</th>
                                                        <th>% VARIANCE </th>
                                                        <th>VARIANCE (MK)</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $c = 1;
                                                        $b1 = 0;
                                                        $b2 = 0;
                                                        $b3 = 0;
                                                        $percent = 0;
                                                        $variance = 0;
                                                        $budget = 0;
                                                        $e_percent = 0;
                                                        $e_variance = 0;
                                                        $e_budget = 0;
                                                        ?>
                                                    <tr>
                                                        <td>{{$c++}}</td>
                                                        <th> <b>INCOME</b></th>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    @foreach($catCredits as $tuple)
                                                        <tr>
                                                            <td>{{$c++}}</td>
                                                            <td></td>
                                                            <th>{{ucwords($tuple->name) }}</th>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                        </tr>
                                                        @foreach($tuple->getAccountBalanceByAccountIdDebits($tuple->id,$start_date,$end_date) as $credit)
                                                            <tr>
                                                                <td>{{$c++}}</td>
                                                                <td></td>
                                                                <td></td>
                                                                <td>{{ucwords(@$credit->name) }}</td>
                                                                <th>{{number_format($credit->amount,2)}}</th>
                                                                <th>{{number_format($credit->getBudgetByAccountId(1,$credit->id,$start_date,$end_date),2)}}</th>
                                                                <th>
                                                                    @if($credit->getBudgetByAccountId(1,$credit->id,$start_date,$end_date)!=0)
                                                                        {{number_format((($credit->amount-$credit->getBudgetByAccountId(1,$credit->id,$start_date,$end_date))/$credit->getBudgetByAccountId(1,$credit->id,$start_date,$end_date)*100),2)}}
                                                                        {{--                                                                    <p class="d-none">{{$percent = $percent+(($credit->amount-$credit->getBudgetByAccountId(1,$credit->id,$start_date,$end_date))/$credit->getBudgetByAccountId($credit->id,$start_date,$end_date)*100)}}</p>--}}
                                                                    @else
                                                                        BUDGET NOT-SET
                                                                    @endif
                                                                </th>
                                                                <th>{{number_format($credit->amount-$credit->getBudgetByAccountId(1,$credit->id,$start_date,$end_date),2)}}</th>
                                                                <p class="d-none">{{$b1 = $b1+$credit->amount}}</p>

                                                            </tr>
                                                        @endforeach
                                                        <tr>
                                                            <td>{{$c++}}</td>
                                                            <td></td>
                                                            <td></td>
                                                            <th>SUB-TOTAL</th>
                                                            <th>{{number_format($tuple->amount,2)}}</th>
                                                            <th>{{number_format($credit->getBudgetByAccountId(2,$tuple->id,$start_date,$end_date),2)}}</th>
                                                            <th>
                                                                @if($credit->getBudgetByAccountId(2,$tuple->id,$start_date,$end_date)!=0)
                                                                    {{number_format((($tuple->amount-$credit->
                                                                            getBudgetByAccountId(2,$tuple->id,$start_date,$end_date))/$credit->
                                                                                getBudgetByAccountId(2,$tuple->id,$start_date,$end_date)*100),2)}}
                                                                    <p class="d-none">{{$percent = $percent+(($tuple->amount-$credit->
                                                                        getBudgetByAccountId(2,$tuple->id,$start_date,$end_date))/$credit->
                                                                            getBudgetByAccountId(2,$tuple->id,$start_date,$end_date)*100)}}</p>
                                                                @else
                                                                    BUDGET NOT-SET
                                                                @endif
                                                            </th>
                                                            <th>{{number_format($tuple->amount-$credit->getBudgetByAccountId(2,$tuple->id,$start_date,$end_date),2)}}</th>
                                                            <p class="d-none">{{$budget = $budget+$credit->getBudgetByAccountId(2,$tuple->id,$start_date,$end_date)}}</p>
                                                            <p class="d-none">{{$variance = $variance+$tuple->amount-$credit->getBudgetByAccountId(2,$tuple->id,$start_date,$end_date)}}</p>

                                                        </tr>
                                                    @endforeach
                                                    <tr>
                                                        <td>{{$c++}}</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{$c++}}</td>
                                                        <th><b>TOTAL INCOME</b></th>
                                                        <td></td>
                                                        <td></td>
                                                        <th><b>{{number_format($b1,2)}}</b> </th>
                                                        <th><b>{{number_format($budget,2)}}</b> </th>
                                                        <th><b>{{number_format($percent,2)}}</b> </th>
                                                        <th><b>{{number_format($variance,2)}}</b> </th>
                                                    </tr>
                                                    <tr>
                                                        <td>{{$c++}}</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{$c++}}</td>
                                                        <th> <b>EXPENDITURES</b></th>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    @foreach($catDebits as $tuple)
                                                        <tr>
                                                            <td>{{$c++}}</td>
                                                            <td></td>
                                                            <th>{{ucwords($tuple->name) }}</th>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                        </tr>
                                                        @foreach($tuple->getAccountBalanceByAccountIdDebits($tuple->id,$start_date,$end_date) as $credit)
                                                            <tr>
                                                                <td>{{$c++}}</td>
                                                                <td></td>
                                                                <td></td>
                                                                <td>{{ucwords(@$credit->name) }}</td>
                                                                <th>{{number_format($credit->amount,2)}}</th>
                                                                <th>{{number_format($credit->getBudgetByAccountId(1,$credit->id,$start_date,$end_date),2)}}</th>
                                                                <th>
                                                                    @if($credit->getBudgetByAccountId(1,$credit->id,$start_date,$end_date)!=0)
                                                                        {{number_format((($credit->
                                                                                            getBudgetByAccountId(1,$credit->id,$start_date,$end_date)-$credit->amount)/$credit->
                                                                                                    getBudgetByAccountId(1,$credit->id,$start_date,$end_date)*100),2)}}
                                                                    @else
                                                                        BUDGET NOT-SET
                                                                    @endif
                                                                </th>
                                                                <th>{{number_format($credit->getBudgetByAccountId(1,$credit->id,$start_date,$end_date)-$credit->amount,2)}}</th>
                                                                <p class="d-none">{{$b2 = $b2+$credit->amount}}</p>
                                                            </tr>
                                                        @endforeach
                                                        <tr>
                                                            <td>{{$c++}}</td>
                                                            <td></td>
                                                            <td></td>
                                                            <th>SUB-TOTAL</th>
                                                            <th>{{number_format($tuple->amount,2)}}</th>
                                                            <th>{{number_format($credit->getBudgetByAccountId(2,$tuple->id,$start_date,$end_date),2)}}</th>
                                                            <th>
                                                                @if($credit->getBudgetByAccountId(2,$tuple->id,$start_date,$end_date)!=0)
                                                                    {{number_format((($credit->
                                                                            getBudgetByAccountId(2,$tuple->id,$start_date,$end_date)-$tuple->amount)/$credit->
                                                                                getBudgetByAccountId(2,$tuple->id,$start_date,$end_date)*100),2)}}
                                                                    <p class="d-none">{{$e_percent = $e_percent+(($credit->
                                                                        getBudgetByAccountId(2,$tuple->id,$start_date,$end_date)-$tuple->amount)/$credit->
                                                                            getBudgetByAccountId(2,$tuple->id,$start_date,$end_date)*100)}}</p>
                                                                @else
                                                                    BUDGET NOT-SET
                                                                @endif
                                                            </th>
                                                            <th>{{number_format($credit->getBudgetByAccountId(2,$tuple->id,$start_date,$end_date)-$tuple->amount,2)}}</th>
                                                            <p class="d-none">{{$e_budget = $e_budget+$credit->getBudgetByAccountId(2,$tuple->id,$start_date,$end_date)}}</p>
                                                            <p class="d-none">{{$e_variance = $e_variance+$credit->getBudgetByAccountId(2,$tuple->id,$start_date,$end_date)-$tuple->amount}}</p>

                                                        </tr>
                                                    @endforeach
                                                    <tr>
                                                        <td>{{$c++}}</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td> </td>
                                                        <td> </td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{$c++}}</td>
                                                        <th><b>TOTAL EXPENDITURES</b></th>
                                                        <td></td>
                                                        <td></td>
                                                        <th><b>({{number_format($b2,2)}})</b> </th>
                                                        <th><b>{{number_format($e_budget,2)}}</b> </th>
                                                        <th><b>@if($e_budget!=0)
                                                                    {{number_format($e_variance/$e_budget*100,2)}}</b>
                                                            @else
                                                                NOT SET
                                                            @endif
                                                        </th>
                                                        <th><b>{{number_format($e_variance,2)}}</b> </th>
                                                    </tr>
                                                    <tr>
                                                        <td>{{$c++}}</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{$c++}}</td>
                                                        <th><b>SURPLUS/SHORTFALL</b></th>
                                                        <td></td>
                                                        <td></td>
                                                        <th style="text-underline: #0c0c0c"><u>{{number_format($b1-$b2,2)}}</u> </th>
                                                        <th><b>{{number_format($budget-$e_budget,2)}}</b> </th>
                                                        <th><b>
                                                                @if($budget-$e_budget!=0)
                                                                    {{number_format(($variance-$e_variance)/($budget-$e_budget)*100,2)}}
                                                                @else
                                                                    BUDGET NOT SET
                                                                @endif

                                                            </b> </th>
                                                        <th><b>{{number_format($variance-$e_variance,2)}}</b> </th>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            @elseif($statement==3)
                                                <table data-page-length='100' class="table table-javascript table-bordered table-hover table-striped" id="data-table">
                                                    <caption style="caption-side:top">                                           Summarized Income Statement from {{date('d F Y', strtotime($start_date))}} To {{date('d F Y', strtotime($end_date))}}
                                                    </caption>
                                                    <thead>
                                                    <tr>
                                                        <th>NO</th>
                                                        <th>ACCOUNT</th>
                                                        <th></th>
                                                        <th>ACTUAL (MK)</th>
                                                        <th>BUDGETED (MK)</th>
                                                        <th>% VARIANCE </th>
                                                        <th>VARIANCE (MK)</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $c = 1;
                                                        $b1 = 0;
                                                        $b2 = 0;
                                                        $b3 = 0;
                                                        $budget = 0;
                                                        $variance = 0;
                                                        $e_budget = 0;
                                                        $e_variance = 0;
                                                        $e_percent = 0;
                                                        $percent = 0;
                                                        ?>
                                                    <tr>
                                                        <td>{{$c++}}</td>
                                                        <th> <b> INCOME</b></th>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    @foreach($credits as $credit)
                                                        <tr>
                                                            <td>{{$c++}}</td>
                                                            <td></td>
                                                            <td>{{ucwords($credit->name) }}</td>
                                                            <th>{{number_format($credit->amount,2)}}</th>
                                                            <th>{{number_format($credit->getBudgetByAccountId(2,$credit->id,$start_date,$end_date),2)}}</th>
                                                            <th>
                                                                @if($credit->getBudgetByAccountId(2,$credit->id,$start_date,$end_date)!=0)
                                                                    {{number_format((($credit->amount-$credit->
                                                                            getBudgetByAccountId(2,$credit->id,$start_date,$end_date))/$credit->
                                                                                getBudgetByAccountId(2,$credit->id,$start_date,$end_date)*100),2)}}
                                                                    <p class="d-none">{{$percent = $percent+(($credit->amount-$credit->
                                                                        getBudgetByAccountId(2,$credit->id,$start_date,$end_date))/$credit->
                                                                            getBudgetByAccountId(2,$credit->id,$start_date,$end_date)*100)}}</p>
                                                                @else
                                                                    BUDGET NOT-SET
                                                                @endif
                                                            </th>
                                                            <th>{{number_format($credit->amount-$credit->getBudgetByAccountId(2,$credit->id,$start_date,$end_date),2)}}</th>
                                                            <p class="d-none">{{$b1 = $b1+$credit->amount}}</p>
                                                            <p class="d-none">{{$budget = $budget+$credit->getBudgetByAccountId(2,$credit->id,$start_date,$end_date)}}</p>
                                                            <p class="d-none">{{$variance = $variance+$credit->amount-$credit->getBudgetByAccountId(2,$credit->id,$start_date,$end_date)}}</p>
                                                        </tr>
                                                    @endforeach
                                                    <tr>
                                                        <td>{{$c++}}</td>
                                                        <th><b>TOTAL INCOME </b></th>
                                                        <td></td>
                                                        <th><b>{{number_format($b1,2)}}</b> </th>
                                                        <th><b>{{number_format($budget,2)}}</b> </th>
                                                        <th><b>{{number_format($percent*100,2)}}</b> </th>
                                                        <th><b>{{number_format($variance,2)}}</b> </th>
                                                    </tr>
                                                    <tr>
                                                        <td>{{$c++}}</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{$c++}}</td>
                                                        <th> <b>DIRECT COSTS</b></th>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    @foreach($admins as $credit)
                                                        <tr>
                                                            <td>{{$c++}}</td>
                                                            <td></td>
                                                            <td>{{ucwords($credit->name) }}</td>
                                                            <th>{{number_format($credit->amount,2)}}</th>
                                                            <p class="d-none">{{$b3 = $b3+$credit->amount}}</p>
                                                            <th>{{number_format($credit->getBudgetByAccountId(2,$credit->id,$start_date,$end_date),2)}}</th>
                                                            <th>
                                                                @if($credit->getBudgetByAccountId(2,$credit->id,$start_date,$end_date)!=0)
                                                                    {{number_format((($credit->
                                                                            getBudgetByAccountId(2,$credit->id,$start_date,$end_date)-$credit->amount)/$credit->
                                                                                getBudgetByAccountId(2,$credit->id,$start_date,$end_date)*100),2)}}
                                                                    <p class="d-none">{{$e_percent = $e_percent+(($credit->
                                                                        getBudgetByAccountId(2,$credit->id,$start_date,$end_date)-$credit->amount)/$credit->
                                                                            getBudgetByAccountId(2,$credit->id,$start_date,$end_date)*100)}}</p>
                                                                @else
                                                                    BUDGET NOT-SET
                                                                @endif
                                                            </th>
                                                            <th>{{number_format($credit->getBudgetByAccountId(2,$credit->id,$start_date,$end_date)-$credit->amount,2)}}</th>
                                                            <p class="d-none">{{$e_budget = $e_budget+$credit->getBudgetByAccountId(2,$credit->id,$start_date,$end_date)}}</p>
                                                            <p class="d-none">{{$e_variance = $e_variance+($credit->getBudgetByAccountId(2,$credit->id,$start_date,$end_date)-$credit->amount)}}</p>
                                                        </tr>
                                                    @endforeach
                                                    <tr>
                                                        <td>{{$c++}}</td>
                                                        <th><b>TOTAL OVERHEADS</b></th>
                                                        <td></td>
                                                        <th><b><u>({{number_format($b3,2)}})</u></b> </th>
                                                        <th><b><u>({{number_format($e_budget,2)}})</u></b> </th>
                                                        <th><b><u>({{number_format($e_percent,2)}})</u></b> </th>
                                                        <th><b><u>({{number_format($e_variance,2)}})</u></b> </th>
                                                    </tr>
                                                    <tr>
                                                        <td>{{$c++}}</td>
                                                        <th><b>SURPLUS/SHORTFALL</b></th>
                                                        <td></td>
                                                        <th><b>{{number_format($b1-$b3,2)}}</b> </th>
                                                        <th><b><u>({{number_format($budget-$e_budget,2)}})</u></b> </th>
                                                        <th>
                                                            @if($budget-$e_budget!=0)
                                                                ({{number_format(($variance-$e_variance)/($budget-$e_budget)*100,2)}})
                                                            @else
                                                                BUDGET NOT SET
                                                            @endif
                                                            <b><u>
                                                                </u></b> </th>
                                                        <th><b><u>({{number_format($variance-$e_variance,2)}})</u></b> </th>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>

@stop
@section('scripts')

@stop
