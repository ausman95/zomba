@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-list-ol"></i>Home churches
        </h4>
        <p>
            Manage Home churches information
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('churches.index')}}">Home churches</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$church->name}}</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
                    <div class="row">
                        <div class="col-sm-4 mb-2">
                            <div class="mt-5">
                                <h5>
                                    <i class="fa fa-microscope"></i>Home Church Information
                                </h5>
                            <div class="card shadow-sm">
                                <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table  table-bordered table-hover table-striped">
                                        <caption style=" caption-side: top; text-align: center">{{$church->name}} Home Church</caption>
                                        <tbody>
                                        <tr>
                                            <td>Name</td>
                                            <td>{{$church->name}}</td>
                                        </tr>
                                        <tr>
                                            <td>Created On</td>
                                            <td>{{$church->created_at}}</td>
                                        </tr>
                                        <tr>
                                            <td>Update ON</td>
                                            <td>{{$church->updated_at}}</td>
                                        </tr>
                                    </table>
                                    <div class="mt-3">
                                        <div>
                                            @if(request()->user()->designation=='administrator')
                                            <a href="{{route('churches.edit',$church->id)}}"
                                               class="btn btn-primary rounded-0" style="margin: 2px">
                                                <i class="fa fa-edit"></i>Update
                                            </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                            </div>
                        </div>
                        @if(request()->user()->designation=='administrator')
                            <div class="col-sm-8 mb-2">
                                <div class="mt-5">
                                    <h5>
                                        <i class="fa fa-microscope"></i>Home Church Transactions
                                    </h5>
                                    <div class="card">
                                        <div class="card-body">
                                            @if($transactions->count() === 0)
                                                <i class="fa fa-info-circle"></i>There are no Transactions!
                                            @else
                                                <div style="overflow-x:auto;">
                                                    <table class="table   table2 table-bordered table-striped" id="incomes-table">
                                                        <caption style=" caption-side: top; text-align: center">CHURCH TRANSACTIONS</caption>
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
                        @endif
                    </div>
            <div class="row">
                <div class="col-sm-12 mb-2">
                    <div class="mt-5">
                        <h5>
                            <i class="fa fa-microscope"></i>Home Church Members
                        </h5>
                        <div class="card">
                            <div class="card-body px-1">
                                @if($church->members->count() === 0)
                                    <i class="fa fa-info-circle"></i>There are no  Member!
                                @else
                                    <div style="overflow-x:auto;">
                                        <table class="table  table-bordered table-hover table-striped" id="data-table">
                                            <caption style=" caption-side: top; text-align: center">Members</caption>
                                            <thead>
                                            <tr>
                                                <th>NO</th>
                                                <th>NAME</th>
                                                <th>HOME CHURCH</th>
                                                <th>PHONE</th>
                                                <th>MINISTRY</th>
                                                <th>STATUS</th>
                                                <th>ACTION</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php $c = 1;?>
                                            @foreach($church->members as $member)
                                                <tr>
                                                    <td>{{$c++}}</td>
                                                    <td>{{$member->name}}</td>
                                                    <td>{{$member->church->name}}</td>
                                                    <td>{{$member->phone_number}}</td>
                                                    <td>{{$member->ministry->name}}</td>
                                                    <td>
                                                        @if($member->status==1)
                                                            {{'ACTIVE'}}
                                                        @elseif($member->status==2)
                                                            {{'MOVED'}}
                                                        @else
                                                            {{'DECEASED'}}
                                                        @endif
                                                    </td>
                                                    <td class="pt-1">
                                                        @if(request()->user()->designation=='administrator')
                                                        <a href="{{route('members.show',$member->id)}}"
                                                           class="btn btn-primary btn-md rounded-0">
                                                            <i class="fa fa-list-ol"></i>  Manage
                                                        </a>
                                                        @endif
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
                confirmationWindow("Confirm Deletion", "Are you sure you want to delete this account?", "Yes,Delete", function () {
                    $("#delete-form").submit();
                });
            });
        })
    </script>
@stop
