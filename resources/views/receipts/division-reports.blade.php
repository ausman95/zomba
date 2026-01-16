@extends('layouts.app')
@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-cash-register"></i>Church Reports
        </h4>
        <p>
            Manage Church Reports
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('finances.index')}}">Finances</a></li>
                <li class="breadcrumb-item"><a href="{{route('analytics')}}">Analytics</a></li>
                <li class="breadcrumb-item active" aria-current="page">Accounts Reports</li>
            </ol>
        </nav>
        <hr>
        <div class="mt-3">
            <div class="card container-fluid" style="min-height: 30em;">
                <div class="row">
                    <div class="mb-2  col-lg-3">
                        <hr>
                        <form action="{{route('church-report.generate')}}" method="POST">
                            @csrf
                            <div class="row mb-3">
                                <label for="inputEmail3" class="col-sm-4 col-form-label">ACCOUNT</label>
                                <div class="col-sm-8">
                                    <select name="account_id"
                                            class="form-select select-relation @error('account_id') is-invalid @enderror" style="width: 100%">
                                        @foreach(\App\Models\Account::all() as $account)
                                            <option value="{{$account->id}}"
                                                {{old('account_id')===$account->id ? 'selected' : ''}}>{{$account->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('account_id')
                                    <span class="invalid-feedback">
                               {{$message}}
                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputEmail3" class="col-sm-4 col-form-label">FROM</label>
                                <div class="col-sm-8">
                                    <select name="from_month_id"
                                            class="form-select select-relation @error('month_id') is-invalid @enderror" style="width: 100%">
                                        @foreach($months as $month)
                                            <option value="{{$month->id}}"
                                                {{old('month')===$month->id ? 'selected' : ''}}>{{$month->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('month_id')
                                    <span class="invalid-feedback">
                               {{$message}}
                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputEmail3" class="col-sm-4 col-form-label">TO</label>
                                <div class="col-sm-8">
                                    <select name="to_month_id"
                                            class="form-select select-relation @error('month_id') is-invalid @enderror" style="width: 100%">
                                        @foreach($months as $month)
                                            <option value="{{$month->id}}"
                                                {{old('month')===$month->id ? 'selected' : ''}}>{{$month->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('month_id')
                                    <span class="invalid-feedback">
                               {{$message}}
                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputEmail3" class="col-sm-5 col-form-label"></label>
                                <div class="col-sm-7">
                                    <button class="btn btn-primary rounded-0" type="submit">
                                        Generate
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                    <div class="mb-2 col-lg-9">
                        <br>
                        <div class="card container-fluid" style="min-height: 30em;">
                            <div class="card-body px-1">
                                @if(!@$receipts)
                                    <div class="text-center">
                                        <div class="alert alert-danger">
                                            Select ACCOUNT, FROM and TO then click Generate.
                                        </div>
                                    </div>
                                @else
                                    <div class="ul list-group list-group-flush">


                                            <div style="overflow-x:auto;">
{{--                                                @if($description ==='1')--}}
{{--                                                    @if($receipts->count() === 0)--}}
{{--                                                        <div class="text-center">--}}
{{--                                                            <div class="alert alert-danger">--}}
{{--                                                                <i class="fa fa-info-circle"></i>There are no Transactions!--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                    @else--}}
{{--                                                    <table class="table table-bordered table-hover table-striped">--}}
{{--                                                        <caption style=" caption-side: top; text-align: center"> PERFORMANCE REPORT FROM {{strtoupper($to_month_name)}} TO {{strtoupper($from_month_name)}}</caption>--}}
{{--                                                        <thead>--}}
{{--                                                        <tr>--}}
{{--                                                            <th>NO</th>--}}
{{--                                                            <th>MEMBER</th>--}}
{{--                                                            <th>POSITION</th>--}}
{{--                                                            @foreach($getMonths as $month)--}}
{{--                                                                <th>{{$month->name}}--}}
{{--                                                                    <br>--}}
{{--                                                                    (MK)--}}
{{--                                                                </th>--}}
{{--                                                            @endforeach--}}
{{--                                                            <th>TOTAL--}}
{{--                                                                <br>--}}
{{--                                                                (MK)--}}
{{--                                                            </th>--}}
{{--                                                        </tr>--}}
{{--                                                        </thead>--}}
{{--                                                        <tbody>--}}
{{--                                                            <?php $c= 1; $totalSumForAllMonths = 0;?>--}}
{{--                                                        @foreach($churches as $church)--}}
{{--                                                            <tr>--}}
{{--                                                                <td>{{$c++}}</td>--}}
{{--                                                                <td>{{$church->pastor}}</td>--}}
{{--                                                                <td>{{$church->position}}</td>--}}
{{--                                                                    <?php $rowTotal = 0; ?>--}}
{{--                                                                @foreach($getMonths as $month)--}}
{{--                                                                    <td>--}}
{{--                                                                            <?php--}}
{{--                                                                            $amountForMonth = $church->getMemberAmount(--}}
{{--                                                                                $church->member_id,--}}
{{--                                                                                $month->start_date,--}}
{{--                                                                                $month->end_date,--}}
{{--                                                                                $church->account_id--}}
{{--                                                                            );--}}
{{--                                                                            echo number_format($amountForMonth, 2);--}}
{{--                                                                            $rowTotal += $amountForMonth;--}}
{{--                                                                            ?>--}}
{{--                                                                    </td>--}}
{{--                                                                @endforeach--}}
{{--                                                                <td>{{number_format($rowTotal, 2)}}</td>--}}
{{--                                                            </tr>--}}
{{--                                                                <?php $totalSumForAllMonths += $rowTotal; ?>--}}
{{--                                                        @endforeach--}}
{{--                                                        </tbody>--}}
{{--                                                        <tfoot>--}}
{{--                                                        <tr>--}}
{{--                                                            <td colspan="{{ 3 + count($getMonths) }}" style="text-align: right; font-weight: bold;">GRAND TOTAL (MK):</td>--}}
{{--                                                            <td style="font-weight: bold;">{{ number_format($totalSumForAllMonths, 2) }}</td>--}}
{{--                                                        </tr>--}}
{{--                                                        </tfoot>--}}
{{--                                                    </table>--}}
{{--                                                    @endif--}}
{{--                                                @elseif($description ==='2' )--}}
                                                    @if($payments->count() === 0)
                                                        <div class="text-center">
                                                            <div class="alert alert-danger">
                                                                <i class="fa fa-info-circle"></i>There are no Transactions!
                                                            </div>
                                                        </div>
                                                    @else
                                                    <table class="table table-bordered table-hover table-striped">
                                                        <caption style=" caption-side: top; text-align: center">Account Statement
                                                        </caption>
                                                        <thead>
                                                        <tr>
                                                            <th>NO</th>
                                                            <th>DATE</th>
                                                            <th>ACCOUNT</th>
                                                            <th>DESCRIPTION</th>
                                                            <th>AMOUNT (MK)</th>
                                                            <th>TYPE</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php  $c= 1; $balance = 0 ?>
                                                        @foreach($payments as $transaction)
                                                            <tr>
                                                                <td>{{$c++}}</td>
                                                                <td>{{date('d F Y', strtotime($transaction->t_date)) }}</td>
                                                                <td>{{ucwords($transaction->account->name) }}</td>
                                                                <td>{{ucwords($transaction->specification) }}</td>
                                                                <td>
                                                                    @if($transaction->type==1)
                                                                        @if($transaction->amount<0)
                                                                            ({{number_format($transaction->amount*-1)}})
                                                                        @else
                                                                            {{number_format($transaction->amount)}}
                                                                        @endif
                                                                    @elseif($transaction->type==2)
                                                                        ({{number_format($transaction->amount)}})
                                                                    @endif
                                                                </td>
                                                                <td>{{ucwords($transaction->type==1 ? "REVENUE" : "EXPENSE") }}</td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>

                                                @endif
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

