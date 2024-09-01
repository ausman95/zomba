@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">

        <h4>
            <i class="fa fa-building"></i>Bank Accounts
        </h4>
        <p>
            Bank Account information
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('finances.index')}}">Finances</a></li>
                <li class="breadcrumb-item"><a href="{{route('banks.index')}}">Bank Accounts</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$bank->account_name}}</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 mb-2 col-md-8 col-lg-9">
                    <div class="row">
                        <div class="col-sm-12 col-md-7 col-lg-8">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover table-striped">
                                            <caption style=" caption-side: top; text-align: center">{{$bank->account_name}} INFORMATION</caption>
                                            <tbody>
                                            <tr>
                                                <td>Bank Name</td>
                                                <td>{{$bank->bank_name}}</td>
                                            </tr>
                                            <tr>
                                                <td>Account Name</td>
                                                <td>{{$bank->account_name}}</td>
                                            </tr>
                                            <tr>
                                                <td>Account Number</td>
                                                <td>{{$bank->account_number}}</td>
                                            </tr>
                                            <tr>
                                                <td>Service Centre</td>
                                                <td>{{$bank->service_centre}}</td>
                                            </tr>
                                            <tr>
                                                <td>Created On</td>
                                                <td>{{$bank->created_at}}</td>
                                            </tr>
                                            <tr>
                                                <td>Update ON</td>
                                                <td>{{$bank->updated_at}}</td>
                                            </tr>
                                            <tr>
                                                <td>Update By</td>
                                                <td>{{\App\Models\Budget::userName($bank->updated_by)}}</td>
                                            </tr>
                                            <tr>
                                                <td>Created By</td>
                                                <td>{{@\App\Models\Budget::userName($bank->created_by)}}</td>
                                            </tr>
                                            <tr>
                                                <td>Status</td>
                                                <td>
                                                    @if($bank->soft_delete==1)
                                                        <p style="color: red">Deleted, and Reserved for Audit</p>
                                                    @else
                                                        Active
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                        <div class="mt-3">
                                            <div>
                                                <a href="{{route('banks.edit',$bank->id)}}"
                                                   class="btn btn-primary rounded-0">
                                                    <i class="fa fa-edit"></i>Update
                                                </a>
                                                <button class="btn btn-danger btn-md rounded-0" id="delete-btn">
                                                    <i class="fa fa-trash"></i>Delete
                                                </button>
                                                <form action="{{route('banks.destroy',$bank->id)}}" method="POST" id="delete-form">
                                                    @csrf
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="id" value="{{$bank->id}}">
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-5">
                    <h5>
                        <i class="fa fa-microscope"></i>Transactions
                    </h5>
                    <div class="card">
                        <div class="card-body">
                            <div style="overflow-x:auto;">
                                <table class="table table-bordered table1 table-hover table-striped" id="data-table">
                                    <caption style="caption-side: top; text-align: center">{{ $bank->account_name }} Bank Statement</caption>
                                    <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>DATE</th>
                                        <th>DESC</th>
                                        <th>ACCOUNT</th>
                                        <th>AMOUNT (MK)</th>
                                        <th>BALANCE (MK)</th>
                                        <th>TYPE</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $c = ($transactions->currentPage() - 1) * $transactions->perPage() + 1;
                                        $balance = 0;
                                    @endphp
                                    @foreach($transactions as $transaction)
                                        <tr>
                                            <td>{{ $c++ }}</td>
                                            <td>{{ date('d F Y', strtotime($transaction->created_at)) }}</td>
                                            <td>{{ ucwords($transaction->description) }}</td>
                                            <td>{{ ucwords(@$transaction->account->name) }}</td>
                                            <td>
                                                @if($transaction->type == 1)
                                                    {{ number_format($transaction->amount, 2) }}
                                                @elseif($transaction->type == 2)
                                                    ({{ number_format(abs($transaction->amount), 2) }})
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    if ($transaction->type == 1) {
                                                        $balance += $transaction->amount; // Add to balance for revenue
                                                    } elseif ($transaction->type == 2) {
                                                        $balance -= $transaction->amount; // Subtract from balance for expense
                                                    }
                                                @endphp
                                                {{ $balance < 0 ? '('.number_format(abs($balance), 2).')' : number_format($balance, 2) }}
                                            </td>
                                            <td>{{ $transaction->type == 1 ? "REVENUE" : "EXPENSE" }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                                <!-- Pagination Links -->
                                <div class="pagination">
                                    {{ $transactions->links() }}
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
        $(document).ready(function () {

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


            $("#delete-btn").on('click', function () {
                confirmationWindow("Confirm Deletion", "Are you sure you want to delete this Bank Account ?", "Yes,Delete", function () {
                    $("#delete-form").submit();
                });
            });
        })
    </script>
@stop
