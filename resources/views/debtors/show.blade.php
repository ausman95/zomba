@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fas fa-user"></i> Debtor Details
        </h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('finances.index') }}">Finances</a></li>
                <li class="breadcrumb-item"><a href="{{ route('debtors.index') }}">Debtors</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$member->name }}</li>
            </ol>
        </nav>

        <div class="mb-5">
            <hr>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <h5>
                    <i class="fa fa-microscope"></i>Member Pledges
                </h5>
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div style="overflow-x:auto;">
                            <table class="table table-bordered table-striped" >
                                <caption style=" caption-side: top; text-align: center">MEMBER PLEDGES</caption>
                                <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>DATE</th>
                                    <th>TRANSACTION NAME</th>
                                    <th>AMOUNT (MK)</th>{{--                                        <th>PAYMENT TYPE</th>--}}

                                    <th>STATUS</th>
                                    <th>CREATED BY</th>
                                    <th>UPDATED BY</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php  $c = 1; $balance = 0;?>
                                @foreach($transactions as $transaction)
                                    @if($transaction->transaction_type==0)
                                        <tr>
                                            <td>{{$c++}}</td>
                                            <td>{{date('d F Y', strtotime($transaction->t_date)) }}</td>
                                            <td>{{ucwords($transaction->account->name) }}</td>
                                            <th>
                                                @if($transaction->amount< 0)
                                                    ({{number_format($transaction->amount*-1,2) }})
                                                @else
                                                    {{number_format($transaction->amount,2) }}
                                                @endif
                                            </th>
                                            {{--                                                <td>{{ucwords($transaction->amount < 0 ? "CR" : "DR") }}</td>--}}
                                            <th>{{ucwords($transaction->status == 1 ? "VERIFIED" : "UN~VERIFIED") }}</th>
                                            <td>{{\App\Models\Budget::userName($transaction->created_by)}}</td>
                                            <td>{{\App\Models\Budget::userName($transaction->updated_by)}}</td>
                                        </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <h5>
                    <i class="fa fa-microscope"></i>Member Pledges Payments
                </h5>
                <div class="card shadow-sm">
                    <div class="card-body">
                        @if($transactions->count() === 0)
                            <i class="fa fa-info-circle"></i>There are no Transactions!
                        @else
                            <div style="overflow-x:auto;">
                                <table class="table table-bordered table-striped">
                                    <caption style=" caption-side: top; text-align: center">MEMBER TRANSACTIONS</caption>
                                    <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>DATE</th>
                                        <th>TRANSACTION NAME</th>
                                        <th>AMOUNT (MK)</th>
                                        {{--                                        <th>PAYMENT TYPE</th>--}}
                                        <th>STATUS</th>
                                        <th>CREATED BY</th>
                                        <th>UPDATED BY</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php  $c = 1; $balance = 0;?>
                                    @foreach($transactions as $transaction)
                                        @if($transaction->transaction_type==2 && $transaction->pledge==2)
                                            <tr>
                                                <td>{{$c++}}</td>
                                                <td>{{date('d F Y', strtotime($transaction->t_date)) }}</td>
                                                <td>{{ucwords($transaction->account->name) }}</td>
                                                <th>
                                                    @if($transaction->amount< 0)
                                                        ({{number_format($transaction->amount*-1,2) }})
                                                    @else
                                                        {{number_format($transaction->amount,2) }}
                                                    @endif
                                                </th>
                                                {{--                                            <td>{{ucwords($transaction->amount < 0 ? "CR" : "DR") }}</td>--}}
                                                <th>{{ucwords($transaction->status == 1 ? "VERIFIED" : "UN~VERIFIED") }}</th>
                                                <td>{{\App\Models\Budget::userName($transaction->created_by)}}</td>
                                                <td>{{\App\Models\Budget::userName($transaction->updated_by)}}</td>
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
        </div>  {{-- End Row --}}

    </div>
@stop
