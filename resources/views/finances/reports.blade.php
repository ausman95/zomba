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
                                <option value="2">Income Statement </option>
                            </select>
                            @error('statement')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Start Date</label>
                            <input type="date" name="start_date"
                                   class="form-control @error('start_date') is-invalid @enderror"
                                   value="{{old('start_date')}}"
                                   placeholder="Start Date" >
                            @error('start_date')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>End Date</label>
                            <input type="date" name="end_date"
                                   class="form-control @error('end_date') is-invalid @enderror"
                                   value="{{old('end_date')}}"
                                   placeholder="End Date" >
                            @error('end_date')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
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
                                   <u><h4 style="text-align: center">
                                        @if($statement==1)
                                            Trial Balance Statement from {{$start_date}} to {{$end_date}}
                                        @elseif($statement==2)
                                            Income Statement from {{$start_date}} to {{$end_date}}
                                        @else
                                        Balance Sheet from {{$start_date}} to {{$end_date}}
                                        @endif
                                    </h4>
                                   </u>
                                    @if($statement==1)
                                    <table class="table table-primary table-bordered table-hover table-striped" id="data-table">
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
                                                <td>{{number_format($account->getAccountBalance($start_date,$end_date))}}</td>
                                                    <p class="d-none">{{$b1 = $b1+$account->getAccountBalance($start_date,$end_date)}}</p>
                                                    <td>-</td>
                                                @endif
                                                @if($account->type==1)
                                                    <td>-</td>
                                                    <td>{{number_format($account->getAccountBalance($start_date,$end_date))}}</td>
                                                    <p class="d-none">{{$b2 = $b2+$account->getAccountBalance($start_date,$end_date)}}</p>
                                                @endif
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td>{{$c++}}</td>
                                            <td>Difference</td>
                                            @if($b1>$b2)
                                               <td>-</td>
                                                <td>{{number_format($b1-$b2)}}</td>
                                            @endif
                                            @if($b2>$b1)
                                                <td>{{number_format($b2-$b1)}}</td>
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
                                                <th>{{number_format($b1)}}</th>
                                                <th>{{number_format($b1)}}</th>
                                            @else
                                                <th>{{number_format($b2)}}</th>
                                                <th>{{number_format($b2)}}</th>
                                            @endif
                                        </tr>
                                        </tbody>
                                    </table>
                                    @elseif($statement==2)
                                            <table class="table table-primary table-bordered table-hover table-striped" id="data-table">
                                                <thead>
                                                <tr>
                                                    <th>NO</th>
                                                    <th>ACCOUNT</th>
                                                    <th></th>
                                                    <th></th>
                                                    <th>AMOUNT (MK)</th>
                                                    <th>AMOUNT (MK)</th>

                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $c = 1;
                                                $b1 = 0;
                                                $b2 = 0;
                                                $b3 = 0;
                                                $df = 0;
                                                ?>
                                                <tr>
                                                    <td>{{$c++}}</td>
                                                    <td> <b>Sales (Revenue)</b></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                @foreach($debits as $debit)
                                                    <tr>
                                                        <td>{{$c++}}</td>
                                                        <td></td>
                                                        <td>{{ucwords($debit->name) }}</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td>{{number_format($debit->amount)}}</td>
                                                        <p class="d-none">{{$b1 = $b1+$debit->amount}}</p>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <td>{{$c++}}</td>
                                                    <td><b>Total Sales (Revenue)</b></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td><b>{{number_format($b1)}}</b> </td>
                                                </tr>
                                                <tr>
                                                    <td>{{$c++}}</td>
                                                    <td> <b>Less Cost of Sales (Revenue)</b></td>
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
                                                        <td></td>
                                                        <td>{{number_format($credit->amount)}}</td>
                                                        <p class="d-none">{{$b2 = $b2+$credit->amount}}</p>
                                                        <td></td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <td>{{$c++}}</td>
                                                    <td><b>Total cost of Sales </b></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td><b><u>({{number_format($b2)}})</u></b> </td>
                                                </tr>
                                                <tr>
                                                    <td>{{$c++}}</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td><b>{{number_format($b1-$b2)}}</b> </td>
                                                </tr>
                                                <tr>
                                                    <td>{{$c++}}</td>
                                                    <td> <b>Less Admin  Costs</b></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                @foreach($admins as $admin)
                                                    <tr>
                                                        <td>{{$c++}}</td>
                                                        <td></td>
                                                        <td>{{ucwords($admin->name) }}</td>
                                                        <td></td>
                                                        <td>{{number_format($admin->amount)}}</td>
                                                        <p class="d-none">{{$b3 = $b3+$admin->amount}}</p>
                                                        <td></td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <td>{{$c++}}</td>
                                                    <td><b>Total Admin Cost </b></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td><b><u>({{number_format($b3)}})</u></b> </td>
                                                </tr>
                                                <tr>
                                                    <td>{{$c++}}</td>
                                                    <td><b>Gross Profit</b></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td><b>{{number_format($b1-$b2-$b3)}}</b> </td>
                                                </tr>
                                                <tr>
                                                    <td>{{$c++}}</td>
                                                    <td><b>Less 1% NCIC</b></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td><b><u>({{number_format(0.01*$b1)}})</u></b> </td>
                                                </tr>
                                                <tr>
                                                    <td>{{$c++}}</td>
                                                    <td><b></b></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td><b>{{number_format($df = $b1-$b2-$b3-(0.01*$b1))}}</b> </td>
                                                </tr>
                                                <tr>
                                                    <td>{{$c++}}</td>
                                                    <td><b>Less 4% WithHolding Tax</b></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td><b><u>({{number_format(0.04*$b1)}})</u></b> </td>
                                                </tr>
                                                <tr>
                                                    <td>{{$c++}}</td>
                                                    <td><b>Profit / Loss for the Period</b></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td><b><u>{{number_format(($b1-$b2-$b3) - (0.05*$b1))}}</u></b> </td>
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
    <script src="{{asset('vendor/simple-datatable/simple-datatable.js')}}"></script>
    <script>
        function confirmationWindow(title, message, primaryLabel, callback) {
            Swal.fire({
                title: title,
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: primaryLabel
            }).then((result) => {
                if (result.isConfirmed) {
                    callback();
                }
            })
        }
        $(document).ready(function () {
            $(".delete-btn").on('click', function () {
                $url = $(this).attr('data-target-url');

                $("#delete-form").attr('action', $url);
                confirmationWindow("Confirm Deletion", "Are you sure you want to delete this position?", "Yes,Delete", function () {
                    $("#delete-form").submit();
                })
            });
        })
    </script>
@stop
