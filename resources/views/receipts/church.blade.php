@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">

        <h4>
            <i class="fa fa-list-ol"></i>Division Receipt
        </h4>
        <p>
            Manage Division Church Transaction
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('finances.index')}}">Finances</a></li>
                <li class="breadcrumb-item"><a href="{{route('receipts.index')}}">Receipts</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$transaction->id}}</li>
            </ol>
        </nav>

        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-5 mb-2 ">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped">
                                    <tbody>
                                    <tr>
                                        <td>Church</td>
                                        <td>{{@$transaction->church->name.' '.@$transaction->section->name.' Section'}}</td>
                                    </tr>
                                    <tr>
                                        <td>Payment For</td>
                                        <td>{{$transaction->account->name}}</td>
                                    </tr>
                                    <tr>
                                        <td>Amount</td>
                                        <td>MK {{number_format($transaction->amount)}}</td>
                                    </tr>
                                    <tr>
                                        <td>Status</td>
                                        <td> @if($transaction->soft_delete==0)
                                                Reversed
                                            @else
                                                Active
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Created On</td>
                                        <td>{{date('d F Y', strtotime($transaction->created_at)) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Update ON</td>
                                        <td>{{date('d F Y', strtotime($transaction->updated_at)) }}</td>
                                    </tr>
                                </table>
                                <div class="mt-3">
                                    <div>
                                        @if($transaction->soft_delete==1)
                                        <a href="{{route('church.generate')."?id=".$transaction->id}}"
                                               class="btn btn-primary rounded-0" style="margin: 2px" target="_blank">
                                                <i class="fa fa-edit"></i>Receipt
                                            </a>
                                        @endif
                                    </div>
                                    @if(request()->user()->desgination!='clerk')
                                        <div class="">
                                            <form action="{{route('church-transactions.destroy',$transaction->id)}}" method="POST" id="delete-form">
                                                @csrf
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="id" value="{{$transaction->id}}">
                                            </form>
{{--                                            @if($transaction->soft_delete==1)--}}
{{--                                            <button class="btn btn-danger rounded-0" style="margin: 2px" id="delete-btn">--}}
{{--                                                <i class="fa fa-trash"></i>Reverse--}}
{{--                                            </button>--}}
{{--                                            @endif--}}
                                        </div>
                                    @endif
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
                confirmationWindow("Confirm Deletion", "Are you sure you want to Reverse this Transaction ?", "Yes,Delete", function () {
                    $("#delete-form").submit();
                });
            });
        })
    </script>
@stop
