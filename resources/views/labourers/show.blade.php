@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">

        <h4>
            <i class="fa fa-users"></i>Employees
        </h4>
        <p>
            Manage Employees information
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('human-resources.index')}}">Human Resources</a></li>
                <li class="breadcrumb-item"><a href="{{route('labourers.employees')}}">Employees</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$labourer->name}}</li>
            </ol>
        </nav>

        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 mb-2 col-md-4 col-lg-3">
                    <div class="card shadow-sm">
                        <div class="card-body election-banner-card p-1">
                            <img src="{{asset('images/avatar.png')}}" alt="avatar image" class="img-fluid">
                        </div>
                    </div>
                    <div class="mt-3">
                        <div>
                            <a href="{{route('labourers.edit',$labourer->id)}}"
                               class="btn btn-primary btn-md rounded-0" style="margin: 5px">
                                <i class="fa fa-edit"></i>Update
                            </a>
                            <button class="btn btn-danger btn-md rounded-0" id="delete-btn" style="margin: 5px">
                                <i class="fa fa-trash"></i>Delete
                            </button>
                            <form action="{{route('labourers.destroy',$labourer->id)}}" method="POST" id="delete-form">
                                @csrf
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="id" value="{{$labourer->id}}">
                            </form>
                        </div>
{{--                        <div class="">--}}
{{--                            <form action="{{route('labourers.destroy',$labourer->id)}}" method="POST" id="delete-form">--}}
{{--                                @csrf--}}
{{--                                <input type="hidden" name="_method" value="DELETE">--}}
{{--                            </form>--}}
{{--                            <button class="btn btn-danger btn-md rounded-0" id="delete-btn" style="margin: 5px">--}}
{{--                                <i class="fa fa-trash"></i>Delete--}}
{{--                            </button>--}}
{{--                        </div>--}}
                    </div>
                </div><!--./ overview -->
                <div class="col-sm-12 mb-2 col-md-8 col-lg-9">
                    <div class="row">
                        <div class="col-sm-12 col-md-7 col-lg-6">
                            <h5>
                                <i class="fa fa-microscope"></i>Personal Information
                            </h5>
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table  table-bordered table-hover table-striped" id="data-table">
                                            <caption style=" caption-side: top; text-align: center">{{$labourer->name}} INFORMATION</caption>
                                        <tbody>
                                        <tr>
                                            <td>Name</td>
                                            <td>{{$labourer->name}}</td>
                                        </tr>
                                        <tbody>
                                        <tr>
                                            <td>Gender</td>
                                            <td>{{$labourer->gender}}</td>
                                        </tr>

                                        <tr>
                                            <td>Phone Number</td>
                                            <td>{{$labourer->phone_number}}</td>
                                        </tr>
                                        <tr>
                                            <td>Professional </td>
                                            <td>{{$labourer->labour->name}}</td>
                                        </tr>
                                        <tr>
                                            <td>Department</td>
                                            <td>{{$labourer->department->name}}</td>
                                        </tr>

                                        <tr>
                                            <td>Status</td>
                                            <td>
                                                @if($labourer->type==1)
                                                    {{'Employed'}}
                                                @elseif($labourer->type==2)
                                                    {{'Sub-Contactor'}}
                                                @else{
                                                {{'Temporary Workers'}}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Created On</td>
                                            <td>{{$labourer->created_at}}</td>
                                        </tr>
                                        <tr>
                                            <td>Updated On</td>
                                            <td>{{$labourer->updated_at}}</td>
                                        </tr>
                                        <tr>
                                            <td>Status</td>
                                            <td>
                                                @if($labourer->soft_delete==1)
                                                    <p style="color: red">Deleted, and Reserved for Audit</p>
                                                @else
                                                    Active
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
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
                        @if($transactions->count() === 0)
                            <i class="fa fa-info-circle"></i>There are no  Transactions!
                        @else
                            <div style="overflow-x:auto;">
                            <table class="table  table2 table-bordered table-hover table-striped" id="data-table">
                                <caption style=" caption-side: top; text-align: center">TRANSACTIONS</caption>
                                <caption style=" caption-side: top; text-align: center">{{$labourer->name}} TRANSACTIONS</caption>
                                <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>DATE</th>
                                    <th>TRANSACTION NAME</th>
                                    <th>AMOUNT (MK)</th>
                                    <th>PAYMENT TYPE</th>
                                    <th>CREATED BY</th>
                                    <th>UPDATED BY</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php  $c= 1; $balance = 0; ?>
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
                                        <td>{{\App\Models\Budget::userName($transaction->created_by)}}</td>
                                        <td>{{\App\Models\Budget::userName($transaction->updated_by)}}</td>
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
                confirmationWindow("Confirm Deletion", "Are you sure you want to delete this Record ?", "Yes,Delete", function () {
                    $("#delete-form").submit();
                });
            });
            $("#delete-bt").on('click', function () {
                confirmationWindow("Confirm De~Allocation", "Are you sure you want to Remove this Labourer?", "Yes,Continue", function () {
                    $("#delete-forms").submit();
                });
            });
        })
    </script>
@stop
