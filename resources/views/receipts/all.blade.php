@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('vendor/simple-datatable/simple-datatable.css') }}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-cash-register"></i> All Transactions
        </h4>
        <p>
            Manage All Transactions
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('finances.index') }}">Finances</a></li>
                <li class="breadcrumb-item active" aria-current="page">All Transactions</li>
            </ol>
        </nav>
        <hr>
        <div class="mt-3">
            <div class="card container-fluid" style="min-height: 30em;">
                <div class="row">
                    <div class="col-sm-12 mb-2 col-md-2 col-lg-2">
                        <hr>
                        <form action="{{ route('all.produce') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="Month">Banks</label>
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
                                <label for="Month">Months</label>
                                <select name="month_id" class="form-select select-relation @error('month_id') is-invalid @enderror"
                                        style="width: 100%">
                                    @foreach($months as $month)
                                        <option value="{{ $month->id }}"
                                            {{ old('month') == $month->id ? 'selected' : '' }}>{{ $month->name }}</option>
                                    @endforeach
                                </select>
                                @error('month_id')
                                <span class="invalid-feedback">
                                    {{ $message }}
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
                    <div class="col-sm-12 mb-2 col-md-10 col-lg-10">
                        <br>
                        <div class="card container-fluid" style="min-height: 30em;">
                            <div class="card-body px-1">
                            @if($payments->isEmpty())
                                <i class="fa fa-info-circle"></i> There are no Transactions!
                            @else
                                <div style="overflow-x:auto;">
                                    <table class="table table-bordered table-hover table-striped">
                                        <caption style="caption-side: top; text-align: center">ALL TRANSACTIONS</caption>
                                        <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>DATE</th>
                                            <th>REF</th>
                                            <th>FOR</th>
                                            <th>DESC</th>
                                            <th>AMOUNT (MK)</th>
                                            <th>ACCOUNT</th>
                                            <th>BANK</th>
                                            <th>METHOD</th>
                                            <th>TYPE</th>
                                            <th>-</th>
                                            <th>STATUS</th>
                                            <th>CREATED BY</th>
                                            <th>VERIFIED BY</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($payments as $payment)
                                            <tr>
                                                <td>{{ ($loop->index + 1) + (($payments->currentPage() - 1) * $payments->perPage()) }}</td>
                                                <td>{{ date('d F Y', strtotime($payment->t_date)) }}</td>
                                                <td>{{ ucwords($payment->reference) }}</td>
                                                <td>{{ ucwords(substr($payment->name, 0, 500)) }}</td>
                                                <td>{{$payment->specification}}</td>
                                                <th>{{ number_format($payment->amount, 2) }}</th>
                                                <td>{{ ucwords($payment->account->name) }}</td>
                                                <td>
                                                    @if(!@$payment->bank->account_name)
                                                        OPENING TRANSACTION
                                                    @else
                                                        {{ $payment->bank->bank_name . ' - ' . $payment->bank->account_name }}
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
                                                <th>{{ ucwords($payment->account->type == 2 ? "EXPENSE" : "REVENUE") }}</th>
                                                <th>{{ ucwords($payment->status == 1 ? "VERIFIED" : "UN~VERIFIED") }}</th>
                                                <td>{{ \App\Models\Budget::userName($payment->created_by) }}</td>
                                                <td>{{ \App\Models\Budget::userName($payment->updated_by) }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                {{ $payments->links() }}
                            @endif
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script src="{{ asset('vendor/simple-datatable/simple-datatable.js') }}"></script>
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
                confirmationWindow("Confirm Deletion", "Are you sure you want to delete this position?", "Yes, Delete", function () {
                    $("#delete-form").submit();
                })
            });
        })
    </script>
@stop