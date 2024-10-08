@extends('layouts.app')

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
                                <label for="Bank">Banks</label>
                                <select name="bank_id" class="form-select select-relation @error('bank_id') is-invalid @enderror" style="width: 100%">
                                    <option value="">Select Bank</option>
                                    @foreach($banks as $bank)
                                        <option value="{{ $bank->id }}" {{ old('bank_id') == $bank->id ? 'selected' : '' }}>
                                            {{ $bank->account_name.' - '.$bank->account_number }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('bank_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="Month">Months</label>
                                <select name="month_id" class="form-select select-relation @error('month_id') is-invalid @enderror" style="width: 100%">
                                    <option value="">Select Month</option>
                                    @foreach($months as $month)
                                        <option value="{{ $month->id }}" {{ old('month_id') == $month->id ? 'selected' : '' }}>
                                            {{ $month->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('month_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- New filters for Start Date and End Date -->
                            <div class="form-group">
                                <label for="Start Date">Start Date</label>
                                <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}">
                                @error('start_date')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="End Date">End Date</label>
                                <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}">
                                @error('end_date')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <button class="btn btn-primary rounded-0" type="submit">
                                    Apply Filters &rarr;
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="col-sm-12 mb-2 col-md-10 col-lg-10">
                        <br>
                        <div class="card container-fluid" style="min-height: 30em;">
                            <div class="card-body px-1">
                                @if(!request()->has('bank_id') && !request()->has('month_id') && !request()->has('start_date') && !request()->has('end_date'))
                                    <!-- Display message if no filters applied -->
                                    <div class="text-center">
                                        <div class="alert alert-danger">
                                            <i class="fa fa-info-circle">
                                            </i> Please apply filters and click 'Apply Filters' to view the data.
                                        </div>
                                    </div>

                                @elseif($payments->isEmpty())
                                    <!-- Display message if no transactions available -->
                                    <i class="fa fa-info-circle"></i> There are no Transactions for the applied filters!
                                @else
                                    <div style="overflow-x:auto;">
                                        <table class="table table-bordered table-hover table-striped">
                                            <caption style="caption-side: top; text-align: center">ALL TRANSACTIONS</caption>
                                            <thead>
                                            <tr>
                                                <th>NO</th>
                                                <th>DATE</th>
                                                <th>REF</th>
                                                <th>AMOUNT (MK)</th>
                                                    <th>BALANCE (MK)</th>
                                                <th>ACCOUNT</th>
                                                <th>BANK</th>
                                                <th>METHOD</th>
                                                <th>CHEQUE #</th>
                                                <th>TYPE</th>
                                                <th>-</th>
                                                <th>CREATED BY</th>
                                                <th>VERIFIED BY</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <!-- Opening Balance Row -->
                                            @php
                                                $balance = $openingBalance; // Initialize balance
                                            @endphp
                                            @if($openingBalance != 0)
                                                <tr>
                                                    <td>1</td>
                                                    <td>{{ date('d F Y', strtotime($start_date)) }} (Opening Balance)</td>
                                                    <td>N/A</td>
                                                    <td>{{ number_format($openingBalance, 2) }}</td>
                                                        <td>{{ $openingBalance < 0 ? '(' . number_format(abs($openingBalance), 2) . ')' : number_format($openingBalance, 2) }}</td>
                                                    <td>N/A</td>
                                                    <td>N/A</td>
                                                    <td>N/A</td>
                                                    <td>N/A</td>
                                                    <td>N/A</td>
                                                    <td>N/A</td>
                                                    <td>N/A</td>
                                                    <td>N/A</td>
                                                </tr>
                                            @endif

                                            <!-- Transactions Loop -->
                                            @foreach($payments as $payment)
                                                <tr>
                                                    <td>{{ ($loop->index + 2) + (($payments->currentPage() - 1) * $payments->perPage()) }}</td>
                                                    <td>{{ date('d F Y', strtotime($payment->t_date)) }}</td>
                                                    <td>
                                                        @if($payment->amount < 0)
                                                            Transaction Reverse: {{ $payment->name }}
                                                        @else
                                                            {{ $payment->name }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($payment->type == 2 && $payment->amount <0)
                                                            {{ number_format(abs($payment->amount), 2) }}
                                                        @elseif($payment->type == 1 && $payment->amount < 0)
                                                            ({{ number_format(abs($payment->amount), 2) }})
                                                        @elseif($payment->type == 1 && $payment->amount > 0)
                                                            {{ number_format(abs($payment->amount), 2) }}
                                                        @elseif($payment->type == 2 && $payment->amount > 0)
                                                           ({{ number_format(abs($payment->amount), 2) }})
                                                        @endif
                                                    </td>
                                                        <td>
                                                            @php
                                                                if ($payment->type == 1) {
                                                                    $balance += $payment->amount;
                                                                } else {
                                                                    $balance -= $payment->amount;
                                                                }
                                                            @endphp

                                                            {{ $balance < 0 ? '('.number_format(abs($balance), 2).')' : number_format($balance, 2) }}
                                                        </td>
                                                    <td>{{ ucwords($payment->account->name) }}</td>
                                                    <td>
                                                        @php
                                                            $bank = \App\Models\Banks::find($payment->bank_id);
                                                        @endphp
                                                        {{ $bank->account_name . ' - ' . $bank->account_number }}
                                                    </td>
                                                    <td>
                                                        @switch($payment->payment_method)
                                                            @case(1)
                                                                CASH
                                                                @break
                                                            @case(3)
                                                                CHEQUE
                                                                @break
                                                            @case(4)
                                                                ONLINE TRANSFER
                                                                @break
                                                            @default
                                                                MOBILE MONEY TRANSFER
                                                        @endswitch
                                                    </td>
                                                    <td>
                                                        {{ $payment->reference }}
                                                    </td>
                                                    <td>
                                                        @switch($payment->type)
                                                            @case(1)
                                                                MAIN CHURCH
                                                                @break
                                                            @case(2)
                                                                ADMIN
                                                                @break
                                                            @case(3)
                                                                SUPPLIERS
                                                                @break
                                                            @case(4)
                                                                EMPLOYEES
                                                                @break
                                                            @case(5)
                                                                MEMBERS
                                                                @break
                                                            @case(6)
                                                                DONATION
                                                                @break
                                                            @case(7)
                                                                LOANS
                                                                @break
                                                            @case(8)
                                                                PROJECTS
                                                                @break
                                                            @default
                                                                OTHER
                                                        @endswitch
                                                    </td>
                                                    <td>
                                                        {{ $payment->type == 2 ? "EXPENSE" : "REVENUE" }}
                                                    </td>
                                                    <td>
                                                        @php
                                                            $creator = \App\Models\User::find($payment->created_by);
                                                        @endphp
                                                        {{ $creator ? $creator->name : 'N/A' }}
                                                    </td>
                                                    <td>
                                                        @php
                                                            $verifier = \App\Models\User::find($payment->verified_by);
                                                        @endphp
                                                        {{ $verifier ? $verifier->name : 'N/A' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="float-end">
                                        {{ $payments->appends(request()->query())->links() }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
