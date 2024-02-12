@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-list-ol"></i>Church Ministries
        </h4>
        <p>
            Manage Ministries information
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('setting.index')}}">Settings</a></li>
                <li class="breadcrumb-item"><a href="{{route('ministries.index')}}">Ministries</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$ministry->name}}</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                    <div class="col-sm-4">
                        <div class="mt-5">
                            <h5>
                                <i class="fa fa-microscope"></i>Ministry Transactions
                            </h5>
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-striped">
                                        <caption style=" caption-side: top; text-align: center">{{$ministry->name}} MINISTRY</caption>
                                        <tbody>
                                        <tr>
                                            <td>Name</td>
                                            <td>{{$ministry->name}}</td>
                                        </tr>
                                        <tr>
                                            <td>Created On</td>
                                            <td>{{$ministry->created_at}}</td>
                                        </tr>
                                        <tr>
                                            <td>Update ON</td>
                                            <td>{{$ministry->updated_at}}</td>
                                        </tr>
                                        <tr>
                                            <td>Update By</td>
                                            <td>{{\App\Models\Budget::userName($ministry->updated_by)}}</td>
                                        </tr>
                                        <tr>
                                            <td>Created By</td>
                                            <td>{{@\App\Models\Budget::userName($ministry->created_by)}}</td>
                                        </tr>
                                        <tr>
                                            <td>Status</td>
                                            <td>
                                                @if($ministry->soft_delete==1)
                                                    <p style="color: red">Deleted, and Reserved for Audit</p>
                                                @else
                                                    Active
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                    <div class="mt-3">
                                        <div>
                                            @if(request()->user()->designation=='administrator')
                                            <a href="{{route('ministries.edit',$ministry->id)}}"
                                               class="btn btn-primary rounded-0" style="margin: 2px">
                                                <i class="fa fa-edit"></i>Update
                                            </a>
                                                <button class="btn btn-danger btn-md rounded-0" id="delete-btn" style="margin: 5px">
                                                    <i class="fa fa-trash"></i>Delete
                                                </button>
                                                <form action="{{route('ministries.destroy',$ministry->id)}}" method="POST" id="delete-form">
                                                    @csrf
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="id" value="{{$ministry->id}}">
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                <div class="col-sm-8">
                    <div class="mt-5">
                        <h5>
                            <i class="fa fa-microscope"></i>Ministry Transactions
                        </h5>
                        <div class="card">
                            <div class="card-body">
                                @if($transactions->count() === 0)
                                    <i class="fa fa-info-circle"></i>There are no Transactions!
                                @else
                                    <div style="overflow-x:auto;">
                                        <table class="table  table2 table-bordered table-striped" id="incomes-table">
                                            <caption style=" caption-side: top; text-align: center">MINISTRY TRANSACTIONS</caption>
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
                <div class="col-sm-12 mb-2">
                    <div class="mt-5">
                        <h5>
                            <i class="fa fa-microscope"></i>Ministry Members
                        </h5>
                        <div class="card">
                            <div class="card-body px-1">
                                @if($ministry->members->count() === 0)
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
                                            @foreach($ministry->members as $member)
                                                @if(@$member->member->status==1 || @$member->member->status ==2)
                                                <tr>
                                                    <td>{{$c++}}</td>
                                                    <td>{{@$member->member->name}}</td>
                                                    <td>{{@$member->member->church->name}}</td>
                                                    <td>{{@$member->member->phone_number}}</td>
                                                    <td>{{@$member->ministry->name}}</td>
                                                    <td>
                                                        @if(@$member->member->status==1)
                                                            {{'ACTIVE'}}
                                                        @elseif(@$member->member->status==2)
                                                            {{'MOVED'}}
                                                        @else
                                                            {{'DECEASED'}}
                                                        @endif
                                                    </td>
                                                    <td class="pt-1">
                                                        @if(request()->user()->designation=='administrator')
                                                            <a href="{{route('members.show',@$member->id)}}"
                                                               class="btn btn-primary btn-md rounded-0">
                                                                <i class="fa fa-list-ol"></i>  Manage
                                                            </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endif
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
