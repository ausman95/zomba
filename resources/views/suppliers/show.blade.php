@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">

        <h4>
            <i class="bx bx-package"></i>&nbsp; Suppliers
        </h4>
        <p>
            Manage supplier information
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('suppliers.index')}}">Supplier</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$supplier->name}}</li>
            </ol>
        </nav>

        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">

                <div class="col-sm-12 rounded-0 col-md-12 col-lg-12">
                    <div class="row">
                        <div class="col-sm-12 col-md-7 col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered  table-hover table-striped" id="project-table">
                                        <caption style=" caption-side: top; text-align: center">{{$supplier->name}} INFORMATION</caption>
                                        <tbody>
                                        <tr>
                                            <td>Name</td>
                                            <td>{{$supplier->name}}</td>
                                        </tr>
                                        <tr>
                                            <td>Phone No.</td>
                                            <td>{{$supplier->phone_number}}</td>
                                        </tr>
                                        <tr>
                                            <td>Location</td>
                                            <td>{{$supplier->location}}</td>
                                        </tr>
                                        <tr>
                                            <td>Created On</td>
                                            <td>{{$supplier->created_at}}</td>
                                        </tr>
                                        <tr>
                                            <td>Update ON</td>
                                            <td>{{$supplier->updated_at}}</td>
                                        </tr>
                                    </table>
                                    <a href="{{route('suppliers.edit',$supplier->id)}}"
                                       class="btn btn-primary btn-md rounded-0" style="margin: 5px">
                                        <i class="fa fa-edit"></i>Update
                                    </a>
{{--                                    @if( request()->user()->designation==='administrator')--}}
{{--                                        <div class="">--}}
{{--                                            <form action="{{route('suppliers.destroy',$supplier->id)}}" method="POST" id="delete-form">--}}
{{--                                                @csrf--}}
{{--                                                <input type="hidden" name="_method" value="DELETE">--}}
{{--                                            </form>--}}
{{--                                            <button   class="btn btn-danger btn-md rounded-0" style="margin: 5px" id="delete-btn">--}}
{{--                                                <i class="fa fa-trash"></i>Delete--}}
{{--                                            </button>--}}
{{--                                        </div>--}}
{{--                                    @endif--}}

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
                    @if($payments->count() === 0)
                        <i class="fa fa-info-circle"></i>There are no  Transactions!
                    @else
                        <div style="overflow-x:auto;">
                            <table class="table table-primary table-bordered table2 table-hover table-striped" id="project-table">
                                <caption style=" caption-side: top; text-align: center">{{$supplier->name}} Statement</caption>
                                <thead>
                            <tr>
                                <th>NO</th>
                                <th>AMOUNT</th>
                                <th>BALANCE</th>
                                <th>DATE</th>
                                <th>DESCRIPTION</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php  $c= 1; $balance = 0; ?>
                            @foreach($payments as $payment)
                                <tr>
                                    <td>{{$c++}}</td>
                                    <th>
                                        @if( $payment->transaction_type==1 && $payment->method==1)
                                            {{number_format($payment->amount)}}
                                        @elseif( $payment->transaction_type==1 && $payment->method==2)
                                            {{number_format($payment->amount)}}
                                        @elseif( $payment->transaction_type==1 && $payment->method==4)
                                            {{number_format($payment->amount)}}
                                        @elseif( $payment->transaction_type==2 && $payment->method==1)
                                            ({{number_format($payment->amount)}})
                                        @elseif( $payment->transaction_type==2 && $payment->method==4)
                                            ({{number_format($payment->amount)}})
                                        @elseif( $payment->transaction_type==2 && $payment->method==3)
                                            ({{number_format($payment->amount)}})
                                        @endif

                                    </th>
                                   <th>
                                       @if($payment->balance<0)
                                           ({{number_format($payment->balance*-1) }})
                                       @else
                                           {{number_format($payment->balance) }}
                                       @endif
                                    <td>{{$payment->created_at}}</td>
                                    <th>
                                        @if( $payment->transaction_type==1 && $payment->method==1)
                                            {{ "Bought on Cash"}}
                                        @elseif( $payment->transaction_type==1 && $payment->method==2)
                                            {{ "Bought on Credit"}}
                                        @elseif( $payment->transaction_type==1 && $payment->method==4)
                                            {{ "Bought Using Online Transfer"}}
                                        @elseif( $payment->transaction_type==2 && $payment->method==1)
                                            {{ "Payment by Cash"}}
                                        @elseif( $payment->transaction_type==2 && $payment->method==3)
                                            {{ "Payment by Cheque"}}
                                        @elseif( $payment->transaction_type==2 && $payment->method==4)
                                            {{ "Payment by Online-Transfer"}}
                                        @endif
                                    </th>
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
                confirmationWindow("Confirm Deletion", "Are you sure you want to delete this Supplier?", "Yes,Delete", function () {
                    $("#delete-form").submit();
                });
            });
        })
    </script>
@stop
