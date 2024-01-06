@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">

        <h4>
            <i class="bx bxs-city "></i>&nbsp; Church Members
        </h4>
        <p>
            Members
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('members.index')}}">Members</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$member->name}}</li>
            </ol>
        </nav>

        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            {{--            <div class="mb-2">--}}
            {{--                <a href="{{route('requisitions.projects.index',$project->id)}}" class="btn btn-primary btn-md rounded-0">--}}
            {{--                    <i class="fa fa-list-ol"></i>Requisitions--}}
            {{--                </a>--}}
            {{--            </div>--}}
            <div class="row">
                <div class="col-sm-4">
                    <h5>
                        <i class="fa fa-microscope"></i>Member Transactions
                    </h5>
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped" id="data-table">
                                    <caption style=" caption-side: top; text-align: center">{{$member->name}} INFORMATION</caption>
                                    <tbody>
                                    <tr>
                                        <td>Name</td>
                                        <td>{{$member->name}}</td>
                                    </tr>
                                    <tr>
                                        <td>Phone Number</td>
                                        <td>{{($member->phone_number)}}</td>
                                    </tr>
                                    <tr>
                                        <td>Ministry</td>
                                        <td>{{$member->ministry->name}}</td>
                                    </tr>
                                    <tr>
                                        <td>Home Church</td>
                                        <td>{{$member->church->name}}</td>
                                    </tr>
                                    <tr>
                                        <td>Status</td>
                                        <td>  @if($member->status==1)
                                                {{'ACTIVE'}}
                                            @elseif($member->status==2)
                                                {{'MOVED'}}
                                            @else
                                                {{'DECEASED'}}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Start Date</td>
                                        <td>{{$member->created_at}}</td>
                                    </tr>
                                    <tr>
                                        <td>Updated Date</td>
                                        <td>{{$member->updated_at}}</td>
                                    </tr>
                                </table>
                                <div class="mt-3">
                                    <div>
                                        <a href="{{route('members.edit',$member->id)}}"
                                           class="btn btn-primary rounded-0" style="margin: 2px">
                                            <i class="fa fa-edit"></i>Update
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-8">
                        <h5>
                            <i class="fa fa-microscope"></i>Member Transactions
                        </h5>
                        <div class="card shadow-sm">
                            <div class="card-body">
                                @if($transactions->count() === 0)
                                    <i class="fa fa-info-circle"></i>There are no Transactions!
                                @else
                                    <div style="overflow-x:auto;">
                                        <table class="table table2 table-bordered table-striped" id="incomes-table">
                                            <caption style=" caption-side: top; text-align: center">MEMBER TRANSACTIONS</caption>
                                            <thead>
                                            <tr>
                                                <th>NO</th>
                                                <th>DATE</th>
                                                <th>TRANSACTION NAME</th>
                                                <th>AMOUNT (MK)</th>
                                                <th>PAYMENT TYPE</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php  $c = 1; $balance = 0;?>
                                            @foreach($transactions as $transaction)
                                                <tr>
                                                    <td>{{$c++}}</td>
                                                    <td>{{date('d F Y', strtotime($transaction->created_at)) }}</td>
                                                    <td>{{ucwords($transaction->account->name) }}</td>
                                                    <th>
                                                        @if($transaction->account->type == 1)
                                                      ({{number_format($transaction->amount,2) }})
                                                        @else
                                                            {{number_format($transaction->amount,2) }}
                                                        @endif
                                                    </th>
                                                    <td>{{ucwords($transaction->account->type == 2 ? "CR" : "DR") }}</td>
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
                confirmationWindow("Confirm Deletion", "Are you sure you want to delete this Record?", "Yes,Delete", function () {
                    $("#delete-form").submit();
                });
            });
        })
    </script>
@stop
