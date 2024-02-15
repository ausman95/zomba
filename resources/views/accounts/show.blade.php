@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-money-bill-alt"></i>Charts Of Accounts
        </h4>
        <p>
            Manage Account information
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('finances.index')}}">Finances</a></li>
                <li class="breadcrumb-item"><a href="{{route('accounts.index')}}">Chart of Accounts</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$account->name}}</li>
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
                            <div class="card shadow-sm">
                                <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table  table-bordered table-hover table-striped">
                                        <caption style=" caption-side: top; text-align: center">{{$account->name}} CHART OF ACCOUNT</caption>
                                        <tbody>
                                        <tr>
                                            <td>Name</td>
                                            <td>{{$account->name}}</td>
                                        </tr>
                                        <tr>
                                            <td>Type</td>
                                            <td>
                                                @if($account->type==1)
                                                    {{"CR"}}
                                                @else
                                                    {{"DR"}}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Category</td>
                                            <td>{{@$account->category->name}}</td>
                                        </tr>
                                        <tr>
                                            <td>Created On</td>
                                            <td>{{$account->created_at}}</td>
                                        </tr>
                                        <tr>
                                            <td>Update ON</td>
                                            <td>{{$account->updated_at}}</td>
                                        </tr>
                                        <tr>
                                            <td>Update By</td>
                                            <td>{{@$account->userName($account->updated_by)}}</td>
                                        </tr>
                                        <tr>
                                            <td>Created By</td>
                                            <td>{{@$account->userName($account->created_by)}}</td>
                                        </tr>
                                        <tr>
                                            <td>Status</td>
                                            <td>
                                                @if($account->soft_delete==1)
                                                    <p style="color: red">Deleted, and Reserved for Audit</p>
                                                @else
                                                    Active
                                                @endif
                                            </td>

                                        </tr>
                                    </table>
                                    <div class="mt-3">
                                        <div>
                                            <a href="{{route('accounts.edit',$account->id)}}"
                                               class="btn btn-primary rounded-0" style="margin: 2px">
                                                <i class="fa fa-edit"></i>Update
                                            </a>
                                            <button class="btn btn-danger btn-md rounded-0" id="delete-btn" style="margin: 5px">
                                                <i class="fa fa-trash"></i>Delete
                                            </button>
                                            <form action="{{route('accounts.destroy',$account->id)}}" method="POST" id="delete-form">
                                                @csrf
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="id" value="{{$account->id}}">
                                            </form>
                                        </div>
{{--                                        @if(request()->user()->designation==='administrator')--}}
{{--                                            <div class="">--}}
{{--                                                <form action="{{route('accounts.destroy',$account->id)}}" method="POST" id="delete-form">--}}
{{--                                                    @csrf--}}
{{--                                                    <input type="hidden" name="_method" value="DELETE">--}}
{{--                                                </form>--}}
{{--                                                <button class="btn btn-danger rounded-0" style="margin: 2px" id="delete-btn">--}}
{{--                                                    <i class="fa fa-trash"></i>Delete--}}
{{--                                                </button>--}}
{{--                                            </div>--}}
{{--                                        @endif--}}
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
                                <i class="fa fa-info-circle"></i>There are no Transactions!
                            @else
                                <table class="table table1  table-bordered table-hover table-striped">
                                    <caption style=" caption-side: top; text-align: center">{{$account->name}} STATEMENT </caption>
                                    <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>AMOUNT</th>
                                    <th>DESCRIPTION</th>
                                    <th>DATE</th>
                                    <th>TYPE</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php  $c= 1; $balance = 0 ?>
                                @foreach($transactions as $transfer)
                                    <tr>
                                        <td>{{$c++}}</td>
                                        <th>{{number_format($transfer->amount,2) }}</th>
                                        <td>{{ucwords($transfer->description) }}</td>
                                        <td>{{ucwords($transfer->created_at) }}</td>
                                        <td>{{ucwords($transfer->transaction_type == 1 ? "CR" : "DR") }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                                @endif
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
