
@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-wallet"></i> Finances
        </h4>
        <p>
            Finances
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Finances</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-3">
            <a href="{{route('suppliers.index')}}" class="btn btn-primary btn-md rounded-0">
                <i class="fa fa-suitcase-rolling"></i> Suppliers
            </a>
            <a href="{{route('financial-years.index')}}" class="btn btn-primary btn-md rounded-0">
                <i class="fa fa-calendar-check"></i> Financial Years
            </a>
            <a href="{{route('budgets.index')}}" class="btn btn-primary btn-md rounded-0">
                <i class="fa fa-file-archive"></i> Budgets
            </a>
            <a href="{{route('banks.index')}}" class="btn btn-primary btn-md rounded-0">
                <i class="fa fa-building"></i> Banks
            </a>
            @if(request()->user()->designation==='administrator')
            <a href="{{route('transfers.index')}}" class="btn btn-primary btn-md rounded-0">
                <i class="fa fa-exchange-alt"></i> Bank Transfers
            </a>
            @endif
            <a href="{{route('Ministries.index')}}" class="btn btn-primary btn-md rounded-0">
                <i class="fa fa-list-ol"></i> Charts of Accounts
            </a>
            <a href="{{route('purchases.index')}}" class="btn btn-primary btn-md rounded-0">
                <i class="fa fa-file-archive"></i> Purchases
            </a>
            <a href="{{route('payments.index')}}" class="btn btn-primary btn-md rounded-0">
                <i class="fa fa-money-bill-wave"></i> Payments
            </a>

            <div class="mt-3">
                <div class="row">
                    <div class="col-sm-12 mb-2 col-md-12 col-lg-12">
                        <div class="card " style="min-height: 30em;">
                            <div class="card-body px-1">
                                @if($banks->count() === 0)
                                    <i class="fa fa-info-circle"></i>There are no Bank Accounts!
                                @else
                                    <div style="overflow-x:auto;">
                                    <table class="table table-bordered table-primary table-hover table-striped" id="data-table">
                                        <caption style=" caption-side: top; text-align: center">BANKS</caption>
                                        <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>ACCOUNT NAME</th>
                                            <th>BALANCE (MK)</th>
                                            <th>ACCOUNT NUMBER</th>
                                            <th>SERVICE CENTRE</th>
                                            <th>ACCOUNT TYPE</th>
                                            <th>ACTION</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php  $c= 1;?>
                                        @foreach($banks as $bank)
                                            <tr>
                                                <td>{{$c++}}</td>
                                                <td>{{ucwords($bank->account_name) }}</td>
                                                <th>
                                                    @if($bank->getBalance()<0)
                                                    ({{number_format($bank->getBalance()*(-1))}})
                                                    @else
                                                    {{number_format($bank->getBalance())}}
                                                    @endif
                                                </th>
                                                <td>{{ucwords($bank->account_number) }}</td>
                                                <td>{{ucwords($bank->service_centre) }}</td>
                                                <td>{{ucwords($bank->account_type) }}</td>

                                                <td class="pt-1">
                                                    <a href="{{route('banks.show',$bank->id)}}"
                                                       class="btn btn-primary btn-md rounded-0">
                                                       <i class="fa fa-list-ol"></i> Manage
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
