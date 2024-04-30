@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-cash-register"></i>Church Bank Statements
        </h4>
        <p>
            Manage Church Statements
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('finances.index')}}">Finances</a></li>
                <li class="breadcrumb-item"><a href="{{route('analytics')}}">Statements</a></li>
                <li class="breadcrumb-item active" aria-current="page">Bank Statement</li>
            </ol>
        </nav>
        <hr>
        <div class="mt-3">
            <div class="mt-3">
                <div class="card container-fluid" style="min-height: 30em;">
                    <div class="row">
                        <div class="col-sm-12 mb-2 col-md-2 col-lg-2">
                            <hr>
                            <form action="{{route('bank-statement.produce')}}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <select name="bank_id"
                                            class="form-select select-relation
                                            @error('bank_id') is-invalid @enderror" style="width: 100%" required>
                                        @foreach($banks as $bank)
                                            <option value="{{$bank->id}}"
                                                {{old('bank_id')===$bank->id ? 'selected' : ''}}>{{$bank->account_name.' - '.$bank->account_number}}</option>
                                        @endforeach
                                    </select>
                                    @error('bank_id')
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
                                           placeholder="Start Date" required>
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
                                           placeholder="End Date" required>
                                    @error('end_date')
                                    <span class="invalid-feedback">
                               {{$message}}
                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <button class="btn btn-primary rounded-0" type="submit">
                                        View &rarr;
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="col-sm-12 mb-2 col-md-11 col-lg-10">
                            <br>
                            <div class="card container-fluid" style="min-height: 30em;">
                                <div class="card-body px-1">
                                    @if($payments== 0)
                                        <i class="fa fa-info-circle"></i>No Statement Found!
                                    @else
                                        <div style="overflow-x:auto;">
                                            <table class="table table-bordered table-hover table-striped">
                                                <caption style=" caption-side: top; text-align: center">{{$bank->account_name.' - '.$bank->account_number}} Bank Statement
                                                FROM {{date('d F Y', strtotime($start_date))}}
                                                     TO  {{date('d F Y', strtotime($end_date))}}
                                                </caption>
                                                <thead>
                                                <tr>
                                                    <th>NO</th>
                                                    <th>DATE</th>
                                                    <th>FOR</th>
                                                    <th>ACCOUNT</th>
                                                    <th>DESCRIPTION</th>
                                                    <th>AMOUNT (MK)</th>
                                                    <th>BALANCE (MK)</th>
                                                    <th>TYPE</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    <?php  $c= 1; $balance = 0 ?>
                                                @foreach($transactions as $transaction)
                                                    <tr>
                                                        <td>{{$c++}}</td>
                                                        <td>{{date('d F Y', strtotime($transaction->t_date)) }}</td>
                                                        <td>{{ucwords($transaction->description) }}</td>
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
                                                        <th>
                                                            {{number_format($transaction->balance)}}
                                                        </th>
                                                        <td>{{ucwords($transaction->type==1 ? "REVENUE" : "EXPENSE") }}</td>
                                                    </tr>
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

