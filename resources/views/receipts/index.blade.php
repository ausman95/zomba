@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-cash-register"></i>Church Receipts
        </h4>
        <p>
            Manage Church Receipts
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('finances.index')}}">Finances</a></li>
                <li class="breadcrumb-item active" aria-current="page">Church Receipts</li>
            </ol>
        </nav>
        <hr>
        <div class="mt-3">
            <a href="{{route('receipts.create')}}" class="btn btn-primary btn-md rounded-0">
                <i class="fa fa-plus-circle"></i>New Receipt
            </a>
            <div class="mt-3">
                <div class="card container-fluid" style="min-height: 30em;">
                    <div class="row">
                        <div class="col-sm-12 mb-2 col-md-2 col-lg-2">
                            <hr>
                            <form action="{{route('receipt.produce')}}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <select name="month_id"
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
                                    @if($payments->count() === 0)
                                        <i class="fa fa-info-circle"></i>There are no Receipts!
                                    @else
                                        <div style="overflow-x:auto;">
                                            <table class="table  table-bordered table-hover table-striped">
                                                <caption style=" caption-side: top; text-align: center">RECEIPTS</caption>
                                                <thead>
                                                <tr>
                                                    <th>NO</th>
                                                    <th>DATE</th>
                                                    <th>DESC</th>
                                                    <th>AMOUNT (MK)</th>
                                                    <th>ACCOUNT</th>
                                                    <th>BANK</th>
                                                    <th>METHOD</th>
                                                    <th>TYPE</th>
{{--                                                    <th>REF</th>--}}
                                                    <th>ACTION</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    <?php  $c= 1;?>
                                                @foreach($payments as $payment)
                                                    <tr>
                                                        <td>{{$c++}}</td>
                                                        <td>{{date('d F Y', strtotime($payment->t_date)) }}</td>
                                                        <td>{{ucwords(substr($payment->name,0,20)) }}</td>
                                                        <th>{{number_format($payment->amount,2) }}</th>
                                                        <td>{{ucwords($payment->account->name) }}</td>
                                                        <td>
                                                            @if(!@$payment->bank->account_name)
                                                                OPENING TRANSACTION
                                                            @else
                                                            {{$payment->bank->bank_name.' - '.$payment->bank->account_name}}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($payment->payment_method==1)
                                                                CASH
                                                            @elseif($payment->payment_method==3)
                                                                CHEQUE
                                                            @elseif($payment->payment_method==4)
                                                                ONLINE TRANSFER
                                                            @else
                                                               MOBILE MONEY TRANSFER
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($payment->type==1)
                                                                MAIN CHURCH
                                                            @elseif($payment->type==2)
                                                                ADMIN
                                                            @elseif($payment->type==3)
                                                                SUPPLIERS
                                                            @elseif($payment->type==4)
                                                                EMPLOYEES
                                                            @elseif($payment->type==5)
                                                                MEMBERS
                                                            @elseif($payment->type==6)
                                                                HOME CHURCH
                                                            @elseif($payment->type==7)
                                                                MINISTRIES
                                                            @else
                                                                OTHERS
                                                            @endif
                                                        </td>
{{--                                                        <td>{{ucwords($payment->reference) }}</td>--}}
                                                        <td class="pt-1">
                                                            <a href="{{route('payments.show',$payment->id)}}"
                                                               class="btn btn-primary btn-md rounded-0">
                                                                <i class="fa fa-list-ol"></i>   Details
                                                            </a>
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

