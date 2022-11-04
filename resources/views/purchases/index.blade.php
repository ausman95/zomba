@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-money-bill-alt"></i>Purchases
        </h4>
        <p>
            Manage Purchases
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('finances.index')}}">Finances</a></li>
                <li class="breadcrumb-item active" aria-current="page">Purchases</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-3">
            <a href="{{route('purchases.create')}}" class="btn btn-primary btn-md rounded-0">
                <i class="fa fa-plus-circle"></i>New Purchases
            </a>
            <div class="mt-3">
                <div class="row">
                    <div class="col-sm-12 mb-2 col-md-12 col-lg-12">
                        <div class="card " style="min-height: 30em;">
                            <div class="card-body px-1">
                                @if($purchases->count() === 0)
                                    <i class="fa fa-info-circle"></i>There are no Purchases!
                                @else
                                    <div style="overflow-x:auto;">
                                        <table class="table  table-bordered table-hover table-striped">
                                            <caption style=" caption-side: top; text-align: center">PURCHASES</caption>
                                            <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>MATERIAL</th>
                                            <th>AMOUNT (MK)</th>
                                            <th>SPECS</th>
                                            <th>QTY</th>
                                            <th>SUPPLIER</th>
                                            <th>PAYMENT METHOD</th>
                                            <th>ACCOUNT</th>
                                            <th>DESTINATION</th>
                                            <th>REF</th>
                                            <th>DATE</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php  $c= 1;?>
                                        @foreach($purchases as $purchase)
                                            <tr>
                                                <td>{{$c++}}</td>
                                                <td>{{ucwords($purchase->material->name) }}</td>
                                                <th>{{number_format($purchase->amount) }}</th>
                                                <td>{{ucwords($purchase->material->specifications) }}</td>
                                                <td>{{number_format($purchase->quantity).' '.$purchase->material->units }}</td>
                                                <td>{{ucwords($purchase->supplier->name) }}</td>
                                                <td>
                                                    @if($purchase->payment_type==1)
                                                        CASH
                                                    @elseif($purchase->payment_type==2)
                                                        CREDIT
                                                    @else
                                                        ONLINE TRANSFER
                                                    @endif
                                                    </td>
                                               <td>{{$purchase->account->name}}</td>
                                                <td>
                                                    @if($purchase->store==1)
                                                        STORES
                                                    @elseif($purchase->store==2)
                                                        ADMIN
                                                    @else
                                                        PROJECTS
                                                    @endif
                                                </td>
                                                <td>{{ucwords($purchase->reference) }}</td>
                                                <td>
                                                    @if($purchase->payment_type==2)
                                                        {{date('d F Y', strtotime($purchase->date)) }}
                                                    @else
                                                        {{date('d F Y', strtotime($purchase->created_at)) }}
                                                    @endif
                                                </td>
{{--                                                <td class="pt-1">--}}
{{--                                                    <a href="{{route('purchases.show',$purchase->id)}}"--}}
{{--                                                       class="btn btn-md btn-outline-success">--}}
{{--                                                      <i class="fa fa-list-ol"></i>  Details--}}
{{--                                                    </a>--}}
{{--                                                </td>--}}
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
