@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-list-ul"></i>Bank Transfers
        </h4>
        <p>
            Manage Bank Transfers
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('finances.index')}}">Finances</a></li>
                <li class="breadcrumb-item"><a href="{{route('banks.index')}}">Banks</a></li>
                <li class="breadcrumb-item active" aria-current="page">Bank Transfers</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-3">
            <a href="{{route('transfers.create')}}" class="btn btn-primary btn-md rounded-0">
                <i class="fa fa-plus-circle"></i>New Bank Transfers
            </a>
            <div class="mt-3">
                <div class="row">
                    <div class="col-sm-12 mb-2 col-md-12 col-lg-12">
                        <div class="card " style="min-height: 30em;">
                            <div class="card-body px-1">
                                @if($transfers->count() === 0)
                                    <i class="fa fa-info-circle"></i>There are no Bank Transfers!
                                @else
                                    <div style="overflow-x:auto;">
                                        <table class="table  table-bordered table-hover table-striped">
                                            <caption style=" caption-side: top; text-align: center">BANK TRANSFERS</caption>
                                            <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>FROM ACC NAME</th>
                                            <th>FROM ACC NUMBER</th>
                                            <th>AMOUNT (MK)</th>
                                            <th>DATE</th>
                                            <th>REF</th>
                                            <th>TO ACC NAME</th>
                                            <th>TO ACC NUMBER</th>
                                            <th>CREATED BY</th>
                                            <th>UPDATED BY</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php  $c= 1;?>
                                        @foreach($transfers as $transfer)
                                            <tr>
                                                <td>{{$c++}}</td>
                                                <td>{{ucwords($transfer->accountFrom->account_name) }}</td>
                                                <td>{{ucwords($transfer->accountFrom->account_number) }}</td>
                                                <th>{{number_format($transfer->amount,2) }}</th>
                                                <td>{{ucwords($transfer->created_at) }}</td>
                                                <td>{{ucwords($transfer->cheque_number) }}</td>
                                                <td>{{ucwords($transfer->accountTo->account_name) }}</td>
                                                <td>{{ucwords($transfer->accountTo->account_number) }}</td>
                                                <td>{{\App\Models\Budget::userName($transfer->created_by)}}</td>
                                                <td>{{\App\Models\Budget::userName($transfer->updated_by)}}</td>
                                                <td class="pt-1">
                                                    <a href="{{route('transfers.show',$transfer->id)}}"
                                                       class="btn btn-primary btn-md rounded-0">
                                                      <i class="fa fa-list-ol"></i>  Details
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

